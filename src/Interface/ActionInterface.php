<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Exception\ActionException;

interface ActionInterface
{
    /**
     * @param array<string, mixed> $data
     * @return ActionInterface
     * @throws ActionException
     */
    public function configure(array $data): ActionInterface;

    /**
     * @throws ActionException
     */
    public function run(): ActionResultInterface;
}
