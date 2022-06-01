<?php

namespace App\Tests\Controller;

use App\Controller\PersonaController;
use App\Entity\Persona;
use App\Repository\PersonaRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PersonaControllerTest extends WebTestCase
{
    private const NOMBRE_TEST = 'nombreTest';
    private const APELLIDOS_TEST = 'test test';
    private const EMAIL_TEST = 'test@example.com';

    public function testGetApellido200Ok_repository_stub(): void
    {
        // Preparar y configurar el stub (repositorio Personas)
        // El stub del repositorio siempre devuelve lo mismo
        $personaRepository = $this->createStub(PersonaRepository::class);
        $personaRepository->method('findOneByApellidos')
            ->willReturn(
                new Persona(self::NOMBRE_TEST, self::APELLIDOS_TEST, self::EMAIL_TEST)
            );
        $sut = new PersonaController($personaRepository);

        // Ejecutar
        $response = $sut->getApellido('DA IGUAL!!!');

        // Verificar
        $this->assertTrue($response->isOk());
        $this->assertJson($response->getContent());
        $resultado = json_decode($response->getContent(), true);
        self::assertArrayHasKey('persona', $resultado);
        self::assertSame(
            self::NOMBRE_TEST,
            $resultado['persona']['nombre']
        );
        self::assertSame(
            self::APELLIDOS_TEST,
            $resultado['persona']['apellidos']
        );
        self::assertSame(
            self::EMAIL_TEST,
            $resultado['persona']['email']
        );
    }
}
