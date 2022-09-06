<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\ImageController;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Psr\Container\ContainerInterface;

final class ImageControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): ImageController
    {
        /** @var string $rootDir */
        $rootDir = $serviceContainer->get('rootDir');

        /** @var Request $request */
        $request = $serviceContainer->get(Request::class);

        return new ImageController(
            $rootDir,
            $request,
        );
    }
}
