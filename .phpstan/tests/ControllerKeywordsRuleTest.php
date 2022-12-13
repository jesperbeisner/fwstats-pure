<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\PHPStan\Tests;

use Jesperbeisner\Fwstats\PHPStan\ControllerKeywordsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

final class ControllerKeywordsRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ControllerKeywordsRule();
    }

    public function test_controller_rule(): void
    {
        $this->analyse(
            [
                __DIR__ . '/Fixtures/ControllerWithoutFinal.php',
                __DIR__ . '/Fixtures/ControllerWithoutReadonly.php',
                __DIR__ . '/Fixtures/AbstractController.php',
            ],
            [
                ['Controller classes need to be final.', 11],
                ['Controller classes need to be readonly.', 11],
                ['Abstract controllers are not allowed.', 9],
            ],
        );
    }
}
