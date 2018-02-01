<?php

namespace Endeavors\MaxMD\DirectUtil;

use Endeavors\Support\VO;

class Recipients
{
    public static function __callStatic($method, $args)
    {
        if( $method === "trusted" ) {
            return (new RecipientValidator($args[0]))->$method();
        } elseif( strtolower($method) === "untrusted" ) {
            return (new RecipientValidator($args[0]))->$method();
        }
    }
}