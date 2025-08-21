<?php
require_once("class/class.php");
if (isset($_SESSION['acceso'])) {

$tra = new Login();

if(isset($_POST["proceso"]) and $_POST["proceso"]=="login")
{
    $log = $tra->Logueo();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title></title>
    <link rel="icon" type="image/png" href="assets/img/favicon.png"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/authentication/form-1.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="assets/css/forms/switches.css">

</head>
<body onLoad="muestraReloj()" class="form">
    

    <div class="form-container">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">

                        <div class="d-flex user-meta">
                        <?php 
                        if($_SESSION['acceso']=="cliente"){

                            if (isset($_SESSION['codigo'])) {
                                if (file_exists("fotos/clientes/".$_SESSION['codigo'].".jpg")){
                                    echo "<img src='fotos/clientes/".$_SESSION['codigo'].".jpg?' class='usr-profile'>"; 
                                 } else {
                                    echo "<img src='fotos/clientes/avatar.png' class='usr-profile'>"; 
                                } } else {
                                    echo "<img src='fotos/clientes/avatar.png' class='usr-profile'>"; 
                            }
                        } else {
                            if (isset($_SESSION['codigo'])) {
                                if (file_exists("fotos/usuarios/".$_SESSION['codigo'].".jpg")){
                                    echo "<img src='fotos/usuarios/".$_SESSION['codigo'].".jpg?' class='usr-profile'>"; 
                                } else {
                                    echo "<img src='fotos/usuarios/avatar.png' class='usr-profile'>"; 
                                } } else {
                                    echo "<img src='fotos/usuarios/avatar.png' class='usr-profile'>"; 
                            } 
                        } ?>
                            <div class="">
                                <h5 class="text-center text-dark alert-link"><?php echo $_SESSION['nombres'] ?></h5>
                            </div>
                        </div>

                        <form class="form form-material new-lg-form text-left" name="lockscreen" id="lockscreen" action="">

                            <div id="login">
                            <!-- error will be shown here ! -->
                            </div>

                            <div class="form">

                                <div id="password-field" class="field-wrapper input mb-2">
                                    <i data-feather="lock"></i>
                                    <input type="hidden" name="proceso" id="proceso" value="login"/>
                                    <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario'] ?>">
                                    <input class="form-control" type="password" placeholder="Ingrese su Password" name="password" id="password" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" required="" aria-required="true">
                                    <a href="logout" class="forgot-pass-link text-left">¡Acceder en Login!</a>
                                </div>
                                <div class="d-sm-flex justify-content-between">
                                    <div class="field-wrapper toggle-pass">
                                        <p class="d-inline-block">Mostrar Password</p>
                                        <label class="switch s-primary">
                                            <input type="checkbox" id="toggle-password" class="d-none">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div><hr>

                                <div class="form-group text-center m-t-20">
                                    <div class="col-xs-12">
                                       <button type="submit" name="btn-login" id="btn-login" class="btn btn-primary btn-block waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="" data-original-title="Haga clic aquí para iniciar sesión"><i data-feather="log-in"></i> Acceder</button>
                                    </div>
                                </div>

                            </div>
                        </form>                        
                        <p class="terms-conditions"> <i data-feather="copyright"></i> <span class="current-year"></span>. <br><a href="javascript:void(0);" class="text-primary"><span class="current-detalle"></span></a>.</p>

                    </div>                    
                </div>
            </div>
        </div>
        <div class="form-image">
            <div class="l-image" style="background:url(fotos/login.jpg); no-repeat center; position: absolute; height: 100%; width: 100%; background-size: 100% 100%;">
            </div>
        </div>
    </div>

    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    
    <script src="plugins/font-icons/feather/feather.min.js"></script>
    <script type="text/javascript">
        feather.replace();
    </script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- script jquery -->
    <script src="assets/script/jquery.min.js"></script> 
    <script type="text/javascript" src="assets/script/titulos.js"></script>
    <script type="text/javascript" src="assets/script/validation.min.js"></script>
    <script type="text/javascript" src="assets/script/script.js"></script>
    <!-- script jquery -->

    <!-- jQuery Noty-->
    <script src="plugins/noty/packaged/jquery.noty.packaged.min.js"></script>
    <!-- jQuery Noty-->

    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/authentication/form-1.js"></script>
    <!-- END GLOBAL MANDATORY STYLES -->

</body>
</html>

<?php } else { ?>
        <script type='text/javascript' language='javascript'>
        alert('NO TIENES PERMISO PARA ACCEDER AL SISTEMA.\nDEBERA DE INICIAR SESION')  
        document.location.href='logout'  
        </script> 
<?php } ?>