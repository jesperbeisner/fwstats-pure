<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Image;

use Jesperbeisner\Fwstats\Enum\PlayerStatusEnum;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Exception\ImageException;
use Jesperbeisner\Fwstats\Model\PlayerStatusHistory;
use Jesperbeisner\Fwstats\Repository\PlayerStatusHistoryRepository;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Image\BanAndDeletionImageTest
 */
final class BanAndDeletionImage extends AbstractImage
{
    public function __construct(
        string $imageFolder,
        string $font,
        private readonly string $imageName,
        private readonly PlayerStatusHistoryRepository $playerStatusHistoryRepository,
    ) {
        parent::__construct($imageFolder, $font);
    }

    public function create(WorldEnum $world): void
    {
        $bansAndDeletions = $this->playerStatusHistoryRepository->getLast15BansAndDeletionsByWorld($world);

        $this->createImage(465, (int) (95 + (count($bansAndDeletions) * 19.1)));
        $this->setBackgroundColor($this->colorWhite());

        if ($world === WorldEnum::AFSRV) {
            $this->write('Letzten 15 Bans oder Löschungen in ActionFreewar', 10, 20, 15);
        } else {
            $this->write('Letzten 15 Bans oder Löschungen in ChaosFreewar', 10, 20, 15);
        }

        $this->createRankColumn($bansAndDeletions);
        $this->createNameColumn($bansAndDeletions);
        $this->createStatusColumn($bansAndDeletions);
        $this->createCreatedColumn($bansAndDeletions);
        $this->createDeletedColumn($bansAndDeletions);

        $this->write('www.fwstats.de', 10, (int) (85 + (count($bansAndDeletions) * 19.1)), 10);
        $this->write(date('Y-m-d - H:i:s'), 333, (int) (85 + (count($bansAndDeletions) * 19.1)), 10);

        $this->save($this->getImageFolder() . sprintf('/%s-%s', $world->value, $this->imageName));
    }

    /**
     * @param array<PlayerStatusHistory> $bansAndDeletions
     */
    private function createRankColumn(array $bansAndDeletions): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('#', 15, 55, 12);

        for ($i = 1; $i <= count($bansAndDeletions); $i++) {
            $this->write($i . '.', 15, 19 * $i + 60, 10);
        }
    }

    /**
     * @param array<PlayerStatusHistory> $bansAndDeletions
     */
    private function createNameColumn(array $bansAndDeletions): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Name', 42, 55, 12);

        foreach ($bansAndDeletions as $id => $playerStatusHistory) {
            $name = strlen($playerStatusHistory->name) > 15 ? substr($playerStatusHistory->name, 0, 15) : $playerStatusHistory->name;
            $this->write($name, 42, 19 * ($id + 1) + 60, 10);
        }
    }

    /**
     * @param array<PlayerStatusHistory> $bansAndDeletions
     */
    private function createStatusColumn(array $bansAndDeletions): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Status', 145, 55, 12);

        foreach ($bansAndDeletions as $id => $playerStatusHistory) {
            if ($playerStatusHistory->status === PlayerStatusEnum::DELETED) {
                $status = 'Gelöscht';
            } elseif ($playerStatusHistory->status === PlayerStatusEnum::BANNED) {
                $status = 'Gebannt';
            } else {
                $status = '-';
            }

            $this->write($status, 145, 19 * ($id + 1) + 60, 10);
        }
    }

    /**
     * @param array<PlayerStatusHistory> $bansAndDeletions
     */
    private function createCreatedColumn(array $bansAndDeletions): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Erstellt', 208, 55, 12);

        foreach ($bansAndDeletions as $id => $playerStatusHistory) {
            $this->write($playerStatusHistory->created->format('Y-m-d H:i:s'), 208, 19 * ($id + 1) + 60, 10);
        }
    }

    /**
     * @param array<PlayerStatusHistory> $bansAndDeletions
     */
    private function createDeletedColumn(array $bansAndDeletions): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Aufgehoben', 337, 55, 12);

        foreach ($bansAndDeletions as $id => $playerStatusHistory) {
            $this->write($playerStatusHistory->deleted === null ? '-' : $playerStatusHistory->deleted->format('Y-m-d H:i:s'), 337, 19 * ($id + 1) + 60, 10);
        }
    }
}
