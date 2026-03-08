<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Enums;

use Patrikjak\Utils\Common\Traits\EnumValues;

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
        return svg('pjutils-' . $this->getIconName())->toHtml();
    }

    public function getIconName(): string
    {
        return str_replace('_', '-', $this->value);
    }

    public function getImagePath(): string
    {
        return asset(sprintf('vendor/pjutils/assets/images/icons/%s.svg', $this->getIconName()));
    }

    public static function getCustomAsHtml(string $icon): string
    {
        return file_get_contents(resource_path(sprintf('views/icons/%s.blade.php', $icon)));
    }

    public static function getCustomImagePath(string $icon): string
    {
        return asset(sprintf('images/icons/%s.svg', $icon));
    }
}
