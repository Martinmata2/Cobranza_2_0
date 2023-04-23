<?php
namespace Cabeca;


interface MiddlewareInterface
{
    
    public static function resolve(string $key,array $data);
}

