<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Response;

use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;

final class ImageResponse implements ResponseInterface
{
    private const PLACEHOLDER_IMAGE = __DIR__ . '/../../data/images/404-image.png';

    public function __construct(
        private readonly string $imageFileName
    ) {
    }

    public function send(): never
    {
        header("Cache-Control: no-cache");
        header("Content-type: image/png");

        if (file_exists($this->imageFileName)) {
            header('Content-Length: ' . filesize($this->imageFileName));
            readfile($this->imageFileName);
        } else {
            header('Content-Length: ' . filesize(self::PLACEHOLDER_IMAGE));
            readfile(self::PLACEHOLDER_IMAGE);
        }

        exit(0);
    }
}
