<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Unit\Common\Rules;

use Illuminate\Support\Facades\Validator;
use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Common\Rules\TelephoneNumber;
use Patrikjak\Utils\Common\Services\TelephonePattern;
use PHPUnit\Framework\Attributes\DataProvider;

class TelephoneNumberTest extends TestCase
{
    #[DataProvider('telephoneNumberDataProvider')]
    public function testTelephoneNumber(
        ?string $telephoneNumber,
        TelephonePattern $pattern = TelephonePattern::SK,
        bool $passes = false,
    ): void {
        $validator = Validator::make(
            ['telephone' => $telephoneNumber],
            ['telephone' => new TelephoneNumber($pattern)],
        );

        $this->assertSame($passes, $validator->passes());
    }

    /**
     * @param array<string> $patterns
     */
    #[DataProvider('telephoneNumberMultipleFormatsDataProvider')]
    public function testMultipleFormats(?string $telephoneNumber, array $patterns, bool $passes = false): void
    {
        $validator = Validator::make(
            ['telephone' => $telephoneNumber],
            ['telephone' => new TelephoneNumber($patterns)],
        );

        $this->assertSame($passes, $validator->passes());
    }

    /**
     * @return iterable<array<string|bool>>
     */
    public static function telephoneNumberDataProvider(): iterable
    {
        yield 'Null' => [null, TelephonePattern::SK, false];
        yield 'Invalid format' => ['+4212345678', TelephonePattern::SK, false];
        yield 'Wrong pattern' => ['+4212345678', TelephonePattern::CZ, false];
        yield 'Valid format' => ['+421123456789', TelephonePattern::SK, true];
        yield 'Invalid format CZ' => ['+42012345678', TelephonePattern::CZ, false];
        yield 'Valid format CZ' => ['+420123456789', TelephonePattern::CZ, true];
        yield 'Valid format international 1' => ['+421123456789', TelephonePattern::INTERNATIONAL, true];
        yield 'Valid format international 2' => ['+420123456789', TelephonePattern::INTERNATIONAL, true];
        yield 'Valid format international 3' => ['+393201234567', TelephonePattern::INTERNATIONAL, true]; // Italy
        yield 'Valid format international 4' => ['+12025550123', TelephonePattern::INTERNATIONAL, true]; // USA
        yield 'Valid format international 5' => ['+44123456789', TelephonePattern::INTERNATIONAL, true]; // UK
        yield 'Invalid format international 1' => ['393201234567', TelephonePattern::INTERNATIONAL, false];
        yield 'Invalid format international 2' => ['+44 7123 456789', TelephonePattern::INTERNATIONAL, false];
        yield 'Invalid format international 3' => ['+1234567890123456', TelephonePattern::INTERNATIONAL, false];
        yield 'Invalid format international 4' => ['+1234567890123456', TelephonePattern::INTERNATIONAL, false];
        yield 'Invalid format international 5' => ['+39-320-1234567', TelephonePattern::INTERNATIONAL, false];
        yield 'Invalid format international 6' => ['++393201234567', TelephonePattern::INTERNATIONAL, false];
    }

    /**
     * @return iterable<array<string|bool>>
     */
    public static function telephoneNumberMultipleFormatsDataProvider(): iterable
    {
        yield 'Null' => [null, [TelephonePattern::SK], false];
        yield 'Invalid format' => ['+4212345678', [TelephonePattern::SK], false];
        yield 'Wrong pattern' => ['+4212345678', [TelephonePattern::CZ], false];
        yield 'Valid format' => ['+421123456789', [TelephonePattern::SK, TelephonePattern::CZ], true];
        yield 'Invalid format CZ' => ['+42012345678', [TelephonePattern::SK, TelephonePattern::CZ], false];
        yield 'Valid format CZ' => ['+420123456789', [TelephonePattern::SK, TelephonePattern::CZ], true];
        yield 'Valid format international 1' => [
            '+421123456789',
            [TelephonePattern::SK, TelephonePattern::INTERNATIONAL],
            true,
        ];

        yield 'Valid format international 2' => [
            '+420123456789',
            [TelephonePattern::SK, TelephonePattern::INTERNATIONAL],
            true,
        ];

        yield 'Valid format international 3' => [
            '+393201234567',
            [TelephonePattern::SK, TelephonePattern::INTERNATIONAL],
            true,
        ]; // Italy

        yield 'Invalid format international 1' => [
            '393201234567',
            [TelephonePattern::SK, TelephonePattern::INTERNATIONAL],
            false,
        ];

        yield 'Invalid instance' => ['+421123456789', ['invalid'], false];
    }
}