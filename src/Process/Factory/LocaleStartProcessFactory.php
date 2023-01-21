<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\TranslatorInterface;
use Jesperbeisner\Fwstats\Process\LocaleStartProcess;

final readonly class LocaleStartProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): LocaleStartProcess
    {
        return new LocaleStartProcess(
            $container->get(TranslatorInterface::class),
        );
    }
}
