<?php

namespace Rentceisy\PachcaBotSdk\Methods;

use Rentceisy\PachcaBotSdk\Exceptions\PachcaSDKException;
use Rentceisy\PachcaBotSdk\Objects\Message as MessageObject;

trait Message
{
    /**
     * @throws PachcaSDKException
     */
    public function sendMessage(array $params): ?MessageObject
    {
        if ($this->getAccessToken() === null) {
            return null;
        }
        $response = $this->post('messages', $params);

        return new MessageObject($response->getDecodedBody());
    }
}
