<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class EmptyState extends Component
{
    public function __construct(
        public string $message = 'No data available',
        public ?string $description = null,
    ) {
    }

    public function render(): View
    {
        return view('pjutils::components.empty-state');
    }
}
