<?php

declare(strict_types=1);

namespace App\Tests\Trait;

use App\Exception\InvalidArgumentException;
use App\Traits\RequestDataTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RequestDataTraitTest extends TestCase
{
    use RequestDataTrait;

    public function testGetRequestDataWithValidJson(): void
    {
        $request = new Request(
            [], // query
            [], // request
            [], // atributos
            [], // cookies
            [], // arquivos
            ['CONTENT_TYPE' => 'application/json'], // server info (maiúscula?)
            json_encode(['key' => 'value'], JSON_THROW_ON_ERROR) // content/body
        );

        $result = $this->getRequestData($request);

        $this->assertEquals(['key' => 'value'], $result);
    }

    public function testGetRequestDataWithInvalidJson(): void
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], '{json inválido}');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Revise a estrutura do JSON submetido');

        $this->getRequestData($request);
    }

    public function testGetRequestDataWithFormData(): void
    {
        $request = new Request([], ['key' => 'value'], [], [], [], ['CONTENT_TYPE' => 'application/x-www-form-urlencoded'] );

        $result = $this->getRequestData($request);

        $this->assertEquals(['key' => 'value'], $result);
    }

    public function testGetRequestDataWithEmptyData(): void
    {
        $request = new Request();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Não foi possível coletar os dados enviados, revise as informações');

        $this->getRequestData($request);
    }
}
