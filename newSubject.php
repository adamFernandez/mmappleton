<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php find_selected_page(); ?>
<?php include ("includes/header.php"); ?>
<?php echo navigation ($sel_subject); ?>
    <div id="main">
		<h2>Add Subject</h2>
		
		<form action="createSubject.php" method="post">
			<p><label for="menu_name">Subject name:</label>
			<input type="text" name="menu_name" value="" id="menu_name" /></p>
			<p><label for="position">Position</label>
			<select name="position">
				<?php
					$subject_set = get_all_subjects();
					$subject_count = mysql_num_rows($subject_set);
					for ($count=1; $count <= $subject_count  ; $count ++){
						echo "<option value='". $subject_count . "'";
						if ($count = $subject_count  ){
							echo " selected='selected'";
							}
							echo ">" . $count . "</option>";
						}
				?>
			</select></p>
			<p><input type="submit" value="Add Subject" /></p>
		</form>
			
		<a href="index.php">Cancel</a>
    	
<?php include ("includes/footer.php"); ?>
