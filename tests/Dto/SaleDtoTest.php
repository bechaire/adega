<?php

declare(strict_types=1);

namespace App\Tests\Dto;

use App\Dto\SaleDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SaleDtoTest extends TestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
    }

    public function testValidSaleDto(): void
    {
        $saleDto = new SaleDto(10, '2024-07-26');
        $errors = $this->validator->validate($saleDto);
        $this->assertCount(0, $errors);
    }

    public function testInvalidDistance(): void
    {
        $saleDto = new SaleDto(-10, '2024-02-26');
        $errors = $this->validator->validate($saleDto);
        $this->assertCount(1, $errors);
        $this->assertEquals('A distância, em quilômetros, precisa ser maior que zero', $errors[0]->getMessage());
    }

    public function testZeroDistance(): void
    {
        $saleDto = new SaleDto(0, '2024-07-26');
        $errors = $this->validator->validate($saleDto);
        $this->assertCount(1, $errors);
        $this->assertEquals('A distância, em quilômetros, precisa ser maior que zero', $errors[0]->getMessage());
    }

    public function testInvalidDate(): void
    {
        $saleDto = new SaleDto(10, 'invalid date');
        $errors = $this->validator->validate($saleDto);
        $this->assertCount(1, $errors);
        $this->assertEquals('Informe uma data válida', $errors[0]->getMessage());
    }


    public function testBlankDate(): void
    {
        $saleDto = new SaleDto(10, '');
        $errors = $this->validator->validate($saleDto);
        $this->assertCount(1, $errors);
        $this->assertEquals('O campo date, referente a data da venda, deve ser preenchido', $errors[0]->getMessage());
    }
}
