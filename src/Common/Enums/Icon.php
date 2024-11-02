<?php

namespace Patrikjak\Utils\Common\Enums;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Common\Exceptions\IconTypeNotFoundException;
use ValueError;

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


    /** @throws IconTypeNotFoundException */
    public function setType(IconType $type): self
    {
        try {
            return self::from(sprintf('%s_%s', $this->value, $type->value));
        } catch (ValueError) {
            throw new IconTypeNotFoundException($type);
        }
    }

    public function getAsView(): string
    {
        return Blade::render(sprintf('%s/%s.blade.php', resource_path('views/icons'), $this->value));
    }

    public function getAsImage(): string
    {
        return sprintf('%s/%s.svg', resource_path('assets/images/icons'), $this->value);
    }
}
