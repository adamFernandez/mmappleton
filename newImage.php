<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php find_selected_page();?>
<?php include ("includes/header.php"); ?>
<?php echo navigation ($sel_subject); ?>
    <div id="main">
		<h2>Add Image to the <?php echo $sel_subject['menu_name']; ?> Accordion</h2>
		<form enctype="multipart/form-data" action="createImage.php?subj=<?php echo urlencode($sel_subject['id']); ?>&sect=<?php echo urlencode($sel_sect); ?>" method="post">
			<?php 
				if ($section['type'] == 2) {
					echo "<p><label class='marginTop5' for='content'>Text:</label>
							<textarea rows='7' cols='30' name='content' value=''></textarea></p>";
				}
			?>
			<p><label for="img_name">Image name:</label>
			<input type="text" name="img_name" value="" /></p>
			<p><label for="file">New Image:</label>
			<input type="file" name="file" value="" id="file" /></p>
			
			<p><label for="position">Position</label>
			<select name="position">
				<?php
					$accordion_set = get_all_accordion_images();
					$accordion_count = mysql_num_rows($accordion_set);
					
					$accordion = mysql_fetch_array($accordion_set);
					for ($count=1; $count <= $accordion_count + 1; $count ++){
						echo "<option value='". $count . "'";
						
						if ($count > $accordion_count ){
							echo " selected='selected'";
							}
							echo ">" . $count . "</option>";
						}
				?>
			</select></p>
			
			<p><input type="submit" value="Add Image to Accordion" /></p>
		</form>
			
		<a href="index.php">Back to Home Page</a>
		
		
<?php include ("includes/footer.php"); ?>