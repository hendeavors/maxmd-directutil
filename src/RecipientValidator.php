<?php

namespace Endeavors\MaxMD\DirectUtil;

use Endeavors\Support\VO;
use Endeavors\MaxMD\Support\Client;
use Endeavors\MaxMD\Api\Auth\Session;
use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Api\Auth\UnauthorizedAccessException;

/**
 * Validate the recipient using MaxMD
 */
class RecipientValidator
{
    private $errorCode;

    private $errorMessage;

    public function __construct($items)
    {
        $this->items = VO\ModernArray::create($items);
    }

    /**
     * Trusted recipients
     */
    public function trusted()
    {
        $valids = [];

        $response = $this->response();
        // no valid recipients sent or something is wrong with maxmd
        if( $response->errors() ) {
            $this->errorCode = (int)$response->return->code;

            $this->errorMessage = $response->return->message;

            return $valids;
        }

        if( is_array($response->return->recipients) ) {
            foreach($response->return->recipients as $item) {
                if ($item->trustRelation === "Trusted" ) {
                    $valids[] = $item->directAddress;
                }
            }
        } else {
            if( $response->return->recipients->trustRelation === "Trusted" ) {
                $valids[] = $response->return->recipients->directAddress;
            }
        }

        return $valids;
    }

    public function unTrusted()
    {
        $invalids = [];

        $response = $this->response();

        // something is wrong with maxmd
        if( 99 === (int)$response->return->code )
            return $invalids;
        // no recipients
        if(10 === (int)$response->return->code )
            return $invalids;

        if( is_array($response->return->recipients) ) {
            foreach($response->return->recipients as $item) {
                if ($item->trustRelation !== "Trusted" ) {
                    $invalids[] = $item->directAddress;
                }
            }
        } else {
            if( $response->return->recipients->trustRelation !== "Trusted" ) {
                $invalids[] = $response->return->recipients->directAddress;
            }
        }

        return $invalids;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    protected function response()
    {
        if( Session::check() ) {
            // pre-validate the items to ensure a correct email is being sent
            $items = ValidRecipientCollection::create($this->items->get());

            $client = Client::DirectUtil();

            $response = $client->ValidateRecipients([
                "sessionId" => Session::getId(),
                "ownerDirectAddress" => User::getInstance()->getUsername(),
                "recipients" => $items->toArray()
            ]);

            return ValidatorResponse::create($response);
        }

        throw new UnauthorizedAccessException("Your session is invalid or expired. Please authenticate with maxmd api.");
    }

    protected function isError($response)
    {
        return 10 === (int)$response->return->code || 99 === (int)$response->return->code;
    }
}
