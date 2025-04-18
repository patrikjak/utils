<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Dto\Image;

class PhotoUploader extends Component
{
    /**
     * @param array<Image> $value
     */
    public function __construct(
        public string $name,
        public bool $multiple = false,
        public array $value = [],
    ) {
    }

    public function render(): View
    {
        return $this->view('pjutils::components.file-uploader.photo-uploader');
    }
}
