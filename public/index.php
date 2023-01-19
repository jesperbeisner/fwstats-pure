<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Application;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../bootstrap.php';

$request = Request::fromGlobals();
$container->set(Request::class, $request);

(new Application($container))->handle($request)->send();
