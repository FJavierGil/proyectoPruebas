<?php

namespace App\Tests\Integration\Controller;

use App\Controller\ExternalServiceController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ExternalServiceControllerTest extends TestCase
{
    public function testCgetComunidades()
    {
        // Arrange
        $expectedResponseData = [ 'ccaa' => 'Comunidad de Madrid' ]; // Estructura esperada...
        $mockResponseJson = json_encode($expectedResponseData);
        $mockResponse = new MockResponse(
            $mockResponseJson,
            [
                'http_code' => 200,
                'response_headers' => ['Content-Type: application/json'],
            ]
        );

        $httpClient = new MockHttpClient(
            $mockResponse,
            ExternalServiceController::URL_SERVICIO
        );
        $service = new ExternalServiceController($httpClient);

        // Act
        $responseData = $service->cgetComunidades();

        // Assert
        self::assertSame(
            'GET',
            $mockResponse->getRequestMethod()
        );
        self::assertStringContainsString(
            ExternalServiceController::URL_SERVICIO,
            $mockResponse->getRequestUrl()
        );
        self::assertContains(
            'Content-Type: application/json',
            $mockResponse->getRequestOptions()['headers']
        );
        self::assertJson($responseData->getContent());
    }
}
