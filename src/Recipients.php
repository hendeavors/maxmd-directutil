<?php

namespace Endeavors\MaxMD\DirectUtil;

use Endeavors\Support\VO;

class Recipients
{
    private static $instance;

    private $args;

    private function __construct($args)
    {
        $this->args = $this->checkArguments($args) ?? [[]];
    }

    private static function getInstance()
    {
        return static::$instance;
    }

    private static function instance($args)
    {
        if(!empty($args)) {
            static::$instance = new static($args);
        } elseif(null === static::getInstance()) {
            static::$instance = new static($args);
        }

        return static::$instance;
    }

    public static function __callStatic($method, $args)
    {
        $instance = static::instance($args);

        if( $method === "trusted" ) {
            return (new RecipientValidator($instance->args[0]))->$method();
        } elseif( strtolower($method) === "untrusted" ) {
            return (new RecipientValidator($args[0]))->$method();
        } elseif( strtolower($method) === "trustederrorcode" ) {
            $validator = (new RecipientValidator($instance->args[0]));
            $validator->trusted();
            return $validator->getErrorCode();
        } elseif( strtolower($method) === "trustederrormessage" ) {
            $validator = (new RecipientValidator($instance->args[0]));
            $validator->trusted();
            return $validator->getErrorMessage();
        }
    }

    protected function checkArguments($args)
    {
        if(empty($args)) 
            return null;

        return $args;
    }
}