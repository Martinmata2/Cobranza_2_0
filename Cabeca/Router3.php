<?php

namespace Cabeca;

use App\Core\Pagina;
use App\Middleware\Middleware;

global $params;
class Router3
{
    protected $routes = [];
   

    public function add($method, $uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'middleware' => "",
            'data'=> []
        ];

        return $this;
    }

    public function get($uri, $controller)
    {
        return $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        return $this->add('POST', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        return $this->add('DELETE', $uri, $controller);
    }

    public function patch($uri, $controller)
    {
        return $this->add('PATCH', $uri, $controller);
    }

    public function put($uri, $controller)
    {
        return $this->add('PUT', $uri, $controller);
    }

    public function middleware($key,$data)
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;
        $this->routes[array_key_last($this->routes)]['data'] = $data;
        return $this;
    }

    public function route(&$uri, $method)
    {

        foreach ($this->routes as $route) 
        {
            $this->params($route['uri'],$uri);    
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method))
            {
                if ($route["middleware"] == "pagina" && $route["data"]["pagina"] == "propia")
                    return require ($route['controller']);
                elseif (Middleware::resolve($route["middleware"], $route["data"])) // TODO Middleware;
                {
                    if (@class_exists("App\\Core\\Pagina"))

                    {
                        $PAGINA = new Pagina($uri, $route['controller']);
                        return $PAGINA->render();
                    }
                    return require ($route['controller']);
                }                
                else 
                    $this->abort("401");
            }
            
        }

        $this->abort();
    }

    private function params(&$route, &$uri)
    {
        global $params;
         // will store all the parameters value in this array
         $params = [];
         $paramMatches = [];
 
         // finding if there is any {?} parameter in $route
         preg_match_all("/(?<={).+?(?=})/", $route, $paramMatches);           
         // if the route does not contain any param call simpleRoute();
         if (empty($paramMatches[0]))
         {             
             return;
         }
         else
         {
            $temp = explode("/",$route);            
            $tempuri = explode("/",$uri);            
            if($temp[1] == $tempuri[1] && count($temp) == count($tempuri))
            {
                $count = 2;                
                foreach ($paramMatches[0] as $key => $value) 
                {

                    $params[$value] = $tempuri[$count++];   
                    //echo "value $value = ".$tempuri[$count++]."<br/>";
                }
                $route = "/".$temp[1];
                $uri = "/".$tempuri[1];                
            }
         }
             
    }

    protected function abort($code = 404)
    {
        http_response_code($code);

        require ("App/Views/{$code}.php");

        die();
    }
}
