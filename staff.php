<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php find_selected_page(); ?>
<?php confirm_logged_in(); ?>
<?php include ("includes/header.php"); ?>
<?php if (logged_in()) {
		echo navigation($sel_subject);
		}
		if (!logged_in()){
			echo public_navigation($sel_subject);
			}
?>
    <h2 class='staffh2'>Admin Menu</h2>
	<div class='clearFloat'></div>
    <p class='staff'>Welcome to the administrator area <span class="bold"><?php echo $_SESSION['username']; ?></span>!</p>
    <div id="main"> 
    	<ul>
        	<li><a href="editSubject.php?subj=1&amp;form=1">Manage Website Content</a></li>
            <li><a href="newUser.php?subj=3&amp;form=1">Add Staff User</a></li>
            <li><a href="logOut.php">Log out</a></li>
        </ul>
    

<?php include ("includes/footer.php"); ?>
