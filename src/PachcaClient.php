<?php

namespace Rentceisy\PachcaBotSdk;

use GuzzleHttp\Promise\PromiseInterface;
use Rentceisy\PachcaBotSdk\Exceptions\PachcaSDKException;
use Psr\Http\Message\ResponseInterface;
use Rentceisy\PachcaBotSdk\HttpClients\GuzzleHttpClient;
use Rentceisy\PachcaBotSdk\HttpClients\HttpClientInterface;

final class PachcaClient
{
    public const BASE_BOT_URL = 'https://api.pachca.com/api/shared/v1/';

    private HttpClientInterface $httpClientHandler;

    private string $baseBotUrl;

    public function __construct(?HttpClientInterface $httpClientHandler = null, ?string $baseBotUrl = null)
    {
        $this->httpClientHandler = $httpClientHandler ?? new GuzzleHttpClient();

        $this->baseBotUrl = $baseBotUrl ?? self::BASE_BOT_URL;
    }

    public function getHttpClientHandler(): HttpClientInterface
    {
        return $this->httpClientHandler ?? new GuzzleHttpClient();
    }

    public function setHttpClientHandler(HttpClientInterface $httpClientHandler): self
    {
        $this->httpClientHandler = $httpClientHandler;

        return $this;
    }

    /**
     * @throws PachcaSDKException
     */
    public function sendRequest(PachcaRequest $request): PachcaResponse
    {
        [$url, $method, $headers] = $this->prepareRequest($request);
        $options = $this->getOptions($request, $method);

        $rawResponse = $this->httpClientHandler
            ->setTimeOut($request->getTimeOut())
            ->setConnectTimeOut($request->getConnectTimeOut())
            ->send($url, $method, $headers, $options);

        $response = $this->getResponse($request, $rawResponse);

        if ($response->isError()) {
            throw $response->getThrownException();
        }

        return $response;
    }

    public function prepareRequest(PachcaRequest $request): array
    {
        $url = $this->baseBotUrl . '/' . $request->getEndpoint();

        return [$url, $request->getMethod(), $request->getHeaders()];
    }

    public function getBaseBotUrl(): string
    {
        return $this->baseBotUrl;
    }

    private function getResponse(PachcaRequest $request, ResponseInterface|PromiseInterface|null $response): PachcaResponse
    {
        return new PachcaResponse($request, $response);
    }

    private function getOptions(PachcaRequest $request, string $method): array
    {
        return $method === 'POST' ? $request->getPostParams() : ['query' => $request->getParams()];
    }
}
