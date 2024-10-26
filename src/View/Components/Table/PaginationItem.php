<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Table;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dtos\Pagination\Settings;

class PaginationItem extends Component
{
    public readonly bool $showDots;

    public readonly int $page;

    public readonly bool $skipPage;

    public readonly string $itemClass;

    public bool $isPrevArrow;

    public bool $isNextArrow;

    private int $lastPageOfIntervalPagination;

    private bool $canEditPaginationDisplay;

    private bool $canSkipOutsideInterval;

    private bool $unSkippable;

    /**
     * @param array{url: string, label:string, active: bool} $link
     */
    public function __construct(public Settings $paginationSettings, public array $link)
    {
        $this->page = (int) $link['label'];
        $this->isPrevArrow = $link['label'] === __('pjutils::table.pagination.previous');
        $this->isNextArrow = $link['label'] === __('pjutils::table.pagination.next');

        $isFirstPage = $this->page === 1;
        $isLastPage = $this->page === $paginationSettings->lastPage;
        $intervalLeftBoundary = $paginationSettings->page - 2;
        $intervalRightBoundary = $paginationSettings->page + 2;

        $this->lastPageOfIntervalPagination = $paginationSettings->lastPage - 4;
        $this->canEditPaginationDisplay = $paginationSettings->lastPage > 7;

        $intervalDisplay = $this->page >= $intervalLeftBoundary && $this->page <= $intervalRightBoundary;

        $isFirstPageOfIntervalPagination = $paginationSettings->page >= 5;
        $isLastPageOfIntervalPagination = $paginationSettings->page <= $this->lastPageOfIntervalPagination;

        $this->canSkipOutsideInterval = $isFirstPageOfIntervalPagination
            && $isLastPageOfIntervalPagination
            && !$intervalDisplay;
        
        $this->unSkippable = $this->isPrevArrow || $this->isNextArrow || $isFirstPage || $isLastPage;

        $this->showDots = $this->shouldShowDots();
        $this->skipPage = $this->shouldSkipPage();

        $this->itemClass = $this->resolveItemClass();
    }

    public function render(): View
    {
        return view('pjutils::components.table.pagination-item');
    }

    private function shouldShowDots(): bool
    {
        $showDots = false;
        $startingDots = $this->paginationSettings->page >= $this->paginationSettings->lastPage - 3 && $this->page === 2;
        $endingDots = $this->paginationSettings->page <= 4 && $this->page === $this->paginationSettings->lastPage;

        if ($this->canEditPaginationDisplay) {
            $showDots = $startingDots || $endingDots;
        }

        if ($this->canEditPaginationDisplay
            && !$this->unSkippable
            && $this->canSkipOutsideInterval
            && ($this->page === 2 || $this->page === $this->paginationSettings->lastPage - 1)
        ) {
            $showDots = true;
        }

        return $showDots;
    }

    private function shouldSkipPage(): bool
    {
        $canSkipPagesOnRight = $this->page > 5 && $this->paginationSettings->page < 5;
        $canSkipPagesOnLeft = $this->page < $this->lastPageOfIntervalPagination
            && $this->paginationSettings->page > $this->lastPageOfIntervalPagination;

        if (($this->canEditPaginationDisplay
            && !$this->unSkippable)
            && ($canSkipPagesOnRight || $canSkipPagesOnLeft || $this->canSkipOutsideInterval)
        ) {
            return true;
        }

        return false;
    }

    private function resolveItemClass(): string
    {
        $classes = ['link'];

        if ($this->link['active']) {
            $classes[] = 'active';
        }

        if ($this->isPrevArrow || $this->isNextArrow) {
            $classes[] = 'arrow';
        }

        if ($this->paginationSettings->isFirstPage && $this->isPrevArrow
            || $this->paginationSettings->isLastPage && $this->isNextArrow
        ) {
            $classes[] = 'disabled';
        }

        return implode(' ', $classes);
    }
}
