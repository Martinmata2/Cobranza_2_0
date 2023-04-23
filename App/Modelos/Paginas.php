<?php
namespace App\Modelos;

use Cabeca\Model;
use Cabeca\Factory\Utilidades\Validar;
use App\Modelos\Datos\PaginasD;
use Cabeca\BasedatosInterface;

//Definiciones para estandarizar valores
define("Arc_ELIMINADO", 1);
define("Arc_ACTIVO", 1);
define("Arc_INACTIVO", 0);
define("Arc_SUCCESS", 200);
define("Arc_ERROR", 400);
define("Arc_DATOS_VALIDOS",200);
define("Arc_DATOS_INVALIDOS",400);
class Paginas extends Model implements BasedatosInterface
{
   
      
    /**
     *
     * @var array
     */
    public $mensaje;
    /**
     *
     * @var PaginasD
     */
    public $data;
    
    public function __construct( $base_datos = BD_GENERAL)
    {
           
        $this->base_datos = $base_datos;
        $this->mensaje = array();        
        parent::__construct($base_datos, "archivos");
        
        //Inicar tabla
        $this->create();
        $this->update();
    }

    public function borrar(string $id, string $campo="ArcID",  int $usuario = 0)
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
            $this->data = new PaginasD($datos);
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
        $sql = "CREATE TABLE IF NOT EXISTS `archivos` (
        `ArcID` int(11) NOT NULL AUTO_INCREMENT,
        `ArcNombre` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
        `ArcPath` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
        `ArcIcon` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
        `ArcModulo` int(11) NOT NULL,
        `ArcOrden` int(11) NOT NULL,
        `ArcSubModulo` int(11) NOT NULL,
        `lastupdate` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`ArcID`)
        ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
        INSERT INTO `archivos` (`ArcID`, `ArcNombre`, `ArcPath`, `ArcIcon`, `ArcModulo`, `ArcOrden`, `ArcSubModulo`, `lastupdate`) VALUES
        (1, 'Admin', 'Admin', 'user-1', 2, 1, 0, '2017-03-24 04:57:00'),
        (2, 'Comisiones', 'Comisiones-Cobros', 'dollar-sign-1', 5, 2, 0, '2017-07-14 11:18:51'),
        (3, 'Reportes', 'Reportes', 'pie-chart-1-1', 6, 1, 0, '2017-07-01 01:23:45'),
        (4, 'Ventas', 'Ventas', 'shopping-cart-1', 4, 1, 0, '2017-06-25 23:02:09'),
        (5, 'Comisiones', 'Comisiones-Ventas', 'dollar-sign-1', 4, 2, 0, '2017-07-04 12:20:19'),
        (6, 'Cobros', 'Cobros', 'credit-card-1', 5, 3, 0, '2017-07-01 01:18:03'),
        (7, 'Morosos', 'Ventas-Morosas', 'minus-1 add-1', 4, 1, 0, '2017-06-22 15:03:11'),
        (8, 'Servicios', 'Servicios', 'add-1', 4, 2, 0, '2017-07-01 18:10:19'),
        (9, 'Catalogos', 'Catalogos', 'portfolio-grid-1', 3, 1, 0, '2017-07-01 01:21:17'),
        (10, 'Facturas', 'Factura', 'document-saved-1', 7, 1, 0, '2017-07-01 01:21:17');";
        
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

