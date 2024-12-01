<?php

declare(strict_types=1);

namespace App\Tests\Dto;

use App\Dto\WineDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class WineDtoTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
    }

    public function testValidWineDto(): void
    {
        $wineDto = new WineDto(
            name: 'Vinho Teste',
            grape: 'Cabernet Sauvignon',
            country: 'Brasil',
            alcoholPerc: 13.5,
            volumeMl: 750,
            weightKg: 1.2,
            stock: 10,
            price: 50.00,
        );
        $errors = $this->validator->validate($wineDto);
        $this->assertCount(0, $errors);
        $this->assertEquals('wine', $wineDto->type); // Verifica se o tipo está correto
    }

    public function testExtremeInvalidWineDto(): void
    {
        $wineDto = new WineDto(
            name: '',
            grape: '',
            country: '',
            alcoholPerc: -1,
            volumeMl: 0,
            weightKg: 0,
            stock: -1,
            price: 0,
        );
        $errors = $this->validator->validate($wineDto);
        $this->assertCount(9, $errors);
        $this->assertEquals('O campo name é obrigatório', $errors[0]->getMessage());
        $this->assertEquals('Campo name muito curto', $errors[1]->getMessage());
        $this->assertEquals('Informe o tipo de uva ou blend', $errors[2]->getMessage());
        $this->assertEquals('Informe o país de origem', $errors[3]->getMessage());
        $this->assertEquals('A graduação alcoólica precisa ser maior ou igual a zero', $errors[4]->getMessage());
        $this->assertEquals('O volume em ML precisa ser maior que zero', $errors[5]->getMessage());
        $this->assertEquals('O peso precisa ser maior que zero', $errors[6]->getMessage());
        $this->assertEquals('A quantidade do estoque precisa ser maior ou igual a zero', $errors[7]->getMessage());
        $this->assertEquals('O preço precisa ser positivo', $errors[8]->getMessage());
    }

    // TODO: testes adicionais se o primeiro e segundo não forem necessários
}
