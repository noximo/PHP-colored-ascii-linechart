<?php
declare(strict_types = 1);

namespace noximo\PHPColoredAsciiLinechart;

use noximo\PHPColoredAsciiLinechart\Colorizers\AsciiColorizer;
use noximo\PHPColoredAsciiLinechart\Colorizers\IColorizer;

/**
 * Class Config
 * @package noximo\PHPColoredConsoleLinegraph
 */
class Settings
{
    /**
     * @var int
     */
    private $offset = 2;

    /**
     * @var string
     */
    private $padding = '      ';

    /**
     * @var callable
     */
    private $format;

    /**
     * @var int
     */
    private $height;
    /**
     * @var int
     */
    private $fps = 12;
    /**
     * @var IColorizer
     */
    private $colorizer;

    /**
     * Settings constructor.
     */
    public function __construct()
    {
        $this->format = function ($x, Settings $settings) {
            $padding = $settings->getPadding();
            $paddingLength = \strlen($padding);

            return substr($padding . round($x, 2), -$paddingLength);
        };
    }

    /**
     * @return string
     */
    public function getPadding(): string
    {
        return $this->padding;
    }

    /**
     * @param int $length
     * @param string $char
     *
     * @return Settings
     */
    public function setPadding(int $length, string $char = null): Settings
    {
        if ($char === null || $char === '') {
            $padding = ' ';
        } else {
            $padding = $char;
        }
        $this->padding = str_pad('', $length, $padding);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return Settings
     */
    public function setHeight(int $height): Settings
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @param int $range
     */
    public function setComputedHeight(int $range): void
    {
        $this->height = $this->height ?? $range;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     *
     * @return Settings
     */
    public function setOffset(int $offset): Settings
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return callable
     */
    public function getFormat(): callable
    {
        return $this->format;
    }

    /**
     * @param callable $format =function ($x, Settings $config) {
     * $padding = $config->getPadding();
     * $paddingLength = strlen($padding);
     * return substr($padding . round($x, 2), -$paddingLength);
     * }
     *
     * @return Settings
     */
    public function setFormat(callable $format): Settings
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return int
     */
    public function getFps(): int
    {
        return $this->fps;
    }

    /**
     * @param int $fps
     *
     * @return Settings
     */
    public function setFPS(int $fps): Settings
    {
        $this->fps = $fps;

        return $this;
    }

    /**
     * @return IColorizer
     */
    public function getColorizer(): IColorizer
    {
        return $this->colorizer ?? new AsciiColorizer();
    }

    /**
     * @param IColorizer $colorizer
     *
     * @return Settings
     */
    public function setColorizer(IColorizer $colorizer): Settings
    {
        $this->colorizer = $colorizer;

        return $this;
    }
}
