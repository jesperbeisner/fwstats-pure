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
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class DatabaseFixtureCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): DatabaseFixtureCommand
    {
        /** @var string $appEnv */
        $appEnv = $serviceContainer->get('appEnv');

        /** @var string $rootDir */
        $rootDir = $serviceContainer->get('rootDir');

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $serviceContainer->get(PlayerRepository::class);

        /** @var ClanRepository $clanRepository */
        $clanRepository = $serviceContainer->get(ClanRepository::class);

        /** @var PlayerActiveSecondRepository $playerActiveSecondRepository */
        $playerActiveSecondRepository = $serviceContainer->get(PlayerActiveSecondRepository::class);

        /** @var PlayerNameHistoryRepository $playerNameHistoryRepository */
        $playerNameHistoryRepository = $serviceContainer->get(PlayerNameHistoryRepository::class);

        /** @var PlayerRaceHistoryRepository $playerRaceHistoryRepository */
        $playerRaceHistoryRepository = $serviceContainer->get(PlayerRaceHistoryRepository::class);

        /** @var PlayerProfessionHistoryRepository $playerProfessionHistoryRepository */
        $playerProfessionHistoryRepository = $serviceContainer->get(PlayerProfessionHistoryRepository::class);

        /** @var RankingImageService $rankingImageService */
        $rankingImageService = $serviceContainer->get(RankingImageService::class);

        /** @var UserRepository $userRepository */
        $userRepository = $serviceContainer->get(UserRepository::class);

        /** @var CreateUserAction $createUserAction */
        $createUserAction = $serviceContainer->get(CreateUserAction::class);

        return new DatabaseFixtureCommand(
            $appEnv,
            $rootDir,
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
