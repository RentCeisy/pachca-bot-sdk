<?php

namespace Rentceisy\PachcaBotSdk\Exceptions;

use Rentceisy\PachcaBotSdk\PachcaResponse;

final class PachcaResponseException extends PachcaSDKException
{
    private array $responseData = [];

    public function __construct(private PachcaResponse $response, ?PachcaSDKException $previousException = null)
    {
        $this->responseData = $response->getDecodedBody();

        $errorMessage = $this->get('description', 'Неизвестная ошибка API Response.');
        $errorCode = $this->get('error_code', -1);

        parent::__construct($errorMessage, $errorCode, $previousException);
    }

    public function get($key, mixed $default = null)
    {
        return $this->responseData[$key] ?? $default;
    }

    public static function create(PachcaResponse $response): self
    {
        $data = $response->getDecodedBody();

        $code = null;
        $message = null;
        if (isset($data['ok'], $data['error_code']) && $data['ok'] === false) {
            $code = $data['error_code'];
            $message = $data['description'] ?? 'Неизвестная ошибка from API.';
        }

        // Others
        return new self($response, new PachcaOtherException($message, $code));
    }

    public function getHttpStatusCode(): ?int
    {
        return $this->response->getHttpStatusCode();
    }

    public function getErrorType(): string
    {
        return $this->get('type', '');
    }

    public function getRawResponse(): string
    {
        return $this->response->getBody();
    }

    public function getResponseData(): array
    {
        return $this->responseData;
    }

    public function getResponse(): PachcaResponse
    {
        return $this->response;
    }
}
