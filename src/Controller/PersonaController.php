<?php

namespace App\Controller;

use App\Repository\PersonaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonaController extends AbstractController
{
    private PersonaRepository $personaRepo;

    public function __construct(PersonaRepository $personaRepo)
    {
        $this->personaRepo = $personaRepo;
    }

    #[Route(
        path: '/hello/{apellido}',
        name: 'app_persona_get',
        methods: [ 'GET' ]
    )]
    public function getApellido(string $apellido): Response
    {
        $persona = $this->personaRepo->findOneByApellidos($apellido);

        return ($persona)
            ? new Response(json_encode([ 'persona' => $persona ]))
            : new Response(
                json_encode([ 'message' => 'Not Found']),
                404
            );
    }
}
