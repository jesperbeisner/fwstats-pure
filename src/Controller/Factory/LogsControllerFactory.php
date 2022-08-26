<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\LogsController;
use Jesperbeisner\Fwstats\Repository\LogRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Psr\Container\ContainerInterface;

final class LogsControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): LogsController
    {
        /** @var mixed[] $config */
        $config = $serviceContainer->get('config');

        /** @var string $logsPassword */
        $logsPassword = $config['logs_password'];

        /** @var Request $request */
        $request = $serviceContainer->get(Request::class);

        /** @var LogRepository $logRepository */
        $logRepository = $serviceContainer->get(LogRepository::class);

        return new LogsController($logsPassword, $request, $logRepository);
    }
}
