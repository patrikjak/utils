<?php

namespace Patrikjak\Utils\Common\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'install:pjutils';

    protected $description = 'Install patrikjak/utils package';

    public function handle(): void
    {
        $this->call('vendor:publish', ['--tag' => 'pjutils-config', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'pjutils-assets', '--force' => true]);
    }
}
