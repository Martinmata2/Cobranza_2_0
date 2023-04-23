<?php
namespace Cabeca\Interfaces;

interface ActividadInterface
{
    /**
     * 
     * @param \stdClass $datos
     */
    public function AgregarActividad(\stdClass $datos);
    
    /**
     * 
     * @param \stdClass $datos
     */
    public function EditaActividad(\stdClass $datos);
}