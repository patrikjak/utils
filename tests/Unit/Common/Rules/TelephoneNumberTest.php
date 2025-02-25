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
    }
}