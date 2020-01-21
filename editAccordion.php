<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php require_once("includes/form_displaying_processing.php"); ?>
<?php
	$accordion_set = get_all_accordion_images();
	$accordion_count = mysql_num_rows($accordion_set);
	if (intval($_GET['subj']) == 0) {
		redirect_to("editSubject.php?subj=" . $_GET['subj']);
	}	
		
		$all_fields = array();
		
	for ($i=0; $i <= $accordion_count -1; $i++) {
		 	if(isset($_POST['submit' . $i])) {
			
			$errors = array();
			$required_fields = array('img_name' . $i ,'position' . $i);	
			foreach ($required_fields as $fieldname){
				if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
					$errors[] = $fieldname;
				}
			}		
			$fields_with_lengths = array();
			foreach($fields_with_lengths as $fieldname => $maxlength){
				if (strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength) { $errors[] = $fieldname; }
			}
			
			if (empty($errors)) {
				//Perform the Update
				$id= $_POST['id' .$i];
				$old_image = $_POST['old_image' . $i];
				$subject_id = mysql_prep($_GET['subj']);
				$accordion = get_accordion_by_id($id);
				$old_position = $accordion['position'];
					$new_position = mysql_prep($_POST['position'. $i]);	
						$query_other = "UPDATE accordion SET position = {$old_position} WHERE position  = {$new_position}"; 
						$result_other = mysql_query($query_other, $connection);
				$img_name = mysql_prep($_POST['img_name'. $i]);
				$image_name = $_FILES['file' .$i]['name'];
				$img_path =   basename($image_name);
				if($_FILES['file' . $i]['name'] != ""){
					if (move_uploaded_file($_FILES['file' . $i]['tmp_name'],
					  'accordion_images/' . $image_name)){
						unlink("accordion_images/" .$old_image);
						 echo "File uploaded.";
					} else {
						echo "File upload failed.";
						}
				}				
				$query = "UPDATE accordion SET
						subject_id = '{$subject_id}',
						img_name = '{$img_name}',";
						if ($_FILES['file' . $i]['name'] != ""){
						$query .= "img_path = '{$img_path}',";
						} else {
							$query .= "img_path = '{$old_image}',";
							}
				$query .= "position = {$new_position}
					WHERE id = '{$id}'";
				$result = mysql_query($query, $connection);
				if (mysql_affected_rows() >= 1){
					// Success
					$message = "The accordion was successfully updated.";
					redirect_to("editAccordion.php?subj=" . $subject_id);
				} else {
					// Failed
					$message = "The accordion update failed.";
					$message .= "<br />" . mysql_error();
				}				
				
			} else {
				//Errors occurred
				if (count($errors)== 1) {
				$message = "There was " . count($errors) . " error in the form.";
				$message .= "<br />" . mysql_error();
				} else {
					$message = "There were " . count($errors) . " errors in the form.";
					$message .= "<br />" . mysql_error();
					}
					
			}
		}	// end: if(isset($_POST['submit']))	
	}
?>
<?php find_selected_page(); ?>
<?php include ("includes/header.php"); ?>
<?php echo navigation ($sel_subject); ?>
    <div id="main">
		<h2>Edit Accordion from <?php echo $sel_subject['menu_name']; ?> Page</h2>
		<?php
				  // MESSAGE DISPLAY IF ACCORDION EDITED SUCCESSFULY
				  if (!empty($message)) {
					  echo "<p class=\"message\">" . $message . "</p>";
				  } ?>
            	
                <?php
				// OUTPUT A LIST OF THE FIELDS THAT HAD ERRORS
				if (!empty($errors)) {
					echo "<p class=\"errors\">";
					echo "Please review the following fields:<br />";
					foreach($errors as $error) {
						echo " - " . $error . "<br />";
						
					}
					echo "</p>";
				}
		?>
		<div class='wrap1'>
				<div class='accordion'>";
		<?php			
			$accordion_set = get_all_accordion_images();
			while ($accordion = mysql_fetch_array($accordion_set)){
				echo "<div>
						<img src='accordion_images/" . $accordion['img_path'] . "'/> <!-- " . $accordion['img_name'] . " -->
					</div>";
				
			}	
			echo "</div></div>";
		?>
		<form class="borderLess" enctype='multipart/form-data' action="editAccordion.php?subj=<?php echo urlencode($sel_subject['id']); ?>&acc=<?php echo urlencode($accordion['id']); ?>" method="post">
			<?php
				$fieldNumber = 0;
				$accordion_set = get_all_accordion_images(); 
				while ($accordion = mysql_fetch_array($accordion_set)){
					echo "<div class='imageContainerAccordion'>
					<div class='imageFrame'>
						<img src='accordion_images/" . $accordion['img_path'] . "' width='60' height='87'/> <!-- " . $accordion['img_name'] . " -->
					</div>
					
					<div class='infoContainerAccordion'>";
					$old_image_path[$fieldNumber] = $accordion['img_path'];
					echo "<p><input type='hidden' name='old_image" . $fieldNumber . "' value='" . $old_image_path[$fieldNumber] . "' /></p>
					<p><label for='img_name" . $fieldNumber . "'>Image name:</label>
					<input type='text' name='img_name" . $fieldNumber . "' value='" . $accordion['img_name'] . "' size='12' /></p>
					<p><input type='hidden' name='id" . $fieldNumber . "' value='" . $accordion['id'] . "' /></p>
					<p><label for='file" . $fieldNumber . "'>Current(" . $accordion['img_path'] . "):</label>
					<input class='smallerButton' type='file' name='file" . $fieldNumber . "' value='' size='4' /></p>
					<p><label for='position" . $fieldNumber . "'>Position</label>
					<select name='position" . $fieldNumber . "'>";
					$accordion_count = mysql_num_rows($accordion_set);
					for ($count=1; $count <= $accordion_count ; $count ++){
						echo "<option value='" . $count . "'";
							if ($accordion['position'] ==  $count ) {
								echo " selected = 'selected'"; 
								}
								echo ">" . $count . "</option>";
						}
					echo "</select></p>";
					
					echo "<p><input class='smallerButton' type='submit' name='submit" . $fieldNumber . "' value='Edit Accordion'/>&nbsp;&nbsp;<a class='important' href='deleteImage.php?subj=" . $sel_subject['id'] . "&acc=" . $accordion['id'] . "&path=" . $accordion['img_path'] . "' onclick='return confirm('Are you sure?');'>Delete Image</a></p>";
					$fieldNumber ++;
					echo "</div> <!-- end infoContainer -->
					</div> <!-- end imageContainer -->";
				} // end while ($accordion = mysql_fetch_array($accordion_set)
				
				?>
			
			
		</form>
		<div class="clearFloat"></div>
		<form class="borderLess" action="newImage.php?subj=<?php echo urlencode($sel_subject['id']); ?>" method="post">
			<h5>Total Images: <?php echo $accordion_count; ?><input class="left" type="submit" name="submit" value="Add Image to Accordion" /> </h5>
		</form>
		<div class="clearFloat"></div>
		<a href="index.php?subj="<?php urlencode ($sel_subject['id']);?>>Back to Main Page</a>
		
		
<?php include ("includes/footer.php"); ?>
