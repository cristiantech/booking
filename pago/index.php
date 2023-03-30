<?php require_once('../Connections/booking.php'); ?>
<? include_once ($_SERVER['DOCUMENT_ROOT'].'/dirs.php'); ?>
<?
// Load the common classes
require_once('../includes/common/KT_common.php');

// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_booking = new KT_connection($booking, $database_booking);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_booking, "../");
//Grand Levels: Level
$restrict->addLevel("1");
$restrict->addLevel("2");
$restrict->addLevel("3");
$restrict->addLevel("4");
$restrict->addLevel("5");
$restrict->Execute();
//End Restrict Access To Page

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

function obtenerEdad($fecha_nacimiento){
        $nacimiento = new DateTime($fecha_nacimiento);
        $ahora = new DateTime(date("Y-m-d"));
        $diferencia = $ahora->diff($nacimiento);
        return $diferencia->y;
}

$colname_login = "-1";
if (isset($_SESSION['kt_login_user'])) {
  $colname_login = $_SESSION['kt_login_user'];
}
mysql_select_db($database_booking, $booking);
$query_login = sprintf("SELECT * FROM users WHERE username = %s", GetSQLValueString($colname_login, "text"));
$login = mysql_query($query_login, $booking) or die(mysql_error());
$row_login = mysql_fetch_assoc($login);
$totalRows_login = mysql_num_rows($login);

mysql_select_db($database_booking, $booking);
$query_pendientes = sprintf("SELECT reservas.*, servicios.nombre as 'nombre', servicios.tipo as 'tipo' FROM reservas INNER JOIN servicios ON servicios.id = reservas.servicio WHERE reservas.username = %s", GetSQLValueString($colname_login, "text"));
$pendientes = mysql_query($query_pendientes, $booking) or die(mysql_error());
$row_pendientes = mysql_fetch_assoc($pendientes);
$totalRows_pendientes = mysql_num_rows($pendientes);

// Make a logout transaction instance
$logoutTransaction = new tNG_logoutTransaction($conn_booking);
$tNGs->addTransaction($logoutTransaction);
// Register triggers
$logoutTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "GET", "KT_logout_now");
$logoutTransaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "../login.php");
// Add columns
// End of logout transaction instance

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Starter | Adminto - Responsive Admin Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

		<!-- App css -->

		<link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

		<!-- icons -->
		<link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    </head>

    <!-- body start -->
    <body class="loading" data-layout-color="light" data-layout-mode="default" data-layout-size="fluid" data-topbar-color="light" data-leftbar-position="fixed" data-leftbar-color="light" data-leftbar-size='default' data-sidebar-user='true'>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <? include (MENU."/top.php"); ?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <? include (MENU."/left.php"); ?>
            <!-- Left Sidebar End -->
            
            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">
                    <!-- Start Content-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 col-xl-3">
                                <!-- Simple card -->
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Servicio de <? echo $row_pendientes['nombre'] ?></h4>
                                        <h5 class="text-danger"><? echo $row_pendientes['tipo']; ?></h5>
                                        <p class="card-text">Para poder seleccionar la fecha y hora de la cita, es preciso que realice el pago previamente</p>
                                        <label for="">Comprobante de Transferencia:</label>
                                        <input type="file" class="form-control">
                                        <br>
                                        <a href="#" class="btn btn-primary">Subir Pago</a>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <!-- end row -->                      
                    </div> <!-- container -->
                </div> <!-- content -->
            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->
            
            <? include (MENU."/footer.php"); ?>

        </div>
        <!-- END wrapper -->

        <!-- Vendor -->
        <script src="../assets/libs/jquery/jquery.min.js"></script>
        <script src="../assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/libs/simplebar/simplebar.min.js"></script>
        <script src="../assets/libs/node-waves/waves.min.js"></script>
        <script src="../assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="../assets/libs/jquery.counterup/jquery.counterup.min.js"></script>
        <script src="../assets/libs/feather-icons/feather.min.js"></script>

        <!-- App js -->
        <script src="../assets/js/app.min.js"></script>
        
    </body>
</html>