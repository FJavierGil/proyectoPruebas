<?php

namespace App\Tests\Integration;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory as FakerFactoryAlias;
use Faker\Generator as FakerGeneratorAlias;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Throwable;

/**
 * Class BaseTestCase
 */
class BaseTestCase extends WebTestCase
{
    protected static EntityManagerInterface $entityManager;
    protected static FakerGeneratorAlias $faker;

    /**
     * This method is called before the first test of this test class is run.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$faker = FakerFactoryAlias::create('es_ES');

        try { // Regenera las tablas con todas las entidades mapeadas
            self::$entityManager = self::bootKernel()
                ->getContainer()
                ->get('doctrine')
                ->getManager();

            $metadata = self::$entityManager
                ->getMetadataFactory()
                ->getAllMetadata();
            $sch_tool = new SchemaTool(self::$entityManager);
            $sch_tool->dropDatabase();
            $sch_tool->updateSchema($metadata);
        } catch (Throwable $e) {
            fwrite(STDERR, 'EXCEPCIÓN: ' . $e->getCode() . ' - ' . $e->getMessage());
            exit(1);
        }
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        self::$entityManager->close();
    }
}
