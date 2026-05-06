<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common;

use Patrikjak\Utils\Common\Exceptions\IconFileNotFoundException;
use Patrikjak\Utils\Common\Exceptions\InvalidIconTypeException;

final readonly class Icon
{
    private const string TYPE_HEROICON = 'heroicon';
    private const string TYPE_SVG = 'svg';
    private const string TYPE_STORAGE = 'storage';

    private function __construct(
        private string $type,
        private string $value,
    ) {
    }

    public static function heroicon(string $name): self
    {
        return new self(self::TYPE_HEROICON, $name);
    }

    public static function svg(string $svgString): self
    {
        return new self(self::TYPE_SVG, $svgString);
    }

    public static function storage(string $path): self
    {
        return new self(self::TYPE_STORAGE, $path);
    }

    public function toHtml(): string
    {
        return match ($this->type) {
            self::TYPE_HEROICON => svg($this->value)->toHtml(),
            self::TYPE_SVG => $this->value,
            self::TYPE_STORAGE => $this->readStorageFile(),
            default => throw new InvalidIconTypeException($this->type),
        };
    }

    private function readStorageFile(): string
    {
        $contents = file_get_contents($this->value);

        if ($contents === false) {
            throw new IconFileNotFoundException($this->value);
        }

        return $contents;
    }
}
