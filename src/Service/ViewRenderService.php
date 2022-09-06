<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service;

use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;

final class ViewRenderService
{
    private const TITLE = 'FWSTATS';
    private const VIEWS_FOLDER = __DIR__ . '/../../views/';

    private ?string $title = null;

    /**
     * @param array<string, mixed> $vars
     */
    public function __construct(
        private readonly string $template,
        private readonly array $vars,
        private readonly SessionInterface $session,
    ) {
    }

    public function render(): string
    {
        ob_start();
        require self::VIEWS_FOLDER . $this->template;
        if (false === $content = ob_get_clean()) {
            throw new RuntimeException("'ob_get_clean' returned false! o.O");
        }

        ob_start();
        require self::VIEWS_FOLDER . 'layout.phtml';
        if (false === $content = ob_get_clean()) {
            throw new RuntimeException("'ob_get_clean' returned false! o.O");
        }

        return $content;
    }

    public function get(string $key): mixed
    {
        if (array_key_exists($key, $this->vars)) {
            return $this->vars[$key];
        }

        throw new RuntimeException("The variable with key '$key' does not exist.");
    }

    public function getTitle(): string
    {
        if ($this->title === null) {
            return self::TITLE;
        }

        return $this->title . ' - ' . self::TITLE;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }
}
