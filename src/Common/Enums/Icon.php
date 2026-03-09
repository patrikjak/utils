<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Enums;

use Patrikjak\Utils\Common\Traits\EnumValues;
use RuntimeException;

enum Icon: string
{
    use EnumValues;

    case CHECK = 'check';
    case WARNING = 'warning';
    case EDIT = 'edit';
    case TRASH = 'trash';
    case EYE = 'eye';
    case EYE_SLASH = 'eye_slash';
    case CIRCLE_EXCLAMATION = 'circle_exclamation';
    case INFO = 'info';
    case SORT = 'sort';
    case SORT_ASC = 'sort_asc';
    case SORT_DESC = 'sort_desc';
    case FILTER = 'filter';
    case SEARCH = 'search';

    public function getAsHtml(): string
    {
        return svg($this->getIconName())->toHtml();
    }

    public function getIconName(): string
    {
        return match ($this) {
            self::CHECK => 'heroicon-o-check',
            self::WARNING => 'heroicon-o-exclamation',
            self::EDIT => 'heroicon-o-pencil-alt',
            self::TRASH => 'heroicon-o-trash',
            self::EYE => 'heroicon-o-eye',
            self::EYE_SLASH => 'heroicon-o-eye-off',
            self::CIRCLE_EXCLAMATION => 'heroicon-o-exclamation-circle',
            self::INFO => 'heroicon-o-information-circle',
            self::SORT => 'heroicon-o-switch-vertical',
            self::SORT_ASC => 'heroicon-o-sort-ascending',
            self::SORT_DESC => 'heroicon-o-sort-descending',
            self::FILTER => 'heroicon-o-filter',
            self::SEARCH => 'heroicon-o-search',
        };
    }

    public static function getCustomAsHtml(string $icon): string
    {
        $path = resource_path(sprintf('views/icons/%s.blade.php', $icon));
        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException(sprintf('Icon file not found: %s', $path));
        }

        return $contents;
    }

    public static function getCustomImagePath(string $icon): string
    {
        return asset(sprintf('images/icons/%s.svg', $icon));
    }
}
