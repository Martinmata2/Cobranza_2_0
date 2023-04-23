<?php
namespace Cabeca\Interfaces;

/**
 *
 * @author Martin
 *         Interface basica para las clases que interactuan con la base de datos
 *        
 */
interface ListaInterface
{

    /**
     * Regresa lista formateada para select
     *
     * @param int $seleccionado
     * @param string $ordenado
     * @param string $condicion
     */
    public function listaSelect($seleccionado, $condicion = "0", $ordenado = "0");

    /**
     * Regresa lista foemateada en Json
     *
     * @param int $seleccionado
     * @param string $ordenado
     * @param string $condicion
     */
    public function listaJson($seleccionado,$condicion = "0", $ordenado = "0");      
}