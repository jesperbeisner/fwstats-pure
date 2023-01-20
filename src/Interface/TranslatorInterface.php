<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Enum\LocaleEnum;

interface TranslatorInterface
{
    /**
     * @param array<string, string|int|float> $replacements
     */
    public function translate(string $string, array $replacements = []): string;

    public function setLocale(LocaleEnum $localeEnum): void;

    public function getLocale(): LocaleEnum;
}
