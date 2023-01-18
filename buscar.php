<?php require_once('Connections/survey.php'); ?>
<?php

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

//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];

$mensaje = "";

if (isset($consultaBusqueda)) {

mysql_select_db($database_survey, $survey);
$query_busqueda = sprintf("SELECT 747338X4X183 as 'nombres', 747338X4X187 as 'cedula', 747338X4X184 as 'email', 747338X4X185 as 'fono' FROM lime_survey_747338 WHERE submitdate IS NOT NULL and 747338X4X187 = %s", GetSQLValueString($consultaBusqueda, "text"));
$busqueda = mysql_query($query_busqueda, $survey) or die(mysql_error());
$row_busqueda = mysql_fetch_assoc($busqueda);
$totalRows_busqueda = mysql_num_rows($busqueda);
    
$datospersonales = $row_busqueda['nombres'];
$datospersonales = explode(" ", $datospersonales);

$nombre1 = $datospersonales[0];
$nombre2 = $datospersonales[1];
        
$apellido1 = $datospersonales[2];
$apellido2 = $datospersonales[3];
    
$correo = $row_busqueda['email'];
$fono = $row_busqueda['fono'];

$mensaje=$nombre1.','.$nombre2.','.$apellido1.','.$apellido2.','.$correo.','.$fono;

echo $mensaje;

}
?>