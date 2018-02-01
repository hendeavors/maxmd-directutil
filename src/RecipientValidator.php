<?php

namespace Endeavors\MaxMD\DirectUtil;

use Endeavors\Support\VO;
use Endeavors\MaxMD\Support\Client;
use Endeavors\MaxMD\Api\Auth\Session;
use Endeavors\MaxMD\Message\User;

/**
 * Validate the recipient using MaxMD
 */
class RecipientValidator
{
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

        foreach($response->return->recipients as $item) {
            if ($item->trustRelation === "Trusted" ) {
                $valids[] = $item->directAddress;
            }
        }

        return $valids;
    }

    public function unTrusted()
    {
        $invalids = [];

        $response = $this->response();

        foreach($response->return->recipients as $item) {
            if ($item->trustRelation !== "Trusted" ) {
                $invalids[] = $item->directAddress;
            }
        }

        return $invalids;
    }

    protected function response()
    {
        // pre-validate the items to ensure a correct email is being sent
        $items = ValidRecipientCollection::create($this->items->get());
        
        $client = Client::DirectUtil();

        $response = $client->ValidateRecipients([
            "sessionId" => Session::getId(),
            "ownerDirectAddress" => User::getInstance()->getUsername(),
            "recipients" => $items->toArray()
        ]);

        return $response;
    }
}