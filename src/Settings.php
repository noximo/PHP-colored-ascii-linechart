<?php
declare(strict_types = 1);

namespace noximo\PHPColoredConsoleLinegraph;

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
    private $padding = '   ';

    /**
     * @var callable
     */
    private $format;

    /**
     * @var int
     */
    private $height;

    /**
     * Config constructor.
     *
     * @param int $offset
     */
    public function __construct()
    {
        $this->format = function ($x, Settings $config) {
            $padding = $config->getPadding();
            $paddingLength = strlen($padding);
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
     * @param string $padding
     *
     * @return Settings
     */
    public function setPadding(int $length, string $char = ' '): Settings
    {
        $padding = strlen($char) === '' ? ' ' : $char;
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
        if (empty($this->height)) {
            $this->height = $range;
        }
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
     * @param callable $format = function ($x, Config $config) {
     *      $padding = $config->getPadding();
     *      $paddingLength = strlen($padding);
     *      return substr($padding . (string) round($x, 2), $paddingLength);
     * };
     *
     * @return Settings
     */
    public function setFormat(callable $format): Settings
    {
        $this->format = $format;
        return $this;
    }
}