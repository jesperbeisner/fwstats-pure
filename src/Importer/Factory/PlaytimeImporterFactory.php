<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer\Factory;

use Jesperbeisner\Fwstats\Importer\PlaytimeImporter;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Service\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

final class PlaytimeImporterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): PlaytimeImporter
    {
        /** @var FreewarDumpServiceInterface $freewarDumpService */
        $freewarDumpService = $serviceContainer->get(FreewarDumpServiceInterface::class);

        /** @var PlayerActiveSecondRepository $playerActiveSecondRepository */
        $playerActiveSecondRepository = $serviceContainer->get(PlayerActiveSecondRepository::class);

        return new PlaytimeImporter(
            $freewarDumpService,
            $playerActiveSecondRepository,
        );
    }
}
