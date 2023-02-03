<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Image;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Exception\ImageException;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Stdlib\Config;

final class RaceChangeImage extends AbstractImage
{
    public function __construct(
        Config $config,
        private readonly PlayerRaceHistoryRepository $playerRaceHistoryRepository,
    ) {
        parent::__construct($config);
    }

    public function create(WorldEnum $world): void
    {
        $raceChanges = $this->playerRaceHistoryRepository->getLast15RaceChangesByWorld($world);

        $this->createImage(475, (int) (95 + (count($raceChanges) * 19.1)));
        $this->setBackgroundColor($this->colorWhite());

        if ($world === WorldEnum::AFSRV) {
            $this->write('Letzten 15 Rassenänderungen in ActionFreewar', 30, 20, 15);
        } else {
            $this->write('Letzten 15 Rassenänderungen in ChaosFreewar', 31, 20, 15);
        }

        $this->createRankColumn($raceChanges);
        $this->createNameColumn($raceChanges);
        $this->createOldRaceColumn($raceChanges);
        $this->createNewRaceColumn($raceChanges);
        $this->createDateColumn($raceChanges);

        $this->write('www.fwstats.de', 10, (int) (85 + (count($raceChanges) * 19.1)), 10);
        $this->write(date('Y-m-d - H:i:s'), 343, (int) (85 + (count($raceChanges) * 19.1)), 10);

        $this->save($this->getImageFolder() . $world->value . '-race-changes.png');
    }

    /**
     * @param array<array{world: string, player_id: int, name: string, old_race: string, new_race: string, created: string}> $raceChanges
     */
    private function createRankColumn(array $raceChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('#', 15, 55, 12);

        for ($i = 1; $i <= count($raceChanges); $i++) {
            $this->write($i . '.', 15, 19 * $i + 60, 10);
        }
    }

    /**
     * @param array<array{world: string, player_id: int, name: string, old_race: string, new_race: string, created: string}> $raceChanges
     */
    private function createNameColumn(array $raceChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Name', 40, 55, 12);

        foreach ($raceChanges as $id => $raceChange) {
            $this->write(strlen($raceChange['name']) > 15 ? substr($raceChange['name'], 0, 15) : $raceChange['name'], 40, 19 * ($id + 1) + 60, 10);
        }
    }

    /**
     * @param array<array{world: string, player_id: int, name: string, old_race: string, new_race: string, created: string}> $raceChanges
     */
    private function createOldRaceColumn(array $raceChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Alte Rasse', 155, 55, 12);

        foreach ($raceChanges as $id => $raceChange) {
            $this->write($raceChange['old_race'], 155, 19 * ($id + 1) + 60, 10);
        }
    }

    /**
     * @param array<array{world: string, player_id: int, name: string, old_race: string, new_race: string, created: string}> $raceChanges
     */
    private function createNewRaceColumn(array $raceChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Neue Rasse', 277, 55, 12);

        foreach ($raceChanges as $id => $raceChange) {
            $this->write($raceChange['new_race'], 277, 19 * ($id + 1) + 60, 10);
        }
    }

    /**
     * @param array<array{world: string, player_id: int, name: string, old_race: string, new_race: string, created: string}> $raceChanges
     */
    private function createDateColumn(array $raceChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Datum', 400, 55, 12);

        foreach ($raceChanges as $id => $raceChange) {
            $this->write((new DateTimeImmutable($raceChange['created']))->format('d.m.Y'), 400, 19 * ($id + 1) + 60, 10);
        }
    }
}
