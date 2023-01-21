<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Attribute\LoginRequired;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Repository\ConfigRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

#[LoginRequired]
final readonly class AdminController implements ControllerInterface
{
    public function __construct(
        private SessionInterface $session,
        private ConfigRepository $configRepository,
    ) {
    }

    public function execute(Request $request): Response
    {
        if (null === $bearerToken = $this->session->getUser()?->token) {
            throw new RuntimeException('This should not be possible? Looks like you messed up once again! :^)');
        }

        return Response::html('admin/admin.phtml', [
            'bearerToken' => $bearerToken,
            'domainName' => $this->configRepository->findByKey('domain-name')?->value ?? 'https://fwstats.de',
        ]);
    }
}
