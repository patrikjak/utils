<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Patrikjak\Utils\Common\Services\TelephonePattern;

final readonly class TelephoneNumber implements ValidationRule
{
    /**
     * @param TelephonePattern|array<TelephonePattern|mixed> $pattern
     */
    public function __construct(private TelephonePattern|array $pattern = TelephonePattern::SK)
    {
    }

    /**
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            $fail(__('pjutils::validation.required'));

            return;
        }

        if (!is_array($this->pattern)) {
            if (!preg_match($this->pattern->value, $value)) {
                $fail(__('pjutils::validation.telephone_format', ['format' => $this->pattern->getExample()]));
            }

            return;
        }

        foreach ($this->pattern as $pattern) {
            if (!$pattern instanceof TelephonePattern) {
                continue;
            }

            if (preg_match($pattern->value, $value)) {
                return;
            }
        }

        $fail(__('pjutils::validation.telephone_format_multiple'));
    }
}
