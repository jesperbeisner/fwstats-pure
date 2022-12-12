<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Response;

use RuntimeException;

final class ImageResponse extends Response
{
    private const PLACEHOLDER_IMAGE = __DIR__ . '/../../data/images/404-image.png';

    public function __construct(
        private readonly string $imageFileName
    ) {
    }

    protected function getStatusCode(): int
    {
        if (file_exists($this->imageFileName)) {
            return 200;
        }

        return 404;
    }

    protected function getHeaders(): array
    {
        $headers = [
            'Content-Type: image/png',
            'Cache-Control: no-cache',
        ];

        if (file_exists($this->imageFileName)) {
            $headers[] = 'Content-Length: ' . filesize($this->imageFileName);
        } else {
            $headers[] = 'Content-Length: ' . filesize(ImageResponse::PLACEHOLDER_IMAGE);
        }

        return $headers;
    }

    protected function getContent(): string
    {
        if (file_exists($this->imageFileName)) {
            $imageFile = file_get_contents($this->imageFileName);
        } else {
            $imageFile = file_get_contents(ImageResponse::PLACEHOLDER_IMAGE);
        }

        if ($imageFile === false) {
            throw new RuntimeException('Could not read image file.');
        }

        return $imageFile;
    }
}
