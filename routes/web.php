<?php

use Illuminate\Support\Facades\Route;
use Patrikjak\Utils\Table\Http\Controllers\TableController;

Route::prefix('pjutils')->group(static function (): void {
    Route::get('/table/filter-form/{type}', [TableController::class, 'form'])
        ->name('table.filter-modal');
});