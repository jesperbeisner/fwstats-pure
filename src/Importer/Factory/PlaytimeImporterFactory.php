<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer\Factory;

use Jesperbeisner\Fwstats\Importer\PlaytimeImporter;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Service\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

final class PlaytimeImporterFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): PlaytimeImporter
    {
        /** @var FreewarDumpServiceInterface $freewarDumpService */
        $freewarDumpService = $container->get(FreewarDumpServiceInterface::class);

        /** @var PlayerActiveSecondRepository $playerActiveSecondRepository */
        $playerActiveSecondRepository = $container->get(PlayerActiveSecondRepository::class);

        return new PlaytimeImporter(
            $freewarDumpService,
            $playerActiveSecondRepository,
        );
    }
}
