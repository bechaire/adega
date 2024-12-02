<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/** MUDAR O BANCO PARA USAR O BANCO DE TESTES DURANTE A EXECUÇÃO
 * TODO: Verificar alternativas
 */
class WineControllerTest extends WebTestCase
{
    public function testGetWineSuccess()
    {
        $client = static::createClient();

        $client->request(
            'GET', 
            'https://127.0.0.1:8000/api/v1/wines', 
            [], 
            [], 
            []);

        $this->assertResponseStatusCodeSame(200);

        $responseContent = $client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('wines', $responseData);
        $this->assertArrayHasKey('0', $responseData['wines']);

        $this->assertArrayHasKey('id', $responseData['wines'][0]);
        $this->assertArrayHasKey('grape', $responseData['wines'][0]);
        $this->assertArrayHasKey('country', $responseData['wines'][0]);
        $this->assertArrayHasKey('alcoholPerc', $responseData['wines'][0]);
        $this->assertArrayHasKey('name', $responseData['wines'][0]);
        $this->assertArrayHasKey('volumeMl', $responseData['wines'][0]);
        $this->assertArrayHasKey('weightKg', $responseData['wines'][0]);
        $this->assertArrayHasKey('stock', $responseData['wines'][0]);
        $this->assertArrayHasKey('price', $responseData['wines'][0]);
        $this->assertArrayHasKey('updatedAt', $responseData['wines'][0]);
        $this->assertArrayHasKey('createdAt', $responseData['wines'][0]);
    }

    public function testCreateWineSuccess()
    {
        $client = static::createClient();

        $client->request(
            'POST', 
            'https://127.0.0.1:8000/api/v1/wines', 
            [], 
            [], 
            ['CONTENT_TYPE' => 'application/json'], 
            <<<JSON
                    {
                    "grape": "Merlot",
                    "country": "Italy",
                    "alcoholPerc": 12.7,
                    "name": "Wine 10 (Merlot)",
                    "volumeMl": 600,
                    "weightKg": 2,
                    "stock": 2,
                    "price": 215.8
                }
            JSON);

        $this->assertResponseStatusCodeSame(201);

        $responseContent = $client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertArrayHasKey('updatedAt', $responseData);
        $this->assertArrayHasKey('createdAt', $responseData);

        $this->assertEquals('Merlot', $responseData['grape'] ?? null);
        $this->assertEquals('Italy', $responseData['country'] ?? null);
        $this->assertEquals(12.7, $responseData['alcoholPerc'] ?? null);
        $this->assertEquals('Wine 10 (Merlot)', $responseData['name'] ?? null);
        $this->assertEquals(600, $responseData['volumeMl'] ?? null);
        $this->assertEquals(2, $responseData['weightKg'] ?? null);
        $this->assertEquals(2, $responseData['stock'] ?? null);
        $this->assertEquals(215.8, $responseData['price'] ?? null);
    }

}
