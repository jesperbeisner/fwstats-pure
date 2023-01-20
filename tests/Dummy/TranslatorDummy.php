<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Dummy;

use Jesperbeisner\Fwstats\Enum\LocaleEnum;
use Jesperbeisner\Fwstats\Interface\TranslatorInterface;

final readonly class TranslatorDummy implements TranslatorInterface
{
    public function translate(string $string, array $replacements = []): string
    {
        return $string;
    }

    public function setLocale(LocaleEnum $localeEnum): void
    {
    }

    public function getLocale(): LocaleEnum
    {
        return LocaleEnum::DE;
    }
}
