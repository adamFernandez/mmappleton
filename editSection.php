<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php require_once("includes/form_displaying_processing.php"); ?>
<?php
	if (isset($_GET['sect'])){
			$sel_sect = $_GET['sect'];
			} else {
				$sel_sect = 1;
				}
	$sel_subject = $_GET['subj'];
?>	
<?php
		$page_by_section = get_pages_by_section($sel_sect, $sel_subject);
			$pages = mysql_num_rows($page_by_section);
			$page_id = mysql_fetch_array($page_by_section);
			
?>					
<?php
		if (intval($_GET['subj']) == 0) {
			$sel_subject= 1;
		}	
		// UPDATE JUST FOR THE HEADERS
		if (isset($_POST['submitHeader'])) {		
			$id = $sel_sect;
			$section_set = get_section_by_id($id);
			$title_name = mysql_prep($_POST['title_name']);
			$sub_title = $_POST['sub_title'];
			$visible_sub = $_POST['visible_sub'];
			if(isset($_POST['visible_sub'])){
				$visible_sub = 1;
				} else {
					$visible_sub = 0;
					}
			$old_position = $section_set['position'];
			$new_position = $_POST['position'];						
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
			$visible = $_POST['visible'];			
			if(isset($_POST['visible'])){
				$visible = 1;
				} else {
					$visible = 0;
			}
			
			
			
			$header_query = "UPDATE section_titles SET
							title_name = '{$title_name}',
							sub_title = '{$sub_title}',
							visible_sub = '{$visible_sub}',
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
		
		
		for ($i=0; $i <= $pages; $i++) {
			
			if(isset($_POST['submit' . $i])) { 	
				$errors = array();			
				$required_fields = array('title' . $i, 'description' . $i, 'year' . $i, 'position' . $i);	
				if (isset($_POST['content' . $i])) {
				array_push($required_fields, 'content' . $i);
				}
				if ($sel_subject['id'] != 5) {
					array_push($required_fields, 'dimensions' . $i);
				}
				foreach ($required_fields as $fieldname){
					if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
						$errors[] = $fieldname;
					}
				}		
				$fields_with_lengths = array('description' . $i => 60);
				foreach($fields_with_lengths as $fieldname => $maxlength){
					if (strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength) { $errors[] = $fieldname; }
				}
				
				if (empty($errors)) {
					//Perform the Update
					
					$id= $_POST['id' .$i]; 
					$section_id = mysql_prep($_GET['sect']);
					$old_image = $_POST['old_image' . $i];
					$l_old_image = $_POST['l_old_image' . $i];
					$title = mysql_prep($_POST['title' . $i]);
					$page = get_page_by_id($id);
					$old_position = $page['position'];
					$new_position = mysql_prep($_POST['position'. $i]);						
					if ($new_position > $old_position){
					$changes = $new_position - $old_position;
					$queryIfGreater = "UPDATE pages SET position = position -1 WHERE position <= {$new_position} AND position > {$old_position} AND section_title_id = {$section_id} LIMIT {$changes}"; 
					$resultIfGreater = mysql_query($queryIfGreater, $connection);
					}
					if ($new_position < $old_position){
					$changes = $old_position - $new_position;
					$queryIfLower = "UPDATE pages SET position = position +1 WHERE position >= {$new_position} AND position < {$old_position} AND section_title_id = {$section_id} LIMIT {$changes}"; 
					$resultIfLower = mysql_query($queryIfLower, $connection);
					}
					if (isset($_POST['content' . $i])) {
						$content = mysql_prep($_POST['content' . $i]);
					}
					$description = mysql_prep($_POST['description' . $i]);
					if ($_GET['subj'] != 5){
						$dimensions = mysql_prep($_POST['dimensions' . $i]);
					}
					$year =  mysql_prep($_POST['year' . $i]);					
					$image_name = $_FILES['file' .$i]['name'];
					$l_image_name = $_FILES['l_file' . $i]['name'];
					$img_path =   basename($image_name);
					$l_img_path = basename($l_image_name);
					
					if($_FILES['file' . $i]['name'] != ""){
						if (move_uploaded_file($_FILES["file" . $i]["tmp_name"],
						  "images/" . $image_name)){
							 unlink("images/" . $old_image);
							echo "File uploaded."; 
						} else {
							echo "File upload failed.";
							}
					}
					if($_FILES['l_file' . $i]['name'] != ""){
						if (move_uploaded_file($_FILES["l_file" . $i]["tmp_name"],
						  "images/" . $l_image_name)){
							 unlink("images/" . $l_old_image);
							echo "File uploaded."; 
						} else {
							echo "File upload failed.";
							}
					}
					
					$query = "UPDATE pages SET
							title = '{$title}',";
							if ($_FILES['file' . $i]['name'] != ""){
								$query .= "img_path = '{$img_path}',";
								} else {
									$query .= "img_path = '{$old_image}',";
							}
							if ($_FILES['l_file' . $i]['name'] != ""){
								$query .= "l_img_path = '{$l_img_path}',";
								} else {
									$query .= "l_img_path = '{$l_old_image}',";
							}							
							if (isset($_POST['content' . $i])) {
								$query .= "content = '{$content}',";
							}
							
					$query .= "description = '{$description}',";
							if ($_GET['subj'] != 5){
								$query .= "dimensions = '{$dimensions}',";
								}
					$query .= "year = '{$year}',
							
							position = {$new_position}
							
						WHERE id = '{$id}' 
						AND section_title_id = '{$section_id}'";
					$result = mysql_query($query, $connection);
					if (mysql_affected_rows() == 1){
						// Success
						$message = "The title was successfully updated.";
					} else {
						// Failed
						$message = "The title update failed.";
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
		} // end: for ($i=0; $i <= $pages; $i++)
?>
<?php
	find_selected_page();
	
?>
<?php include ("includes/header.php"); ?>
<?php echo navigation($sel_subject); ?>
<?php		
		$page_by_section = get_pages_by_section($sel_sect, $sel_subject['id']);
			$pages = mysql_num_rows($page_by_section);
?>
    <div id="main">
	<?php
		$section_set = get_section_title_by_id($sel_sect);
		$section = mysql_fetch_array($section_set);
		?>
		
		<h2>Edit Section<?php echo " " . $section['title_name']; ?></h2>
		<?php
			
				  // MESSAGE DISPLAY IF SUBJECT EDITED SUCCESSFULY
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
		 <?php 					
			$section_set = get_section_title_by_id($sel_sect);
		$section = mysql_fetch_array($section_set);
		$sect_count_set = get_section_title_by_subject($sel_subject['id']);
		$section_count = mysql_num_rows($sect_count_set);
			$idSubject = $sel_subject['id'];
			$idSection = $sel_sect;
			$formNumber = 0;
			if ($section['type'] == 1) {
						echo "<form class='borderLess' action='editSection.php?subj=" . $idSubject . "&sect=" . $idSection . "&form=1' method='post'>
						<h4><label for='" . $section['title_name'] ."'>Section Header:</label>
						<input type='text' name='title_name' value='" . $section['title_name'] ."'/>
						<label class='smaller' for='position'>position</label>
						<select name='position'>";
							for ($count=1; $count <= $section_count; $count ++){
								echo "<option value='". $count . "'";
									
								if ($count == $section['position'] ){
									echo " selected ";
								}
								echo ">" . $count . "</option>";
								
							}
						echo "</select>
					<label class='smaller' for='visible'>visible</label>
					<input name='visible' class='checkbox' "; 
					if ($section['visible'] == 1){
						echo "checked "; 
					}
			echo "type='checkbox' value='" . $section['visible'] ."' />
						</h4>
					
					
					<label for='sub_title'>Sub-title: </label>
						<textarea cols='5' rows='1' name='sub_title'>" . $section['sub_title'] . "</textarea>
						<label class='smaller' for='sub_visible'>visible</label>
						<input name='visible_sub' class='checkbox' ";  
						if ($section['visible_sub'] == 1){
							echo "checked "; 
						}
						echo "type='checkbox' value='" . $section['visible_sub'] ."' />";
					
					echo"<input type='submit' name='submitHeader' value='Edit Header' />
						</form>";
						$countTitles = 1;
			echo "<div class='clearFloat'></div>";
			echo "<h5 class='noBorder'>Total Titles: " . $pages . "  <a class='noUnderline' href='newTitle.php?subj=" . $idSubject . "&sect=" . $idSection ."&form=1'><input  type='submit' value='Add Title'></a> </h5>";
				while ($page = mysql_fetch_array($page_by_section)) {
					$old_image_path[$formNumber] = $page['img_path'];
					$l_old_image_path[$formNumber] = $page['l_img_path'];
					 if($countTitles == $pages){ echo "<a name='bottomEditSection'> ";}
					echo "<form enctype='multipart/form-data' class='borderLess' action='editSection.php?subj=" . $idSubject . "&sect=" . $idSection . "&form=1' method='post'>";
					if($countTitles == $pages){ echo "</a>";}
					echo "<div class='imageContainer";
						if ($page['format'] == 1){
									echo "Wide";
									}
					echo "'>
					
					<div class='imageFrame";
						if ($sel_subject['id'] == 5 && $page['format'] == 0){
							echo "LessFields";
							}
						echo "'>
						
						<img "; if ($page['section_title_id'] == 5) {echo "class='widePaper'";}
						echo " src='images/" . $page['img_path'] . "' />
						
					</div>";
						if ($sel_subject['id'] == 5 && $page['format'] == 0){
							echo "<div class='infoContainerLessFields'>";
							} elseif ($sel_subject['id'] == 5 && $page['format'] == 1) {
									echo "<div class='infoContainerLessFieldsWide'>";
									} else {
										echo "<div class='infoContainer'>";
									}
					echo "<p><input type='hidden' name='old_image" . $formNumber . "' value='" . $old_image_path[$formNumber] . "' /></p>
						<p><input type='hidden' name='l_old_image" . $formNumber . "' value='" . $l_old_image_path[$formNumber] . "' /></p>
						
						<p><label for='title" . $formNumber . "' >Title:</label>
						<input type='text' name='title" . $formNumber . "' value='" . $page['title'] ."' />
						<label for='position" . $formNumber . "'>Position</label>
						<select name='position" . $formNumber . "'>";
							for ($count=1; $count <= $pages; $count ++){
								echo "<option value='". $count . "'";
									
								if ($count == $page['position'] ){
									echo " selected ";
									}
									echo ">" . $count . "</option>";
								
								}
						echo "</select>
						<input type='hidden' name='id" . $formNumber . "' value='" . $page['id'] . "' /></p>
						<p><label for='file" . $formNumber . "'>Current Image: (" . $page['img_path'] . ") </label>
						<input type='file' name='file" . $formNumber . "' value=''/></p>";
						
							echo "<p><label for='l_file" . $formNumber . "'>";
								if (!empty($page['l_img_path'])){
									echo "Zooming Image: (" . $page['l_img_path'] . ") </label>";
									} else {
										
										echo "No Zooming Image!";
									}
								echo "<input type='file' name='l_file" . $formNumber . "' value='' /></p>";

						echo "<p><label for='description" . $formNumber . "'>Description:</label>
						<input type='text' name='description" . $formNumber . "' value='" . $page['description'] ."' size='14'/></p>";
						if ($sel_subject['id'] != 5){
							echo "<p><label for='dimensions" . $formNumber . "' >Dimensions:</label>
								<input type='text' name='dimensions" . $formNumber . "' value='" . $page['dimensions'] ."' size='14' /></p>";
						}
						echo "<p><label for='year" . $formNumber . "'>Year:</label>
						<input type='text' name='year" . $formNumber . "' value='" . $page['year'] ."' size='14' /></p>
						<p class='borderTop'><input type='submit' name='submit" . $formNumber . "' value='Edit' />"; ?>
							
							<a class="important" href="<?php
							echo "deleteTitle.php?page=" . $page['id'] . "";
							echo "&amp;path=" . $page['img_path'] . "";
							echo "&amp;l_path=" . $page['l_img_path'] . "";
							echo "&amp;subj=" . $sel_subject['id'] . "";
							echo "&amp;sect=" . $idSection . ""?>"
							<?php 
							echo " onclick=";?>"<?php echo "return confirm('Are you sure?');"; ?>"<?php echo ">Delete " . $page['title'] . "!!</a></p>
					</div> <!-- end infoContainer -->
					<div class='captionContainer'>
						<p><span class='italic'></span><br /><span class='smaller'><br  /></span></p>
					</div> <!-- end captionContainer -->
					
					</div> <!-- end imageContainer -->
					<div class='clearFloat'></div>
					</form>";
					$formNumber ++;					
				$countTitles++;
				} //  end while ($page = mysql_fetch_array($page_by_section)) {
			
			} // END IF section type = 1
		if ($section['type'] == 2) {
			echo "<form class='borderLess' action='editSection.php?subj=" . $idSubject . "&sect=" . $idSection . "&form=1' method='post'>
						<h4><label for='" . $section['title_name'] ."'>Section Header:</label>
						<input type='text' name='title_name' value='" . $section['title_name'] ."'/>
						<label class='smaller' for='position'>position</label>
						<select name='position'>";
							for ($count=1; $count <= $section_count; $count ++){
								echo "<option value='". $count . "'";
									
								if ($count == $section['position'] ){
									echo " selected ";
								}
								echo ">" . $count . "</option>";
								
							}
						echo "</select>
					<label class='smaller' for='visible'>visible</label>
					<input name='visible' class='checkbox' "; 
					if ($section['visible'] == 1){
						echo "checked "; 
					}
			echo "type='checkbox' value='" . $section['visible'] ."' />
						</h4>";
						
					
					echo"<label for='sub_title'>Sub-title: </label>
						<textarea cols='30' rows='0' name='sub_title'>" . $section['sub_title'] . "</textarea>
						<label class='smaller' for='sub_visible'>visible</label>
						<input name='visible_sub' class='checkbox' "; 
						if ($section['visible_sub'] == 1){
							echo "checked "; 
						}
						echo "type='checkbox' value='" . $section['visible_sub'] ."' />";
					
					echo"<input type='submit' name='submitHeader' value='Edit Header' />
						</form>";
			echo "<div class='clearFloat'></div>";
			echo "<h5 class='noBorder'>Total Titles: " . $pages . "  <a class='noUnderline' href='newTitle.php?subj=" . $idSubject . "&sect=" . $idSection ."&form=1'><input  type='submit' value='Add Title'></a> </h5>";
			$page_by_section = get_pages_by_section($section['id'], $sel_subject['id']);
			$countTitles = 1;
			while ($page = mysql_fetch_array($page_by_section)) {
				if($countTitles == $pages-1){ echo "<a name='bottomEditSection'>";}
				$old_image_path[$formNumber] =  $page['img_path'];
				$l_old_image_path[$formNumber] = $page['l_img_path'];
				echo "<form enctype='multipart/form-data' class='borderLess' action='editSection.php?subj=" . $idSubject . "&sect=" . $idSection . "&form=1' method='post'>";
				if($countTitles == $pages-1){ echo "</a>";}
				echo "
						<div class='imageContainer";
						if ($page['format'] == 1){
									echo "Wide";
									}
					echo "'>
					<div class='imageFrameType2'>
						<img src='images/" . $page['img_path'] . "' />
					</div>
					
				  <table align='left' height='250' cellpadding='0' cellspacing='0'>
						<tr>
							<td height='10'>" ;							
							 if ($page['title'] == "Hoop Snake Bebop") {
								 echo "<p class='bold'>The Idea of Order at Key West</p>
										<p class='indented'>by Wallace Stevens</p>   " ;
							 }
						  echo "</td>
						</tr>
						<tr>
							<td height='100'>
							<p>
							Text:<br/>
							 <textarea rows='3' cols='45' name='content" . $formNumber . "'>" . $page['content'] . "</textarea> 
							</p>
						  </td>
						</tr>
						<tr>                
						<td class='bottomAlign'> 
							<p><input type='hidden' name='old_image" . $formNumber . "' value='" . $old_image_path[$formNumber] . "' /></p>
							<p><input type='hidden' name='l_old_image" . $formNumber . "' value='" . $l_old_image_path[$formNumber] . "' /></p>
							<p><label for='title" . $formNumber . "'>Title:</label>
							<input type='text' name='title" . $formNumber . "' value='" . $page['title'] . "'  size='20'/>
							<label for='position" . $formNumber . "'>Position</label>
						<select name='position" . $formNumber . "'>";
							for ($count=1; $count <= $pages; $count ++){
								echo "<option value='". $count . "'";
									
								if ($count == $page['position'] ){
									echo " selected ";
									}
									echo ">" . $count . "</option>";
								
								}
						echo "</select>
							<input type='hidden' name='id" . $formNumber . "' value='" . $page['id'] . "' /></p>
							<p><label for='file" . $formNumber . "'>Current Image: (" . $page['img_path'] . ") </label>
						<input type='file' name='file" . $formNumber . "' value='' id='file' size='28' /></p>";
						
							echo "<p><label for='l_file" . $formNumber . "'>";
								if (!empty($page['l_img_path'])){
									echo "Zooming Image: (" . $page['l_img_path'] . ") </label>";
									} else {
										
										echo "No Zooming Image!";
									}
								
								echo "<input type='file' name='l_file" . $formNumber . "' value='' /></p>";
						
						echo "	<p><label for='description" . $formNumber . "'>Descr:</label>
							<input type='text' name='description" . $formNumber . "' value='" . $page['description'] . "' size='5'/>	
							<label for='" . $page['dimensions'] . "'>Dimen:</label>
							<input type='text' name='dimensions" . $formNumber . "' value='" . $page['dimensions'] . "'  size='4'/>
							<label for='year" . $formNumber . "'>Year:</label>
							<input type='text' name='year" . $formNumber . "' value='" . $page['year'] . "'  size='1'/></p>
							<p class='borderTop'><input type='submit' name='submit" . $formNumber . "' value='Edit' /> "; ?>
							
							<a class="important" href="<?php
							echo "deleteTitle.php?page=" . $page['id'] . "";
							echo "&amp;path=" . $page['img_path'] . "";
							echo "&amp;l_path=" . $page['l_img_path'] . "";
							echo "&amp;subj=" . $sel_subject['id'] . "";
							echo "&amp;sect=" . $idSection . ""?>"
							<?php 
							echo " onclick=";?>"<?php echo "return confirm('Are you sure?');"; ?>"<?php echo ">Delete " . $page['title'] . "!!</a></p>
						  </td>
						</tr>
					</table>				
				</div> <!-- end imageContainer -->
				</form>
				<div class='clearFloat'></div>";
				$formNumber++;
				$countTitles++;
			} // end while ($page = mysql_fetch_array($page_by_section)) 
		} // END IF type 2
		echo "<div class='clearFloat'></div>";
       echo "<h5>Total Titles: " . $pages . "  <a class='noUnderline' href='newTitle.php?subj=" . $idSubject . "&sect=" . $idSection ."&form=1'><input  type='submit' value='Add Title'></a> </h5>";	
	   $page_by_section = get_pages_by_section($idSection, $idSubject);
	   $page = mysql_fetch_array($page_by_section);
		?>
		<div class="clearFloat"></div>
		<p><input type="submit" name="submit" value="Edit Section" />&nbsp;&nbsp;
			<a class='important' href="deleteSection.php?subj=<?php echo urlencode($sel_subject['id']); ?>&sect=<?php echo urlencode($idSection); ?>&page=<?php echo urlencode($page['id']);?>" onclick="return confirm('Are you sure?');">Delete Section <?php echo $section['title_name']; ?> !!</a></p>
				
		
		<a href="index.php?subj=<?php echo urlencode($sel_subject['id']);?>">Cancel</a>
    	
<?php include ("includes/footer.php"); ?>
