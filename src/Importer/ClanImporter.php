<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

use DateTimeImmutable;
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
        $importResult->addMessage('Starting ClanImporter.');

        $clans = $this->clanRepository->findAllByWorld($world);
        $clansDump = $this->freewarDumpService->getClansDump($world);

        // First import. No comparison needed.
        if (count($clans) === 0) {
            $importResult->addMessage(sprintf('No clans found for world "%s". Inserting clans dump into the database.', $world->value));
            $this->clanRepository->insertClans($world, $clansDump);

            return $importResult;
        }

        foreach ($clans as $clan) {
            if (isset($clansDump[$clan->clanId])) {
                $clanDump = $clansDump[$clan->clanId];

                if ($clan->shortcut !== $clanDump->shortcut || $clan->name !== $clanDump->name) {
                    $importResult->addMessage(sprintf('Clan naming changed for clan "%s" in world "%s".', $clan->name, $world->value));
                    $this->clanNameHistoryRepository->insert(new ClanNameHistory(null, $clan->world, $clan->clanId, $clan->shortcut, $clanDump->shortcut, $clan->name, $clanDump->name, new DateTimeImmutable()));
                }
            } else {
                // Clan is in database but not in clan dump: Clan deleted
                $importResult->addMessage(sprintf('Clan "%s" in world "%s" was deleted.', $clan->name, $world->value));
                $this->clanDeletedHistoryRepository->insert(new ClanDeletedHistory(null, $clan->world, $clan->clanId, $clan->shortcut, $clan->name, $clan->leaderId, $clan->coLeaderId, $clan->diplomatId, $clan->warPoints, new DateTimeImmutable()));
            }
        }

        foreach ($clansDump as $clanDump) {
            // Clan is in dump but not in database: Clan created
            if (!isset($clans[$clanDump->clanId])) {
                $importResult->addMessage(sprintf('Clan "%s" in world "%s" was created.', $clanDump->name, $world->value));
                $this->clanCreatedHistoryRepository->insert(new ClanCreatedHistory(null, $clanDump->world, $clanDump->clanId, $clanDump->shortcut, $clanDump->name, $clanDump->leaderId, $clanDump->coLeaderId, $clanDump->diplomatId, $clanDump->warPoints, new DateTimeImmutable()));
            }
        }

        $this->clanRepository->insertClans($world, $clansDump);

        $importResult->addMessage('Finishing ClanImporter.');

        return $importResult;
    }
}
