<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service;

use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Interface\TranslatorInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;

final class RenderService
{
    private ?string $title = null;

    public function __construct(
        private readonly string $viewsDirectory,
        private readonly string $appEnv,
        private readonly Request $request,
        private readonly SessionInterface $session,
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @param array<string, mixed> $vars
     */
    public function render(string $template, array $vars = []): string
    {
        $templateFile = $this->viewsDirectory . '/' . $template;
        $layoutFile = $this->viewsDirectory . '/_layout/layout.phtml';

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

    /**
     * @param array<string, string|int|float> $replacements
     */
    public function text(string $text, array $replacements = []): string
    {
        return $this->escape($this->translate($text, $replacements));
    }

    public function escape(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * @param array<string, string|int|float> $replacements
     */
    public function translate(string $text, array $replacements = []): string
    {
        return $this->translator->translate($text, $replacements);
    }

    public function setTitle(string $title): void
    {
        $this->title = $this->text($title);
    }

    public function getTitle(): string
    {
        return $this->title === null ? 'FWSTATS' : $this->title . ' - FWSTATS';
    }

    public function getAppEnv(): string
    {
        return $this->appEnv;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
