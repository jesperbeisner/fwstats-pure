<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\ImageController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;

final readonly class ImageControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ImageController
    {
        return new ImageController();
    }
}
