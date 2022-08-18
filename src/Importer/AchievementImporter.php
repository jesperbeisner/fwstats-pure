<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Service\FreewarDumpService;

final class AchievementImporter implements ImporterInterface
{
    public function __construct(
        private readonly FreewarDumpService $freewarDumpService,
    ) {
    }

    public function import(WorldEnum $world): ImportResult
    {
        $importResult = new ImportResult();

        return $importResult;
    }
}
