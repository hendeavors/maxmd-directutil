<?php

namespace Endeavors\MaxMD\DirectUtil;

use Endeavors\Support\VO;

class ValidatorResponse
{
    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public static function create($response)
    {
        return new static($response);
    }
    
    /**
     * @return bool
     */
    public function errors()
    {
        return 10 === (int)$this->response->return->code || 99 === (int)$this->response->return->code;
    }

    public function code()
    {
        return $this->response->return->code;
    }

    public function message()
    {
        return $this->response->return->message;
    }

    public function recipients()
    {
        return $this->response->return->recipients;
    }

    public function __get($arg)
    {
        $defaultResponse = (object)['return' => (object)['code' => 10, 'recipients' => [], 'message' => 'recipients: missing']];

        if('return' === $arg) {
            $defaultResponse = (object)['code' => 10, 'recipients' => [], 'message' => 'recipients: missing'];
        }

        if (null !== $this->response && null !== $this->response->return) {
            return isset($this->response->return->{$arg}) ? $this->response->return->{$arg} : $this->response->return;
        }

        if(method_exists($this, $arg)) {
            return $this->$arg();
        }

        return $defaultResponse;
    }
}