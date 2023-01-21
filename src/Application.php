<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats;

use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Service\RenderService;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

final readonly class Application
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function handle(Request $request): Response
    {
        $config = $this->container->get(Config::class);

        foreach ($config->getStartProcesses() as $startProcessClassString) {
            $this->container->get($startProcessClassString)->run($request);
        }

        if (null === $controllerClassString = $request->getController()) {
            throw new RuntimeException('None of the processes has set a controller class string. This should not be possible?');
        }

        $response = $this->container->get($controllerClassString)->execute($request);

        if ($response->contentType === Response::CONTENT_TYPE_HTML) {
            $response->setRenderService($this->container->get(RenderService::class));
        }

        foreach ($config->getEndProcesses() as $endProcessClassString) {
            $this->container->get($endProcessClassString)->run($request, $response);
        }

        return $response;
    }
}
