<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View\FileUploader;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Dto\Image;

class PhotoPreview extends Component
{
    public string $fileName;

    public function __construct(public Image $image)
    {
    }

    public function render(): View
    {
        $this->fileName = $this->image->fileName ?? basename($this->image->src);

        return $this->view('pjutils::components.file-uploader.photo-preview');
    }
}
