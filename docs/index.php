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

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
        
        //archivo 1
		if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK){
			// get details of the uploaded file
			$fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
			$fileName = $_FILES['uploadedFile']['name'];
			$fileSize = $_FILES['uploadedFile']['size'];
			$fileType = $_FILES['uploadedFile']['type'];
			$fileNameCmps = explode(".", $fileName);
			$fileExtension = strtolower(end($fileNameCmps));
            
            $fecha = date("Y-m-d");
			
			// sanitize file-name
			$newFileName = $_POST['documento'] . _ . $fecha .  '.' . $fileExtension;

			// check if file has one of the following extensions
			$allowedfileExtensions = array('pdf', 'png', 'jpg');

			if (in_array($fileExtension, $allowedfileExtensions))
			{
			  // directory in which the uploaded file will be moved
			  $uploadFileDir = 'ids/';
			  $dest_path = $uploadFileDir . $newFileName;

			  if(move_uploaded_file($fileTmpPath, $dest_path)) 
			  {
				$message ='Archivo subido correctamente.';

				/*$updateSQL=sprintf("UPDATE orden_laboratorio SET resultados='1', resultados_fecha=CURRENT_TIMESTAMP() WHERE id_cita LIKE %s", GetSQLValueString($_POST['id_cita'], "int"));
				mysql_select_db($database_limesurvey, $limesurvey);
				$Result1 = mysql_query($updateSQL, $limesurvey) or die(mysql_error());    */

			  }
			  else 
			  {
				$message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
			  }
			}
			else
			{
			  $message = 'Fallo al subir. Tipos de archivo permitidos: ' . implode(',', $allowedfileExtensions);
			}
		  }
		  else
		  {
			$message = 'There is some error in the file upload. Please check the following error.<br>';
			$message .= 'Error:' . $_FILES['uploadedFile']['error'];
		  }
		
		$updateSQL = sprintf("UPDATE users SET documento_file = %s WHERE username = %s",
					GetSQLValueString($newFileName, "text"),
					GetSQLValueString($_SESSION['kt_login_user'], "text"));
		
		mysql_select_db($database_booking, $booking);
		$Result1 = mysql_query($updateSQL, $booking) or die(mysql_error());
		

  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
        
        <!-- Sweet Alert-->
        <link href="../assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        
        <script src="../assets/libs/jquery/jquery.min.js"></script>
        <script src="../assets/libs/moment/moment.js"> </script>

    </head>

    <!-- body start -->
    <body class="loading" data-layout-color="light" data-layout-mode="default" data-layout-size="fluid" data-topbar-color="light" data-leftbar-position="fixed" data-leftbar-color="light" data-leftbar-size='default' data-sidebar-user='true'>
        
        <script>
            $(document).ready(function() {
                <? if ($row_login['consentimiento']!=1){ ?>
                setTimeout(function(){
                    myalert();
                }, 2000);
                <? } ?>
			});        
        </script>
        
        <script>
            function myalert(){
                Swal.fire('Lea el consentimiento informado y acéptelo para poder empezar a utilizar nuestro servicio')    
            }            
        </script>
        
        <script>
            function aceptar(){
                //location.href = "accept.php?username=<? echo base64_encode($row_login['username']); ?>&consentimiento=1&token="+value;
                //location.href = "accept.php?username=<? echo base64_encode($row_login['username']); ?>";
                avisoAceptacion();
            }
            
            const avisoAceptacion = () => {
                Swal.fire({
                    title: "Al aceptar el consentimiento informado, su dirección IP será registrada",
                    //text: description,
                    icon: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#28bb4b",
                    cancelButtonColor: "#f34e4e",
                    confirmButtonText: "Acepto",
                    cancelButtonText: "Volver"
                }).then((e) => {
                    if (e.value){
                        location.href = "pdf/makepdf.php?username=<? echo base64_encode($row_login['username']); ?>";
                    }
                })
            }
            
            const avisoCancelacion = () => {
                Swal.fire({
                    title: "Si no acepta el consentimiento informado, no podrá acceder al servicio",
                    //text: description,
                    icon: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#28bb4b",
                    cancelButtonColor: "#f34e4e",
                    confirmButtonText: "No Acepto",
                    cancelButtonText: "Volver"
                }).then((e) => {
                    if (e.value){
                        location.href = "<?php echo $logoutTransaction->getLogoutLink(); ?>";
                    }
                })
            }
            
            function cancelar(){
                avisoCancelacion();                
            }
            
            function reservar(){
                location.href = "../booking/index.php";
            }
        </script>

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
                        <? if ($row_login['consentimiento']!=1){ ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <iframe src="pdf/showpdf.php" style="width:100%; height:700px;" frameborder="0" ></iframe>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-6 text-center">
                                <button class="btn btn-primary float-end" onClick="aceptar()">Aceptar</button>
                            </div>
                            <div class="col-sm-6 text-center">
                                <button class="btn btn-danger float-start" onClick="cancelar()">Cancelar</button>
                            </div>
                        </div>
                        <!-- end row -->
                        <? } else { ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">Paciente:</th>
                                                        <td><? echo $row_login['fullname']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Consentimiento Informado:</th>
                                                        <td><a href="download.php?file=<? echo $row_login[consentimiento_file]; ?>" target="_blank">Descargar</a></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Documento de Identificación:</th>
                                                        <td><? if ($row_login['documento_file']==NULL){ ?><input type="file" name="uploadedFile"><? } else { ?><a href=download2.php?file=<? echo $row_login[documento_file]; ?>>Descargar</a><? } ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="text-center">
                                                <input type="hidden" name="documento" value="<? echo $row_login['username']; ?>">
                                                <? if ($row_login['documento_file']==NULL){ ?><input type="hidden" name="MM_insert" value="form"><button class="btn btn-primary">Enviar</button><? } ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <? if ($row_login['documento_file']!=NULL){ ?>
                                <div class="text-center">
                                    <button class="btn btn-secondary" onClick="reservar()">Reservar</button>
                                </div>
                                <? } ?>
                            </div>
                        </div>
                        <? } ?>
                        
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
        
        <!-- Sweet Alerts js -->
        <script src="../assets/libs/sweetalert2/sweetalert2.all.min.js"></script>

        <!-- App js -->
        <script src="../assets/js/app.min.js"></script>
        
    </body>
</html>