<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php 
find_selected_page();
$section_set = get_section_title_by_id($sel_sect);
$section = mysql_fetch_array($section_set);	
?>
<?php include ("includes/header.php"); ?>
<?php echo navigation ($sel_subject); ?>
    <div id="main">
		<h2>Add Section to <?php echo $sel_subject['menu_name']; ?></h2>
		
		<form action="createSection.php?subj=<?php echo urlencode($sel_subject['id']);?>" method="post">
			
			<p><label for="title_name">Name:</label>
			<input type="text" name="title_name" value="" /></p>
			<p><label for="type">Type:</label>
			<select name="type">
				<?php
					$idSection = urlencode($sel_sect);
					for ($count=1; $count <= 2 ; $count ++){
						echo "<option value='" . $count . "'
							"; if ($section['type'] == $count){
								echo " selected";
								}
							echo ">" . $count . "</option>";
						}
				?>
				</select></p>
			<p><label for="position">Position</label>
			<select name="position">
				<?php
					$section_set = get_section_title_by_subject($sel_subject['id']);
					$section_count = mysql_num_rows($section_set);
					for ($count=1; $count <= $section_count + 1; $count ++){
						echo "<option value='". $count . "'";
						if ($count > $section_count ){
							echo " selected='selected'";
							}
							echo ">" . $count . "</option>";
						
						}
				?>
			</select></p>
			<p><input type="submit" value="Add Section" /></p>
		</form>
			
		<a href="index.php?subj=<?php echo urlencode($sel_subject['id']);?>">&lt;&lt; Back to Main Page</a>
    	
<?php include ("includes/footer.php"); ?>
