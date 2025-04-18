<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Dto;

readonly class Image
{
    public function __construct(public string $src, public string $alt, public ?string $fileName = null)
    {
    }
}