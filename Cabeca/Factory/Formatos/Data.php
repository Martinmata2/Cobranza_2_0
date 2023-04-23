<?php
namespace App\Modelos\Datos;

class <classname>
{
    <public> 
    public function __construct($datos = null)
    {
        try
        {
            if ($datos == null)
            {
                <datamembers>
            }
            else
            {
                foreach ($datos as $k => $v)
                {
                    $this->$k = $v;
                }
            }
        }
        catch (\Exception $e)
        {}
    }
}