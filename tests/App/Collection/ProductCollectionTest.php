<?php

namespace App\Tests\App\Collection;

use App\Collection\ProductCollection;
use App\Enum\WeightUnitEnum;
use App\Model\Product;
use PHPUnit\Framework\TestCase;

class ProductCollectionTest extends TestCase
{
    public function testItCanContainProducts()
    {
        $items = [
            $this->createMock(Product::class),
            $this->createMock(Product::class),
            $this->createMock(Product::class),
        ];

        $this->assertCount(3, new ProductCollection($items));
    }

    public function testItCanAddProducts()
    {
        $collection = new ProductCollection();
        $collection->add($this->createMock(Product::class));
        $this->assertCount(1, $collection);
    }

    public function testProductsCanBeRemoved()
    {
        $collection = new ProductCollection([$this->createMock(Product::class)]);
        $this->assertCount(1, $collection);
        $product = Product::createFromArray(['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 11, 'unit' => 'g']);
        $collection->add($product);
        $this->assertCount(2, $collection);
        $collection->remove($product);
        $this->assertCount(1, $collection);
    }

    public function testItCanListProducts()
    {
        $items = [
            Product::createFromArray(['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 11, 'unit' => 'g']),
            Product::createFromArray(['id' => 1, 'name' => 'Apple', 'type' => 'fruit', 'quantity' => 20, 'unit' => 'g']),
        ];
        $collection = new ProductCollection($items);
        $this->assertIsArray($collection->list());
        $this->assertCount(2, $collection->list());
    }

    public function testItemsCanBeSearched()
    {
        $items = [
            Product::createFromArray(['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 1100, 'unit' => 'g']),
            Product::createFromArray(['id' => 1, 'name' => 'Apple', 'type' => 'fruit', 'quantity' => 20000, 'unit' => 'g']),
        ];
        $collection = new ProductCollection($items);
        /** @var Product[] $search */
        $search = $collection->search('banana');
        $this->assertCount(1, $search);
        $this->assertEquals(20000, reset($search)->quantity);
    }

    public function testItCanConvertResults()
    {
        $items = [
            Product::createFromArray(['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 2000, 'unit' => 'g']),
        ];
        $collection = new ProductCollection($items);
        $this->assertEquals(2, $collection->list(WeightUnitEnum::KILO_GRAM)[0]->quantity);
        $this->assertEquals(2000, $collection->list()[0]->quantity);
    }

    public function testSearchResultsCanBeConverted()
    {
        $items = [
            Product::createFromArray(['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 1100, 'unit' => 'g']),
            Product::createFromArray(['id' => 1, 'name' => 'Apple', 'type' => 'fruit', 'quantity' => 20000, 'unit' => 'g']),
        ];
        $collection = new ProductCollection($items);
        /** @var Product[] $search */
        $search = $collection->search('banana', WeightUnitEnum::KILO_GRAM);
        $this->assertCount(1, $search);
        $this->assertEquals(20, reset($search)->quantity);
    }
}
