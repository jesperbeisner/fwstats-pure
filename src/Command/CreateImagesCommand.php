<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\ImageService\RankingImageService;

final class CreateImagesCommand extends AbstractCommand
{
    public static string $name = 'app:create-images';
    public static string $description = 'Creates statistic images for the freewar profile.';

    public function __construct(
        private readonly RankingImageService $rankingImageService,
    ) {
    }

    public function execute(): int
    {
        $this->startTime();
        $this->write("Starting the 'app:create-images' command...");

        foreach (WorldEnum::cases() as $world) {
            $this->rankingImageService->create($world);
        }

        $this->write("Finished the 'app:create-images' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }
}
