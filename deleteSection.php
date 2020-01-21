<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
		
	
	$id = mysql_prep($_GET['sect']);
	$subj = mysql_prep($_GET['subj']);
	$pages_set = get_pages_by_section($id, $subj);
	while($page = mysql_fetch_array($pages_set)){
		$img_path = "../images/" . $page['img_path'];
		if(file_exists($img_path)){
			unlink($img_path);
		}
		if(($page['l_img_path'] != 0) || ($page['l_img_path'] != "")){ 
			$l_img_path = "../images/" . $page['l_img_path'];
		}
		if(file_exists($l_img_path)){
			unlink($l_img_path);
		}
	}
	$query1 = "DELETE FROM pages WHERE section_title_id = '{$id}'";
	$result1 = mysql_query($query1, $connection);
	if ($section = get_section_by_id($id)) {
		$query2 = "DELETE FROM section_titles WHERE id = '{$id}' LIMIT 1";
		$result2 = mysql_query($query2, $connection);
		if (mysql_affected_rows() == 1) {
			redirect_to('editSubject.php?subj=' . $_GET['subj'] . '&form=1');
		} else {
			// Deletion Fail
		}
	} else {
		// subject didnt' exist in database
		redirect_to("editSubject.php?subj=1");
	}
?>
<?php mysql_close($connection); ?>