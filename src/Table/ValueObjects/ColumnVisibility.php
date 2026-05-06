<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects;

final readonly class ColumnVisibility
{
    /**
     * @param array<string, string> $columns All toggleable columns (key => label)
     * @param array<string> $defaultHidden Column keys hidden by default
     */
    public function __construct(
        public array $columns,
        public array $defaultHidden = [],
    ) {
    }

    /**
     * Returns the currently visible column keys, enforcing at least one column visible.
     *
     * @param array<string>|null $requestedVisible Keys sent from the frontend; null means "use defaults"
     * @return array<string>
     */
    public function getVisibleColumns(?array $requestedVisible): array
    {
        $allKeys = array_keys($this->columns);

        if ($requestedVisible !== null) {
            $visible = array_values(
                array_filter($allKeys, static fn (string $key) => in_array($key, $requestedVisible, strict: true)),
            );

            return $visible !== [] ? $visible : [$allKeys[0]];
        }

        $visible = array_values(
            array_filter($allKeys, fn (string $key) => !in_array($key, $this->defaultHidden, strict: true)),
        );

        return $visible !== [] ? $visible : [$allKeys[0]];
    }
}
