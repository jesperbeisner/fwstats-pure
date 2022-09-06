<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Response;

use Jesperbeisner\Fwstats\Service\ViewRenderService;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;

final class HtmlResponse implements ResponseInterface
{
    private SessionInterface $session;

    /**
     * @param array<string, mixed> $vars
     */
    public function __construct(
        private readonly string $template,
        private readonly array $vars = [],
        private readonly int $statusCode = 200,
    ) {
    }

    public function send(): never
    {
        $viewRenderService = new ViewRenderService($this->template, $this->vars, $this->session);

        http_response_code($this->statusCode);
        echo $viewRenderService->render();

        exit(0);
    }

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }
}
