<? require_once('../Connections/booking.php'); ?>
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

//Lista de Servicios
mysql_select_db($database_booking, $booking);
$query_servicios = sprintf("SELECT * FROM servicios WHERE activo = '1' AND id = %s ORDER BY nombre", GetSQLValueString($_GET['servicio'], "int"));
$servicios = mysql_query($query_servicios, $booking) or die(mysql_error());
$row_servicios = mysql_fetch_assoc($servicios);
$totalRows_servicios = mysql_num_rows($servicios);

include('JsonRpcClient.php');

$loginClient = new JsonRpcClient('https://user-api.simplybook.me' . '/login/');
$token = $loginClient->getToken('corporacionkimirina', 'ac68cd96a5c843f97c7ef60117f1648847924c7307cf5a73f68ead7a85c80dde');

$client = new JsonRpcClient( 'https://user-api.simplybook.me' . '/', array(
    'headers' => array(
        'X-Company-Login: ' .  'corporacionkimirina',
        'X-Token: ' . $token
    )
));

/* Extraccion del id del Servicio */
$services = $client->getEventList('','', '', $row_servicios['nombre']);

foreach($services as $service => $idServicio){
    $idServicio->name;
    //echo "<br>";
}

/* Extraccion del Nombre del Medico segun el id del Servicio */
$doctores = $client->getUnitList();

foreach($doctores as $doctor => $idDoctor){
    $servicesDr = $idDoctor->services;
    foreach($servicesDr as $serviceDr){
        if ($serviceDr == $idServicio->id){
            echo $idDoctor->name;
            echo '<br>';
        } 
    }
}

/* Horarios Disponbiles del Medico */
function horariosDr($id){
    //
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
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>
</body>
</html>