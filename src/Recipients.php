<?php

namespace Endeavors\MaxMD\DirectUtil;

use Endeavors\Support\VO;

class Recipients
{
    private static $instance;

    private $args;

    private $validator;

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
            $validator = (new RecipientValidator($instance->args[0]));

            $results = $validator->$method();

            $instance->cacheValidatorInstance($validator);

            return $results;
        } elseif( strtolower($method) === "untrusted" ) {
            return (new RecipientValidator($args[0]))->$method();
        } elseif( strtolower($method) === "trustederrorcode" ) {
            return $instance->getValidator()->getErrorCode();
        } elseif( strtolower($method) === "trustederrormessage" ) {
            return $instance->getValidator()->getErrorMessage();
        }
    }

    protected function checkArguments($args)
    {
        if(empty($args)) 
            return null;

        return $args;
    }

    protected function getValidator()
    {
        return $this->validator;
    }

    protected function cacheValidatorInstance($validator)
    {
        $this->validator = $validator;
    }
}