<?php

/**
 *
 * @version 2022_1
 * @author Martin Mata
 */
namespace App\Core;
use App\Core\Acceso;
use App\Modelos\Modulos;
use App\Modelos\Paginas;
use App\Modelos\Rol;
use Cabeca\Factory\Utilidades\StringHelper;
use App\Modelos\Permisos;
use Cabeca\PaginaInterface;


class Pagina extends Acceso implements PaginaInterface
{
    /**
     * @var string
     */
    private $destino;

    /**
     * @var string
     */
    private $html;
    /**
     * @var array
     */
    private $paginas;
    /**
     * @var Rol
     */
    private $Rol;
    /**
     * @var string
     */
    private $modulo;
    /**
     * @var bool
     */
    private $page;
    /**
     * @var Permisos
     */
    private $PERMISOS;
    
    /**
     * 
     * @var array
     */
    private $permisos;
        

    public function __construct(string $destino, string $archivo)
    {   
        @session_start();
        parent::__construct();
        $destino = str_replace("/","", $destino);
        $this->PERMISOS = new Permisos();
        $this->Rol = new Rol();
        if($destino != "Login")
        {
            if(!$this->estaLogueado())
            {
                header("Location: /public/Login");
                exit();
            }
        }
        if($destino == "Login")
        {
            $this->permisos = [];
        }
        else 
        {
            $this->permisos = explode(",",$this->PERMISOS->obtener($_SESSION["USR_ID"], "PusUsuario")->PusPermisos);
        }
        
        $this->paginas = [];
        $this->page = false;
        $this->destino = $destino;
        $this->file = $archivo;
        
        
        $PAGINAS = new Paginas();
        foreach ($this->permisos as $permiso) 
        {
            $this->paginas[] = $PAGINAS->obtener($permiso,"ArcID");
        }        
               
        $this->modulo = 0;
        if(count($this->paginas) > 0)
            foreach ($this->paginas as $pagina)
            {
                if($pagina->ArcPath == $this->destino)
                {                            
                    $this->modulo = $pagina->ArcModulo;                
                    break;
                }
            }           
       
    }

    public function render()
    {
        $this->encabezado();
        $this->menu();
        $this->cuerpo();
        $this->pie();
        return $this->html;
    } 

    public function encabezado()
    {
        @session_start();
        if (! isset($_SESSION["CSRF"])) 
            $_SESSION["CSRF"] = session_id();       
        $this->html = 
        "
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset='utf-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <title>FUNERARIAS GPSM</title>
                <meta name='description' content='Sistema de cobranza'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <meta name='robots' content='all,follow'>

                <!-- Choices CSS-->
                <link rel='stylesheet' href='https://funeraria.gposanmiguel.com/public/css/dashboard/choices.css'>
                <!-- Custom Scrollbar-->
                <link rel='stylesheet' href='https://funeraria.gposanmiguel.com/public/css/dashboard/OverlayScrollbars.min.css'>
                <!-- theme stylesheet-->
                <link rel='stylesheet' href='https://funeraria.gposanmiguel.com/public/css/theme/style.blue.css' id='theme-stylesheet'>
               
                <!-- Custom stylesheet - for your changes-->
                <link rel='stylesheet' href='https://funeraria.gposanmiguel.com/public/css/dashboard/custom.css'>
                <!-- Favicon-->
                <link rel='shortcut icon' href='https://funeraria.gposanmiguel.com/public/img/rest.png'>
                <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css'>
                <!-- Google fonts - Roboto -->
                <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700'>
                

                <!-- Core theme CSS (includes Bootstrap)-->
                <link href='https://funeraria.gposanmiguel.com/public/css/styles.css' rel='stylesheet' />
                <link href='https://funeraria.gposanmiguel.com/public/css/Colorbox/colorbox.css' rel='stylesheet' />
                <link href='https://funeraria.gposanmiguel.com/public/css/dashboard/graph.css' rel='stylesheet' />


                <script src='https://funeraria.gposanmiguel.com/public/js/jquery-3.6.0.min.js'></script>
                <script src='https://funeraria.gposanmiguel.com/public/js/Grid/js/jquery-ui.js'></script>

                    
                <link href='https://funeraria.gposanmiguel.com/public/css/Grid/css/ui.jqgrid.css'
                    rel='stylesheet' type='text/css' media='screen'>
                <!-- EL orden de locale-es.js debe de ser antes de jqGrid -->
                <script src='https://funeraria.gposanmiguel.com/public/js/Grid/js/i18n/grid.locale-es.js'
                    type='text/javascript'></script>
                <script src='https://funeraria.gposanmiguel.com/public/js/Grid/js/jquery.jqGrid.min.js'
                    type='text/javascript'></script>
            
            
                <link href='https://funeraria.gposanmiguel.com/public/css/Grid/css/themes/redmond/jquery-ui.custom.css'
                    rel='stylesheet' type='text/css' media='screen'/>
                <link href='https://funeraria.gposanmiguel.com/public/css/bootstrap.min.css'/>
                <link href='https://funeraria.gposanmiguel.com/public/css/bootstrap-responsive.min.css'/> 	
                <link href='https://funeraria.gposanmiguel.com/public/css/grid-overwrite.css'
                    rel='stylesheet' type='text/css' media='screen'>      
                <link href='https://funeraria.gposanmiguel.com/public/css/checkbox/jquery.checkboxtree.css'
                    rel='stylesheet' type='text/css' media='screen'>                		        
                <link href='https://funeraria.gposanmiguel.com/public/css/checkbox/jquery.tree.css'
                    rel='stylesheet' type='text/css' media='screen'>      
                <style>
            
                    .lineonly 
                    {
                        width: 80%;
                        text-align: right;
                        background: transparent;
                        border: none;
                        -webkit-box-shadow: none;
                        box-shadow: none;        
                    }
                </style>
                <script>        
                    var INICIO = 'https://funeraria.gposanmiguel.com/public/';        
                    var classValid = 'border-success text-primary';
                    var classInvalid = 'border-danger text-danger';
                </script>
            </head>
            <body>
        ";
        //echo $this->html;
        //$this->html = "";

    }
    public function menu()
    {        
        if(count($this->paginas) > 0)
            foreach ($this->paginas as $value) 
            {           
                if($value->ArcPath == $this->destino)
                {              
                    $this->page = true; 
                    $this->menuSuperior();
                    $this->html .="<div class='page'>";
                    $this->menuLateral();
                }
                
            }        
    }

