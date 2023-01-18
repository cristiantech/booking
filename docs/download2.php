<?php
if(!empty($_GET['file'])){
    $fileName = basename($_GET['file']);
    $ext = basename($_GET['ext']);
	$link="$_SERVER[DOCUMENT_ROOT]docs/ids/".$fileName;
    $filePath = $link;
	$fileName = $fileName.".".$ext;
    if(!empty($filePath) && file_exists($filePath)){
        // Define headers
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: application/$ext");
        header("Content-Transfer-Encoding: binary");
        
        // Read the file
        readfile($filePath);
        exit;
    }else{
        echo 'The file does not exist.';
		echo"<br>".$filePath;
    }
}
?>