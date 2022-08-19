<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service;

use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;

final class ViewRenderService
{
    private const TITLE = 'FWSTATS';
    private const VIEWS_FOLDER = ROOT_DIR . '/views/';

    private ?string $title = null;

    /**
     * @param array<string, mixed> $vars
     */
    public function __construct(
        private readonly string $template,
        private readonly array $vars = [],
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

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->vars)) {
            return $this->vars[$id];
        }

        throw new RuntimeException();
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
}
