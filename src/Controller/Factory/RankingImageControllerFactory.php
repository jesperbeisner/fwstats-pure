<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\RankingImageController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\ConfigRepository;

final readonly class RankingImageControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RankingImageController
    {
        return new RankingImageController(
            $container->get(ConfigRepository::class),
        );
    }
}
