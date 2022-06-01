<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HelloControllerTest extends WebTestCase
{
    public function testIndex_200_Ok(): void
    {
        $client = static::createClient();

        $crawler = $client->request(
            'GET',
            '/hello'
        );
        $cuerpoRespuesta = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        self::assertJson($cuerpoRespuesta);
        $datos = json_decode($cuerpoRespuesta, true);
        self::assertArrayHasKey('message', $datos);
    }
}
