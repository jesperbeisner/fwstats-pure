<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process;

use Jesperbeisner\Fwstats\Interface\LoggerInterface;
use Jesperbeisner\Fwstats\Interface\StartProcessInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;
use Throwable;

final readonly class ExceptionHandlerStartProcess implements StartProcessInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private string $appEnv,
    ) {
    }

    public function run(Request $request): void
    {
        set_exception_handler(function (Throwable $e): never {
            $this->logger->error($e->getMessage());

            if ($this->appEnv === 'prod') {
                Response::text('Something went wrong, please try again later.', 500);
            }

            throw $e;
        });
    }
}
