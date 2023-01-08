<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\LoggerInterface;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Logger;

final class LoggerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): LoggerInterface
    {
        /** @var Config $config */
        $config = $container->get(Config::class);

        $logFile = $config->getString('log_file');

        return new Logger($logFile);
    }
}
