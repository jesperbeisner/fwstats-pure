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
        self::assertSame('FWSTATS', $viewRenderService->getTitle());

        $viewRenderService->setTitle('Index');
        self::assertSame('Index - FWSTATS', $viewRenderService->getTitle());
    }
}
