<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Service\FreewarDumpServiceInterface;

final class AchievementImporter implements ImporterInterface
{
    public function __construct(
        private readonly FreewarDumpServiceInterface $freewarDumpService,
    ) {
    }

    public function import(WorldEnum $world): ImportResult
    {
        $importResult = new ImportResult();

        return $importResult;
    }
}
