<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Model\ClanCreatedHistory;
use Jesperbeisner\Fwstats\Model\ClanDeletedHistory;
use Jesperbeisner\Fwstats\Model\ClanNameHistory;
use Jesperbeisner\Fwstats\Repository\ClanCreatedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanDeletedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanRepository;

final class ClanImporter implements ImporterInterface
{
    public function __construct(
        private readonly FreewarDumpServiceInterface $freewarDumpService,
        private readonly ClanRepository $clanRepository,
        private readonly ClanNameHistoryRepository $clanNameHistoryRepository,
        private readonly ClanDeletedHistoryRepository $clanDeletedHistoryRepository,
        private readonly ClanCreatedHistoryRepository $clanCreatedHistoryRepository,
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

                    $clanNameHistory = new ClanNameHistory(
                        world: $clan->world,
                        clanId: $clan->clanId,
                        oldShortcut: $clan->shortcut,
                        newShortcut: $clanDump->shortcut,
                        oldName: $clan->name,
                        newName: $clanDump->name,
                    );

                    $this->clanNameHistoryRepository->insert($clanNameHistory);
                }
            } else {
                // Clan is in database but not in clan dump: Clan deleted
                $importResult->addMessage("Clan '$clan->name' in world '$world->value' was deleted.");

                $clanDeletedHistory = new ClanDeletedHistory(
                    world: $clan->world,
                    clanId: $clan->clanId,
                    shortcut: $clan->shortcut,
                    name: $clan->name,
                    leaderId: $clan->leaderId,
                    coLeaderId: $clan->coLeaderId,
                    diplomatId: $clan->diplomatId,
                    warPoints: $clan->warPoints,
                );

                $this->clanDeletedHistoryRepository->insert($clanDeletedHistory);
            }
        }

        foreach ($clansDump as $clanDump) {
            // Clan is in dump but not in database: Clan created
            if (!isset($clans[$clanDump->clanId])) {
                $importResult->addMessage("Clan '$clanDump->name' in world '$world->value' was created.");

                $clanCreatedHistory = new ClanCreatedHistory(
                    world: $clanDump->world,
                    clanId: $clanDump->clanId,
                    shortcut: $clanDump->shortcut,
                    name: $clanDump->name,
                    leaderId: $clanDump->leaderId,
                    coLeaderId: $clanDump->coLeaderId,
                    diplomatId: $clanDump->diplomatId,
                    warPoints: $clanDump->warPoints,
                );

                $this->clanCreatedHistoryRepository->insert($clanCreatedHistory);
            }
        }

        $this->clanRepository->insertClans($world, $clansDump);

        $importResult->addMessage('Finishing ClanImporter...');

        return $importResult;
    }
}