    public function cuerpo()
    {
        $this->html .= "
        <section class='py-2'>
			<div class='container-fluid px-2'>				
				<div class='bg-light rounded-3 py-2 px-2 px-md-2 mb-5'>";
            ob_start();           
            require($this->file);
            $this->html .= ob_get_clean();
      	$this->html .="		
                </div>
			</div>
		</section>
        ";
    }

    public function pie()
    {
        if($this->page)
        {
            $this->html .= "
                <footer class='main-footer w-100 position-absolute bottom-0 start-0 py-2'
                    style='background: #222'>
                    <div class='container-fluid'>
                        <div class='row text-center gy-3'>
                            <div class='col-sm-6 text-sm-start'>
                                <p class='mb-0 text-sm text-gray-600'>GPO SANMIGEL &copy; 2022-2023</p>
                            </div>
                            <div class='col-sm-6 text-sm-end'>
                                <p class='mb-0 text-sm text-gray-600'>
                                    Design by <a href='https://bootstrapious.com/p/bootstrap-4-dashboard' class='external'>Bootstrapious</a>
                                </p>
                                <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions and it helps me to run Bootstrapious. Thank you for understanding :)-->
                            </div>
                        </div>
                    </div>
                </footer>
            </div>";
        }
        $this->html .="
        <!-- JavaScript files-->
        <script src='https://funeraria.gposanmiguel.com/public/js/bootstrap/bootstrap.bundle.min.js'></script>        
        <script src='https://funeraria.gposanmiguel.com/public/js/Dashboard/OverlayScrollbars.min.js'></script>        
        <!-- Main File-->
        <script src='https://funeraria.gposanmiguel.com/public/js/Dashboard/front.js'></script>
        <script src='https://funeraria.gposanmiguel.com/public/js/notify.js'></script>
        <script src='https://funeraria.gposanmiguel.com/public/js/funsiones.js'></script>		
        <script src='https://funeraria.gposanmiguel.com/public/js/Colorbox/jquery.colorbox.js'></script>
        <script src='https://funeraria.gposanmiguel.com/public/js/ajax.js'></script>        
        <script src='https://funeraria.gposanmiguel.com/public/js/valida.js'></script>
        <script src='https://funeraria.gposanmiguel.com/public/js/validar.js'></script>        
        <script	src='https://funeraria.gposanmiguel.com/public/css/Grid/css/themes/jquery-ui.custom.min.js'
                type='text/javascript'></script>
        <script type='text/javascript' src='https://funeraria.gposanmiguel.com/public/js/Grid/js/datepicker-es.js'></script>
         <script src='https://funeraria.gposanmiguel.com/public/js/checkbox/checkbox.js'></script>   
         <script src='https://funeraria.gposanmiguel.com/public/js/checkbox/tree.js'></script>   		        
        <script>
        // ------------------------------------------------------- //
        //   Inject SVG Sprite - 
        //   see more here 
        //   https://css-tricks.com/ajaxing-svg-sprite/
        // ------------------------------------------------------ //
        function injectSvgSprite(path) {
        
            var ajax = new XMLHttpRequest();
            ajax.open('GET', path, true);
            ajax.send();
            ajax.onload = function(e) {
            var div = document.createElement('div');
            div.className = 'd-none';
            div.innerHTML = ajax.responseText;
            document.body.insertBefore(div, document.body.childNodes[0]);
            }
        }
        
