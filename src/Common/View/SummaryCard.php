<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Enums\BadgeType;
use Patrikjak\Utils\Common\Icon;

final class SummaryCard extends Component
{
    public function __construct(
        public string $title,
        public ?BadgeType $status = null,
        public ?string $statusLabel = null,
        public readonly ?Icon $icon = null,
    ) {
    }

    public function render(): View
    {
        return view('pjutils::components.summary-card');
    }
}
