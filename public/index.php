<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\App;
use Jesperbeisner\Fwstats\Stdlib\ServiceContainer;

/** @var ServiceContainer $serviceContainer */
$serviceContainer = require __DIR__ . '/../bootstrap.php';

(new App($serviceContainer))->run();
