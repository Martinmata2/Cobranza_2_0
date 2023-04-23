<?php
namespace Cabeca\Interfaces;

interface GridInterface
{
    /**
     * Devuelve los campos del datagrid
     * @param array $arguments
     * @return array
     */
    public function grid($arguments = null);
    
    /**
     * Devuelve la forma a usar para agregar datos
     * @return string
     */
    public function forma($arguments = null);
    
    /**
     * Devuelve la forma a usar para agregar datos modal
     * @return string
     */
    public function modal($arguments = null);
}