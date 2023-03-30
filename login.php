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

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("kt_login_user", true, "text", "", "", "", "");
$formValidation->addField("kt_login_password", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make a login transaction instance
$loginTransaction = new tNG_login($conn_booking);
$tNGs->addTransaction($loginTransaction);
// Register triggers
$loginTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "kt_login1");
$loginTransaction->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$loginTransaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "{kt_login_redirect}");
// Add columns
$loginTransaction->addColumn("kt_login_user", "STRING_TYPE", "POST", "kt_login_user");
$loginTransaction->addColumn("kt_login_password", "STRING_TYPE", "POST", "kt_login_password");
// End of login transaction instance

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
        <title>Corporación Kimirina .:. Sistema de Agendamiento Online</title>
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

    </head>

    <!-- body start -->
    <body class="authentication-bg">
        
        <?php
		echo $tNGs->getLoginMsg();
		?>
        
        <!-- Begin page -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="account-pages mt-4 mb-4">
                <div class="content">                    
                    <!-- Start Content-->
                    <div class="container-fluid">                        
                        <div class="row justify-content-center">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body p-4">
                                        <div class="text-center mb-4">
                                            <span>
                                                <img class="img-fluid" src="assets/images/logo.png" alt="" width="50%">
                                            </span>
                                        </div>
                                        <div class="text-center mb-4">
                                            <h4 class="text-uppercase mt-0">AGENDAMIENTO DE CITAS</h4>
                                        </div>
                                        <form method="post" id="form1" class="KT_tngformerror" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                                            <div class="form-group mb-3">
                                                <label for="kt_login_user">Documento de Identificación:</label>
                                                <input type="text" class="form-control" placeholder="Ingrese su Usuario" required="" name="kt_login_user" autocomplete="off" id="kt_login_user" value="<?php echo KT_escapeAttribute($row_rscustom['kt_login_user']); ?>">
                                                <?php echo $tNGs->displayFieldHint("kt_login_user");?> <?php echo $tNGs->displayFieldError("custom", "kt_login_user"); ?> 
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="kt_login_password">Contraseña:</label>
                                                <input type="password" class="form-control" placeholder="Ingrese su Contraseña" required="" name="kt_login_password" id="kt_login_password">
                                                <?php echo $tNGs->displayFieldHint("kt_login_password");?> <?php echo $tNGs->displayFieldError("custom", "kt_login_password"); ?>
                                            </div>
                                            <div class="form-group mb-0 text-center">
                                                <button class="btn btn-primary btn-block" type="submit" name="kt_login1" id="kt_login1"> Ingresar </button>
                                            </div>
                                        </form>
                                    </div> <!-- end card-body -->
                                </div>
                                <!-- end card -->
                                <div class="row mt-3">
                                    <div class="col-12 text-center">
                                        <p> <a href="pages-recoverpw.html" class="text-muted ml-1"><i class="fa fa-lock mr-1"></i> Olvidó su contraseña?</a></p>
                                        <p class="text-muted">No tiene una cuenta? <a href="" class="text-dark ml-1" data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg"><b>Registrarse</b></a></p>
                                    </div> <!-- end col -->
                                </div>
                                <!-- end row -->
                            </div> <!-- end col -->
                        </div>
                        
                        <div class="modal fade" id="bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <? include('register.php'); ?>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div>
                        
                    </div> <!-- container -->

                </div> <!-- content -->

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        <!-- Vendor -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="assets/libs/jquery.counterup/jquery.counterup.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        
    </body>
</html>