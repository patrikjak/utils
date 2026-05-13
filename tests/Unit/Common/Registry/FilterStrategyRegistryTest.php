<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Common\Registry;

use Patrikjak\Utils\Common\Services\QueryBuilder\Filters\RangeFilter;
use Patrikjak\Utils\Common\Services\QueryBuilder\Filters\TextFilter;
use Patrikjak\Utils\Table\Contracts\Filter\FilterCriteriaFactory;
use Patrikjak\Utils\Table\Exceptions\UnregisteredFilterStrategyException;
use Patrikjak\Utils\Table\Factories\Filter\Criteria\DateFilterCriteriaFactory;
use Patrikjak\Utils\Table\Factories\Filter\Criteria\NumberFilterCriteriaFactory;
use Patrikjak\Utils\Table\Factories\Filter\Criteria\SelectFilterCriteriaFactory;
use Patrikjak\Utils\Table\Factories\Filter\Criteria\TextFilterCriteriaFactory;
use Patrikjak\Utils\Table\Registry\FilterStrategyRegistry;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;
use PHPUnit\Framework\TestCase;

final class FilterStrategyRegistryTest extends TestCase
{
    private FilterStrategyRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new FilterStrategyRegistry();
    }

    public function testRegisteredStrategyCanBeRetrieved(): void
    {
        $strategy = new TextFilter();
        $this->registry->register('text', $strategy, new TextFilterCriteriaFactory());

        $this->assertSame($strategy, $this->registry->get('text'));
    }

    public function testMultipleStrategiesCanBeRegistered(): void
    {
        $textFilter = new TextFilter();
        $rangeFilter = new RangeFilter();

        $this->registry->register('text', $textFilter, new TextFilterCriteriaFactory());
        $this->registry->register('date', $rangeFilter, new DateFilterCriteriaFactory());

        $this->assertSame($textFilter, $this->registry->get('text'));
        $this->assertSame($rangeFilter, $this->registry->get('date'));
    }

    public function testRegisterOverwritesExistingStrategy(): void
    {
        $original = new TextFilter();
        $replacement = new TextFilter();

        $this->registry->register('text', $original, new TextFilterCriteriaFactory());
        $this->registry->register('text', $replacement, new TextFilterCriteriaFactory());

        $this->assertSame($replacement, $this->registry->get('text'));
    }

    public function testGetThrowsForUnregisteredType(): void
    {
        $this->expectException(UnregisteredFilterStrategyException::class);
        $this->expectExceptionMessage('No filter strategy registered for type "custom".');

        $this->registry->get('custom');
    }

    public function testDateAndNumberCanShareTheSameInstance(): void
    {
        $rangeFilter = new RangeFilter();
        $factory = new DateFilterCriteriaFactory();

        $this->registry->register('date', $rangeFilter, $factory);
        $this->registry->register('number', $rangeFilter, new NumberFilterCriteriaFactory());

        $this->assertSame($this->registry->get('date'), $this->registry->get('number'));
    }

    public function testHasReturnsTrueForRegisteredType(): void
    {
        $this->registry->register('text', new TextFilter(), new TextFilterCriteriaFactory());

        $this->assertTrue($this->registry->has('text'));
    }

    public function testHasReturnsFalseForUnregisteredType(): void
    {
        $this->assertFalse($this->registry->has('custom'));
    }

    public function testGetCriteriaFactoryReturnsRegisteredFactory(): void
    {
        $factory = new TextFilterCriteriaFactory();
        $this->registry->register('text', new TextFilter(), $factory);

        $this->assertSame($factory, $this->registry->getCriteriaFactory('text'));
    }

    public function testGetCriteriaFactoryThrowsForUnregisteredType(): void
    {
        $this->expectException(UnregisteredFilterStrategyException::class);
        $this->expectExceptionMessage('No filter strategy registered for type "custom".');

        $this->registry->getCriteriaFactory('custom');
    }

    public function testRegisterOverwritesCriteriaFactory(): void
    {
        $original = new TextFilterCriteriaFactory();
        $replacement = new TextFilterCriteriaFactory();

        $this->registry->register('text', new TextFilter(), $original);
        $this->registry->register('text', new TextFilter(), $replacement);

        $this->assertSame($replacement, $this->registry->getCriteriaFactory('text'));
    }

    public function testCustomCriteriaFactoryCanBeRegistered(): void
    {
        $customFactory = new class implements FilterCriteriaFactory {
            /**
             * @param array<string, mixed> $data
             */
            public function make(string $column, array $data): ?AbstractFilterCriteria
            {
                return null;
            }
        };

        $this->registry->register('custom', new TextFilter(), $customFactory);

        $this->assertTrue($this->registry->has('custom'));
        $this->assertSame($customFactory, $this->registry->getCriteriaFactory('custom'));
    }
}
