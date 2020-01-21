<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?><?php require_once("includes/form_displaying_processing.php"); 
?><?php
	$sel_subject = $_GET['subj'];
		if (intval($_GET['subj']) == 0) {
			redirect_to("index.php?subj=1");
		}	
		if(isset($_POST['submit'])) { 	
			$errors = array();		
			$required_fields = array('address','phone', 'email');	
			foreach ($required_fields as $fieldname){
				if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
					$errors[] = $fieldname;
				}
			}		
			if (empty($errors)) {
				//Perform the Update
				$id = $_POST['contentId'];
				$address = $_POST['address'];
				$old_image = $_POST['old_image'];
				$caption = mysql_prep($_POST['caption']);
				$phone = $_POST['phone'];
				$email = mysql_prep($_POST['email']);
				$image_name = $_FILES['file']['name'];
				
				$img_path =   basename($image_name);
				if($_FILES['file']['name'] != ""){
					if (move_uploaded_file($_FILES["file"]["tmp_name"],
					  "images/content" . $image_name)){
						 unlink("images/" . $old_image);
					} else {
						}
				}
				
				$query = "UPDATE contact SET
						caption = '{$caption}',";
						if ($_FILES['file']['name'] != ""){
							$query .= "img_path = 'content{$img_path}',";
							} else {
								$query .= "img_path = '{$old_image}',";
						}						
				$query .= "address = '{$address}',						
						phone = '{$phone}',
						email = '{$email}'
						
					WHERE id = '{$id}'";
				$result = mysql_query($query, $connection);
				if (mysql_affected_rows() == 1){
					// Success
					redirect_to("editSubject.php?subj=" . $_GET['subj'] . "&form=1");
				} else {
					// Failed
					redirect_to("editSubject.php?subj=" . $_GET['subj'] . "&form=1&id=" . $id . "&old_image="  . $old_image . "&phone=" . $phone . "");
				}				
				
			} else {
				//Errors occurred
				if (count($errors)== 1) {
				} else {
					}
					
			}
		}	// end: if(isset($_POST['submit']))
?>