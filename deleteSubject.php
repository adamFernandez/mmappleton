<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	$id = mysql_prep($_GET['subj']);
	if ($id != 6){	
		if ($subject = get_subject_by_id($id)) {
			$contactQuery = "UPDATE subjects SET position = position -1 WHERE id = 6";
			$contactResult = mysql_query($contactQuery, $connection);
			
			$pages_set = get_pages_by_subject($id);
			while($page = mysql_fetch_array($pages_set)){
				$img_path = "../images/" . $page['img_path'];
				if(($page['l_img_path'] != 0) || ($page['l_img_path'] != "")){ 
					$l_img_path = "../images/" . $page['l_img_path'];
				}
				if(file_exists($img_path)){
					unlink($img_path);
				}
				if(file_exists($l_img_path)){
					unlink($l_img_path);
				}
			}
			$query1 = "DELETE FROM pages WHERE subject_id = '{$id}'";
			$result1 = mysql_query($query1, $connection);
			$query2  = "DELETE FROM section_titles WHERE subject_id = '{$id}'";
			$result2 = mysql_query($query2, $connection);
			$query3 = "DELETE FROM subjects WHERE id = '{$id}' LIMIT 1";
			$result3 = mysql_query($query3, $connection);
			if (mysql_affected_rows() == 1) {
			
				redirect_to("editSubject.php?subj=1&form=1");
			} else {
				// Deletion Fail
				echo "<p>Subject deletion failed.</p>";
				echo "<p>" . mysql_error() . "</p>";
				echo "<a href='index.php?subj=1'>Return to Main Page</a>";
			}
		} else {
			// subject didnt' exist in database
			redirect_to("editSubject.php?subj=1");
		}
	}
?>
<?php mysql_close($connection); ?>