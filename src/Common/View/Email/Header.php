<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View\Email;

use Illuminate\Config\Repository;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    public string $appName;

    public string $appUrl;

    public ?string $logoPath;

    public function __construct(Repository $config, public bool $useLogo = true)
    {
        $this->appName = $config->get('pjutils.app_name');
        $this->appUrl = $config->get('pjutils.app_url');
        $this->logoPath = $config->get('pjutils.email_logo_path');
    }

    public function render(): View
    {
        return view('pjutils::email.header');
    }
}
