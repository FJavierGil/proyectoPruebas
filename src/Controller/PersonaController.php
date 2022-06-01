<?php

namespace App\Controller;

use App\Repository\PersonaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PersonaController extends AbstractController
{
    private PersonaRepository $personaRepo;

    public function __construct(PersonaRepository $personaRepo)
    {
        $this->personaRepo = $personaRepo;
    }

    #[Route(
        '/hello/{apellido}',
        name: 'app_persona_get',
        methods: [ 'GET' ]
    )]
    public function index(string $apellido): JsonResponse
    {
        $persona = $this->personaRepo->findByApellido($apellido);

        return ($persona)
            ? $this->json([ 'persona' => $persona ])
            : $this->json([ 'message' => 'Not Found'], 404);
    }
}
