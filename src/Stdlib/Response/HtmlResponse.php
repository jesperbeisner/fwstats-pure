<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Response;

use Jesperbeisner\Fwstats\Service\ViewRenderService;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;

final class HtmlResponse implements ResponseInterface
{
    public function __construct(
        private readonly string $template,
        private readonly array $vars = [],
        private readonly int $statusCode = 200,
    ) {
    }

    public function send(): never
    {
        http_response_code($this->statusCode);
        echo (new ViewRenderService($this->template, $this->vars))->render();

        exit(0);
    }
}
