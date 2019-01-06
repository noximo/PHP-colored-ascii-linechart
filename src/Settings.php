<?php

declare(strict_types=1);

namespace noximo\PHPColoredAsciiLinechart;

use noximo\PHPColoredAsciiLinechart\Colorizers\AsciiColorizer;
use noximo\PHPColoredAsciiLinechart\Colorizers\IColorizer;

/**
 * Class Config
 * @package noximo\PHPColoredConsoleLinegraph
 */
final class Settings
{
    /**
     * @var callable
     */
    private $format;

    /**
     * @var int
     */
    private $offset = 2;

    /**
     * @var string
     */
    private $padding = '      ';

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
        $this->format = function ($x, self $settings) {
            $padding = $settings->getPadding();
            $paddingLength = \strlen($padding);

            return substr($padding . round($x, 2), -$paddingLength);
        };
    }
    
    public function getPadding(): string
    {
        return $this->padding;
    }
    
    public function setPadding(int $length, ?string $char = null): self
    {
        if ($char === null || $char === '') {
            $padding = ' ';
        } else {
            $padding = $char;
        }
        $this->padding = str_pad('', $length, $padding);

        return $this;
    }
    
    public function getHeight(): ?int
    {
        return $this->height;
    }
    
    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }
    
    public function getOffset(): int
    {
        return $this->offset;
    }
    
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }
    
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
     */
    public function setFormat(callable $format): self
    {
        $this->format = $format;

        return $this;
    }
    
    public function getFps(): int
    {
        return $this->fps;
    }
    
    public function setFPS(int $fps): self
    {
        $this->fps = $fps;

        return $this;
    }
    
    public function getColorizer(): IColorizer
    {
        return $this->colorizer ?? new AsciiColorizer();
    }
    
    public function setColorizer(IColorizer $colorizer): self
    {
        $this->colorizer = $colorizer;

        return $this;
    }
}
