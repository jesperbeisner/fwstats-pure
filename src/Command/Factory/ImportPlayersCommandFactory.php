<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\ImportPlayersCommand;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Database;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class ImportPlayersCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, string $serviceName): ImportPlayersCommand
    {
        /** @var Database $database */
        $database = $container->get(Database::class);

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $database->getRepository(PlayerRepository::class);

        return new ImportPlayersCommand($playerRepository);
    }
}
