<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\ImageService;

use GdImage;
use RuntimeException;

abstract class AbstractImageService implements ImageServiceInterface
{
    protected const IMAGE_FOLDER = ROOT_DIR . '/data/images/';
    private const ROBOTO_FONT = ROOT_DIR . '/data/fonts/Roboto-Light.ttf';

    protected ?GdImage $image = null;
    protected ?int $colorBlack = null;
    protected ?int $colorWhite = null;

    protected function createImage(int $width, int $height): void
    {
        $this->image = imagecreate($width, $height);
    }

    protected function setBackgroundColor(int $color): void
    {
        $this->checkImage();

        if (false === imagefill($this->image, 0, 0, $color)) {
            throw new RuntimeException('Could not set background color.');
        }
    }

    protected function write(string $text, int $x, int $y, int $size = null, int $color = null, int $angle = 0): void
    {
        $this->checkImage();

        if ($size === null) {
            $size = 14;
        }

        if ($color === null) {
            $color = $this->colorBlack();
        }

        if (false === imagettftext($this->image, $size, $angle, $x, $y, $color, self::ROBOTO_FONT, $text)) {
            throw new RuntimeException('Could not write to image.');
        }
    }

    protected function save(string $fileName): void
    {
        $this->checkImage();

        if (file_exists($fileName)) {
            if (false === unlink($fileName)) {
                throw new RuntimeException("Could not unlink '$fileName'.");
            }
        }

        if (false === imagepng($this->image, $fileName)) {
            throw new RuntimeException('Could not save the image.');
        }

        $this->colorBlack = null;
        $this->colorWhite = null;

        $this->image = null;
    }

    protected function colorBlack(): int
    {
        $this->checkImage();

        if ($this->colorBlack !== null) {
            return $this->colorBlack;
        }

        if (false === $color = imagecolorallocate($this->image, 0, 0, 0)) {
            throw new RuntimeException('Could not allocate image color.');
        }

        $this->colorBlack = $color;

        return $this->colorBlack;
    }

    protected function colorWhite(): int
    {
        $this->checkImage();

        if ($this->colorWhite !== null) {
            return $this->colorWhite;
        }

        if (false === $color = imagecolorallocate($this->image, 255, 255, 255)) {
            throw new RuntimeException('Could not allocate image color.');
        }

        $this->colorWhite = $color;

        return $this->colorWhite;
    }

    protected function checkImage(): void
    {
        if ($this->image === null) {
            throw new RuntimeException("Did you forget to run 'imageCreate'?");
        }
    }
}
