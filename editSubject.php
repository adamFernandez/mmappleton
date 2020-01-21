<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php find_selected_page(); ?>
<?php require_once("includes/form_displaying_processing.php"); ?>
<?php
		
		// UPDATE JUST FOR THE HEADERS
		$section_set = get_section_title_by_subject($sel_subject['id']);
		$section_count = mysql_num_rows($section_set);
		for($i=0; $i<=$section_count; $i++){
			if (isset($_POST['submitHeader' . $i])) {	
				$id = $_POST['id' . $i];
				$section_set = get_section_by_id($id);
				$old_position = $section_set['position'];
				$new_position = $_POST['position' . $i];						
				if ($new_position > $old_position){
					$changes = $new_position - $old_position;
					$queryIfGreater = "UPDATE section_titles SET position = position -1 WHERE position <= {$new_position} AND position > {$old_position} AND subject_id = {$_GET['subj']} LIMIT {$changes}"; 
					$resultIfGreater = mysql_query($queryIfGreater, $connection);
					}
					if ($new_position < $old_position){
					$changes = $old_position - $new_position;
					$queryIfLower = "UPDATE section_titles SET position = position +1 WHERE position >= {$new_position} AND position < {$old_position} AND subject_id = {$_GET['subj']} LIMIT {$changes}"; 
					$resultIfLower = mysql_query($queryIfLower, $connection);
					}
				if(isset($_POST['visible' . $i])){
					$visible = 1;
					} else {
						$visible = 0;
				}
				
				$header_query = "UPDATE section_titles SET
								position = '{$new_position}',
								visible = '{$visible}'
								WHERE id = '{$id}' AND subject_id = '{$_GET['subj']}'";
						$header_result = mysql_query($header_query, $connection);
						if (mysql_affected_rows() == 1){
							// Success
							$message = "The section was successfully updated.";
						} else {
							// Failed
							$message = "The section update failed.";
							$message .= "<br />" . mysql_error();
						}				
			
			}
		}
		if(isset($_POST['submit'])) { 
			if ($sel_subject['id'] == 6){
				$errors = array();	
			$required_fields = array('address','phone', 'email');	
			foreach ($required_fields as $fieldname){
				if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
					$errors[] = $fieldname;
				}
			}		
			if (empty($errors)) {
				//Perform the Update
				$contentId = $_POST['contentId'];
				$address = $_POST['address'];
				$old_image = $_POST['old_image'];
				$caption = mysql_prep($_POST['caption']);
				$phone = mysql_prep($_POST['phone']);
									
				$image_name = $_FILES['file']['name'];
				
				$img_path =   "content" . basename($image_name);
				if($_FILES['file']['name'] != ""){
					if (move_uploaded_file($_FILES["file"]["tmp_name"],
					  "images/content" . $image_name)){
						 unlink("images/" . $old_image);
						echo "File uploaded."; 
					} else {
						echo "File upload failed.";
						}
				}
				
				$query = "UPDATE content SET
						caption = '{$caption}',";
						if ($_FILES['file']['name'] != ""){
							$query .= "img_path = '{$img_path}',";
							} else {
								$query .= "img_path = '{$old_image}',";
						}						
				$query .= "address = '{$address}',						
						phone = '{$phone}',
						email = '{$email}'
						
					WHERE contentId = '{$contentId}'";
				$result = mysql_query($query, $connection);
				if (mysql_affected_rows() == 1){
					// Success
					$message = "The info was successfully updated.";
					redirect_to("editSubject.php?subj=" . $_GET['subj'] . "&form=1");
				} else {
					// Failed
					$message = "The info update failed.";
					$message .= "<br />" . mysql_error();
					redirect_to("editSubject.php?subj=" . $_GET['subj'] . "&form=1");
				}				
				
			} else {
				//Errors occurred
				if (count($errors)== 1) {
				$message = "There was " . count($errors) . " error in the form.";
				} else {
					$message = "There were " . count($errors) . " errors in the form.";
					}
					
			}
			}
			$errors = array();			
		
			$required_fields = array('menu_name','position');	
			foreach ($required_fields as $fieldname){
				if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
					$errors[] = $fieldname;
				}
			}		
			$fields_with_lengths = array('menu_name' => 50);
			foreach($fields_with_lengths as $fieldname => $maxlength){
				if (strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength) { $errors[] = $fieldname; }
			}
			
			if (empty($errors)) {
				//Perform the Update
				$subject = get_subject_by_id($_GET['subj']);
				$id = mysql_prep($_GET['subj']);
				$menu_name = mysql_prep($_POST['menu_name']);
				$old_position = $subject['position'];
				$new_position = mysql_prep($_POST['position']); 
					if ($new_position > $old_position){
					$changes = $new_position - $old_position;
					$queryIfGreater = "UPDATE subjects SET position = position -1 WHERE position <= {$new_position} AND position > {$old_position} LIMIT {$changes}"; 
					$resultIfGreater = mysql_query($queryIfGreater, $connection);
					}
					if ($new_position < $old_position){
					$changes = $old_position - $new_position;
					$queryIfLower = "UPDATE subjects SET position = position +1 WHERE position >= {$new_position} AND position < {$old_position} LIMIT {$changes}"; 
					$resultIfLower = mysql_query($queryIfLower, $connection);
					}
						
				$query = "UPDATE subjects SET";
						$query .= " position = {$new_position},";
						$query .= " menu_name = '{$menu_name}'						
						WHERE id = '{$id}'";
				$result = mysql_query($query, $connection);
				if (mysql_affected_rows() == 1){
					// Success
					redirect_to("editSubject.php?subj=" . $_GET['subj'] . "&form=1");
					$message = "The subject was successfully updated.";
					
				} else {
					// Failed
					redirect_to("editSubject.php?subj=" . $_GET['subj'] . "&form=1");
					$message = "The subject update failed.";
					$message .= "<br />" . mysql_error();
				}				
				
			} else {
				//Errors occurred
				if (count($errors)== 1) {
				$message = "There was " . count($errors) . " error in the form.";
				} else {
					$message = "There were " . count($errors) . " errors in the form.";
					}
					
			}
		}	// end: if(isset($_POST['submit']))	
