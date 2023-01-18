<?php 
    $conexion=mysqli_connect('localhost','root','principe406!','booking');
    $provincia=$_POST['id_provincia'];

    $canton = $_POST['id_canton'];

    mysqli_query($conexion,"SET CHARACTER SET 'utf8'");

	$sql="SELECT id, nombre from cantones where id_provincia='$provincia' ORDER BY nombre";

	$result=mysqli_query($conexion,$sql);

	$cadena="<label>SELECT 2 (paises)</label> 
			<select id='lista2' nombre='lista2'><option value=''>Seleccione un Canton</option>";

	while ($ver=mysqli_fetch_row($result)) {
        
        $selected  = "";
        
        if (!(strcmp($canton, $ver[0]))) { $selected = "selected=\"selected\""; } else { }
        
		$cadena=$cadena.'<option value='.$ver[0].' '.$selected.'>'.$ver[1].'</option>';
	}

	echo  $cadena."</select>";
	

?>