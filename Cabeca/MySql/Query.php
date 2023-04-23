<?php
namespace Cabeca\MySql;

abstract class Query extends Conexion
{

    protected $error;

    function __construct(string $base_datos = BD_GENERAL)
    {
        $this->error = new Error();
        parent::__construct($base_datos);
        if ($this->conexion->select_db($base_datos))
        {
            if ($resultado = $this->conexion->query("SHOW TABLES LIKE 'eliminados'"))
            {
                if ($resultado->num_rows == 0) $this->conexion->query($this->sqlTabla());
            }
        }
    }

    function __destruct()
    {
        if (is_resource($this->conexion)) $this->conexion->close();
    }

    /**
     *
     * @param string $tabla
     * @param \stdClass $datos
     * @param int $usuario
     * @return int
     */
    protected function insertar(string $tabla, \stdClass $datos, int $usuario = 0): int
    {
        if ($this->estaConectado())
        {
            $queryelements = "";
            $query = "INSERT INTO " . $this->conexion->real_escape_string($tabla) . " SET ";
            foreach ($datos as $key => $value)
            {
                $key = $this->conexion->real_escape_string($key);
                $value = $this->conexion->real_escape_string($value);
                $queryelements .= "$key = '$value',";
            }
            $queryelements = rtrim($queryelements, ',');
            $query .= $queryelements;
            try
            {
                $result = $this->conexion->query($query);
                if ($result !== FALSE)
                {
                    return $this->conexion->insert_id;
                }
                else
                {
                    $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $this->conexion->error);
                }
            }
            catch (\Exception $e)
            {
                $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $e->getMessage(), $usuario);
            }
        }
        else
        {
            return 0;
        }
        return 0;
    }

    /**
     *
     * @param string $tabla
     * @param \stdClass $datos
     * @param int $usuario
     * @return int
     */
    protected function reemplazar(string $tabla, \stdClass $datos, int $usuario = 0): int
    {
        if ($this->estaConectado())
        {
            $queryelements = "";
            $query = "REPLACE INTO " . $this->conexion->real_escape_string($tabla) . " SET ";
            foreach ($datos as $key => $value)
            {
                $key = $this->conexion->real_escape_string($key);
                $value = $this->conexion->real_escape_string($value);
                $queryelements .= "$key = '$value',";
            }
            $queryelements = rtrim($queryelements, ',');
            $query .= $queryelements;
            try
            {
                $result = $this->conexion->query($query);
                if ($result !== FALSE)
                {
                    return $this->conexion->insert_id;
                }
                else
                {
                    $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $this->conexion->error, $usuario);
                }
            }
            catch (\Exception $e)
            {
                $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $e->getMessage(), $usuario);
            }
        }
        else
        {
            return 0;
        }
        return 0;
    }

    /**
     *
     * @param string $tabla
     * @param \stdClass $datos
     * @param string $id
     * @param string $buscar_por
     * @param int $usuario
     */
    protected function modificar(string $tabla, \stdClass $datos, string $id, string $buscar_por, int $usuario = 0)
    {
        if ($this->estaConectado())
        {
            $queryelements = "";
            $query = "Update " . $tabla . " SET ";
            foreach ($datos as $key => $value)
            {               
                $key = $this->conexion->real_escape_string($key);
                $value = $this->conexion->real_escape_string($value);
                $queryelements .= "$key = '$value',";
            }
            $queryelements = rtrim($queryelements, ',');
            $query .= $queryelements;
            $query .= " WHERE " . $buscar_por . " = '$id' ";
            // $this->error->reporte("aqui", $query);
            try
            {
                $result = $this->conexion->query($query);
                if ($result !== FALSE)
                {
                    return $id;
                }
                else
                {
                    $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $this->conexion->error, $usuario);
                }
            }
            catch (\Exception $e)
            {
                $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $e->getMessage(), $usuario);
            }
        }
        else
        {
            return 0;
        }
        return 0;
    }

    /**
     *
     * @param string $tabla
     * @param string $id
     * @param string $busca_por
     * @param number $usuario
     * @return int
     */
    protected function eliminar(string $tabla, string $id, string $busca_por, $usuario = 0): int
    {
        if ($this->estaConectado())
        {
            try
            {
                $query = "DELETE FROM $tabla WHERE " . $busca_por . "='$id' ";
                $result = $this->conexion->query($query);
                if ($result !== FALSE)
                {
                    $data = new \stdClass();
                    $data->EliTabla = $tabla;
                    $data->EliCampoID = $id;
                    $data->EliCampoNombre = $busca_por; 
                    $this->insertar("eliminados", $data);
                    return 1;
                }
                else
                {
                    $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $this->conexion->error, $usuario);
                }
            }
            catch (\Exception $e)
            {
                $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $e->getMessage() . $usuario);
            }
        }
        else
        {
            return 0;
        }
        return 0;
    }

    /**
     *
     * @param string $tabla
     * @param string $condicion
     * @param int $usuario
     * @return int
     */
    protected function eliminarEspecial(string $tabla, string $condicion, int $usuario = 0): int
    {
        if ($this->estaConectado())
        {
            try
            {
                $query = "DELETE FROM $tabla WHERE $condicion";
                $result = $this->conexion->query($query);
                if ($result !== FALSE)
                {
                    return 1;
                }
                else
                {
                    $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $this->conexion->error, $usuario);
                }
            }
            catch (\Exception $e)
            {
                $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $e->getMessage() . $usuario);
            }
        }
        else
        {
            return 0;
        }
        return 0;
    }

    /**
     * 
     * @param string $campos
     * @param string $tabla
     * @param string $sel_campo
     * @param string $seleccionado
     * @param string $where
     * @param string $orderby
     * @param string $limit
     * @return number|string
     */
    protected function options(string $campos, string $tabla, string $sel_campo, string $seleccionado = "0", string $where = "0", string $orderby = "0", string $limit = "0")
    {
        $options = "";
        if ($this->estaConectado())
        {
            try
            {
                $query = "SELECT $campos FROM " . $tabla;
                if ($where != "0") $query .= " WHERE " . $where;
                if ($orderby != "0") $query .= " ORDER BY " . $orderby;
                if ($limit != 0) $query .= " Limit " . $limit;
                $result = $this->conexion->query($query);
                // $this->error->reporte("paises", $query , "admin");
                while ($fila = $result->fetch_object())
                {
                    $sltd = "";
                    if ($seleccionado == $fila->$sel_campo) $sltd = " selected ";

                    $options .= "<option value='" . $fila->id . "' " . $sltd . ">" . $fila->nombre . "</option>";
                }

                return (strlen($options) > 0) ? $options : 0;
            }
            catch (\Exception $e)
            {
                $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $e->getMessage(), "admin");
            }
        }
        else
            return 0;
        return 0;
    }
    
    /**
     * 
     * @param string $campos
     * @param string $tabla
     * @param string $where
     * @param string $orderby
     * @param string $groupby
     * @param string $limit
     * @param int $usuario
     * @return array|number
     */
    protected function consulta(string $campos, string $tabla, string $where = "0", string $orderby = "0", string $groupby = "0", string $limit = "0", int $usuario = 0)
    {
        $datos = array();
        if ($this->estaConectado())
        {
            try
            {
                $query = "SELECT " . $campos . " FROM " .$tabla;
                if ($where != "0") $query .= " WHERE " . $where;
                if ($groupby != "0") $query .= " GROUP BY " .$groupby;
                if ($orderby != "0") $query .= " ORDER BY " . $orderby;
                if ($limit != "0") $query .= " Limit " . $limit;
                //$this->error->reporte("aqui", $query);
                $result = $this->conexion->query($query);
                while ($fila = $result->fetch_object())
                {
                    $datos[] = $fila;
                }
                return $datos;
            }
            catch (\Exception $e)
            {
                $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $e->getMessage(), $usuario);
            }
        }
        else
            return 0;
        return 0;
    }

    /**
     * 
     * @param string $tabla
     * @param \stdClass $datos
     * @param string $where
     * @param int $usuario
     * @return int
     */
    protected function modificarEspecial(string $tabla, \stdClass $datos, string $where, int $usuario = 0): int
    {
        if ($this->estaConectado())
        {
            $queryelements = "";
            $query = "Update " . $tabla . " SET ";
            foreach ($datos as $key => $value)
            {
                $queryelements .= "$key = $value,";
            }
            $queryelements = rtrim($queryelements, ',');
            $query .= $queryelements;
            if ($where != "0") $query .= " WHERE " . $where;
            // $this->error->reporte ( get_class ( $this ) . __METHOD__, $query . " ", $usuario );
            try
            {
                $result = $this->conexion->query($query);
                if ($result !== FALSE)
                {
                    return 1;
                }
                else
                {
                    $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $this->conexion->error, $usuario);
                }
            }
            catch (\Exception $e)
            {
                $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $e->getMessage(), $usuario);
                
            }
        }
        else
            return 0;
        return 0;
    }
    
    /**
     * 
     * @param string $tabla
     * @param string $id
     * @return array|number
     */
    protected function ultimoRecord(string $tabla, string $id)
    {
        $datos = array();
        if ($this->estaConectado())
        {
            try
            {
                $query = "SELECT MAX($id) as ultimo FROM $tabla";
                //$this->error->reporte("aqui", $query);
                $result = $this->conexion->query($query);
                while ($fila = $result->fetch_object())
                {
                    $datos[] = $fila;
                }
                return $datos;
            }
            catch (\Exception $e)
            {
                $this->error->reporte(get_class($this) . __METHOD__, $query . "  " . $e->getMessage());
            }
        }
        else
            return 0;
        return 0;
    }
    
    /**
     *
     * @return string Estructura para base de datos mysql
     */
    private function sqlTabla()
    {
        $mysql = "
            
			CREATE TABLE IF NOT EXISTS `eliminados` (
			  `EliID` int(11) NOT NULL AUTO_INCREMENT,
			  `EliTabla` varchar(40) NOT NULL,
			  `EliCampoID` int(11) NOT NULL ,
			  `EliCampoNombre` varchar(40) NOT NULL,
			  `updated` tinyint(1) NOT NULL,
			  PRIMARY KEY (`EliID`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        return $mysql;
    }
}

