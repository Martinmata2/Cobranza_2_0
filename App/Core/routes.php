<?php
if($LOGED->estaLogueado())
{
    /************ Login  **/
    $ROUTE->get("/Login",           $inicio."App/Views/Login.php");
    $ROUTE->get("/Inicio",          $inicio."App/Views/Login.php");
    $ROUTE->get("/Salir",           $inicio."salir.php");
    
    
    /********************importar base de datos *************/
   // $ROUTE->get("/Setup",           $inicio."App/Views/setup.php");
   // $ROUTE->get("/Setup/{remote}",  $inicio."App/Views/setup.php");
  
    
    /***********  Catalogos ***********/
    $ROUTE->get("/Catalogos",       $inicio."App/Views/Catalogos/Catalogos.php")->middleware("auth",array("pagina"=>"Catalogos"));
    $ROUTE->post("/Catalogos",       $inicio."App/Views/Catalogos/Catalogos.php");
    $ROUTE->get("/Cliente",         $inicio."App/Views/Catalogos/Cliente.php");   
    $ROUTE->get("/Producto",        $inicio."App/Views/Catalogos/Producto.php");    
    $ROUTE->get("/Producto/{id}",   $inicio."App/Views/Catalogos/Producto.php");
    $ROUTE->get("/Cliente/{id}",    $inicio."App/Views/Catalogos/Cliente.php");
    $ROUTE->get("/ListaClientes/{option}",    $inicio."App/Views/Lista/Clientes.php");
    $ROUTE->get("/ListaProductos",    $inicio."App/Views/Lista/Productos.php");
    /*****************end catalogos *******/
   
    /******************** Ventas  */

    $ROUTE->get("/Ventas",          $inicio."App/Views/Pos/Ventas.php")->middleware("auth",array("pagina"=>"Ventas"));
    $ROUTE->post("/Ventas",         $inicio."App/Views/Pos/Ventas.php");
    $ROUTE->get("/Venta",           $inicio."App/Views/Pos/Venta.php");
    $ROUTE->get("/Venta/{FacID}",   $inicio."App/Views/Pos/Venta.php");
    $ROUTE->get("/Cancelar/{FacID}",   $inicio."App/Views/Pos/Cancelar.php");
    $ROUTE->get("/Cancelar/{FacID}/{Aprovacion}",   $inicio."App/Views/Pos/Cancelar.php");
    /***************  end Ventas */

    /********************** Cobros */
    $ROUTE->get("/Cobros",          $inicio."App/Views/Cobrador/Cobros.php")->middleware("auth",array("pagina"=>"Cobros"));
    $ROUTE->get("/Pago/{CliID}/{FacID}",   $inicio."App/Views/Cobrador/Cobro.php");    
    $ROUTE->get("/Abono/{AboID}",   $inicio."App/Views/Cobrador/Abono.php");
    
    /********************  PDF ***********************/
    $ROUTE->get("/Pdf-Abono/{AboID}",   $inicio."App/Views/Pdf/Pago.php")->middleware("pagina",array("pagina"=>"propia"));
    $ROUTE->get("/Pdf-Bono/{AboID}",   $inicio."App/Views/Pdf/Bono.php")->middleware("pagina",array("pagina"=>"propia"));
        /****************************   end cobros */

    /**********************  Back up base de datos ***************************/
    $ROUTE->get("/Backup",         $inicio."backup.php");
  

    /*************** Admin **/
    if($LOGED->isAdmin($_SESSION["USR_ROL"]))
    {
        $ROUTE->get("/Usuarios",       $inicio."App/Views/Admin/Usuarios.php")->middleware("auth",array("pagina"=>"Usuarios"));
        $ROUTE->post("/Usuarios",       $inicio."App/Views/Admin/Usuarios.php");
        $ROUTE->get("/Admin",       $inicio."App/Views/Admin/Usuarios.php")->middleware("auth",array("pagina"=>"Usuarios"));
        $ROUTE->post("/Admin",       $inicio."App/Views/Admin/Usuarios.php");
        $ROUTE->get("/Permisos/{UsuID}",       $inicio."App/Views/Admin/Permisos.php");
       
    }
    /***************** Supervisor */                
    if($LOGED->isSupervisor($_SESSION["USR_ROL"]))
    {
        /***************Reportes */
        $ROUTE->get("/Reportes",    $inicio."App/Views/Reportes/Ventas.php")->middleware("auth",array("pagina"=>"Reportes"));
        $ROUTE->get("/ReportesC",    $inicio."App/Views/Reportes/Cobros.php")->middleware("auth",array("pagina"=>"ReportesC"));
        /************** Comisiones */
        $ROUTE->get("/Comisiones-Ventas",          $inicio."App/Views/Pos/Comisiones.php")->middleware("auth",array("pagina"=>"Comisiones-Ventas"));
        $ROUTE->get("/Comision/{FacID}",           $inicio."App/Views/Pos/Comision.php");        
        $ROUTE->get("/ComisionesC",          $inicio."App/Views/Cobrador/Comision.php")->middleware("auth",array("pagina"=>"ComisionesC"));
        
        /****************** Modificar Abono ***********************/
        $ROUTE->get("/Abono/{AboID}",   $inicio."App/Views/Cobrador/Abono.php");
        /***************Facturacion */
       
    }

}
else /****** Acesible para los que no estan logeados */
{
    $ROUTE->get("/",                $inicio."App/Views/Login.php");    
    $ROUTE->get("/Login",           $inicio."App/Views/Login.php");
    $ROUTE->get("/Salir",           $inicio."salir.php");
}
