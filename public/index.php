<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\App;
use Jesperbeisner\Fwstats\Middleware\MiddlewareInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../bootstrap.php';

/** @var array<class-string<MiddlewareInterface>> $middlewaresArray */
$middlewaresArray = require __DIR__ . '/../config/middlewares.php';

(new App($container, $middlewaresArray))->handle(Request::fromGlobals())->send();
