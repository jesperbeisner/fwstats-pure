<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\FlashEnum;
use Jesperbeisner\Fwstats\Enum\LocaleEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

final readonly class LocaleController implements ControllerInterface
{
    public function __construct(
        private SessionInterface $session,
    ) {
    }

    public function execute(Request $request): Response
    {
        if (null === $locale = $request->getGetParameter('locale')) {
            return Response::redirect('/');
        }

        if (null === $localeEnum = LocaleEnum::tryFrom($locale)) {
            return Response::redirect('/');
        }

        $this->session->setFlash(FlashEnum::SUCCESS, 'text.translation-change-success');

        $response = Response::redirect('/');
        $response->setCookie('locale', $localeEnum->value);

        return $response;
    }
}
