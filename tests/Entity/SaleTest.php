<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Sale;
use App\Entity\SaleItem;
use App\Entity\Wine;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class SaleTest extends TestCase
{
    public function testSaleEntity(): void
    {
        $sale = new Sale();
        $date = new \DateTimeImmutable();
        $distance = 150;

        $sale->setDate($date);
        $sale->setDistance($distance);

        $this->assertTrue( Uuid::isValid($sale->getCustomerId()) );
        $this->assertEquals($date, $sale->getDate());
        $this->assertEquals($distance, $sale->getDistance());
        $this->assertEquals(0.0, $sale->getTotalWeight());
        $this->assertEquals(0.0, $sale->getItemsPrice());
        $this->assertEquals(0.0, $sale->getOrderTotal());

        // testando o processo de adicionar produto numa compra
        $wine1 = new Wine();
        $wine1->setName('Sangue de Boi')
              ->setPrice(40)
              ->changeStock(10)
              ->setWeightKg(1.5);

        // ao adicionar o item na venda, ele é decrementado do estoque
        $saleItem1 = new SaleItem();
        $saleItem1->setDrink($wine1)->setQuantity(2);
        $sale->addItem($saleItem1);

        // um item adicionado e 8 itens em estoque (2 "vendidos")
        $this->assertEquals(1, $sale->getItems()->count());
        $this->assertEquals(8, $wine1->getStock());

        // segundo item, mesmo teste
        $wine2 = new Wine();
        $wine2->setName('Sangue de Boi Plus')
              ->setPrice(150)
              ->changeStock(5)
              ->setWeightKg(1);

        $saleItem2 = new SaleItem();
        $saleItem2->setDrink($wine2)->setQuantity(1);
        $sale->addItem($saleItem2);

        // agora 2 itens adicionados, estoque do item 2 alterado
        $this->assertEquals(2, $sale->getItems()->count());
        $this->assertEquals(4, $wine2->getStock());

        // teste de remoção de item da collection
        $sale->removeItem($saleItem1);
        $this->assertEquals(1, $sale->getItems()->count());
        $this->assertEquals(10, $wine1->getStock());
    }

    public function testTypeOfDataFields(): void
    {
        // Valida tipo de campo (via métodos) para das datas
        $sale = new Sale();
        
        $sale->setCreatedAtValue();
        $this->assertInstanceOf(\DateTimeImmutable::class, $sale->getCreatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $sale->getUpdatedAt());
    }

}
