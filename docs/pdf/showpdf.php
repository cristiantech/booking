<?php require_once('../../Connections/booking.php'); ?>
<?
// Load the common classes
require_once('../../includes/common/KT_common.php');

// Load the tNG classes
require_once('../../includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("../");

// Make unified connection variable
$conn_booking = new KT_connection($booking, $database_booking);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_booking, "../../");
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

/* Creando PDF */
//require('class/fpdf.php');

require('class/code128.php');

class PDF extends FPDF
{
//Columna actual
var $col=0;
//Ordenada de comienzo de la columna
var $y0;

var $B;
var $I;
var $U;
var $HREF;

//Cargar los datos
function LoadData($file)
{
    //Leer las líneas del fichero
    $lines=file($file);
    $data=array();
    foreach($lines as $line)
        $data[]=explode(';',chop($line));
    return $data;
}

//Tabla simple
function BasicTable($header,$data)
{
	$this->SetFont('Arial','',7);
	$this->Cell(4);
	$this->SetFillColor(255,0,0);
    //Cabecera
    foreach($header as $col)
        $this->Cell(40,7,$col,1,0,C,1);
    $this->Ln();
    //Datos
    foreach($data as $row)
    {
		$this->Cell(4);
        foreach($row as $col)
            $this->Cell(40,6,$col,1,0,C);
        $this->Ln();
    }
}

function BasicTable2($header,$data)
{
	$this->SetFont('Arial','',7);
	$this->Cell(5);
    //Cabecera
    foreach($header as $col)
        $this->Cell(60,7,$col,1,0,C);
    $this->Ln();
    //Datos
    foreach($data as $row)
    {
		$this->Cell(5);
        foreach($row as $col)
            $this->Cell(60,6,$col,1,0,C);
        $this->Ln();
    }
}

	function BasicTable4($header, $data, $x = 0, $y = 0) {

		$this->SetXY($x , $y);
		$this->Cell(5);
		$this->SetFont('Arial','',7);
		$this->SetFillColor(255,0,0);
		// Header
		foreach($header as $col)
			$this->Cell(35 ,7,$col,1);
		$this->Ln();
		
		// Data
		$i = 7;
		$this->SetXY($x , $y + $i);
		foreach($data as $row){
			foreach($row as $col){
				//$this->SetXY($x , $y + $i);
				$this->Cell(5);
				$this->Cell(35 ,6,$col,1);
				
			}
			$i= $i + 6 ;  // incremento el valor de la columna
			$this->SetXY($x , $y + $i);		
		  //$this->Ln();
		}
	}
	
/* Copiado a class/code128.php
function Header()
{
    //Cabacera
    global $title;
	
    $this->SetFont('Arial','B',11);
	$this->Image('img/logo.jpg',10,8,25,0,JPG,'../index.html');
    $this->Ln(4);
	$w=$this->GetStringWidth($title);
    $this->SetX((210-$w)/2);
    //$this->SetDrawColor(0,80,180);
    //$this->SetFillColor(230,230,0);
    $this->SetTextColor(220,50,50);
    //$this->SetLineWidth(1);
	$this->Cell($w,9,$title,0,1,'C',false);
    $this->Ln(1);
    //Guardar ordenada
    $this->y0=$this->GetY();
}
*/
function Footer()
{
    //Pie de página
    $this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->SetTextColor(128);
    $this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
}

function SetCol($col)
{
    //Establecer la posición de una columna dada
    $this->col=$col;
    $x=10+$col*65;
    $this->SetLeftMargin($x);
    $this->SetX($x);
}

function AcceptPageBreak()
{
    //Método que acepta o no el salto automático de página
    if($this->col<2)
    {
        //Ir a la siguiente columna
        $this->SetCol($this->col+1);
        //Establecer la ordenada al principio
        $this->SetY($this->y0);
        //Seguir en esta página
        return false;
    }
    else
    {
        //Volver a la primera columna
        $this->SetCol(0);
        //Salto de página
        return true;
    }
}

function ChapterTitle($num,$label)
{
    //Título
    $this->SetFont('Arial','',12);
    $this->SetFillColor(200,220,255);
    $this->Cell(0,6,"$label",0,1,'L',true);
    $this->Ln(3);
    //Guardar ordenada
    $this->y0=$this->GetY();
}
function PrintChapter($num,$title)
{
    //Añadir capítulo
    //$this->AddPage();
    $this->ChapterTitle($num,$title);
    //$this->ChapterBody($file);
}

function WriteHTML($html)
{
    //Intérprete de HTML
	$this->SetFont('Arial','',7);
    $html=str_replace("\n",' ',$html);
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            //Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            //Etiqueta
            if($e[0]=='/')

                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                //Extraer atributos
                $a2=explode(' ',$e);
                $tag=strtoupper(array_shift($a2));
                $attr=array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])]=$a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag,$attr)
{
    //Etiqueta de apertura
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF=$attr['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}

function CloseTag($tag)
{
    //Etiqueta de cierre
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF='';
}

function SetStyle($tag,$enable)
{
    //Modificar estilo y escoger la fuente correspondiente
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s)
    {
        if($this->$s>0)
            $style.=$s;
    }
    $this->SetFont('',$style);
}

function PutLink($URL,$txt)
{
    //Escribir un hiper-enlace
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}
}