        // this is set to BootstrapTemple website as you cannot 
        // inject local SVG sprite (using only 'icons/orion-svg-sprite.svg' path)
        // while using file:// protocol
        // pls don't forget to change to your domain :)
        injectSvgSprite('https://bootstraptemple.com/files/icons/orion-svg-sprite.svg');     
        
        
        </script>
        <script>
        $.fn.extend({
            toggleText: function(a, b){
                return this.text(this.text() == b ? a : b);
            }
        });
            function loading(button,loading)
            {
                if(loading)
                {
                    // disable button
                    $(button).prop('disabled', true);
                    // add spinner to button
                    $(button).html(
                        '<i class=\"fa fa-circle-o-notch fa-spin\"></i> Trabajando...'
                    );
                }
                else
                {
                    // enable button
                    $(button).prop('disabled', false);
                    // remove spinner to button
                    $(button).html(
                        'Enviar'
                    );        	
                }
            }

            function validData(object)
            {
                $(object).removeClass(classInvalid);
                $(object).addClass(classValid);
            }
            function invalidData(object)
            {
                $(object).removeClass(classValid);
                $(object).addClass(classInvalid);			
            }			

            $('input, .noenter').keypress(function(e)
            {
                var key;
                if(window.event)
                    key = window.event.keyCode;
                else
                    key = e.which;
                return (key != 13);
            }); 

            /** shortcut.add('Shift+Enter',function() 
            {		
                $('.archivar:visible').trigger('click');		
            }); **/
            
