<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\Service;

use Jesperbeisner\Fwstats\Service\ViewRenderService;
use Jesperbeisner\Fwstats\Tests\Unit\TestCase;

final class ViewRenderServiceTest extends TestCase
{
    public function test_title_creation(): void
    {
        $viewRenderService = new ViewRenderService('');
        self::assertSame('fwstats.de', $viewRenderService->getTitle());
        $viewRenderService->setTitle('Home');
        self::assertSame('Home - fwstats.de', $viewRenderService->getTitle());
    }
}
