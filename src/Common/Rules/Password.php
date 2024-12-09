<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Patrikjak\Utils\Common\Helpers\GrammaticalGender;

class Password implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail(trans_choice('pjutils::validation.required', GrammaticalGender::NEUTER));

            return;
        }

        if (mb_strlen($value) < 8) {
            $fail(__('pjutils::validation.password.min'));

            return;
        }

        if (mb_strlen($value) > 191) {
            $fail(__('pjutils::validation.password.max'));

            return;
        }

        if (!preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value)) {
            $fail(__('pjutils::validation.password.mixed'));
        }
    }
}
