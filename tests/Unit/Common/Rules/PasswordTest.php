<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Unit\Common\Rules;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Common\Rules\Password;
use PHPUnit\Framework\Attributes\DataProvider;

class PasswordTest extends TestCase
{
    /**
     * @param array<array<string>> $messages
     */
    #[DataProvider('passwordDataProvider')]
    public function testPassword(
        ?string $password,
        bool $passes = false,
        array $messages = [],
    ): void {
        $validator = Validator::make(
            ['password' => $password],
            ['password' => new Password()],
        );

        $this->assertSame($passes, $validator->passes());
        $this->assertSame($messages, $validator->errors()->get('password'));
    }

    /**
     * @return iterable<array<string|bool|array<string>>>
     */
    public static function passwordDataProvider(): iterable
    {
        yield 'Null' => [null, false, ['pjutils::validation.required']];
        yield 'Min length' => ['pass', false, ['pjutils::validation.password.min']];
        yield 'Max length' => [strtolower(Str::random(192)), false, ['pjutils::validation.password.max']];
        yield 'Valid format' => ['Password123', true, []];
    }
}