<?php
namespace App\Modelos;

use Cabeca\Model;
use Cabeca\Factory\Utilidades\Validar;
use App\Modelos\Datos\PermisosD;
use Cabeca\BasedatosInterface;
use Cabeca\MiddlewareHandleInterface;

//Definiciones para estandarizar valores
define("PER_ELIMINADO", 1);
define("PER_ACTIVO", 1);
define("PER_INACTIVO", 0);
define("PER_SUCCESS", 200);
define("PER_ERROR", 400);
define("PER_DATOS_VALIDOS",200);
define("PER_DATOS_INVALIDOS",400);
class Permisos extends Model implements BasedatosInterface, MiddlewareHandleInterface
{
   
      
    /**
     *
     * @var array
     */
    public $mensaje;
    /**
     *
     * @var PermisosD
     */
    public $data;
    
    public function __construct( $base_datos = BD_GENERAL)
    {
           
        $this->base_datos = $base_datos;
        $this->mensaje = array();        
        parent::__construct($base_datos, "permisosusuarios");
        
        //Inicar tabla
        $this->create();
        $this->update();
    }

    public function borrar(string $id, string $campo="PusID",  int $usuario = 0)
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
            $this->data = new PermisosD($datos);
            if($this->validar() === true)
            {               
                return parent::agregar($datos);
            }
            else return 0;
        }
        else return 0;
    }      
    
    public function acceso($pagina)
    {
        
        $permisos = $this->obtener($_SESSION["USR_ID"],"PusUsuario");
        $permiso = explode(",", $permisos->PusPermisos);
        $PAGINA = new Generico(BD_GENERAL, "archivos");
        $pagid = $PAGINA->obtener($pagina,"ArcPath");
        if($pagid !== 0)
            return in_array($pagid->ArcID, $permiso);
        else return false;
    }
    public function render($usuario = 0)
    {
        $modulo = "0";
        $html = "<div class='row row gx-5 justify-content-center'>";
        $secciones = $this->consulta("*", "modulos", ($usuario == 1)?"0":"ModRol > 1");
        $count = 0;
        foreach ($secciones as $seccion)
        {
            if($count++ > 2)
            {
                $count = 0;                
                $html .= "<hr>";
            }
            else $end = "";
            $html .= "<div class='col-md-4'>";
            $html .= "    <h5> ".$seccion->ModNombre."  </h5>";
            $html .= "  <ul class='tree1' style='margin-left:0.5rem;'>";
            $permisos = $this->consulta("*", "archivos","ArcModulo = ".$seccion->ModID,  "ArcOrden");
            $html .= "<li style='vertical-align: middle;'>";
            $html .= "  <ul style='display: table'>";
            foreach ($permisos as $permiso)
            {
                $html .= "      <li style='vertical-align: middle;'>
                                    <div class='form-check'>
    		                              <input class='form-check' type='checkbox' id='id_".$permiso->ArcID."' value='".$permiso->ArcID."' />
    		                              <label  class='form-check-label' for='id_".$permiso->ArcID."' >".$permiso->ArcNombre."</label>
                                    </div>
		                      </li>";
                
            }
            
            $html .= "        </ul>";
            $html .= "      </li>";
            $html .= "    </ul>";
            $html .= "</div>";
            
        }
        $html .= "</div>";
        return $html;
    }
    private function create()
    {
        $sql = "CREATE TABLE `permisosusuarios` (
          `PusID` int(11) NOT NULL AUTO_INCREMENT,
          `PusUsuario` int(11) NOT NULL,
          `PusPermisos` varchar(500) COLLATE utf8_spanish2_ci NOT NULL,
          `updated` int(1) NOT NULL,
          `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`PusID`),
          UNIQUE KEY `PusUsuario` (`PusUsuario`)
        ) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci";
        
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
    
    public function handle(array $auth)
    {
        return $this->acceso($auth["pagina"]);        
    }

        
}

