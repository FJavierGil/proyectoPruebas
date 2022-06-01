<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Persona;
use App\Repository\PersonaRepository;
use App\Tests\Integration\BaseTestCase;

class PersonaRepositoryTest extends BaseTestCase
{
    private static PersonaRepository $personaRepository;
    protected const PERSONA1 = [
        'nombre' => 'testNombrePersona1',
        'apellidos' => 'testApellidosPersona1',
        'email' => 'testEmailPersona1@example.com',
    ];

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Obtiene el repositorio de personas
        self::$personaRepository = self::$entityManager
            ->getRepository(Persona::class);

        // añade la Persona1
        self::$personaRepository->add(
            new Persona(
                self::PERSONA1['nombre'],
                self::PERSONA1['apellidos'],
                self::PERSONA1['email']
            ),
            true
        );
    }

    /**
     * Comprueba que existe la persona previamente añadida
     */
    public function testFindOneByApellidos_Ok()
    {
        // OJO: y si hay apellidos repetidos???
        $persona = self::$personaRepository
            ->findOneByApellidos(self::PERSONA1['apellidos']);

        self::assertNotNull($persona);
        self::assertSame(
            self::PERSONA1['apellidos'],
            $persona->getApellidos()
        );
    }

    /**
     * Comprueba que se devuelve null cuando no existe el apellido
     */
    public function testFindOneByApellidos_Not_Found(): void
    {
        $apellidosMod = 'X-X' . self::PERSONA1['apellidos'];
        $persona = self::$personaRepository
            ->findOneByApellidos($apellidosMod);

        self::assertNull($persona);
        self::assertNotEquals(
            $apellidosMod,
            $persona?->getApellidos()
        );
    }
}
