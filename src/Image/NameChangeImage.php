<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Image;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Exception\ImageException;
use Jesperbeisner\Fwstats\Model\PlayerNameHistory;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Image\NameChangeImageTest
 */
final class NameChangeImage extends AbstractImage
{
    public function __construct(
        string $imageFolder,
        string $font,
        private readonly string $imageName,
        private readonly PlayerNameHistoryRepository $playerNameHistoryRepository,
    ) {
        parent::__construct($imageFolder, $font);
    }

    public function create(WorldEnum $world): void
    {
        $nameChanges = $this->playerNameHistoryRepository->getLast15NameChangesByWorld($world);

        $this->createImage(435, (int) (95 + (count($nameChanges) * 19.1)));
        $this->setBackgroundColor($this->colorWhite());

        if ($world === WorldEnum::AFSRV) {
            $this->write('Letzten 15 Namensänderungen in ActionFreewar', 5, 20, 15);
        } else {
            $this->write('Letzten 15 Namensänderungen in ChaosFreewar', 5, 20, 15);
        }

        $this->createRankColumn($nameChanges);
        $this->createNewNameColumn($nameChanges);
        $this->createOldNameColumn($nameChanges);
        $this->createDateColumn($nameChanges);

        $this->write('www.fwstats.de', 10, (int) (85 + (count($nameChanges) * 19.1)), 10);
        $this->write(date('Y-m-d - H:i:s'), 303, (int) (85 + (count($nameChanges) * 19.1)), 10);

        $this->save($this->getImageFolder() . sprintf('/%s-%s', $world->value, $this->imageName));
    }

    /**
     * @param PlayerNameHistory[] $nameChanges
     */
    private function createRankColumn(array $nameChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('#', 15, 55, 12);

        for ($i = 1; $i <= count($nameChanges); $i++) {
            $this->write($i . '.', 15, 19 * $i + 60, 10);
        }
    }

    /**
     * @param PlayerNameHistory[] $nameChanges
     */
    private function createNewNameColumn(array $nameChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Neuer Name', 45, 55, 12);

        foreach ($nameChanges as $id => $nameChange) {
            $newName = strlen($nameChange->newName) > 20 ? substr($nameChange->newName, 0, 20) : $nameChange->newName;
            $this->write($newName, 45, 19 * ($id + 1) + 60, 10);
        }
    }

    /**
     * @param PlayerNameHistory[] $nameChanges
     */
    private function createOldNameColumn(array $nameChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Alter Name', 195, 55, 12);

        foreach ($nameChanges as $id => $nameChange) {
            $oldName = strlen($nameChange->oldName) > 20 ? substr($nameChange->oldName, 0, 20) : $nameChange->oldName;
            $this->write($oldName, 195, 19 * ($id + 1) + 60, 10);
        }
    }

    /**
     * @param PlayerNameHistory[] $nameChanges
     */
    private function createDateColumn(array $nameChanges): void
    {
        if ($this->image === null) {
            throw new ImageException("Did you forget to run 'imageCreate'?");
        }

        $this->write('Datum', 357, 55, 12);

        foreach ($nameChanges as $id => $nameChange) {
            $this->write($nameChange->created->format('d.m.Y'), 357, 19 * ($id + 1) + 60, 10);
        }
    }
}
