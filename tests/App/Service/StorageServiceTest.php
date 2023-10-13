<?php

namespace App\Tests\App\Service;

use App\Enum\ProductTypeEnum;
use App\Enum\WeightUnitEnum;
use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function PHPUnit\Framework\assertEquals;

class StorageServiceTest extends KernelTestCase
{
    public function testReaderIsInstantiated()
    {
        $request = file_get_contents('request.json');
        $service = $this->getStorageService();
        $this->assertCount(count(ProductTypeEnum::cases()), $service->process($request));
    }

    public function testItConvertKgValuesToGrams()
    {
        $input = json_encode([
            ['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 11, 'unit' => 'kg'],
        ]);
        $service = $this->getStorageService();

        $service->process($input);

        assertEquals(WeightUnitEnum::GRAM, $service->fruits()[0]->unit);
        assertEquals(11000, $service->fruits()[0]->quantity);
    }

    public function testItDoesNotModifyGram()
    {
        $input = json_encode([
            ['id' => 1, 'name' => 'Banana', 'type' => 'fruit', 'quantity' => 11000, 'unit' => 'g'],
        ]);
        $service = $this->getStorageService();
        $service->process($input);
        assertEquals(WeightUnitEnum::GRAM, $service->fruits()[0]->unit);
        assertEquals(11000, $service->fruits()[0]->quantity);
    }

    private function getStorageService(): StorageService
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var StorageService $service */
        $service = $container->get(StorageService::class);

        return $service;
    }
}