/// FUNCION PARA CAMBIAR EL FORMATO A LA FECHA DIA-MES-AÑO --> AÑO-MES-DIA ////
function cambiarFormatoFecha2($change){ 
    list($dia,$mes,$anio)=explode("/",$change); 
    return $anio."-".$mes."-".$dia; 
}

///FUNCION PARA CAMBIAR EL FORMATO A LA FECHA////
function cambiarFormatoFecha($change){ 
    list($anio,$mes,$dia)=explode("/",$change); 
    return $dia."-".$mes."-".$anio; 
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

$fullname = $row_login['fullname'];
$documento = $row_login['username'];
$telefono = $row_login['telefono'];

$fecha = date(strtotime('now'));
setlocale(LC_TIME, 'es_ES');
$fecha = strftime("%A, %d de %B del %Y", $fecha);

$fecha2 = date("Y-m-d");

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;    
}

//Creación del PDF

$pdf = new PDF_Code128();
$pdf->AliasNbPages();
//$title='CONSENTIMIENTO INFORMADO';

//Valores de Pruebas
$pdf->AddPage();
//
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,'CONSENTIMIENTO INFORMADO',0,'C');
$pdf->Ln(6);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0,5,utf8_decode("Quito, $fecha"),0,'R');
$pdf->Ln(4);
$pdf->MultiCell(0,5,utf8_decode("Por medio del presente otorgo a Corporación Kimirina y sus proveedores médicos mi consentimiento informado, para ser asesorado(a), diagnosticado(a) y tratado(a) a través de servicios electrónicos (telemedicina)."),0,'J');
$pdf->Ln(3);
$pdf->MultiCell(0,5,utf8_decode("Yo, $fullname con documento de identidad # $documento y con número de teléfono $telefono, acepto recibir las atenciones médicas requeridas a través de consultas electrónicas/telemedicina y me comprometo a declarar toda la información solicitada por el médico de forma fidedigna."),0,'J');
$pdf->Ln(3);
$pdf->MultiCell(0,5,utf8_decode("Corporación Kimirina ofrece los siguientes servicios vía electrónica (telemedicina):"),0,'J');
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("Profilaxis pre-exposición- PrEP, que es el uso de medicación diaria para reducir el riesgo de contraer VIH como resultado de una posible exposición al virus."),0,'J');
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("Profilaxis post exposición-nPEP, que es el uso de medicación diaria después de una relación sexual de alto riesgo y como resultado está expuesto al VIH"),0,'J');
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("Tratamiento antirretroviral (ARV) a personas que viven con VIH"),0,'J');
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("Diagnóstico y tratamiento de infecciones de transmisión sexual (ITS)"),0,'J');

$pdf->Ln(3);
$pdf->MultiCell(0,5,utf8_decode("Comprendo los potenciales beneficios y riesgos de este proceso, detallados a continuación:"),0,'J');

$pdf->Ln(3);
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(0,5,utf8_decode("Potenciales beneficios:"),0,'J');
$pdf->SetFont('Arial','',10);
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("Recibir lo más rápido posible, un diagnóstico, tratamiento y recomendaciones de un especialista, para continuar siendo negativo para VIH o iniciar oportunamente un tratamiento."),0,'J');
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("Puede reducir su riesgo de contraer el VIH e ITS, mediante el uso de condones cuando tiene actividades sexuales, y/o usar agujas estériles."),0,'J');
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("Acepto que la receta, de haberla; sea enviada de manera electrónica y me comprometo a cumplir la misma de la forma que indique el profesional médico."),0,'J'); 
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("Desde el punto de vista tecnológico, mejora el acceso a opiniones médicas especializadas utilizando medios electrónicos, de forma oportuna."),0,'J');

