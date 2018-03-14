<?php


namespace SAREhub\Microt\Test\Api;


use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class ApiClient
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(ApiClientOptions $options)
    {
        $baseUri = sprintf('http://%s:%d/%s/', $options->getHost(), $options->getPort(), $options->getApiVersion());
        $auth = [$options->getAuthUser(), $options->getAuthPassword()];
        $this->client = new Client([
            'base_uri' => $baseUri,
            'http_errors' => false,
            'auth' => $auth
        ]);
    }

    public function getServiceHealthStatus(): ResponseInterface
    {
        return $this->sendRequest('GET', $this->serviceUri('health'));
    }

    public function serviceUri(string $uri): string
    {
        return "service/$uri";
    }

    public function sendRequest(string $method, string $uri, array $options = []): ResponseInterface
    {
        return $this->getClient()->request($method, $uri, array_merge($options, [
            'headers' => ['X-Skip-Cache' => '1']
        ]));
    }

    /**
     * @param ResponseInterface $resp
     * @return mixed
     */
    public function decodeResponseBody(ResponseInterface $resp)
    {
        return json_decode((string)$resp->getBody(), true);
    }

    protected function getClient(): Client
    {
        return $this->client;
    }
}
