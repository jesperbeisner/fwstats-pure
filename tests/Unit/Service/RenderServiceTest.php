<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\Service;

use Jesperbeisner\Fwstats\Service\RenderService;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use Jesperbeisner\Fwstats\Tests\Doubles\SessionFake;
use Jesperbeisner\Fwstats\Tests\Doubles\TranslatorDummy;

/**
 * @covers \Jesperbeisner\Fwstats\Service\RenderService
 */
final class RenderServiceTest extends AbstractTestCase
{
    public function test_title_creation(): void
    {
        $viewRenderService = new RenderService('', '', new Request([], [], [], [], []), new SessionFake(), new TranslatorDummy());
        self::assertSame('FWSTATS', $viewRenderService->getTitle());

        $viewRenderService->setTitle('Index');
        self::assertSame('Index - FWSTATS', $viewRenderService->getTitle());
    }
}
