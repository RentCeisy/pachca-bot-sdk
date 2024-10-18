<?php

namespace Rentceisy\PachcaBotSdk;

use Rentceisy\PachcaBotSdk\Exceptions\PachcaSDKException;
use Rentceisy\PachcaBotSdk\Methods\Message;
use Rentceisy\PachcaBotSdk\Traits\Http;

class Pachca
{
    use Http;
    use Message;
    public const VERSION = 'v0.0.1';
    /**
     * Instantiates a new Pachca super-class object.
     *
     * @throws PachcaSDKException
     */
    public function __construct(string $token)
    {
        $this->setAccessToken($token);
        $this->validateAccessToken();
    }

    /**
     * @throws PachcaSDKException
     */
    private function validateAccessToken(): void
    {
        if ($this->getAccessToken() === '' || $this->getAccessToken() === '0') {
            throw PachcaSDKException::tokenNotProvided();
        }
    }
}