?>
<?php find_selected_page(); ?>
<?php include("includes/header.php"); 
?>
<?php echo navigation($sel_subject);?><div id="main">
		<h2>Edit Subject <?php echo $sel_subject['menu_name']; ?></h2>
<?php
				  // MESSAGE DISPLAY IF SUBJECT EDITED SUCCESSFULY
				  if (!empty($message)) {
					  echo "<p class=\"message\">" . $message . "</p>";
				  }
?>            	
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
		<form action="editSubject.php?subj=<?php echo urlencode($sel_subject['id']); ?>" method="post">
			<p><label for="menu_name">Subject name:</label>
			<input type="text" name="menu_name" value="<?php echo $sel_subject['menu_name']; ?>" id="menu_name" />
			<label for="position">Position</label>
			<select name="position">
<?php
					$subject_set = get_all_subjects();
					$subject_count = mysql_num_rows($subject_set);
					for ($count=1; $count <= $subject_count ; $count ++){
							
						echo "<option value='" . $count . "'";
							if ($sel_subject['position'] ==  $count ) {
								echo " selected = 'selected'"; 
								}
								echo ">" . $count . "</option>";
						}
?>
			</select></p>
			<p><input type="submit" name="submit" value="Apply Changes" />&nbsp;&nbsp;<a class='important' href="deleteSubject.php?subj=<?php echo urlencode($sel_subject['id']); ?>" onclick="return confirm('Are you sure?');">Delete Subject <?php echo $sel_subject['menu_name']; ?> !!</a></p>
		</form>			
<?php
			if ($sel_subject['id'] == 1) {
				echo "<form class='borderLess' action='editAccordion.php?subj=";
				echo urlencode($sel_subject['id']);
				echo "&form=1' method='post'>
						<input class='marginTop5' type='submit' name='submit' value='Edit Accordion'/>
					</form>";
					
			}
