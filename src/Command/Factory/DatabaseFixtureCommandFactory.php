<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Action\CreateUserAction;
use Jesperbeisner\Fwstats\Command\DatabaseFixtureCommand;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Service\NameChangeImageService;
use Jesperbeisner\Fwstats\Service\RankingImageService;
use Jesperbeisner\Fwstats\Stdlib\Config;

class DatabaseFixtureCommandFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): DatabaseFixtureCommand
    {
        return new DatabaseFixtureCommand(
            $container->get(Config::class)->getAppEnv(),
            $container->get(Config::class)->getString('fixtures_directory'),
            $container->get(PlayerRepository::class),
            $container->get(ClanRepository::class),
            $container->get(PlayerActiveSecondRepository::class),
            $container->get(PlayerNameHistoryRepository::class),
            $container->get(PlayerRaceHistoryRepository::class),
            $container->get(PlayerProfessionHistoryRepository::class),
            $container->get(RankingImageService::class),
            $container->get(NameChangeImageService::class),
            $container->get(UserRepository::class),
            $container->get(CreateUserAction::class),
        );
    }
}
