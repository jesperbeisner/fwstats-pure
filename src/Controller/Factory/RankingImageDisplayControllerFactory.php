<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\RankingImageDisplayController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Config;

final readonly class RankingImageDisplayControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RankingImageDisplayController
    {
        return new RankingImageDisplayController(
            $container->get(Config::class)->getRootDir(),
        );
    }
}
