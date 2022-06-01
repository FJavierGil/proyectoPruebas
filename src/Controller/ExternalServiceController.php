<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExternalServiceController extends AbstractController
{
    private HttpClientInterface $httpClient;

    // URL del servicio externo
    public const URL_SERVICIO = 'https://datos.gob.es/apidata/nti/territory/Autonomous-region.json?_sort=label&_pageSize=25';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route(
        '/comunidades',
        name: 'app_comunidades_get',
        methods: [ 'GET' ]
    )]
    public function cgetComunidades(): JsonResponse
    {
        $response = $this->httpClient
            ->request(
                'GET',
                self::URL_SERVICIO,
                [
                    'headers' => [
                        'Content-Type: application/json',
                        'Accept: application/json',
                    ],
                    // 'body' => 'abcde',
                ]
            );

        return new JsonResponse($response->getContent(), json: true);
    }
}
