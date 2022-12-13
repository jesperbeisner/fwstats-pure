<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Class_>
 */
final class ControllerKeywordsRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $namespace = $scope->getNamespace();

        if ($namespace !== 'Jesperbeisner\Fwstats\Controller') {
            return [];
        }

        if ($node->isAbstract()) {
            return ['Abstract controllers are not allowed.'];
        }

        if (!$node->isFinal()) {
            return ['Controller classes need to be final.'];
        }

        if (!$node->isReadonly()) {
            return ['Controller classes need to be readonly.'];
        }

        return [];
    }
}
