<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Enums;

enum Icon: string
{
    case CHECK = 'check';
    case WARNING = 'warning';
    case EDIT = 'edit';
    case TRASH = 'trash';
    case EYE = 'eye';
    case EYE_SLASH = 'eye_slash';
    case CIRCLE_EXCLAMATION = 'circle_exclamation';
    case INFO = 'info';

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
        return file_get_contents(
            sprintf('%s/../../../resources/views/icons/%s.blade.php', __DIR__, $icon),
        );
    }

    public static function getCustomImagePath(string $icon): string
    {
        return asset(sprintf('vendor/pjutils/assets/images/icons/%s.svg', $icon));
    }
}
