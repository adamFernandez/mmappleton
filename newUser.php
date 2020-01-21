<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php	
	include("includes/form_functions.php");
	$username = "";
	$password = "";
	$message = "";
	if (isset($_POST['submit'])) { // Form has been submitted.
		$errors = array();
		// perform validations on the form data
		$required_fields = array();
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));
		$fields_with_lengths = array('username' => 30, 'password' => 30);
		$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths, $_POST));
		$username = trim(mysql_prep($_POST['username']));
		$password = trim(mysql_prep($_POST['password']));
		$hashed_password = sha1($password);
		$query = "SELECT * FROM users";
		$users_set = mysql_query($query, $connection);
		while ($users = mysql_fetch_array($users_set)){
			if($users['username'] == $username){
			$message .=  "The user already exist!";
			$username = "";
			$password = "";
			}
		}
		if ( (empty($errors) ) && (empty($message))) {
			$query = "INSERT INTO users (
							username, hashed_password
						) VALUES (
							'{$username}', '{$hashed_password}'
						)";
			$result = mysql_query($query, $connection);
			if ($result) {
				$message .= "The user was successfully created.";
				redirect_to("staff.php?subj=3");
			} else {
				$message .= "The user could not be created.";
				$message .= "<br />" . mysql_error();
			}
		} else { // no empty errors!!
			if (count($errors) == 1) {
				$message .= "There was 1 error in the form.";
			} elseif (count($errors) > 1) {
				$message .= "There were " . count($errors) . " errors in the form.";
			}
		}
	} else { // Form has not been submitted.
		$username = "";
		$password = "";
	}
?>		
<?php find_selected_page(); ?>
<?php include ("includes/header.php"); ?>
<?php echo navigation ($sel_subject); ?>
    <div id="main">
				
		<h2>Create New User</h2>
		<?php if (!empty($message)) {echo "<p>" . $message . "</p>";} ?>
			<?php if (!empty($errors)) { display_errors($errors); } ?>
			<form action="newUser.php?subj=3" method="post">
			<p><label for="username">Username:</label>
			<input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>"/></p>
			<p><label for="password">Password:</label>
			<input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>"/></p>
			<p><input type="submit" name="submit" value="Create" /></p>
		</form>
			
		<a href="index.php?subj=1">Back to Main Page</a>
    	
<?php include ("includes/footer.php"); ?>
