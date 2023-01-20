<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer\Factory;

use Jesperbeisner\Fwstats\Importer\PlaytimeImporter;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;

final class PlaytimeImporterFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): PlaytimeImporter
    {
        return new PlaytimeImporter(
            $container->get(FreewarDumpServiceInterface::class),
            $container->get(PlayerActiveSecondRepository::class),
        );
    }
}
