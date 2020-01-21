<?php
	function download($name){
		echo 'hole';
		if((isset($_POST['submitDownloadS'])) || (isset($_POST['submitDownloadL']))){
			
				$name = $_POST['name'];
				$fileName = $_POST['fileName'];
				header('Content-Description: File Transfer');			
				header('Content-Type: application/octet-stream');
				header('Content-disposition: attachment; filename=' . $fileName . '');	
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-type: application/jpeg');
				readfile('uploads/' . $fileName); 
				unlink('uploads/' . $fileName);
				imagedestroy('uploads/' . $fileName) ;
				!file_exists('uploads/'. $fileName) == TRUE;
				redirect_to('uploadCrop.php?subj=4&form=1&name='. $name);
		}
		
	}
?>