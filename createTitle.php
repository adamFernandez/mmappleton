<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php 
	$subj_id = mysql_prep($_GET['subj']);
	$sect_id = mysql_prep($_GET['sect']); 
?>
<?php
	$errors = array();
	
	// Form Validation
	$fields = array('title','description','position','year');
	foreach ($fields as $field_name) {
		if (!isset($_POST[$field_name]) || empty($_POST[$field_name])) {
			$errors[] = $field_name;
		}
	}
	$fields_with_lengths = array();
	foreach($fields_with_lengths as $fieldname => $maxlength ) {
		if (strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength ) {
			$errors[] = $fieldname; 
			}
	}
	if (!empty($errors)){
		//Please review the fields.
		}	
?>
<?php	
	$subj_id = mysql_prep($_GET['subj']);
	$sect_id = mysql_prep($_GET['sect']); 
	if (isset($_POST['content'])) {
				$content = $_POST['content'];
				}
	$title = mysql_prep($_POST['title']);
	$image_name = $_FILES['file']['name'];
	$path = basename($image_name);
	$l_image_name = $_FILES['l_file']['name'];
	$l_path = basename($l_image_name);	
	if (move_uploaded_file($_FILES["file"]["tmp_name"],
      "images/" . $image_name)){		 
		//File uploaded. 
	} else {
		//File upload failed.
		}
	if (move_uploaded_file($_FILES["l_file"]["tmp_name"],
      "images/" . $l_image_name)){
		 
		//Large file uploaded.
	} else {
		//Large file upload failed.
		}
	
	$format = mysql_prep($_POST['format']);
	$description = mysql_prep($_POST['description']);
	if ($subj_id != 5) {
			$dimensions = mysql_prep($_POST['dimensions']);
	}
	$year = mysql_prep($_POST['year']);
	$position = mysql_prep($_POST['position']);
?>
<?php 
	$query = "INSERT INTO pages (";
			if (isset($_POST['content'])) {
			$query .= "content,";
			}
	$query .= "subject_id,section_title_id,title,img_path,l_img_path,format,description,";
			if ($subj_id != 5) {
				$query .= "dimensions,";
				}
			$query .= "year,position
			)VALUES(
				";
				if (isset($_POST['content'])) {
				$query .= "'{$content}',"; 
				}
				$query .= "'{$subj_id}','{$sect_id}','{$title}','{$path}','{$l_path}','{$format}','{$description}',";
				if ($subj_id != 5) {
					$query.= "'{$dimensions}',";
				}
				$query .= "'{$year}',{$position})";
	$result = mysql_query($query,$connection); 
	if ($result){
		// Success!
		redirect_to("editSection.php?subj={$subj_id}&sect={$sect_id}&form=1");
	} else {
		// Display error message
		}	
?>
<?php mysql_close($connection); ?>