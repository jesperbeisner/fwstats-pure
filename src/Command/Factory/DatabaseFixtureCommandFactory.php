<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\DatabaseFixtureCommand;
use Jesperbeisner\Fwstats\ImageService\RankingImageService;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class DatabaseFixtureCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): DatabaseFixtureCommand
    {
        /** @var mixed[] $config */
        $config = $serviceContainer->get('config');

        /** @var string $appEnv */
        $appEnv = $config['app_env'];

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $serviceContainer->get(PlayerRepository::class);

        /** @var ClanRepository $clanRepository */
        $clanRepository = $serviceContainer->get(ClanRepository::class);

        /** @var PlayerActiveSecondRepository $playerActiveSecondRepository */
        $playerActiveSecondRepository = $serviceContainer->get(PlayerActiveSecondRepository::class);

        /** @var RankingImageService $rankingImageService */
        $rankingImageService = $serviceContainer->get(RankingImageService::class);

        return new DatabaseFixtureCommand(
            $appEnv,
            $playerRepository,
            $clanRepository,
            $playerActiveSecondRepository,
            $rankingImageService,
        );
    }
}
