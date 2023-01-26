<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\Result;

use Jesperbeisner\Fwstats\Result\ImportResult;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;

/**
 * @covers \Jesperbeisner\Fwstats\Result\ImportResult
 */
final class ImportResultTest extends AbstractTestCase
{
    public function test_it_works(): void
    {
        $result = new ImportResult();

        self::assertSame([], $result->getMessages());
        $result->addMessage('test');
        self::assertSame(['test'], $result->getMessages());
        $result->addMessage('test2');
        self::assertSame(['test', 'test2'], $result->getMessages());
    }
}
