<?php
namespace Cabeca;


interface BasedatosInterface
{
    /**
     * Agregar datos
     * @param \stdClass $datos
     */
    public function agregar(\stdClass $datos);
    /**
     * Obtener datos
     * @param string $id
     * @param string $campo
     * @param string $condicion
     */
    public function obtener(string $id,string $campo = "", string $condicion = "");
    /**
     * Editar campo
     * @param string $id
     * @param string $campo
     * @param string $condicion
     */
    public function editar(\stdClass $datos, string $id, string $campo = "", string $condicion = "", int $usuario = 0);
    /**
     * Borrar campo
     * @param string $id
     * @param string $campo
     * @param string $condicion
     */
    public function borrar(string $id, string $campo, int $usuario = 0);
    
    /**
     * comprueba que exista
     */
    public function existe(string $campo, string $valor);
}

