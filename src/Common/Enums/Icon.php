<?php

namespace Patrikjak\Utils\Common\Enums;

enum Icon: string
{
    case CHECK = 'check';
    case CHECK_SUCCESS = 'check_success';
    case CHECK_INFO = 'check_info';
    case CHECK_WHITE = 'check_white';

    case WARNING = 'warning';
    case WARINING_WARNING = 'warning_warning';
    case WARNING_DANGER = 'warning_danger';
    case WARNING_WHITE = 'warning_white';

    case EDIT = 'edit';
    case EDIT_WHITE = 'edit_white';

    case TRASH = 'trash';
    case TRASH_DANGER = 'trash_danger';
    case TRASH_WHITE = 'trash_white';

    case EYE = 'eye';
    case EYE_WHITE = 'eye_white';

    case EYE_SLASH = 'eye_slash';
    case EYE_SLASH_WHITE = 'eye_slash_white';


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
}
