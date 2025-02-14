<?php

declare(strict_types = 1);

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

    public function getAsHtml(): string
    {
        return file_get_contents(
            sprintf('%s/../../../resources/views/icons/%s.blade.php', __DIR__, $this->value),
        );
    }

    public function getImagePath(): string
    {
        return asset(sprintf('vendor/pjutils/assets/images/icons/%s.svg', $this->value));
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
