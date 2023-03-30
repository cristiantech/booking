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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = $_POST['id'];
    
    if ((isset($_POST["MM_insert_$id"])) && ($_POST["MM_insert_$id"] == "form$id")) {
    
        $insertSQL = sprintf("INSERT INTO reservas (username, servicio, localidad, costo) VALUES (%s, %s, %s, %s)",
                             GetSQLValueString($_SESSION['kt_login_user'], "text"),
                             GetSQLValueString($id, "int"),
                             GetSQLValueString($_POST["ciudad$id"], "int"),
                             GetSQLValueString($_POST["costo$id"], "int"));
    
        mysql_select_db($database_booking, $booking);
        $Result1 = mysql_query($insertSQL, $booking) or die(mysql_error());
    
        //$insertGoTo = 'list.php';
        
        $insertGoTo = "../js/index3.php?servicio=$id";    
        
        if (isset($_SERVER['QUERY_STRING'])) {
            //$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
            //$insertGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $insertGoTo));
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

$sexo = $row_login['sexo'];

//Listado de Servicios
mysql_select_db($database_booking, $booking);
$query_servicios = "SELECT * FROM servicios WHERE activo = '1' ORDER BY nombre";
$servicios = mysql_query($query_servicios, $booking) or die(mysql_error());
$row_servicios = mysql_fetch_assoc($servicios);
$totalRows_servicios = mysql_num_rows($servicios);

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
        <title>Corporación Kimirina .:. Sistema de Agendamiento</title>
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
                            <? do { ?> 
                            <div class="col-md-6 col-xl-3">
                                <!-- Simple card -->
                                <form action="" id="form<? echo $row_servicios['id']; ?>" name="form" data-parsley-validate novalidate method="post">
                                    <div class="card">
                                        <img class="card-img-top img-fluid" src="../assets/images/gallery/1.jpg" alt="Card image cap">
                                        <div class="card-body">
                                            <h4 class="card-title"><? echo $row_servicios['nombre']; ?></h4>
                                            <h5 class="text-danger"><? echo $row_servicios['tipo']; ?></h5>
                                            <p class="card-text mb-1"><? echo $row_servicios['descripcion']; ?></p>
                                            <label for="costo<? echo $row_servicios['id']; ?>" class="form-label mt-2">Costo:</label>
                                            <output id="costo<? echo $row_servicios['id']; ?>" class="form-control"><? echo $row_servicios['costo']; ?></output>
                                            <? if ($row_servicios['tipo']=='Presencial') { ?>
                                            <label for="ciudad<? echo $row_servicios['id']; ?>" class="form-label mt-2">Ciudad de Atención:</label>
                                            <? 
                                                mysql_select_db($database_booking, $booking);
                                                $query_offered = sprintf("SELECT localidades.id AS 'idCiudad', localidades.nombre AS 'ciudadNombre' FROM localidades INNER JOIN offered_services ON offered_services.id_localidades = localidades.id INNER JOIN servicios ON servicios.id =  offered_services.id_servicios WHERE servicios.id = %s AND offered_services.activo = 1", GetSQLValueString($row_servicios['id'], "int"));
                                                $offered = mysql_query($query_offered, $booking) or die(mysql_error());
                                                $row_offered = mysql_fetch_assoc($offered);
                                                $totalRows_offered = mysql_num_rows($offered);
                                            ?>
                                            <select name="ciudad<? echo $row_servicios['id']; ?>" id="ciudad<? echo $row_servicios['id']; ?>" class="form-select" required>
                                                <option value="" selected>Seleccione Ciudad:</option>
                                                <?php
                                                    do {  
                                                ?>
                                                <option value="<?php echo $row_offered['idCiudad']?>"><?php echo $row_offered['ciudadNombre']?></option>
                                                <?php
                                                    } while ($row_offered = mysql_fetch_assoc($offered));
                                                    $rows = mysql_num_rows($offered);
                                                    if($rows > 0) {
                                                        mysql_data_seek($offered, 0);
                                                        $row_offered = mysql_fetch_assoc($offered);
                                                    }
                                                ?>
                                            </select>
                                            <? } ?>
                                            <input type="hidden" name="id" value="<? echo $row_servicios['id'];  ?>">
                                            <input type="hidden" name="costo<? echo $row_servicios['id']; ?>" value="<? echo $row_servicios['costo']; ?>">
                                            <input type="hidden" name="MM_insert_<? echo $row_servicios['id']; ?>" value="form<? echo $row_servicios['id']; ?>">
                                            <button type="submit" class="btn btn-primary mt-2">Reservar</button>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- end col -->   
                            <? } while ($row_servicios = mysql_fetch_assoc($servicios)); ?>
                        </div>                  
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
        
        <script src="../assets/libs/parsleyjs/parsley.min.js"></script>

        <!-- App js -->
        <script src="../assets/js/app.min.js"></script>
        
    </body>
</html>