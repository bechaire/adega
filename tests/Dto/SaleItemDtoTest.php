<?php

declare(strict_types=1);

namespace App\Tests\Dto;

use App\Dto\SaleItemDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SaleItemDtoTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
    }

    public function testValidSaleItemDto(): void
    {
        $saleItemDto = new SaleItemDto(1);
        $errors = $this->validator->validate($saleItemDto);
        $this->assertCount(0, $errors);
    }

    public function testInvalidQuantity(): void
    {
        $saleItemDto = new SaleItemDto(0);
        $errors = $this->validator->validate($saleItemDto);
        $this->assertCount(1, $errors);
        $this->assertEquals('A quantidade vendida precisa ser maior que zero', $errors[0]->getMessage());
    }

}
