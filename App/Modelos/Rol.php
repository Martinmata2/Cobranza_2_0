<?php
namespace App\Modelos;

use Cabeca\Model;
use Cabeca\Factory\Utilidades\Validar;
use App\Modelos\Datos\RolD;
use Cabeca\BasedatosInterface;
use Cabeca\Interfaces\ListaInterface;

//Definiciones para estandarizar valores
define("ROL_ELIMINADO", 1);
define("ROL_ACTIVO", 1);
define("ROL_INACTIVO", 0);
define("ROL_SUCCESS", 200);
define("ROL_ERROR", 400);
define("ROL_DATOS_VALIDOS",200);
define("ROL_DATOS_INVALIDOS",400);

define("ROL_PROG", 1);
define("ROL_ADMIN", 2);
define("ROL_SUPER", 3);
define("ROL_VEND", 4);
define("ROL_COBRA", 5);
define("ROL_USU",6);

class Rol extends Model implements BasedatosInterface, ListaInterface
{
   
      
    /**
     *
     * @var array
     */
    public $mensaje;
    /**
     *
     * @var RolD
     */
    public $data;
    
    public function __construct( $base_datos = BD_GENERAL)
    {
           
        $this->base_datos = $base_datos;
        $this->mensaje = array();        
        parent::__construct($base_datos, "rol");
        
        //Inicar tabla
        $this->create();
        $this->update();
    }

    public function borrar(string $id, string $campo="RolID",  int $usuario = 0)
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
            $this->data = new RolD($datos);
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
        $sql = "CREATE TABLE IF NOT EXISTS `rol` (
        `RolID` int(11) NOT NULL,
        `RolNombre` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
        `lastupdate` timestamp NOT NULL DEFAULT current_timestamp()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
        INSERT INTO `rol` (`RolID`, `RolNombre`, `lastupdate`) VALUES
        (1, 'PROGRAMADOR', '2017-06-22 09:09:40'),
        (2, 'ADMINISTRADOR', '2017-06-22 09:09:50'),
        (3, 'SUPERVISOR', '2017-06-22 09:10:00'),
        (4, 'VENDEDOR', '2017-06-22 09:10:10'),
        (5, 'COBRADOR', '2017-06-22 09:10:10'),
        (6, 'USUARIO', '2017-06-22 09:10:10');";
        
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
        
	/**
	 * Regresa lista formateada para select
	 *
	 * @param int $seleccionado
	 * @param string $condicion
	 * @param string $ordenado
	 * @return mixed
	 */
	public function listaSelect($seleccionado, $condicion = "0", $ordenado = "0") 
    {
        return $this->options("RolID as id, RolNombre as nombre" , $this->tabla, "id", $seleccionado, $condicion, $ordenado );
	}
	
	/**
	 * Regresa lista foemateada en Json
	 *
	 * @param int $seleccionado
	 * @param string $condicion
	 * @param string $ordenado
	 * @return mixed
	 */
	public function listaJson($seleccionado, $condicion = "0", $ordenado = "0") 
    {
        return "No se ha implementado";
	}
}

