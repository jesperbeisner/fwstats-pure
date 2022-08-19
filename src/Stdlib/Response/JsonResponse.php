<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Response;

use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use JsonException;

final class JsonResponse implements ResponseInterface
{
    /**
     * @param array<string, mixed> $vars
     */
    public function __construct(
        private readonly array $vars = [],
        private readonly int $statusCode = 200,
    ) {
    }

    public function send(): never
    {
        try {
            $json = json_encode($this->vars, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException('Could not encode the json response');
        }

        http_response_code($this->statusCode);
        header('Content-Type: application/json');

        echo $json;

        exit(0);
    }
}
