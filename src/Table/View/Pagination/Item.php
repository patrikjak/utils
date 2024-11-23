<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Pagination;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Pagination\Settings;
use Patrikjak\Utils\Table\Interfaces\Pagination\LinkItem;

class Item extends Component
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

    public function __construct(public Settings $paginationSettings, public LinkItem $link)
    {
        $this->page = (int) $link->getLabel();
        $this->isPrevArrow = $link->getLabel() === (function_exists('__')
                ? __('pagination.previous')
                : 'Previous');
        $this->isNextArrow = $link->getLabel() === (function_exists('__')
                ? __('pagination.next')
                : 'Next');

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
        return view('pjutils::table.pagination.item');
    }

    private function shouldShowDots(): bool
    {
        $range = 2;
        $current = $this->paginationSettings->page;
        $total = $this->paginationSettings->lastPage;

        $startingDots = $this->page === 2 && $current > $range + 2;
        $endingDots = $this->page === $total - 1 && $current < $total - $range - 1;

        return $startingDots || $endingDots;
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

        if ($this->link->isActive()) {
            $classes[] = 'active';
        }

        if ($this->isPrevArrow || $this->isNextArrow) {
            $classes[] = 'arrow';
        }

        $isFirstPage = $this->paginationSettings->page === 1;
        $isLastPage = $this->paginationSettings->page === $this->paginationSettings->lastPage;

        if ($isFirstPage && $this->isPrevArrow || $isLastPage && $this->isNextArrow) {
            $classes[] = 'disabled';
        }

        return implode(' ', $classes);
    }
}
