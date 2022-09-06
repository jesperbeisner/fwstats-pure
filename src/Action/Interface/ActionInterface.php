<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action\Interface;

interface ActionInterface
{
    /**
     * @param mixed[] $data
     */
    public function configure(array $data): void;

    public function run(): ActionResultInterface;
}
