<?php
namespace Cabeca;

class Config
{
    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';
    
    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'basededtoa';
    
    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';
    
    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '';
    
    
    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = false;
    
    /**
     * Esta en estado de desarrollo
     * @var boolean
     */
    const TIMBRE_TESTING = true;
}

