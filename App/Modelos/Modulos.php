<?php
namespace App\Modelos;

use Cabeca\Model;
use Cabeca\Factory\Utilidades\Validar;
use App\Modelos\Datos\ModulosD;
use Cabeca\BasedatosInterface;

//Definiciones para estandarizar valores
define("MOD_ELIMINADO", 1);
define("MOD_ACTIVO", 1);
define("MOD_INACTIVO", 0);
define("MOD_SUCCESS", 200);
define("MOD_ERROR", 400);
define("MOD_DATOS_VALIDOS",200);
define("MOD_DATOS_INVALIDOS",400);
class Modulos extends Model implements BasedatosInterface
{
   
      
    /**
     *
     * @var array
     */
    public $mensaje;
    /**
     *
     * @var ModulosD
     */
    public $data;
    
    public function __construct( $base_datos = BD_GENERAL)
    {
           
        $this->base_datos = $base_datos;
        $this->mensaje = array();        
        parent::__construct($base_datos, "modulos");
        
        //Inicar tabla
        $this->create();
        $this->update();
    }

    public function borrar(string $id, string $campo="ModID",  int $usuario = 0)
    {
        if($this->isAdmin($_SESSION["USR_ROL"]))
           return  parent::borrar($id,$campo,$usuario);
        else return 0;
    }

    public function validar()
    {
        //((validacion de campos))
        return true;       
    }   

    public function agregar($datos)
    {
        if($this->isUsuario($_SESSION["USR_ROL"]))
        {
            $this->data = new ModulosD($datos);
            if($this->validar() === true)
            {               
                return parent::agregar($datos);
            }
            else return 0;
        }
        else return 0;
    }      
    
    private function create()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `modulos` (
        `ModID` int(11) NOT NULL AUTO_INCREMENT,
        `ModNombre` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
        `ModRol` int(11) NOT NULL,
        `ModPath` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
        `ModIcon` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
        `ModOrder` int(3) NOT NULL,
        `lastupdate` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`ModID`)
        ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

        INSERT INTO `modulos` (`ModID`, `ModNombre`, `ModRol`, `ModPath`, `ModIcon`, `ModOrder`, `lastupdate`) VALUES
        (1, 'PROGRAMA', 1, 'Programa', 'settings-1', 8, '2017-06-22 09:19:37'),
        (2, 'ADMINISTRADOR', 2, 'Admin', 'settings-1', 4, '2017-06-22 09:20:06'),
        (3, 'CATALOGOS', 4, 'Catalogos', 'portfolio-grid-1', 2, '2017-06-22 09:20:20'),
        (4, 'VENTAS', 4, 'Ventas', 'shopping-cart-1', 1, '2017-06-22 09:20:28'),
        (5, 'COBROS', 4, 'Cobros', 'list-details-1', 3, '2017-06-22 09:20:37'),
        (6, 'REPORTES', 3, 'Reportes', 'sales-up-1', 5, '2017-06-22 09:20:37'),
        (7, 'FACTURACION', 3, 'Factura', 'document-saved-1', 6, '2017-06-22 09:20:37');";
        
        if ($resultado = $this->conexion->query("SHOW TABLES LIKE '$this->tabla'"))
        {
            if ($resultado->num_rows == 0) $this->conexion->multi_query($sql);
        }               
    }
        
    private function update()
    {
        $update = "";        
        if ($resultado = $this->conexion->query("SHOW TABLES LIKE '$this->tabla'"))
        {
            if ($resultado->num_rows > 0 && strlen($update) > 10) $this->conexion->multi_query($update);
        }   
    }
        
}

