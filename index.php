<?php

/**
 *
 * @version 2023_1
 * @author Martin Mata
 */

use App\Core\Acceso;

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off")
{
    $location = 'https://funeraria.gposanmiguel.com/';
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit();
}

@session_start();
$inicio = "";
include_once 'autoload.php';
$LOGIN = new Acceso();

if($LOGIN->estaLogueado())
{          
    header("Location: /public/Catalogos");
    exit();           
}
else
{
    header("Location: /public/Login");
    exit();
}

?>