<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php find_selected_page();?>
<?php include ("includes/header.php"); ?>
<?php 
	$sel_sect = $_GET['sect'];
	$section_set = get_section_title_by_id($_GET['sect']);
	$section =  mysql_fetch_array($section_set) ;
?>
<?php echo navigation ($sel_subject); ?>
    <div id="main">
		<h2>Add Title to: <?php echo $section['title_name']; ?></h2>
		<form enctype="multipart/form-data" action="createTitle.php?subj=<?php echo urlencode($sel_subject['id']);?>&sect=<?php echo urlencode($sel_sect); ?>" method="post">
			<?php 
				if ($section['type'] == 2) {
					echo "<p><label class='marginTop5' for='content'>Text:</label>
							<textarea rows='7' cols='30' name='content' value=''></textarea></p>";
				}
			?>
			<p><label for="title">Title name:</label>
			<input type="text" name="title" value="" id="title" /></p>
			<p><label for="file">Image:</label>
			<input type="file" name="file" value="" id="file" /><span class="smaller"> </span></p>
		
	   
			<p><label for="file">Zooming Image:</label>
			<input type="file" name="l_file" value="" id="file" /><span class="smaller"> </span></p>			
			<p><label for="format">Format:</label>
			<select name="format">
				<?php
					for ($count=0; $count <= 1 ; $count ++){
						echo "<option value='" . $count . "'
							"; if ($count == 0){
								echo " selected";
								}
							echo ">" . $count . "</option>";
						}
				?>
				</select><span class="smaller"> (F0 = Normal ; F1 = Wide)</span> </p>
			
			<p><label for="position">Position</label>
			<select name="position">
				<?php
					$sel_sect = $_GET['sect'];
					$page_set = get_pages_by_section($sel_sect,  $sel_subject['id']);
					$pages_count = mysql_num_rows($page_set);
					for ($count=1; $count <= $pages_count + 1; $count ++){
						echo "<option value='". $count . "'";						
						if ($count > $pages_count  ){
							echo " selected";
							}
							echo ">" . $count . "</option>";
					}
		
				?>
			</select></p>
			<p><span class='smaller'><label for="description">Description:</label></span>
						<input type="text" name="description" value="" id="description" /></p>	
						<?php 
							if ($sel_subject['id'] != 5){
								echo "<p><span class='smaller'><label for='dimensions'>Dimensions:</label></span>
										<input type='text' name='dimensions' value='' id='dimensions' /></p>";
							}
						?>
						<p><span class='smaller'><label for="year">Year:</label></span>
						<input type="text" name="year" value="" /></p>
			<p><input type="submit" value="Add Title" /></p>
		</form>
			
		<a href="index.php?subj=<?php echo urlencode($sel_subject['id']);?>">Cancel</a>
		
		
<?php include ("includes/footer.php"); ?>