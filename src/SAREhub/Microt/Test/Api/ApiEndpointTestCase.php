<?php

namespace SAREhub\Microt\Test\Api;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

abstract class ApiEndpointTestCase extends TestCase
{
    /**
     * @var ApiClient
     */
    protected $client;

    protected function setUp()
    {
        $this->client = $this->createApiClient();
        $this->cleanService();
    }

    protected function tearDown()
    {
        $this->cleanService();
    }

    /**
     * Must returns instance of ApiClient used on test
     * @return ApiClient
     */
    protected abstract function createApiClient(): ApiClient;

    /**
     * Helper method to clean service before and after test
     */
    protected function cleanService(): void
    {

    }

    protected function assertResponseStatusCode(int $expected, ResponseInterface $resp): void
    {
        $this->assertEquals($expected, $resp->getStatusCode(), "response status code");
    }

    protected function assertNoContentResponse(ResponseInterface $response): void
    {
        $this->assertResponseStatusCode(204, $response);
        $this->assertEmpty((string)$response->getBody(), "response body");
    }

    protected function assertJsonResponseBody(array $expected, ResponseInterface $resp): void
    {
        $this->assertEquals($expected, $this->decodeResponseBody($resp), "response body");
    }

    protected function sendRequestWithQuery(string $uri, array $query): ResponseInterface
    {
        return $this->client->sendRequest('GET', $uri, ['query' => $query]);
    }

    protected function sendRequestWithJsonBody(string $method, string $uri, array $data): ResponseInterface
    {
        return $this->sendRequest($method, $uri, ['json' => $data]);
    }

    protected function sendRequest(string $method, string $uri, array $options = []): ResponseInterface
    {
        return $this->client->sendRequest($method, $uri, $options);
    }

    /**
     * @param ResponseInterface $resp
     * @return mixed
     */
    protected function decodeResponseBody(ResponseInterface $resp)
    {
        return $this->client->decodeResponseBody($resp);
    }
}