?>
<?php
			if ($sel_subject['id'] == 6){
				$contact_set = get_contact_page();
				$contact = mysql_fetch_array($contact_set);
				echo "<div class='wrap1'>";
					$old_image = $contact['img_path'];
					echo "
						<form enctype='multipart/form-data' action='editContact.php?subj=6' method='post'>
						<h3>Edit " . $sel_subject['menu_name'] . " page info.</h3>
						<div class='imageContainerContact'>
							<div class='imageFrame'>
								<div class='image-decorator'>
								<img src='images/" . $contact['img_path'] . "' width='200' height='254'  />
								</div>
								<p class='italic'>" . $contact['caption'] . "</p>
						   </div>
						   <div class='infoContact'>
							  <h3>M M Appleton </h3><br />
							  <input type='hidden' name='contentId' value='" . $contact['id'] . "' />
							  <input type='hidden' name='old_image' value='" . $old_image . "' />
						<p><label for='file'>Current Image: (" . $contact['img_path'] . ") </label><br />
						<input type='file' name='file' value=''/></p>
								<p><label for='caption'>Caption: </label>
								<input type='text' name='caption' value='" . $contact['caption'] . "' /></p>
							  <h3><label for='address'>Address: </label>
							  <textarea name='address' rows='5' cols='40' value='' >" . $contact['address'] . "</textarea>  </h3><br/>
							  <h3><label for='phone'>Phone: </label>
							  <input type='text' name='phone' value='" . $contact['phone'] ."' /></h3>
							  <h3 class='peque' ><label for='email'>Email: </label>
							  <input type='text' name='email' value='" . $contact['email'] . "' /></h3>    
								
							  <a href='#' class='twitter-follow-button' data-show-count='false' data-size='large'>Follow @appletonstudio</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='//platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>
							
						   </div> <!-- end infoContainer -->
							 
							<div class='clearFloat'></div>
						</div><!-- end imageContainerContatc -->
						<input class='marginTop5' type='submit' name='submit' value='Edit Page Info'/>
				</form>
						</div><!-- end wrap1 -->
						   <div class='clearFloat'></div>";
			}
?>
<?php		
		$section_set = get_section_title_by_subject($sel_subject['id']);
		$section_count = mysql_num_rows($section_set);
		if (($section_count == 0) && (($sel_subject['id'] == 1) OR ($sel_subject['id'] == 6))){
			echo "<h2  class='textLeft'>No Section Titles to edit</h2>"; 
		} else{
			echo "<h2  class='textLeft'>Section titles</h2>
			<p>To edit a Section position or header visibility (checked = visible; unchecked = no visible) change the values for the Section to edit and click on <span class='italic'>Update</span> button.</p>
			
			<p>To edit the titles in a Section click over the Section name.</p>";
?>
			<form class="upDownBorder"  action="newSection.php?subj=<?php echo urlencode($sel_subject['id']); ?>&form=1" method="post">
		<input type="submit" name="submit" value="Add Section" />
		</form>
<?php
			echo"<div class='table'>
					<span class='smaller'>
						<div class='dataSelect'>pos</div>
						<div class='dataCheckbox'>vis</div>
						<div class='dataSection'>edit</div>
					</span>
				</div>
				<form class='borderLess' method='post'>"; 
		} 
		$header = 0;
		$section_count = mysql_num_rows($section_set);
		while ($section = mysql_fetch_array($section_set)) {
			 
			echo "<input type='hidden' name='id" . $header . "' value='" . $section['id'] . "' />
					<span class='likeH4'>
						<select name='position" . $header . "' >";
							for ($count=1; $count <= $section_count; $count ++){
								echo "<option value='". $count . "'";
									
								if ($count == $section['position'] ){
									echo " selected ";
									}
									echo ">" . $count . "</option>";
								
								}
						echo "</select>
					<input class='checkbox' name='visible" . $header . "' "; 
					if ($section['visible'] == 1){
						echo "checked "; 
					}
			echo "type='checkbox' value='" . $section['visible'] . "' /><a href='editSection.php?subj=" . urlencode($sel_subject['id']) . "&sect=" . urlencode($section['id']) . "&form=1'>
			 {$section['title_name']} </a><input  type='submit' name='submitHeader" . $header . "' value='Update' /></span>
			<span class='sectionTitlesDelete'>"; ?>
			<a class="important" href="<?php
							echo "deleteSection.php?subj=" . $sel_subject['id'] . "";
							echo "&amp;sect=" . $section['id'] . ""?>"
							<?php 
							echo " onclick=";?>"<?php echo "return confirm('Are you sure?');"; ?>"<?php echo ">Delete Section !!</a>
			</span>";
			$header ++;
		}
		
 ?>		
		</form>		
<?php
		
?>
<?php include ("includes/footer.php"); ?>
