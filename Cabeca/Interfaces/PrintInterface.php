<?php
namespace Cabeca\Interfaces;

interface PrintInterface
{
    /**
     *
     * imprimir
     * @param int $id
     */
    public function Imprimir(int $id);    
    
    /**
     * Mostrar en pdf
     * @param int $id
     */
    public function pdf(int $id);
}