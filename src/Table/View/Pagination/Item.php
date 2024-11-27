<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Pagination;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Pagination\Settings;
use Patrikjak\Utils\Table\Interfaces\Pagination\LinkItem;

/**
 * We display 9 items in the pagination. Range is 1, so we display 1 item before and 1 item after the current page.
 */
class Item extends Component
{
    public readonly bool $showDots;

    public readonly int $page;

    public readonly bool $skipPage;

    public readonly string $itemClass;

    public bool $isPrevArrow;

    public bool $isNextArrow;

    private int $range = 1;

    private readonly int $current;

    public function __construct(public Settings $paginationSettings, public LinkItem $link)
    {
        $this->page = (int) $link->getLabel();
        $this->isPrevArrow = $link->getLabel() === (function_exists('__')
                ? __('pagination.previous')
                : 'Previous');
        $this->isNextArrow = $link->getLabel() === (function_exists('__')
                ? __('pagination.next')
                : 'Next');

        $this->current = $paginationSettings->page;
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
        if ($this->paginationSettings->links->count() <= 9) {
            return false;
        }

        $current = $this->current;
        $total = $this->paginationSettings->lastPage;

        $startingDots = $this->page === 2 && $current > $this->range + 2;
        $endingDots = $this->page === $total - 1 && $current < $total - $this->range - 1;

        return $startingDots || $endingDots;
    }

    private function shouldSkipPage(): bool
    {
        if ($this->current === $this->page) {
            return false;
        }

        if ($this->isPageUnskippable()) {
            return false;
        }

        if ($this->pageIsNotRecognizable()) {
            return true;
        }

        if ($this->isWithinFirstPagesRange() || $this->isWithinLastPagesRange()) {
            return false;
        }

        return $this->page < $this->current - $this->range || $this->page > $this->current + $this->range;
    }

    private function isPageUnskippable(): bool
    {
        $page = $this->page;
        $isArrow = $this->isPrevArrow || $this->isNextArrow;
        $isFirstPageOrLastPage = $page === 1 || $page === $this->paginationSettings->lastPage;

        return $isArrow || $isFirstPageOrLastPage;
    }

    private function isWithinFirstPagesRange(): bool
    {
        return $this->page <= 5 && $this->page >= $this->current - $this->range;
    }

    private function isWithinLastPagesRange(): bool
    {
        return $this->page >= $this->paginationSettings->lastPage - 4 && $this->page <= $this->current + $this->range;
    }

    private function pageIsNotRecognizable(): bool
    {
        return $this->page === 0;
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
