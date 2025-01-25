<?php

namespace Patrikjak\Utils\Tests\Unit\Table\Http\Request\Traits;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Table\Http\Requests\Traits\HandlesBulkActionsIds;

class HandlesBulkActionsIdsTest extends TestCase
{
    public function testGetBulkActionsIdsDefault(): void
    {
        $request = new class extends FormRequest {
            use HandlesBulkActionsIds;
        };

        $request->replace(['bulkActionsIds' => []]);

        $this->assertInstanceOf(Collection::class, $request->getBulkActionsIds());
        $this->assertSame([], $request->getBulkActionsIds()->all());
    }

    public function testGetBulkActionsIdsFromRequest(): void
    {
        $request = new class extends FormRequest {
            use HandlesBulkActionsIds;
        };
        
        $request->replace(['bulkActionsIds' => [1, 2, 3]]);

        $this->assertInstanceOf(Collection::class, $request->getBulkActionsIds());
        $this->assertSame([1, 2, 3], $request->getBulkActionsIds()->all());
    }
}