            $('.box').colorbox({iframe:true, width:'90%', height:'90%'});
            $('#toggle-btn').trigger('click');
            
         
        </script>
        <!-- FontAwesome CSS - loading as last, so it doesn't block rendering- -->
        <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.1/css/all.css'
            integrity='sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr'
            crossorigin='anonymous'> 
        
	
    </body>
</html>";
        //echo $this->html;
    }

    public function menuSuperior()
    {        
        if($this->destino == "Login")
        {
            $usuario = "Invitado";
            $rol = "Invitado";
        }
        else
        {
            $usuario = $_SESSION['USR_NOMBRE'];
            $rol = $this->Rol->obtener($_SESSION['USR_ROL'], 'RolID')->RolNombre;
        }
        $this->html .=
            "
            <!-- Side Navbar -->
            <nav class='side-navbar'>
                <!-- Sidebar Header    -->
                <div class='sidebar-header d-flex align-items-center justify-content-center p-3 mb-3'>
                    <!-- User Info-->
                    <div class='sidenav-header-inner text-center'>				
                        <h2 class='h5 text-white text-uppercase mb-0'>$usuario</h2>
                        <p class='text-sm mb-0 text-muted'>$rol</p>
                    </div>
                    <!-- Small Brand information, appears on minimized sidebar-->
                    <span class='brand-small text-center'>
                        <p class='h5 m-0'>".StringHelper::iniciales($usuario)."</p>
                    </span>
                </div>
                
                <!-- Sidebar Navigation Menus-->
                <span class='text-uppercase text-gray-500 text-sm fw-bold letter-spacing-0 mx-lg-2 heading'>Principal</span>
                <ul class='list-unstyled'>                
                    <li class='sidebar-item'>
                        <a class='sidebar-link' href='Inicio'>
                            <svg class='svg-icon svg-icon-sm svg-icon-heavy me-xl-2'>
                                <use xlink:href='#real-estate-1'> </use>
                            </svg>Inicio
                        </a>
                    </li>";                               
                    foreach ($this->paginas as $pagina) 
                    {         
                        if($pagina->ArcModulo == $this->modulo)  
                        {            
                            $this->html .= "
                            <li class='sidebar-item'>
                                <a class='sidebar-link' href='$pagina->ArcPath'>
                                    <svg class='svg-icon svg-icon-sm svg-icon-heavy me-xl-2'>
                                    <use xlink:href='#".$pagina->ArcIcon."'> </use>
                                    </svg>$pagina->ArcNombre                                
                                </a>
                            </li>
                            ";        
                        }
                    }                                    			
            $this->html .="
                </ul>
            </nav>";     
            //echo $this->html;
            //$this->html = "";
    }

    public function menuLateral()
    {
        $this->html .= "
        <header class='header'>
			<nav class='navbar'>
				<div class='container-fluid'>
					<div class='d-flex align-items-center justify-content-between w-100'>
						<div class='d-flex align-items-center'>
							<a class='menu-btn d-flex align-items-center justify-content-center p-2 bg-gray-900'
								id='toggle-btn' href='#'> 
								<svg class='svg-icon svg-icon-sm svg-icon-heavy text-white'>
                    				<use xlink:href='#menu-1'> </use>
                  				</svg>
              				</a>
              				<a class='navbar-brand ms-2' href='/'>
								<div class='brand-text d-none d-md-inline-block text-uppercase letter-spacing-0'>
								<!-- Area para panel de contadores -->
								
								<!-- Termina area de contadores -->
								
									<strong class='text-primary text-sm'>" 
										.StringHelper::procesaPath($this->destino).
									"</strong>
								</div>
							</a>
						</div>
						<ul class='nav-menu mb-0 list-unstyled d-flex flex-md-row align-items-md-center'>";
                        $MENU = new Modulos();        
                        $modulos = [];
                        foreach ($this->paginas as $pagina)                    
                        {          
                            $modulo = $MENU->obtener($pagina->ArcModulo,"ModID");
                            if(!in_array($modulo->ModNombre, $modulos))
                            {
                                $modulos[] = $modulo->ModNombre;
                                $this->html .="
                                <li class='nav-item'>
                                    <a class='nav-link text-white text-sm ps-0'
                                        href='$modulo->ModPath'> 
                                        <span class='d-none d-sm-inline-block'>$modulo->ModNombre</span> 
                                        <svg class='svg-icon svg-icon-xs svg-icon-heavy'>
                                            <use xlink:href='#$modulo->ModIcon'> </use>
                                        </svg>
                                    </a>
                                </li>
                                ";
                            }
                            
                        }       	
						$this->html .="			
							<!-- Notifications dropdown-->
							<li class='nav-item dropdown ps-0 d-none d-sm-block'>
								<a class='nav-link text-white position-relative' id='notifications'
									rel='nofollow' data-bs-target='#' href='#'
									data-bs-toggle='dropdown' aria-haspopup='true'
									aria-expanded='false'> 
									<svg class='svg-icon svg-icon-xs svg-icon-heavy'>
                      					<use xlink:href='#chart-1'> </use>
                    				</svg>
                    				<span class='badge bg-red'>1</span>
                				</a>
								<ul class='dropdown-menu dropdown-menu-end mt-sm-3 shadow-sm'
									aria-labelledby='notifications'>
									<li>
										<a class='dropdown-item py-3'
											href='Backup'>
											<div class='d-flex'>
												<div class='icon icon-sm bg-blue'>
													<svg class='svg-icon svg-icon-xs svg-icon-heavy'>
                              							<use xlink:href='#envelope-1'> </use>
                            						</svg>
												</div>
												<div class='ms-3'>
													<span
														class='h6 d-block fw-normal mb-1 text-xs text-gray-600'>No
														olvides hacer respaldo 
													</span>
													<small class='small text-gray-600'>Hoy</small>
												</div>
											</div>
										</a>
									</li>

								</ul>
							</li>
							<!-- Messages dropdown-->

							
							<li class='nav-item ps-0 d-none d-sm-block'>
								<a class='nav-link text-white text-sm ps-0'
    								href='cambiarclave'> 
    								<span class='d-none d-sm-inline-block'>Clave</span> 
    								<svg class='svg-icon svg-icon-xs svg-icon-heavy'>
                          				<use xlink:href='#checked-window-1'> </use>
                        			</svg>
                    			</a>
                			</li>                			                			
							<!-- Log out-->
							<li class='nav-item'>
                                <a class='nav-link text-white text-sm ps-0'
								href='Salir'> <span
									class='d-none d-sm-inline-block'>Salir</span> 
									<svg class='svg-icon svg-icon-xs svg-icon-heavy'>
                      					<use xlink:href='#security-1'> </use>
                    		        </svg>
                                </a>
                            </li>
						</ul>
					</div>
				</div>
			</nav>
		</header>";
		//echo $this->html;
		//$this->html = "";
    }
   

}
