<?php

use App\Core\Acceso;
use Cabeca\Router3;
use App\Core\Pagina;

@session_start();
set_include_path(get_include_path().PATH_SEPARATOR.realpath(dirname(__FILE__)."/../"));
$inicio = "../";
global $params;
if(! isset($_SESSION["CSRF"]))
    $_SESSION["CSRF"] = session_id();

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off")
{
    $location = 'https://funeraria.gposanmiguel.com/public/Home';
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit();
}

include_once "autoload.php";
$LOGED = new Acceso();
$ROUTE = new Router3();

require $inicio.'App/Core/routes.php';


$uri = str_replace("/public","",parse_url($_SERVER['REQUEST_URI'])['path']);
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
echo $ROUTE->route($uri, $method);
    
?>