<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Image\NameChangeImage;
use Jesperbeisner\Fwstats\Image\ProfessionChangeImage;
use Jesperbeisner\Fwstats\Image\RaceChangeImage;
use Jesperbeisner\Fwstats\Image\RankingImage;
use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Importer\PlayerImporter;
use Jesperbeisner\Fwstats\Importer\PlaytimeImporter;
use Jesperbeisner\Fwstats\Interface\CronjobInterface;
use Jesperbeisner\Fwstats\Model\Cronjob;
use Jesperbeisner\Fwstats\Repository\CronjobRepository;

final readonly class CronjobService implements CronjobInterface
{
    public function __construct(
        private CronjobRepository $cronjobRepository,
        private ClanImporter $clanImporter,
        private PlayerImporter $playerImporter,
        private AchievementImporter $achievementImporter,
        private PlaytimeImporter $playtimeImporter,
        private RankingImage $rankingImage,
        private NameChangeImage $nameChangeImage,
        private RaceChangeImage $raceChangeImage,
        private ProfessionChangeImage $professionChangeImage,
    ) {
    }

    public function isAllowedToRun(): bool
    {
        if (null === $cronjob = $this->cronjobRepository->findLastCronjob()) {
            return true;
        }

        if ($cronjob->created->getTimestamp() < (new DateTimeImmutable())->getTimestamp() - 3 * 60) {
            return true;
        }

        return false;
    }

    public function run(): void
    {
        $this->cronjobRepository->insert(new Cronjob(null, new DateTimeImmutable()));

        foreach (WorldEnum::cases() as $world) {
            $this->clanImporter->import($world);
            $this->playerImporter->import($world);
            $this->achievementImporter->import($world);
            $this->playtimeImporter->import($world);
            $this->rankingImage->create($world);
            $this->nameChangeImage->create($world);
            $this->raceChangeImage->create($world);
            $this->professionChangeImage->create($world);
        }
    }
}
