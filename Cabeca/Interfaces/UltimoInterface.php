<?php
namespace Cabeca\Interfaces;

interface UltimoInterface
{
    /**
     * Ultimo record
     * @param string $id
     * @param string $tabla
     */
    public function Ultimo(string $id, string $tabla);        
}