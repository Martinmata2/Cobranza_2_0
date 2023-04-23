<?php
namespace Cabeca;


interface MiddlewareHandleInterface
{    
    public function handle(array $auth);
}

