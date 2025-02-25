<?php

namespace Patrikjak\Utils\Tests\Unit\Table\Factories\Filter;

use Patrikjak\Utils\Table\Factories\Filter\SelectFilterOptionsFactory;
use Patrikjak\Utils\Tests\Integration\Table\TestCase;

class SelectFilterOptionsFactoryTest extends TestCase
{
    public function testCreateFromArray(): void
    {
        $options = [
            '1' => 'Option 1',
            '2' => 'Option 2',
            '3' => 'Option 3',
        ];

        $selectFilterOptions = SelectFilterOptionsFactory::createFromArray($options);
        $arrayOptions = $selectFilterOptions->toArray();

        $this->assertCount(3, $selectFilterOptions->options);
        
        $this->assertSame('1', $selectFilterOptions->options[1]->value);
        $this->assertSame('Option 1', $selectFilterOptions->options[1]->label);

        $this->assertSame('2', $selectFilterOptions->options[2]->value);
        $this->assertSame('Option 2', $selectFilterOptions->options[2]->label);

        $this->assertSame('3', $selectFilterOptions->options[3]->value);
        $this->assertSame('Option 3', $selectFilterOptions->options[3]->label);

        $this->assertSame($options, $arrayOptions['options']);
        $this->assertArrayHasKey('htmlComponent', $arrayOptions);
    }
}