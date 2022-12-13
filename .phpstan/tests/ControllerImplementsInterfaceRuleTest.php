<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\PHPStan\Tests;

use Jesperbeisner\Fwstats\PHPStan\ControllerImplementsInterfaceRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

final class ControllerImplementsInterfaceRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ControllerImplementsInterfaceRule();
    }

    public function test_controller_rule(): void
    {
        $this->analyse(
            [
                __DIR__ . '/Fixtures/ControllerWithoutInterface.php',
            ],
            [
                ['Controllers need to implement "Jesperbeisner\Fwstats\Stdlib\Interface\ControllerInterface".', 7],
            ]
        );
    }
}
