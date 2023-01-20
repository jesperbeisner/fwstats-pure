<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\TranslatorInterface;
use Jesperbeisner\Fwstats\Process\LocaleProcess;

final readonly class LocaleProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): LocaleProcess
    {
        /** @var TranslatorInterface $translator */
        $translator = $container->get(TranslatorInterface::class);

        return new LocaleProcess($translator);
    }
}
