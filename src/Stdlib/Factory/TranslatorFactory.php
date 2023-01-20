<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\TranslatorInterface;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Translator;

final readonly class TranslatorFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): TranslatorInterface
    {
        return new Translator(
            $container->get(Config::class)->getString('translations_directory'),
        );
    }
}
