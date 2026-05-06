<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DebugBacktrace extends Component
{
    /**
     * @var array<int, array<string, mixed>>
     */
    public readonly array $normalizedFrames;

    /**
     * @var array<int, array<string, mixed>>|null
     */
    public readonly ?array $normalizedLines;

    public readonly bool $hasVendorFrames;

    /**
     * @param array<int, array<string, mixed>> $frames
     * @param array<int, string>|null $lines
     */
    public function __construct(
        public array $frames = [],
        public ?array $lines = null,
        public ?string $trace = null,
        public ?string $title = null,
        public ?string $message = null,
        public bool $collapse = true,
        public int $collapseThreshold = 5,
    ) {
        $this->normalizedFrames = $this->normalizeFrames($frames);
        $this->normalizedLines = $lines !== null ? $this->normalizeLines($lines) : null;
        $this->hasVendorFrames = $this->detectVendorFrames();
    }

    public function render(): View
    {
        return view('pjutils::components.debug-backtrace');
    }

    /**
     * @param array<int, array<string, mixed>> $frames
     * @return array<int, array<string, mixed>>
     */
    private function normalizeFrames(array $frames): array
    {
        $result = [];

        foreach ($frames as $index => $frame) {
            $file = $frame['file'] ?? null;
            $isVendor = isset($frame['vendor'])
                ? (bool) $frame['vendor']
                : ($file !== null && str_contains($file, '/vendor/'));

            $result[] = [
                'number' => $index + 1,
                'file' => $file ?? '[internal]',
                'line' => $frame['line'] ?? null,
                'callable' => $frame['callable'] ?? '',
                'is_vendor' => $isVendor,
            ];
        }

        return $result;
    }

    private function detectVendorFrames(): bool
    {
        $items = $this->normalizedLines ?? $this->normalizedFrames;

        foreach ($items as $item) {
            if ($item['is_vendor']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<int, string> $lines
     * @return array<int, array<string, mixed>>
     */
    private function normalizeLines(array $lines): array
    {
        $result = [];

        foreach ($lines as $index => $line) {
            $result[] = [
                'number' => $index + 1,
                'text' => $line,
                'is_vendor' => str_contains($line, '/vendor/'),
            ];
        }

        return $result;
    }
}
