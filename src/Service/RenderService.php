<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service;

use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\SessionInterface;

final class RenderService
{
    private ?string $title = null;

    public function __construct(
        private readonly string $viewsDir,
        private readonly SessionInterface $session,
    ) {
    }

    public function render(string $template, array $vars = []): string
    {
        $templateFile = $this->viewsDir . '/' . $template;
        $layoutFile = $this->viewsDir . '/_layout/layout.phtml';

        if (!is_file($templateFile)) {
            throw new RuntimeException(sprintf('Template file "%s" does not exist.', $templateFile));
        }

        ob_start();
        require $templateFile;
        if (false === $content = ob_get_clean()) {
            throw new RuntimeException(sprintf('"ob_get_clean" returned false for template file "%s".', $templateFile));
        }

        if (!is_file($layoutFile)) {
            throw new RuntimeException(sprintf('Layout file "%s" does not exist.', $layoutFile));
        }

        ob_start();
        require $layoutFile;
        if (false === $content = ob_get_clean()) {
            throw new RuntimeException(sprintf('"ob_get_clean" returned false for layout file "%s".', $layoutFile));
        }

        return $content;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title === null ? 'FWSTATS' : $this->title . ' - FWSTATS';
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }
}
