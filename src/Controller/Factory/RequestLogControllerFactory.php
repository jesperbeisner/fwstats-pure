<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\RequestLogController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\RequestLogRepository;

final readonly class RequestLogControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RequestLogController
    {
        return new RequestLogController(
            $container->get(RequestLogRepository::class),
        );
    }
}
