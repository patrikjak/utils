<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View\Email;

use Illuminate\Config\Repository;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Layout extends Component
{
    public string $appName;

    public function __construct(Repository $config, public bool $useLogo = true, public ?string $footerText = null)
    {
        $this->appName = $config->get('pjutils.app_name');
    }

    public function render(): View
    {
        return view('pjutils::email.layout');
    }
}
