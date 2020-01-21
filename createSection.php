<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php
	$errors = array();
	
	// Form Validation
	$fields = array('title_name', 'position','type');
	foreach ($fields as $field_name) {
		if (!isset($_POST[$field_name]) || empty($_POST[$field_name])) {
			$errors[] = $field_name;
		}
	}
	$fields_with_lengths = array('title_name' => 50);
	foreach($fields_with_lengths as $fieldname => $maxlength ) {
		if (strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength ) {
			$errors[] = $fieldname; 
			}
	}
	if (!empty($errors)) {
		redirect_to("newSection.php?subj=" . $_GET['subj']);
		}	

	$subject_id = mysql_prep($_GET['subj']);
	$title_name = mysql_prep($_POST['title_name']);
	$position = mysql_prep($_POST['position']);
	$type = mysql_prep($_POST['type']);
 
	$query = "INSERT INTO section_titles (
			subject_id, title_name, position, type
			) VALUES (
				'{$subject_id}','{$title_name}', {$position},'{$type}')";
	$result = mysql_query($query, $connection); 
	if ($result){
		// Success!
		redirect_to("editSubject.php?subj=" . $subject_id . "&form=1");
		
	} else {
		// Display error message
		echo "<p>Section creation failed.</p>";
		echo "<p>" . mysql_error() . "</p>";
		}
	
?>
<?php mysql_close($connection); ?>