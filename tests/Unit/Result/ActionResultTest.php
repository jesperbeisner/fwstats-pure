<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\Result;

use Jesperbeisner\Fwstats\Enum\ResultEnum;
use Jesperbeisner\Fwstats\Result\ActionResult;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;

/**
 * @covers \Jesperbeisner\Fwstats\Result\ActionResult
 */
final class ActionResultTest extends AbstractTestCase
{
    public function test_it_works(): void
    {
        $result = new ActionResult(ResultEnum::SUCCESS, 'test', ['test' => 'test']);

        self::assertTrue($result->isSuccess());
        self::assertSame('test', $result->getMessage());
        self::assertSame(['test' => 'test'], $result->getData());
    }
}
