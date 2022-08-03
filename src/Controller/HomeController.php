<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Stdlib\HtmlResponse;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;

final class HomeController extends AbstractController
{
    public function __invoke(): ResponseInterface
    {
        return new HtmlResponse('home/home.phtml');
    }
}
