<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Image;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Exception\ImageException;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Stdlib\Config;

final class ProfessionChangeImage extends AbstractImage
{
    public function __construct(
        Config $config,
        private readonly PlayerProfessionHistoryRepository $playerProfessionHistoryRepository,
    ) {
        parent::__construct($config);
    }

    public function create(WorldEnum $world): void
    {
        $professionChanges = $this->playerProfessionHistoryRepository->getLast15ProfessionChangesByWorld($world);

        $this->createImage(475, (int) (95 + (count($professionChanges) * 19.1)));
        $this->setBackgroundColor($this->colorWhite());

        if ($world === WorldEnum::AFSRV) {
            $this->write('Letzten 15 Berufsänderungen in ActionFreewar', 30, 20, 15);
        } else {
            $this->write('Letzten 15 Berufsänderungen in ChaosFreewar', 31, 20, 15);
        }

        $this->createRankColumn($professionChanges);
        $this->createNameColumn($professionChanges);
        $this->createOldProfessionColumn($professionChanges);
        $this->createNewProfessionColumn($professionChanges);
        $this->createDateColumn($professionChanges);

        $this->write('www.fwstats.de', 10, (int) (85 + (count($professionChanges) * 19.1)), 10);
        $this->write(date('Y-m-d - H:i:s'), 343, (int) (85 + (count($professionChanges) * 19.1)), 10);

        $this->save($this->getImageFolder() . $world->value . '-profession-changes.png');
    }

    /**
     * @param array<array{world: string, player_id: int, name: string, old_profession: null|string, new_profession: null|string, created: string}> $professionChanges
     */
    private function createRankColumn(array $professionChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('#', 15, 55, 12);

        for ($i = 1; $i <= count($professionChanges); $i++) {
            $this->write($i . '.', 15, 19 * $i + 60, 10);
        }
    }

    /**
     * @param array<array{world: string, player_id: int, name: string, old_profession: null|string, new_profession: null|string, created: string}> $professionChanges
     */
    private function createNameColumn(array $professionChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Name', 40, 55, 12);

        foreach ($professionChanges as $id => $professionChange) {
            $this->write(strlen($professionChange['name']) > 15 ? substr($professionChange['name'], 0, 15) : $professionChange['name'], 40, 19 * ($id + 1) + 60, 10);
        }
    }

    /**
     * @param array<array{world: string, player_id: int, name: string, old_profession: null|string, new_profession: null|string, created: string}> $professionChanges
     */
    private function createOldProfessionColumn(array $professionChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Alter Beruf', 155, 55, 12);

        foreach ($professionChanges as $id => $professionChange) {
            $this->write($professionChange['old_profession'] ?? '-', 155, 19 * ($id + 1) + 60, 10);
        }
    }

    /**
     * @param array<array{world: string, player_id: int, name: string, old_profession: null|string, new_profession: null|string, created: string}> $professionChanges
     */
    private function createNewProfessionColumn(array $professionChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Neuer Beruf', 277, 55, 12);

        foreach ($professionChanges as $id => $professionChange) {
            $this->write($professionChange['new_profession'] ?? '-', 277, 19 * ($id + 1) + 60, 10);
        }
    }

    /**
     * @param array<array{world: string, player_id: int, name: string, old_profession: null|string, new_profession: null|string, created: string}> $professionChanges
     */
    private function createDateColumn(array $professionChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Datum', 400, 55, 12);

        foreach ($professionChanges as $id => $professionChange) {
            $this->write((new DateTimeImmutable($professionChange['created']))->format('d.m.Y'), 400, 19 * ($id + 1) + 60, 10);
        }
    }
}
