<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Builder;

use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Common\Icon;
use Patrikjak\Utils\Table\ValueObjects\Cells\Chip;
use Patrikjak\Utils\Table\ValueObjects\Cells\Link;
use Patrikjak\Utils\Table\ValueObjects\Cells\Simple;
use Patrikjak\Utils\Table\ValueObjects\Cells\TwoLine;

final class Cell
{
    private function __construct()
    {
    }

    public static function simple(
        string $value,
        ?Icon $icon = null,
        ?int $maxLength = null,
        bool $noTruncation = false,
    ): Simple {
        return new Simple($value, $icon, $maxLength, $noTruncation);
    }

    public static function twoLine(
        string $value,
        string $addition,
        ?int $maxLength = null,
        bool $noTruncation = false,
    ): TwoLine {
        return new TwoLine($value, $addition, $maxLength, $noTruncation);
    }

    public static function chip(
        string $value,
        Type $type = Type::NEUTRAL,
        ?int $maxLength = null,
        bool $noTruncation = false,
    ): Chip {
        return new Chip($value, $type, $maxLength, $noTruncation);
    }

    public static function link(
        string $value,
        string $href,
        ?int $maxLength = null,
        bool $noTruncation = false,
    ): Link {
        return new Link($value, $href, $maxLength, $noTruncation);
    }
}
