<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dtos;

use Illuminate\Contracts\Support\Arrayable;

final readonly class Parameters implements Arrayable
{
    public function __construct(public int $page, public int $pageSize)
    {
    }

    /**
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'pageSize' => $this->pageSize,
        ];
    }
}