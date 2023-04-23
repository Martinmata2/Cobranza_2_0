<?php
namespace Cabeca;

use Cabeca\MySql\Query;

define("PERMISOS_TRUNCATE", 1);
define("PERMISOS_DELETE", 2);
define("PERMISOS_EDIT", 3);
define("PERMISOS_ADD", 4);
abstract class PermisosBD extends Query
{

    public function __construct(string $base_datos = BD_GENERAL)
    {
        parent::__construct($base_datos);        
    }
    
    private function valid($role)
    {
        return $role > 0 && $role <= PERMISOS_ADD;
    }
    
    /**
     * Verificar si el usuario tiene permiso de truncate
     *
     * @param int $role
     * @return boolean
     */
    protected function canTruncate($role)
    {
        if ($this->valid($role)) return $role <= PERMISOS_TRUNCATE;
        else return false;
    }
    
    /**
     * Verificar si el usuario tiene permido de borrar
     *
     * @param int $role
     * @return boolean
     */
    protected function canDelete($role)
    {
        if ($this->valid($role)) return $role == PERMISOS_DELETE;
        else return false;
    }
    
    /**
     * Verificar si el usuario tiene permiso de editar
     *
     * @param int $role
     * @return boolean
     */
    protected function canEdit($role)
    {
        if ($this->valid($role)) return $role <= PERMISOS_EDIT;
        else return false;
    }
    
    /**
     * verifica si el usuario tiene permisos de admin
     *
     * @param int $role
     * @return boolean
     */
    public function isAdmin($role)
    {
        if ($this->valid($role)) return $role <= PERMISOS_DELETE;
        else return false;
    }
    
    /**
     * Verifica si el usuario tiene permiso de supervisor
     * @param int $role
     * @return boolean
     */
    public function isSupervisor($role)
    {
        if($this->valid($role)) return $role <= PERMISOS_EDIT;
        else return false;
    }
    
    /**
     * verifica que el usuario tenga permiso de agregar informacion
     * @param int $role
     * @return boolean
     */
    public function isUsuario($role)
    {
        if($this->valid($role)) return $role <= PERMISOS_ADD;
        else return false;
    }  
}

