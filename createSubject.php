<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	$errors = array();
	
	// Form Validation
	$fields = array('menu_name', 'position');
	foreach ($fields as $field_name) {
		if (!isset($_POST[$field_name]) || empty($_POST[$field_name])) {
			$errors[] = $field_name;
		}
	}
	$fields_with_lengths = array('menu_name' => 50);
	foreach($fields_with_lengths as $fieldname => $maxlength ) {
		if (strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength ) {
			$errors[] = $fieldname; 
			}
	}
	if (!empty($errors)) {
		redirect_to("newSubject.php");
		}	

	$menu_name = mysql_prep($_POST['menu_name']);
	$position = mysql_prep($_POST['position']);

	$contactQuery = "UPDATE subjects SET
						position =	position + 1
					WHERE id = 6";
	$resultContact = mysql_query($contactQuery, $connection);
	$query = "INSERT INTO subjects (
			menu_name, position
			) VALUES (
				'{$menu_name}', '{$position}')";
	$result = mysql_query($query, $connection);
	$id = mysql_insert_id();
	if ($result){
		// Success!
		redirect_to("editSubject.php?subj=" . $id . "&form=1");
		
	} else {
		// Display error message
		echo "<p>Subject creation failed.</p>";
		echo "<p>" . mysql_error() . "</p>";
		}
	
?>
<?php mysql_close($connection); ?>