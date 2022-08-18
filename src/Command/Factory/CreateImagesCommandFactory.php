<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\CreateImagesCommand;
use Jesperbeisner\Fwstats\ImageService\RankingImageService;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class CreateImagesCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): CreateImagesCommand
    {
        /** @var RankingImageService $rankingImageService */
        $rankingImageService = $serviceContainer->get(RankingImageService::class);

        return new CreateImagesCommand($rankingImageService);
    }
}
