<?php declare(strict_types=1); 

namespace App\Tests\Entity;

use App\Entity\Drink;
use App\Entity\Sale;
use App\Entity\SaleItem;
use App\Entity\Wine;
use PHPUnit\Framework\TestCase;

class SaleItemTest extends TestCase
{
    public function testSaleItemEntity(): void
    {
        $wine = new Wine();
        $wine->setName('Não é suco')
             ->setPrice(65)
             ->setWeightKg(1);
        
        $sale = new Sale();
        $saleItem = new SaleItem();

        $price = 10.0;
        $quantity = 3;
        $weightKg = 2.25;

        $saleItem->setDrink($wine);
        $saleItem->setSale($sale);
        $saleItem->setPrice($price);
        $saleItem->setQuantity($quantity);
        $saleItem->setWeightKg($weightKg);

        // valida a instância das entidades relacionadas
        $this->assertInstanceOf(Sale::class, $saleItem->getSale());
        $this->assertInstanceOf(Drink::class, $saleItem->getDrink());

        // valida os valores inseridos
        $this->assertEquals($price, $saleItem->getPrice());
        $this->assertEquals($quantity, $saleItem->getQuantity());
        $this->assertEquals($weightKg, $saleItem->getWeightKg());

    }

    public function testTypeOfDataFields(): void
    {
        // Valida tipo de campo (via métodos) para das datas
        $saleItem = new SaleItem();
        
        $saleItem->setCreatedAtValue();
        $updatedAt = $saleItem->getUpdatedAt();

        $this->assertInstanceOf(\DateTimeImmutable::class, $saleItem->getCreatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $saleItem->getUpdatedAt());

        sleep(1);

        $saleItem->setUpdatedAtValue();
        $this->assertNotSame($updatedAt, $saleItem->getUpdatedAt());
    }
}