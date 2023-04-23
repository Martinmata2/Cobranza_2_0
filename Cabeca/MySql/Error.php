<?php
namespace Cabeca\MySql;


class Error extends Conexion
{
    /**
     * 
     * @param string $base_datos
     */
    public function __construct(string $base_datos = BD_GENERAL)
    {
        parent::__construct($base_datos);               
        if($this->estaConectado($base_datos))
        {
            if($resultado = $this->conexion->query("SHOW TABLES LIKE 'errores'"))
            {
                if($resultado->num_rows == 0)
                {
                    $this->conexion->query($this->tabla());
                }
            }
        }
    }
    
    /**
     * Cierra conexion al terminar su ciclo
     */
    public function __destruct() 
    {
        if(is_resource($this->conexion))
            $this->conexion->close();
    }
    
    /**
     * 
     * @param string $donde
     * @param string $que
     * @param string $usuario
     */
    public function reporte(string $donde, string $que, int $usuario = 0):void
    {
        if($this->estaConectado())
        {
            $query = "INSERT INTO errores VALUES(
			NULL,
			'" . $this->conexion->real_escape_string($usuario) . "',
			'" . date("Y-m-d H:i:s") . "',
			'" . $this->conexion->real_escape_string($donde) . "',
			'" . $this->conexion->real_escape_string($que) . "')";
            $this->conexion->query($query);
        }        
    }
    
    /**
     * Genera tabla para archivar errores
     * @return string
     */
    private function tabla():string
    {
        $mysql = "
            
			CREATE TABLE IF NOT EXISTS `errores` (
			  `ErrID` int(11) NOT NULL AUTO_INCREMENT,
			  `ErrUsuario` varchar(20) NOT NULL,
			  `ErrHora` datetime NOT NULL,
			  `ErrDonde` varchar(100) NOT NULL,
			  `ErrQue` text NOT NULL,
			  PRIMARY KEY (`ErrID`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        return $mysql;
    }
}

