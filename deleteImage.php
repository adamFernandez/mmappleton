<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	if (intval($_GET['subj']) == 0) {
			redirect_to("editAccordion.php?subj=" . $_GET['subj']);
		}	
		
	$id = mysql_prep($_GET['acc']);
	$old_image = "images/" . $_GET['path'];
	
	if ($accordion = get_accordion_by_id($id)) {
		$query = "DELETE FROM accordion WHERE id = '{$id}' LIMIT 1";
		$result = mysql_query($query, $connection);
		if (mysql_affected_rows() == 1) {
			unlink($old_image);
			redirect_to("editAccordion.php?subj=" . $_GET['subj'] . "&form=1");
		} else {
			// Deletion Fail
			echo "<p>Image deletion failed.</p>";
			echo "<p>" . mysql_error() . "</p>";
			echo "<a href='index.php?subj=" . $_GET['subj'] . "'>Return to Main Page</a>";
		}
	} else {
		// subject didnt' exist in database
		redirect_to("index.php?subj=" . $_GET['subj']);
	}
?>
<?php mysql_close($connection); ?>