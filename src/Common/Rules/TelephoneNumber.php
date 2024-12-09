<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Patrikjak\Utils\Common\Services\TelephonePattern;

final readonly class TelephoneNumber implements ValidationRule
{
    public function __construct(private TelephonePattern $pattern = TelephonePattern::SK)
    {
    }

    /**
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            $fail(__('pjutils::validation.required', ['attribute' => $attribute]));

            return;
        }

        if (!preg_match($this->pattern->value, $value)) {
            $fail(__('pjutils::validation.telephone_format', ['format' => $this->pattern->getExample()]));
        }
    }
}
