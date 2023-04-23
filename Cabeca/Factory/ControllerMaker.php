<?php

include_once '../../autoload.php';

//The name of the directory that we need to create.
$directoryName = '../../App/Controllers/';

//Check if the directory already exists.
if(!is_dir($directoryName))
{
    //Directory does not exist, so lets create it.
    mkdir($directoryName, 0755, true);
}

$val = getopt(null,  ["Controller:","Modelo:"]);
if (isset($val["Modelo"]) && isset($val["Controller"])) 
{
    echo "Creando clases en folder App/Controllers/".$val["Controller"].".php \n";
    $class = file_get_contents("Formatos/Controller.php");    
    $buscar = array("<modelo>", "<controller>");
    $reemplazar = array($val["Modelo"],$val["Controller"]);
    $class = str_replace($buscar, $reemplazar,$class);
    $filename = "../../App/Controllers/".$val["Controller"].".php";
    file_put_contents($filename, $class);
    
    //echo var_export($val, true);
}
else {
    echo "_____________________________\n";  
    echo "--Controller: Nombre del archivo\n";
    echo "--Modelo: Nombre de la clase\n";    
    echo "---------------------------------\n";
}
?>
