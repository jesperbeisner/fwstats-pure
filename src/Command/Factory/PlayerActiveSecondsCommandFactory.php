<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\PlayerActiveSecondsCommand;
use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Repository\AchievementRepository;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Service\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class PlayerActiveSecondsCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): PlayerActiveSecondsCommand
    {
        /** @var FreewarDumpServiceInterface $freewarDumpService */
        $freewarDumpService = $serviceContainer->get(FreewarDumpServiceInterface::class);

        /** @var PlayerActiveSecondRepository $playerActiveSecondRepository */
        $playerActiveSecondRepository = $serviceContainer->get(PlayerActiveSecondRepository::class);

        return new PlayerActiveSecondsCommand(
            $freewarDumpService,
            $playerActiveSecondRepository,
        );
    }
}
