<?php
require_once("class/class.php");

$tra = new Login();

if(isset($_POST["proceso"]) and $_POST["proceso"]=="recuperar")
{
  $reg = $tra->RecuperarPassword();
  exit;
}
?><!DOCTYPE html>
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
<body class="form">
    

    <div class="form-container">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">

                        <h3 class="">Recuperación de Password</h3>
                        <p class="signup-link">Ingrese su correo electrónico y se le enviarán las instrucciones.!</p>
                        <form class="form form-material text-left" name="formrecover" id="formrecover" action="">

                        <div id="recover">
                        <!-- error will be shown here ! -->
                        </div>

                        <div class="form">

                            <div id="email-field" class="field-wrapper input">
                                <i data-feather="at-sign"></i>
                                <input type="hidden" name="proceso" value="recuperar"/>
                                <input type="text" class="form-control" name="email" id="email" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese su Correo Electronico" autocomplete="off" value="" required="" aria-required="true"/>
                                <a href="index" class="forgot-pass-link text-primary text-left">¡Acceder en Login!</a>
                            </div><br>

                                <div class="form-group text-center m-t-20">
                                    <div class="col-xs-12">
                                        <button type="submit" name="btn-recuperar" id="btn-recuperar" class="btn btn-primary btn-lg btn-block waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="" data-original-title="Haga clic aquí para iniciar sesión"><i data-feather="mail"></i> Recuperar Password</button>
                                    </div>
                                </div>

                            </div>
                        </form>                        
                        <p class="terms-conditions"> <i data-feather="copyright"></i> <span class="current-year"></span>. <br><a href="javascript:void(0);" class="text-primary"><span class="current-detalle"></span></a></p>

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
    <script src="plugins/noty/themes/relax.js"></script>
    <!-- jQuery Noty-->
    
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/authentication/form-1.js"></script>
    <!-- END GLOBAL MANDATORY STYLES -->

</body>
</html>