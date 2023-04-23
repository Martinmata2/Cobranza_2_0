<?php
use App\Controllers\Simple;


set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__) . "/../../"));
include_once 'autoload.php';
try
{    
    $partes = explode("/",$_POST["accion"]);
    if(count($partes) > 2)
    {
        $controller = new Simple($_SESSION["USR_BD"], strtolower($partes[0]), $partes[2]);
    }
    else
    {
        $class = "App\\Controllers\\" . $partes[0];
        $controller = new $class();
    }    
    $controller->run($partes[1], $_POST);
}
catch(Exception $ex)
{
    print_r($ex);
}
?>
