<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php
	$errors = array();
	
	// Form Validation
	$fields = array('img_name','position',);
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
	if (!empty($errors)) {
		redirect_to("newImage.php?subj=" . $_GET['subj']);
		}
	
?>
<?php
	$subj_id = mysql_prep($_GET['subj']);
	$img_name = mysql_prep($_POST['img_name']);
	$image_name = $_FILES['file']['name'];
	$img_path =   basename($image_name);
	if (move_uploaded_file($_FILES["file"]["tmp_name"],
      "accordion_images/" . $image_name)){
		 
		echo "File uploaded."; 
	} else {
		echo "File upload failed.";
		}
	$position = mysql_prep($_POST['position']);
 ?> 
<?php 
	$query = "INSERT INTO accordion (";
	$query .= " subject_id, img_name, img_path, position
			) VALUES (
				";
	$query .= "'{$subj_id}','{$img_name}','{$img_path}',{$position})";
	$result = mysql_query($query, $connection); 
	if ($result){
		// Success!
		redirect_to("newImage.php?subj=" . $subj_id . "&sect=" . $sect_id . "&form=1");
		
	} else {
		// Display error message
		echo "<p>Subject creation failed.</p>";
		echo "<p>" . mysql_error() . "</p>";
		}
	
?>
<?php mysql_close($connection); ?>