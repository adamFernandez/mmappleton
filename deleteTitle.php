<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	if (intval($_GET['page']) == 0) {
			redirect_to("index.php?subj=1");
		}	
	$sel_subj = $_GET['subj'];	
	$id = mysql_prep($_GET['page']);
	$old_image = "../images/" . $_GET['path'];
	if($_GET['l_path'] != ""){
		$l_old_image = "../images/" . $_GET['l_path'];
	}
	$idSection = $_GET['sect'];
	
	if ($page = get_pages()) {
		$query = "DELETE FROM pages WHERE id = '{$id}' LIMIT 1";
		$result = mysql_query($query, $connection);
		if (mysql_affected_rows() == 1) {
			if(file_exists($old_image)){
				unlink($old_image);
			}
			if(file_exists($l_old_image)){
				unlink($l_old_image);
			}
			redirect_to("editSection.php?subj=" . $sel_subj . "&sect=" . $idSection . "&form=1#bottomEditSection" );
		} else {
			// Deletion Fail
			echo "<p>Subject deletion failed.</p>";
			echo "<p>" . mysql_error() . "</p>";
			echo "<a href='index.php?subj=1'>Returnt to Main Page</a>";
		}
	} else {
		// subject didnt' exist in database
			redirect_to("editSection.php?subj=" . $sel_subj . "&sect=" . $idSection . "&form=1#bottomEditSection" );
	}
?>
<?php mysql_close($connection); ?>