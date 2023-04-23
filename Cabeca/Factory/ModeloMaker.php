<?php


use Cabeca\Factory\DataHelper;
include_once '../../autoload.php';

//The name of the directory that we need to create.
$directoryName = '../../App/Modelos/Datos/';

//Check if the directory already exists.
if(!is_dir($directoryName))
{
    //Directory does not exist, so lets create it.
    mkdir($directoryName, 0755, true);
}

$val = getopt(null, ["Modelo:","Tabla:","id:","const:"]);
if (isset($val["Modelo"]) && isset($val["Tabla"]) &&
    isset($val["id"]) && isset($val["const"])) 
{
    echo "Creando clases en folder App/Modelos/Datos/".$val["Modelo"]."D.php \n";
    $HELPER = new DataHelper();
    $datos = $HELPER->getDataMembers($val["Tabla"]);
    $classD = file_get_contents("Formatos/Data.php");
    $buscar = array("<classname>","<public>","<datamembers>");
    $reemplazar = array($val["Modelo"]."D", $datos["public"],$datos["datamembers"]);
    $classD = str_replace($buscar,$reemplazar, $classD);
    $filename = "../../App/Modelos/Datos/".$val["Modelo"]."D.php";
    file_put_contents($filename, $classD);
      
    
    echo "Creando clases en folder App/Models/".$val["Modelo"].".php \n";
    $class = file_get_contents("Formatos/Modelo.php");
    $create = $HELPER->createTable($val["Tabla"]);
    $buscar = array("<class>","<classD>","<tabla>","<tableid>","<iniciales>","<create>");
    $reemplazar = array($val["Modelo"], $val["Modelo"]."D", $val["Tabla"],
            $val["id"],$val["const"],$create[1]);
    $class = str_replace($buscar, $reemplazar,$class);
    $filename = "../../App/Modelos/".$val["Modelo"].".php";
    file_put_contents($filename, $class);
    
    //echo var_export($val, true);
}
else {
    echo "_____________________________\n";
    echo "Todos los campos son necesarios\n";
    echo "---------------------------------\n";  
    echo "--Modelo: Nombre de la clase\n";
    echo "--Tabla: Nombre de la tabla\n";
    echo "--id: id de la tabla\n";   
    echo "--const: Tres iniciales que describen la clase ejemplo Canal: CNL\n";
    echo "---------------------------------\n";
}
?>
