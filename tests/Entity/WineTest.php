<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Wine;
use App\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class WineTest extends TestCase
{
    public function testWineEntity(): void
    {
        $wine = new Wine();
        $name = 'Test Wine';
        $volume = 750;
        $weight = 1.2;
        $stock = 10;
        $price = 25.99;
        $grape = 'Merlot';
        $country = 'France';
        $alcoholPerc = 14.5;

        $wine->setName($name);
        $wine->setVolumeMl($volume);
        $wine->setWeightKg($weight);
        $wine->changeStock($stock);
        $wine->setPrice($price);
        $wine->setGrape($grape);
        $wine->setCountry($country);
        $wine->setAlcoholPerc($alcoholPerc);

        $this->assertEquals($name, $wine->getName());
        $this->assertEquals($volume, $wine->getVolumeMl());
        $this->assertEquals($weight, $wine->getWeightKg());
        $this->assertEquals($stock, $wine->getStock());
        $this->assertEquals($price, $wine->getPrice());
        $this->assertEquals($grape, $wine->getGrape());
        $this->assertEquals($country, $wine->getCountry());
        $this->assertEquals($alcoholPerc, $wine->getAlcoholPerc());

        // Validando processo qu altera a quantidade em estoque
        $wine->increaseStock(5);
        $this->assertEquals($stock + 5, $wine->getStock());
        $wine->decreaseStock(3);
        $this->assertEquals($stock + 2, $wine->getStock());

        // Teste da excessão ao decrementar do estoque mais do que existe 
        // (processo realizado quando um item é deletado de uma venda ou a quantiadade muda)
        $this->expectException(InvalidArgumentException::class);
        $wine->decreaseStock(100);
    }

    public function testDecreaseStockException(): void
    {
        $wine = new Wine();
        $wine->changeStock(5);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantidade superior ao estoque atual');
        $wine->decreaseStock(10);
    }

    public function testTypeOfDataFields(): void
    {
        // Valida tipo de campo (via métodos) para das datas
        $wine = new Wine();
        
        $wine->setCreatedAtValue();
        $this->assertInstanceOf(\DateTimeImmutable::class, $wine->getCreatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $wine->getUpdatedAt());
    }

    public function testNullableFields(): void
    {
        $wine = new Wine();

        $this->assertNull($wine->getGrape());
        $this->assertNull($wine->getCountry());
        $this->assertNull($wine->getAlcoholPerc());
    }
}
