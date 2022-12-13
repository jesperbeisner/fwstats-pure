<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Stdlib\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;

final readonly class ImageController implements ControllerInterface
{
    public function __invoke(): ResponseInterface
    {
        return new HtmlResponse('image/ranking.phtml');
    }
}
