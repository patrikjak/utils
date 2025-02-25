<?php

declare(strict_types = 1);

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

    public function testModalFormWithoutType(): void
    {
        $response = $this->get(route('table.filter-modal', ['type' => 'wrong']));

        $response->assertBadRequest();
    }
}