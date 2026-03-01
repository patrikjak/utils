<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Table\Http\Controllers;

use Patrikjak\Utils\Tests\Integration\Table\TestCase;

class TableControllerTest extends TestCase
{
    public function testModalForm(): void
    {
        $response = $this->get(route('table.filter-modal', ['type' => 'text']));

        $response->assertOk();
        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    public function testJsonModalForm(): void
    {
        $response = $this->get(route('table.filter-modal', ['type' => 'json']));

        $response->assertOk();
        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    public function testJsonModalFormWithPath(): void
    {
        $response = $this->get(route('table.filter-modal', [
            'type' => 'json',
            'jsonPath' => 'email',
        ]));

        $response->assertOk();
        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    public function testJsonModalFormWithNestedPath(): void
    {
        $response = $this->get(route('table.filter-modal', [
            'type' => 'json',
            'jsonPath' => 'user.address.city',
        ]));

        $response->assertOk();
        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    public function testJsonModalFormWithArrayPath(): void
    {
        $response = $this->get(route('table.filter-modal', [
            'type' => 'json',
            'jsonPath' => 'items[0]',
        ]));

        $response->assertOk();
        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    public function testJsonModalFormWithComplexPath(): void
    {
        $response = $this->get(route('table.filter-modal', [
            'type' => 'json',
            'jsonPath' => 'users[0].profile.settings.theme',
        ]));

        $response->assertOk();
        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    public function testJsonModalFormWithoutPath(): void
    {
        $response = $this->get(route('table.filter-modal', [
            'type' => 'json',
            'jsonPath' => null,
        ]));

        $response->assertOk();
        $this->assertMatchesJsonSnapshot($response->getContent());
    }

    public function testModalFormWithoutType(): void
    {
        $response = $this->get(route('table.filter-modal', ['type' => 'wrong']));

        $response->assertBadRequest();
    }
}
