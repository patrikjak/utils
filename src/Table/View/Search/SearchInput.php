<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View\Search;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Search\Settings;

class SearchInput extends Component
{
    public function __construct(public readonly Settings $settings)
    {
    }

    public function render(): View
    {
        return $this->view('pjutils::table.search.search-input');
    }
}
