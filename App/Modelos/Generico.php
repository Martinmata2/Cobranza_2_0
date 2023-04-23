<?php
namespace App\Modelos;

use Cabeca\Model;


class Generico extends Model
{
   
      
    /**
     *
     * @var array
     */
    public $mensaje;
    
    public function __construct( $base_datos, $tabla)
    {
           
        $this->base_datos = $base_datos;
        $this->mensaje = array();        
        parent::__construct($base_datos, $tabla);                      
    }               
    
    public function create($sql)
    {
        if ($resultado = $this->conexion->query("SHOW TABLES LIKE '$this->tabla'"))
        {
            if ($resultado->num_rows == 0) $this->conexion->multi_query($sql);
        }
    }
}