$pdf->Ln(3);
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(0,5,utf8_decode("Potenciales riesgos:"),0,'J');
$pdf->SetFont('Arial','',10);
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("La toma de cualquier medicamento no está exenta de riesgos. Específicamente, Emtricitabine 200 mg y Tenofovir Disoproxil Fumarato 300 mg   para la PrEP y Atripla para la nPEP o cualquier otro medicamento ARV, presentan posibles efectos secundarios que incluyen: dolor de cabeza, náuseas, vómitos, malestar estomacal o erupción. Asimismo, en casos muy poco frecuentes, pueden causar toxicidad renal u ósea o una reacción alérgica reversibles."),0,'J');
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("De considerar el profesional médico que la información es insuficiente (ej.: imágenes de baja calidad) para la integridad del usuario, esta atención puede no ser concluyente, no generará diagnóstico y/o receta alguna, tampoco concebirá un consejo médico o derivación a otro especialista."),0,'J'); 
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("La falta de acceso a una historia clínica completa puede resultar en errores o inconsistencias en el criterio médico."),0,'J'); 
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("La evaluación y el tratamiento médico podría demorarse debido a los protocolos de seguridad de información o/a deficiencias o fallas en el equipamiento, lo que podría repercutir en violaciones de privacidad de mi información médica."),0,'J');
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("No hay garantías de que la tele consulta eliminará la necesidad de que consulte a un especialista de manera presencial."),0,'J');

$pdf->Ln(3);
$pdf->MultiCell(0,5,utf8_decode("Autorizo además a que la consulta médica por telemedicina sea grabada (en video y audio) para efectos de seguridad de las dos partes."),0,'J');

$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(0,5,utf8_decode("Otras consideraciones:"),0,'J');
$pdf->SetFont('Arial','',10);
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("Estoy de acuerdo con aportar $10,00 (DIEZ,00/100 DOLARES AMERICANOS), su contribución apoyará a financiar otras prestaciones a nuestras poblaciones que no tienen financiamiento."),0,'J');
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("Si usted no está en la fecha y hora acordada deberá pagar nuevamente el valor de la cita."),0,'J');
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("Usted dispone de 72 horas laborables para el retiro de la medicación que haya sido recetada por el médico, pasado este tiempo usted tendrá que volver a realizar el procedimiento de la telemedicina y los exámenes correspondientes."),0,'J');

$pdf->Ln(3);
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(0,5,utf8_decode("Uso de Datos:"),0,'J');
$pdf->SetFont('Arial','',10);
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("Autorizo el uso de mis datos para fines exclusivos de investigación científica por parte de Corporación Kimirina, misma que garantizará la confidencialidad y el uso exclusivo por parte de los investigadores y personal médico de la institución."),0,'J'); 
$pdf->Cell(10);
$pdf->MultiCell(0,5,utf8_decode("Corporación Kimirina garantiza que las investigaciones no tendrán repercusiones personales, físicas y/o psicológicas para el usuario. Comprendo que puedo retirar mi consentimiento y cancelar el proceso de consultas electrónicas y uso de mis datos en cualquier momento y por cualquier razón sin consecuencia alguna."),0,'J');

$valorY = $pdf->GetY();
$pdf->Line(0, $valorY+2, 210, $valorY+2);
/*
$pdf->SetFont('Arial','B',10);
$pdf->Ln(7);
$pdf->MultiCell(0,5,"ACEPTADO POR:",0,'C');
$pdf->Ln(5);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0,5,"$fullname",0,'C');
$pdf->MultiCell(0,5,"$documento",0,'C');
$pdf->MultiCell(0,5,getUserIpAddr(),0,'C');
$pdf->Ln(5);
*/
//$pdf->SetFont('Courier','B',11);

//$pdf->SetFont('Courier','',9);
//$pdf->Cell(5);

//$pdf->MultiCell(0,0,getUserIpAddr(),0,'C');
//

//
//QR CODE para Validación
require('class/qrcode.class.php');
//$qrcode = new QRcode('0916312903', 'M'); // error level : L, M, Q, H
$err = 'M';
//$pdf->Image(
//$pdf->Image("http://laboratorio.kimirina.org/laboratorio/pdf/qr/image.php?msg=$colname_SpecimenID", 15, $valorY+10, 20, 20, "png");
//$qrcode->displayFPDF('test.pdf',0,0,0);
    
$pdf_file = $documento.'_'.$fecha;
//
$pdf->Output("$pdf_file.pdf","I");
//$pdf->Output("../resultados/$pdf_file.pdf","F");
    
    /* Actualizando el PDF Creado */
    $insertSQL = sprintf("UPDATE users SET consentimiento_file = '1', fecha_consentimiento = %s WHERE username = %s", GetSQLValueString($fecha, "date"), GetSQLValueString($colname_fecha, "date"));

    mysql_select_db($database_klinica, $klinica);
    $Result1 = mysql_query($insertSQL, $klinica) or die(mysql_error());

//$insertGoTo = "../generacion/index.php";

/*if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
}
header(sprintf("Location: %s", $insertGoTo));
*/
?>
