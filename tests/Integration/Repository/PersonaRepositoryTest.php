<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Persona;
use App\Repository\PersonaRepository;
use App\Tests\Integration\BaseTestCase;

class PersonaRepositoryTest extends BaseTestCase
{
    private static PersonaRepository $personaRepository;
    protected static array $PERSONA1 = [ ];

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$PERSONA1 = [
            'nombre' => self::$faker->firstName(),
            'apellidos' => self::$faker->lastName(),
            'email' => self::$faker->email(),
        ];

        // Obtiene el repositorio de personas
        self::$personaRepository = self::$entityManager
            ->getRepository(Persona::class);

        // añade la Persona1
        self::$personaRepository->add(
            new Persona(
                self::$PERSONA1['nombre'],
                self::$PERSONA1['apellidos'],
                self::$PERSONA1['email']
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
            ->findOneByApellidos(self::$PERSONA1['apellidos']);

        self::assertNotNull($persona);
        self::assertSame(
            self::$PERSONA1['apellidos'],
            $persona->getApellidos()
        );
    }

    /**
     * Verifica que sólo devuelve una persona aunque haya más de un resultado
     */
    public function testFindOneByApellidos_Con_Duplicados(): void
    {
        // duplica Persona1
        self::$personaRepository->add(
            new Persona(
                self::$PERSONA1['nombre'],
                self::$PERSONA1['apellidos'],
                self::$PERSONA1['email']
            ),
            true
        );
        $persona = self::$personaRepository
            ->findOneByApellidos(self::$PERSONA1['apellidos']);

        self::assertNotNull($persona);
        self::assertSame(
            self::$PERSONA1['apellidos'],
            $persona->getApellidos()
        );
        self::assertCount(
            2,
            self::$personaRepository->findBy(['apellidos' => self::$PERSONA1['apellidos']])
        );
    }

    /**
     * Comprueba que se devuelve null cuando no existe el apellido
     */
    public function testFindOneByApellidos_Not_Found(): void
    {
        $apellidosMod = 'X-X' . self::$PERSONA1['apellidos'];
        $persona = self::$personaRepository
            ->findOneByApellidos($apellidosMod);

        self::assertNull($persona);
        self::assertNotEquals(
            $apellidosMod,
            $persona?->getApellidos()
        );
    }

    /**
     * Prueba el método add($persona)
     *
     * @return string apellidos usuario añadido
     */
    public function testAdd(): string
    {
        $persona = new Persona(
            self::$faker->firstName(),
            self::$faker->lastName(),
            self::$faker->email(),
        );

        self::$personaRepository->add($persona, true);

        self::assertGreaterThanOrEqual(
            1,
            count(self::$personaRepository->findBy([
                'email' => $persona->getEmail()
            ]))
        );

        return $persona->getApellidos();
    }

    /**
     * @depends testAdd
     * @param string $apellidosPersona_A_Eliminar Apellidos recibidos desde testAdd()
     */
    public function testRemove(string $apellidosPersona_A_Eliminar): void
    {
        /** @var Persona[] $personas */
        $personas = self::$personaRepository->findBy([ 'apellidos' => $apellidosPersona_A_Eliminar ]);
        $numPersonas = count($personas);

        self::assertInstanceOf(Persona::class, $personas[0]);

        // elimina la instancia
        self::$personaRepository->remove($personas[0], true);

        // verifica eliminación
        $personas = self::$personaRepository->findBy([ 'apellidos' => $apellidosPersona_A_Eliminar ]);
        self::assertCount(
            $numPersonas - 1,
            $personas
        );
    }
}
