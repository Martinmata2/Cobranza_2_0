<?php 


namespace App\Middleware;

use Cabeca\MiddlewareInterface;
use App\Modelos\Permisos;
class Middleware implements MiddlewareInterface 
{
    public const MAP = [        
        'auth' => Permisos::class
    ];
    public static function resolve(string $key,array $data = [])
    {
        if($key == "")
        {
            return true;
        }
        
        $middleware = static::MAP[$key]?? false;
        if(!$middleware)
            return true;        
        return (new $middleware)->handle($data);
    }

}