<?php


/**
 * Clase con datos de include y define, para auto include los archivos que contienen clases
* se incluyen al momento en que la clase es llamada
* @author Martin
*
*/

@session_start();


//error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
//ini_set("display_errors","off");
date_default_timezone_set("America/Mexico_City");
define("BD_GENERAL","gposauel_funeraria_general");
define("HOMEDR", "/Funeraria");
define("DFH", "public/");
define("_EOL", "\n");

spl_autoload_register(function ($classname) 
{
    $path = str_replace('\\', '/', $classname);
    include_once $path . '.php';
});
