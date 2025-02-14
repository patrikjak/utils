<?php

namespace Patrikjak\Utils\Table\Services;

use Patrikjak\Utils\Table\Dto\Parameters;

interface Renderable
{
    /**
     * @return array<string, string|null>
     */
    public function getHtmlParts(Parameters $parameters): array;

    public function getHtmlPartsUrl(): ?string;
}