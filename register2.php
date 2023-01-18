<?php require_once('Connections/survey.php'); ?>
<?php require_once('Connections/booking.php'); ?>
<?php
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_booking = new KT_connection($booking, $database_booking);

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
    
    $insertSQL = sprintf("INSERT INTO users (fullname, username, birthday, genero, provincia, canton, telefono, email, password, level) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						 GetSQLValueString($_POST['fullname'], "text"),
						 GetSQLValueString($_POST['docId'], "text"),
						 GetSQLValueString($_POST['birthday'], "date"),
						 GetSQLValueString($_POST['genero'], "int"),
						 GetSQLValueString($_POST['provincia'], "int"),
						 GetSQLValueString($_POST['canton'], "int"),
						 GetSQLValueString($_POST['telefono'], "text"),
						 GetSQLValueString($_POST['correo'], "text"),
						 GetSQLValueString(md5($_POST['password']), "text"),
						 GetSQLValueString(1, "int"));
        
    mysql_select_db($database_booking, $booking);
    $Result1 = mysql_query($insertSQL, $booking) or die(mysql_error());
    
    if (isset($_SERVER['QUERY_STRING'])) {
        //$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        //$insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_booking, $booking);
$query_provincias = "SELECT provincias.* FROM provincias";
$provincias = mysql_query($query_provincias, $booking) or die(mysql_error());
$row_provincias = mysql_fetch_assoc($provincias);
$totalRows_provincias = mysql_num_rows($provincias);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Corporación Kimirina .:. Sistema de Agendamiento Online - Registro de Usuario</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

		<!-- App css -->
		<link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

		<!-- icons -->
		<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        
        <script src="assets/js/jquery-1.9.1.min.js"></script>
        <script src="assets/libs/moment/moment.js"> </script>
        
        <script>		
			function cargarCantones(){
                var prov = document.getElementById('provincia').value
                document.getElementById('codigoHoja').value = prov;	
            }
		</script>

    </head>

    <body class="authentication-bg">
        
        <script>
            function buscar() {
			var textoBusqueda = $("input#docId").val();
		
		 	if (textoBusqueda != "" && textoBusqueda.length > 6) {
				$.post("buscar.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
					mensaje = mensaje.split(",");
                    document.getElementById('fullname').value=(mensaje[0]) + ' ' + (mensaje[1]) + ' ' + (mensaje[2]) + ' ' + (mensaje[3]);
                    document.getElementById('correo').value=(mensaje[4]);
                    document.getElementById('fono').value=(mensaje[5]);					
				}); 
			} else { 
				document.getElementById('tipoIntervencion').value=("");
			};
		};
        </script>
        
        <script type="text/javascript">
			$(document).ready(function(){
				$('#provincia').val('');
				recargarLista();

				$('#provincia').change(function(){
					recargarLista();
				});
			})
		</script>
		
		<script>
			function recargarLista(){
				$.ajax({
					type:"POST",
					url:"canton.php",
					data:"id_provincia=" + $('#provincia').val(),
					success:function(r){
						$('#canton').html(r);
					}
				});
			}
		</script>
        
        <script type="text/javascript">
            $(document).ready(function() {
                $("form").keypress(function(e) {
                    if (e.which == 13) {
                        return false;
                    }
                });
            });
        </script>
        
        <div class="account-pages">
            <div class="content">
                <?php
                echo $tNGs->getErrorMsg();
                ?>
                <form action="<?php echo $editFormAction; ?>" id="form1" name="form1" data-parsley-validate novalidate method="post">
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center" style="margin-top: -20px">
                                            <img src="assets/images/logo.png" alt="" class="img-fluid" width="25%">
                                        </div>
                                        <div class="text-center mt-3 mb-4">
                                            <h4 class="text-uppercase mt-0">SISTEMA DE AGENDAMIENTO ONLINE</h4>
                                            <h4 class="text-uppercase mt-0">Registro de Usuario</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="tipoDocId" class="form-label">Tipo de Identificación:</label>
                                                        <select name="tipoDocId" id="tipoDocId" class="form-select" required>
                                                                <option value="1" selected>Cédula</option>
                                                            <option value="2">Pasaporte</option>
                                                        </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="fullname" class="form-label">Cédula:</label>
                                                    <input class="form-control" type="text" id="docId" name="docId" placeholder="Ingrese su # de Identificación" onkeyup="buscar()" autocomplete="off" required>
                                                </div>
                                            </div>                                            
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="fullname" class="form-label">Nombres Completos:</label>
                                                    <input class="form-control" type="text" id="fullname" name="fullname" placeholder="Ingrese su nombre completo" required autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento:</label>
                                                    <input type="date" id="birthday" name="birthday" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="provincia" class="form-label">Provincia de Residencia:</label>
                                                    <select class="form-select" id="provincia" name="provincia" required>
                                                        <option value="">Escoja:</option>
                                                        <?php
                                                        do {  
                                                        ?>
                                                        <option value="<?php echo $row_provincias['id']?>"><?php echo $row_provincias['nombre']?></option>
                                                        <?php
                                                        } while ($row_provincias = mysql_fetch_assoc($provincias));
                                                        $rows = mysql_num_rows($provincias);
                                                        if($rows > 0) {
                                                            mysql_data_seek($provincias, 0);
                                                            $row_provincias = mysql_fetch_assoc($provincias);
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="canton" class="form-label">Cantón de Residencia:</label>
                                                    <select class="form-select" id="canton" name="canton" required></select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="genero" class="form-label">Género:</label>
                                                    <select name="genero" id="genero" class="form-select" required>
                                                        <option value="">Seleccionar Género</option>
                                                        <option value="1">Masculino</option>
                                                        <option value="2">Femenino</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="sexoNacimiento" class="form-label">Sexo al Nacimiento</label>
                                                    <select name="sexoNacimiento" id="sexoNacimiento" class="form-select" required></select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="emailaddress" class="form-label">Teléfono:</label>
                                                    <input class="form-control" type="tel" id="telefono" name="telefono" required placeholder="Ingrese su número de teléfono" autocomplete="off">
                                                </div>                                             
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="emailaddress" class="form-label">Correo Electrónico:</label>
                                                    <input class="form-control" type="email" id="correo" name="correo" required placeholder="Ingrese su correo electrónico" autocomplete="off">
                                                </div>
                                            </div>                                            
                                        </div>
                                                                                        
                                        <div class="row">
                                            <div class="col-lg-6">                    
                                                
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Contraseña:</label>
                                                    <div class="input-group input-group-merge">
                                                        <input type="password" id="password" name="password" class="form-control" placeholder="Ingrese su Clave" required autocomplete="off">
                                                        <div class="input-group-text" data-password="false">
                                                            <span class="password-eye"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- end col -->                                            
                                        </div><!-- end row-->
                                        
                                        <div class="row justify-content-center">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="checkbox-signup" required>
                                                        <label class="form-check-label" for="checkbox-signup">Yo acepto los <a href="javascript: void(0);" class="text-dark">Términos y Condiciones</a></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="float-end">
                                            <input type="hidden" name="MM_insert" value="form">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light mr-1" name="KT_Insert1" id="KT_Insert1"> Registrarse </button>
                                        </div>
                                    </div> <!-- end card-body -->
                                </div> <!-- end card -->
                                <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <p class="text-muted">Ya está registrado?  <a href="index.php" class="text-dark ms-1"><b>Ingresar</b></a></p>
                                </div> <!-- end col -->
                            </div>
                            </div><!-- end col -->
                        </div>
                    </div>
                </form>
            </div>        
        </div>        
        <!-- end page -->

        <!-- Vendor -->
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>
        
        <script src="assets/libs/parsleyjs/parsley.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        
    </body>
</html>