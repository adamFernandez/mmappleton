<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php require_once("includes/form_displaying_processing.php"); ?>
<?php find_selected_page(); ?>
<?php include ("includes/header.php"); ?>
<?php echo public_navigation ($sel_subject); ?>
    <div id="main">
<?php
		if(($sel_subject['id'] == 1) || ($sel_subject['id'] == 6)){
		} else {
?>
    <h2 class="expanded"><?php echo $sel_subject['menu_name']; ?></h2>    
<?php
	}
?>
	<div class='borderBottom'></div>
<?php
		if ($sel_subject['id'] == 1 ){
			echo "<div class='wrap1'>
				<div class='accordion'>";
					
			$accordion_set = get_all_accordion_images();
			while ($accordion = mysql_fetch_array($accordion_set)){
				echo "<div>
						<img src='accordion_images/" . $accordion['img_path'] . "'/> <!-- " . $accordion['img_name'] . " -->
					</div>";
				
			}	
			echo "</div></div>";
			
		}
		$section_set = get_section_title_by_subject($sel_subject['id']);
		$image_count = 0;
?>
<?php 
		
		while ($section = mysql_fetch_array($section_set)) {
			if ($section['visible'] != 0){
				$titleName = str_replace(" ", "", $section['title_name']);
					echo "<a name='" . $titleName . "'><h4 "; if (($section['id'] == 3) || ($section['id'] == 6)){echo "class='noBorder'";}
					echo ">" . $section['title_name'] . "</h4></a>";
					if($section['visible_sub'] != 0){
					echo "<p class='noMarginTop'>" . $section['sub_title'] . "</p>";
					}
			}	
			if($section['id'] == 6){
				echo "<p class='noMarginTopSeeingThings'>
        	...many things conspired/ to tell me the whole story./ Not only did they touch me,/ or my hand touched them:/ they were/ so close/ that they were a part/ of my being,/ they were so alive with me/ that they lived half my life/ and will die half my death.</p>
		
        	
         
            <div class='fullWidth'>
            <div class='rightMiddle'>
           
			           <p><span class='italic'>Ode to things</span> (fragment)<br /> Pablo Neruda, translated by Ken Krabbenhoft</p>
			
            </div><div class='clearFloat'></div>
         </div>";
				}
			if ($section['type'] == 1) {	
				ifType1($section,$page,$sel_subject);
			} // END IF section type = 1
			if ($section['type'] == 2) {
				ifType2($section,$page,$sel_subject);
			} // END IF type 2
        
		} // END while $section = mysql_fetch_array($sect)
?>
<?php
			if ($sel_subject['id'] == 6) {
			$contact_set = get_contact_page();
				$contact = mysql_fetch_array($contact_set);
			echo "<div class='wrap1'>
					<div class='imageContainerContact'>
						<div class='imageFrame'>
							<img src='images/" . $contact['img_path'] . "' width='200' height='254'  />
							<p class='italic'>" . $contact['caption'] . "</p>
					   </div>
					   <div class='infoContact'>
						  <h3>M M Appleton </h3><br />
						  <h3>" . nl2br($contact['address']) . "</h3><br />
						  <h3>" . $contact['phone'] ."</h3>
<h3 class='peque'><a href='mailto:appleton.studio.613@gmail.com'>" . $contact['email'] . "</a> </h3>    
							
						  </br><a href='https://twitter.com/appletonstudio' class='twitter-follow-button' data-show-count='false' data-size='large'>Follow @appletonstudio</a></h3>
						  </br class='prices'><p>Prices available on request</p>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='//platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>
						
					   </div> <!-- end infoContainer -->
						 
						<div class='clearFloat'></div>
					</div><!-- end imageContainerContact -->
					</div><!-- end wrap1 -->";
			}
?>		
<?php
			if ($sel_subject['id'] == 8) {
				echo "<p>Welcome to the staff area.</p>
					<ul class='submenu'>
						<li><a href='editSubject.php?subj=1'>Manage Website Content</a></li>
						<li><a href='new_user.php'>Add Staff User</a></li>
						<li><a href='logout.php'>Log out</a></li>
					</ul>";
				}
?>
<?php if (($sel_subject['id'] == 1) || ($sel_subject['id'] == 6)){
			echo "<div class='fotterBorderTop'></div>";
			}
?>
<?php include ("includes/footer.php"); ?>