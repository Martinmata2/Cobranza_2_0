<?php

use App\Core\Acceso;

/**
 *
 * @version 2023_1
 * @author Martin Mata
 */
@session_start();
include_once 'autoload.php';
$LOGIN = new Acceso();
if(isset($_SESSION["USR_ID"]))
    $LOGIN->sesionDestruir($_SESSION["USR_ID"]);
header("Location: /public/Login");
exit();
?>