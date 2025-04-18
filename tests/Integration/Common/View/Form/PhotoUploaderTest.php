<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Common\View\Form;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Common\Dto\Image;
use Patrikjak\Utils\Tests\Integration\TestCase;

class PhotoUploaderTest extends TestCase
{
    public function testPhotoUploaderCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::photo-uploader name="photo" label="Photos" />
            HTML
        ));
    }

    public function testRequiredPhotoCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::photo-uploader name="photo" label="Photos" required />
            HTML
        ));
    }

    public function testPhotoUploaderCanBeRenderedWithAttributes(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::photo-uploader name="photo" label="Photos" class="custom class" id="custom-id" />
            HTML
        ));
    }

    public function testPhotoUploaderCanBeRenderedWithError(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::photo-uploader name="photo" label="Photos" error="Error message" />
            HTML
        ));
    }

    public function testPhotoUploaderCanBeRenderedWithValue(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::photo-uploader name="photo" label="Photos" :value="$value" />
            HTML,
            [
                'value' => [
                    new Image('source/of/image', 'alt text'),
                    new Image('source/of/image', 'another alt text', 'custom-filename.jpg'),
                ],
            ],
        ));
    }
}