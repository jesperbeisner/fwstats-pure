<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Action\CreateUserAction;
use Jesperbeisner\Fwstats\Command\DatabaseFixtureCommand;
use Jesperbeisner\Fwstats\ImageService\RankingImageService;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

class DatabaseFixtureCommandFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): DatabaseFixtureCommand
    {
        /** @var Config $config */
        $config = $container->get(Config::class);

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $container->get(PlayerRepository::class);

        /** @var ClanRepository $clanRepository */
        $clanRepository = $container->get(ClanRepository::class);

        /** @var PlayerActiveSecondRepository $playerActiveSecondRepository */
        $playerActiveSecondRepository = $container->get(PlayerActiveSecondRepository::class);

        /** @var PlayerNameHistoryRepository $playerNameHistoryRepository */
        $playerNameHistoryRepository = $container->get(PlayerNameHistoryRepository::class);

        /** @var PlayerRaceHistoryRepository $playerRaceHistoryRepository */
        $playerRaceHistoryRepository = $container->get(PlayerRaceHistoryRepository::class);

        /** @var PlayerProfessionHistoryRepository $playerProfessionHistoryRepository */
        $playerProfessionHistoryRepository = $container->get(PlayerProfessionHistoryRepository::class);

        /** @var RankingImageService $rankingImageService */
        $rankingImageService = $container->get(RankingImageService::class);

        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);

        /** @var CreateUserAction $createUserAction */
        $createUserAction = $container->get(CreateUserAction::class);

        return new DatabaseFixtureCommand(
            $config,
            $playerRepository,
            $clanRepository,
            $playerActiveSecondRepository,
            $playerNameHistoryRepository,
            $playerRaceHistoryRepository,
            $playerProfessionHistoryRepository,
            $rankingImageService,
            $userRepository,
            $createUserAction,
        );
    }
}
