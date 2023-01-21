<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Action\ChangeDomainNameAction;
use Jesperbeisner\Fwstats\Attribute\LoginRequired;
use Jesperbeisner\Fwstats\Enum\FlashEnum;
use Jesperbeisner\Fwstats\Exception\ActionException;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

#[LoginRequired]
final readonly class ChangeDomainNameController implements ControllerInterface
{
    public function __construct(
        private ChangeDomainNameAction $changeDomainNameAction,
        private SessionInterface $session,
    ) {
    }

    public function execute(Request $request): Response
    {
        if (null === $domainName = $request->getPostParameter('domain-name')) {
            $this->session->setFlash(FlashEnum::ERROR, 'text.no-domain-name-specified');

            return Response::redirect('/admin', 303);
        }

        try {
            $this->changeDomainNameAction->configure(['domainName' => $domainName])->run();
        } catch (ActionException $e) {
            $this->session->setFlash(FlashEnum::ERROR, $e->getMessage());

            return Response::redirect('/admin', 303);
        }

        $this->session->setFlash(FlashEnum::SUCCESS, 'Domain name changed successfully');

        return Response::redirect('/admin', 303);
    }
}
