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
    protected string $accessToken;
    protected ?PachcaClient $client = null;
    protected ?PachcaResponse $lastResponse = null;
    protected int $connectTimeOut = 10;
    protected ?HttpClientInterface $httpClientHandler = null;
    protected ?string $baseBotUrl = null;
    protected int $timeOut = 60;

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
        $params = ['form_params' => $params];
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
        ))->setTimeOut($this->getTimeOut())->setConnectTimeOut($this->getConnectTimeOut());
    }

    public function getTimeOut(): int
    {
        return $this->timeOut;
    }
}
