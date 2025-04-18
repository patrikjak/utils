<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Http\Requests\Traits;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

trait FileUpload
{
    /**
     * @return Collection<UploadedFile>
     */
    public function getNewFiles(string $inputName): Collection
    {
        assert($this instanceof FormRequest);
        $deleted = $this->getFilesToDelete($inputName);

        return new Collection($this->file($inputName) ?? [])
            ->filter(static function (UploadedFile $file) use ($deleted) {
                return !$deleted->contains($file->getClientOriginalName());
            });
    }

    /**
     * @return Collection<string>
     */
    public function getFilesToDelete(string $inputName): Collection
    {
        assert($this instanceof FormRequest);

        return $this->collect(sprintf('deleted_files_%s', $inputName));
    }
}