<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats;

use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\ProcessInterface;
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
        /** @var Config $config */
        $config = $this->container->get(Config::class);

        foreach ($config->getProcesses() as $processClassString) {
            /** @var ProcessInterface $process */
            $process = $this->container->get($processClassString);
            $process->run($request);
        }

        if (null === $controllerClassString = $request->getController()) {
            throw new RuntimeException('None of the processes has set a controller class string. This should not be possible?');
        }

        /** @var ControllerInterface $controller */
        $controller = $this->container->get($controllerClassString);
        $response = $controller->execute($request);

        if ($response->contentType === Response::CONTENT_TYPE_HTML) {
            /** @var RenderService $renderService */
            $renderService = $this->container->get(RenderService::class);
            $response->setRenderService($renderService);
        }

        return $response;
    }
}
