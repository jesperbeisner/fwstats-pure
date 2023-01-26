<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Interface\ImporterInterface;
use Jesperbeisner\Fwstats\Model\ClanCreatedHistory;
use Jesperbeisner\Fwstats\Model\ClanDeletedHistory;
use Jesperbeisner\Fwstats\Model\ClanNameHistory;
use Jesperbeisner\Fwstats\Repository\ClanCreatedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanDeletedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Result\ImportResult;

final readonly class ClanImporter implements ImporterInterface
{
    public function __construct(
        private FreewarDumpServiceInterface $freewarDumpService,
        private ClanRepository $clanRepository,
        private ClanNameHistoryRepository $clanNameHistoryRepository,
        private ClanDeletedHistoryRepository $clanDeletedHistoryRepository,
        private ClanCreatedHistoryRepository $clanCreatedHistoryRepository,
    ) {
    }

    public function import(WorldEnum $world): ImportResult
    {
        $importResult = new ImportResult();
        $importResult->addMessage('Starting ClanImporter...');

        $clans = $this->clanRepository->findAllByWorld($world);
        $clansDump = $this->freewarDumpService->getClansDump($world);

        if (count($clans) === 0) {
            $importResult->addMessage("No clans found for world '$world->value'. Inserting clans dump into the database.");
            $this->clanRepository->insertClans($world, $clansDump);

            return $importResult;
        }

        foreach ($clans as $clan) {
            if (isset($clansDump[$clan->clanId])) {
                $clanDump = $clansDump[$clan->clanId];

                if ($clan->shortcut !== $clanDump->shortcut || $clan->name !== $clanDump->name) {
                    $importResult->addMessage("Clan naming changed for clan '$clan->name' in world '$world->value'.");

                    $clanNameHistory = new ClanNameHistory(null, $clan->world, $clan->clanId, $clan->shortcut, $clanDump->shortcut, $clan->name, $clanDump->name);

                    $this->clanNameHistoryRepository->insert($clanNameHistory);
                }
            } else {
                // Clan is in database but not in clan dump: Clan deleted
                $importResult->addMessage("Clan '$clan->name' in world '$world->value' was deleted.");

                $clanDeletedHistory = new ClanDeletedHistory(null, $clan->world, $clan->clanId, $clan->shortcut, $clan->name, $clan->leaderId, $clan->coLeaderId, $clan->diplomatId, $clan->warPoints);

                $this->clanDeletedHistoryRepository->insert($clanDeletedHistory);
            }
        }

        foreach ($clansDump as $clanDump) {
            // Clan is in dump but not in database: Clan created
            if (!isset($clans[$clanDump->clanId])) {
                $importResult->addMessage("Clan '$clanDump->name' in world '$world->value' was created.");

                $clanCreatedHistory = new ClanCreatedHistory(null, $clanDump->world, $clanDump->clanId, $clanDump->shortcut, $clanDump->name, $clanDump->leaderId, $clanDump->coLeaderId, $clanDump->diplomatId, $clanDump->warPoints);

                $this->clanCreatedHistoryRepository->insert($clanCreatedHistory);
            }
        }

        $this->clanRepository->insertClans($world, $clansDump);

        $importResult->addMessage('Finishing ClanImporter...');

        return $importResult;
    }
}
