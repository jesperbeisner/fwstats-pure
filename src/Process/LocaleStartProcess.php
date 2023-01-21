<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process;

use Jesperbeisner\Fwstats\Enum\LocaleEnum;
use Jesperbeisner\Fwstats\Interface\StartProcessInterface;
use Jesperbeisner\Fwstats\Interface\TranslatorInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;

final readonly class LocaleStartProcess implements StartProcessInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    public function run(Request $request): void
    {
        if (null !== $locale = $request->getCookieParameter('locale')) {
            if (null !== $localeEnum = LocaleEnum::tryFrom($locale)) {
                $this->translator->setLocale($localeEnum);
            }
        }
    }
}
