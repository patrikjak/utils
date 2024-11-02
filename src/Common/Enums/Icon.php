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
