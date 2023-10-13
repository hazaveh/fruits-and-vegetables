<?php

namespace App\Tests\App\Parser;

use App\Enum\ProductAttributesEnum;
use App\Parsers\JsonParser;
use PHPUnit\Framework\TestCase;

class JsonParserTest extends TestCase
{
    public function testItValidatesData()
    {
        $parser = new JsonParser();
        $this->expectException(\InvalidArgumentException::class);
        $parser->parse('invalidJsonData');
    }

    public function testItValidatesAttributes()
    {
        $parser = new JsonParser();
        $dataWithMissingAttribute = json_encode([
            ['id' => 1, 'name' => 'x'],
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $parser->parse($dataWithMissingAttribute);
    }

    public function testItParsesData()
    {
        $parser = new JsonParser();
        $input = file_get_contents('request.json');
        $output = $parser->parse($input);
        $this->assertIsArray($output);

        foreach (ProductAttributesEnum::cases() as $attribute) {
            $this->assertArrayHasKey($attribute->value, $output[0]);
        }
    }
}
