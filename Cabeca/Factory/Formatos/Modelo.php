<?php
namespace App\Modelos;

use Cabeca\Model;
use Cabeca\Factory\Utilidades\Validar;
use App\Modelos\Datos\<classD>;
use Cabeca\BasedatosInterface;

//Definiciones para estandarizar valores
define("<iniciales>_ELIMINADO", 1);
define("<iniciales>_ACTIVO", 1);
define("<iniciales>_INACTIVO", 0);
define("<iniciales>_SUCCESS", 200);
define("<iniciales>_ERROR", 400);
define("<iniciales>_DATOS_VALIDOS",200);
define("<iniciales>_DATOS_INVALIDOS",400);
class <class> extends Model implements BasedatosInterface
{
   
      
    /**
     *
     * @var array
     */
    public $mensaje;
    /**
     *
     * @var <classD>
     */
    public $data;
    
    public function __construct( $base_datos = BD_GENERAL)
    {
           
        $this->base_datos = $base_datos;
        $this->mensaje = array();        
        parent::__construct($base_datos, "<tabla>");
        
        //Inicar tabla
        $this->create();
        $this->update();
    }

    public function borrar(string $id, string $campo="<tableid>",  int $usuario = 0)
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
            $this->data = new <classD>($datos);
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
        $sql = "<create>";
        
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

