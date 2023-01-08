<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Application;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../bootstrap.php';

(new Application($container))->handle(Request::fromGlobals())->send();
