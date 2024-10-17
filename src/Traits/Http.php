<?php

namespace Rentceisy\PachcaBotSdk\Traits;

use Rentceisy\PachcaBotSdk\Exceptions\PachcaSDKException;
use Rentceisy\PachcaBotSdk\PachcaClient;
use Rentceisy\PachcaBotSdk\PachcaRequest;
use Rentceisy\PachcaBotSdk\PachcaResponse;
use Rentceisy\PachcaBotSdk\HttpClients\HttpClientInterface;

/**
 * Http.
 */
trait Http
{
    /** @var string Pachca Bot API Access Token. */
    protected string $accessToken;

    /** @var PachcaClient|null The Pachca client service. */
    protected ?PachcaClient $client = null;

    /** @var PachcaResponse|null Stores the last request made to Pachca Bot API. */
    protected ?PachcaResponse $lastResponse = null;

    /** @var int Connection timeout of the request in seconds. */
    protected int $connectTimeOut = 10;

    /** @var HttpClientInterface|null Http Client Handler */
    protected ?HttpClientInterface $httpClientHandler = null;

    /** @var string|null Base Bot Url */
    protected ?string $baseBotUrl = null;

    /**
     * Sets the bot access token to use with API requests.
     *
     * @param  string  $accessToken  The bot access token to save.
     */
    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Returns Pachca Bot API Access Token.
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Sends a POST request to Pachca Bot API and returns the result.
     *
     * @throws PachcaSDKException
     */
    protected function post(string $endpoint, array $params = []): PachcaResponse
    {
        return $this->sendRequest('POST', $endpoint, $params);
    }

    public function getConnectTimeOut(): int
    {
        return $this->connectTimeOut;
    }

    /**
     * Returns the PachcaClient service.
     */
    public function getClient(): PachcaClient
    {
        if ($this->client === null) {
            $this->client = new PachcaClient($this->httpClientHandler, $this->baseBotUrl);
        }

        return $this->client;
    }

    /**
     * Sends a request to Pachca Bot API and returns the result.
     *
     * @throws PachcaSDKException
     */
    protected function sendRequest(string $method, string $endpoint, array $params = []): PachcaResponse
    {
        $request = $this->resolvePachcaRequest($method, $endpoint, $params);

        return $this->lastResponse = $this->getClient()->sendRequest($request);
    }

    /**
     * Instantiates a new PachcaRequest entity.
     */
    protected function resolvePachcaRequest(string $method, string $endpoint, array $params = []): PachcaRequest
    {
        return (new PachcaRequest(
            $this->getAccessToken(),
            $method,
            $endpoint,
            $params,
        ))->setConnectTimeOut($this->getConnectTimeOut());
    }
}
