<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * @var string
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $signature = 'install:pjutils';

    /**
     * @var string
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $description = 'Install patrikjak/utils package';

    public function handle(): void
    {
        $this->call('vendor:publish', ['--tag' => 'pjutils-config', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'pjutils-assets', '--force' => true]);
    }
}
