<?php
namespace Cabeca;


interface PaginaInterface
{
    
    function render();
    
    function encabezado();
    
    function menusuperior();
    
    function menulateral();
    
    function pie();
    
    function cuerpo();
}

