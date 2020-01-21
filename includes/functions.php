<?php
	function mysql_prep($value) {
		$magic_quotes_active = get_magic_quotes_gpc();
		$new_enough_php = function_exists( "mysql_real_escape_string"); // i.e PHP >= v4.3.0
		
		if( $new_enough_php ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysql_real_escape_string can do the job
			
			if( $magic_quotes_active ) {
				$value = stripslashes( $value );
			}
				$value = mysql_real_escape_string( $value );
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			
			if ( !$magic_quotes_active ) {
				$value = addslashes( $value ); 
			}
			// if magic quotes are active, then the slashes already exist
		}
		return $value;
	}
	
	function redirect_to($location) {
		if ($location != NULL) {
			header("Location:{$location}");
			exit;
		}
	}
	function confirm_query($result_set) {
		if (!$result_set) {
				die("Database query failed: " . mysql_error());
			}
	}
	
	function get_all_subjects() {
		global $connection;
		$query = "SELECT *
					FROM subjects 
					ORDER BY position ASC";
		$subject_set = mysql_query($query, $connection);
		confirm_query($subject_set);
		return $subject_set;
	}
	function get_all_sections($subject_id) {
		global $connection;
		$query = "SELECT *
					FROM section_titles
					WHERE subject_id =" . $subject_id . " 
					ORDER BY position ASC";
		$subject_set = mysql_query($query, $connection);
		confirm_query($subject_set);
		return $subject_set;
	}
	
	function get_subject_by_id($subject_id) {
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM subjects ";
		$query .= "WHERE id=" . $subject_id . " ";
		$query .= " LIMIT 1";
		
		$result_set = mysql_query($query, $connection);
		confirm_query($result_set);
		// if no rows are returned, false will be returned
		if ($subject= mysql_fetch_array($result_set)) {
			return $subject;
		} else {
			return NULL;
		}
	}
	function get_accordion_by_id($accordion_id) {
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM accordion ";
		$query .= "WHERE id=" . $accordion_id . " ";
		$query .= " LIMIT 1";
		
		$result_set = mysql_query($query, $connection);
		confirm_query($result_set);
		// if no rows are returned, false will be returned
		if ($accordion= mysql_fetch_array($result_set)) {
			return $accordion;
		} else {
			return NULL;
		}
	}
	function get_section_by_id($section_id) {
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM section_titles ";
		$query .= "WHERE id=" . $section_id . " ";
		$query .= " LIMIT 1";
		
		$result_set = mysql_query($query, $connection);
		confirm_query($result_set);
		// if no rows are returned, false will be returned
		if ($section= mysql_fetch_array($result_set)) {
			return $section;
		} else {
			return NULL;
		}
	}
	
	function get_page_by_id($page_id){
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE id=" . $page_id;
		
		$result_set = mysql_query($query, $connection);
		confirm_query($result_set);
		// if no rows are returned, false will be returned
		if ($page= mysql_fetch_array($result_set)) {
			return $page;
		} else {
			return NULL;
		}
	}
	
	function get_pages_by_subject($subject_id){
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE subject_id=" . $subject_id;		
		$result_set = mysql_query($query, $connection);
		confirm_query($result_set);
		return $result_set;
	}
	function get_l_path_for_page($page_id){
		global $connection;
		$query = "SELECT l_img_path ";
		$query .= "FROM pages ";
		$query .= "WHERE id =" . $page_id;
		$result_set = mysql_query($query, $connection);
		confirm_query($result_set);
		return $result_set;
	}	
	function get_contact_page(){
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM contact ";
		$result_set = mysql_query($query, $connection);
		confirm_query($result_set);
		return $result_set;
	}	
	function get_pages() {
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "ORDER BY position ASC";
		$result_set = mysql_query($query, $connection);
		confirm_query($result_set);
		return $result_set;
	}	
	
	function get_pages_by_section($section_id, $subject_id) {
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE section_title_id=" . $section_id;
		$query .= " AND subject_id =" . $subject_id;
		$query .= " ORDER BY position ASC";
		$result_set = mysql_query($query, $connection);
		
		return $result_set;
	}
	function get_1_image_by_section($section_id,$subject_id) {
	global $connection;
		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE section_title_id=" . $section_id;
		$query .= " AND subject_id =" . $subject_id;
		$query .= " LIMIT 1;";
		$result_set = mysql_query($query, $connection);
		
		return $result_set;
	}
	function get_section_title_by_subject($subject_id) {
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM section_titles ";
		$query .= "WHERE subject_id=" . $subject_id;
		$query .=  " ORDER BY position ASC";
		$result_set = mysql_query($query, $connection);
		confirm_query($result_set);
		return $result_set;
	}	
	function get_section_title_by_id($section_id) {
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM section_titles ";
		$query .= "WHERE id=" . $section_id;
		//$query .=  " ";
		$result_set = mysql_query($query, $connection);
		confirm_query($result_set);
		return $result_set;
	}	

	function get_all_accordion_images(){
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM accordion ";
		$query .= " ORDER BY position ASC";
		$result_set = mysql_query($query, $connection);
		return $result_set;
	}
	function download(){
	
				$name = $_POST['fileName'];
				header('Content-Description: File Transfer');			
				header('Content-Type: application/octet-stream');
				header('Content-disposition: attachment; filename=' . $name . '');	
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-type: application/jpeg');
				readfile('uploads/' . $name); 
				unlink('uploads/' . $name);
				imagedestroy('uploads/' . $name) ;
				!file_exists('uploads/'. $name) == TRUE;
				$fileName = substr_replace($name, '',-5,5);
				redirect_to('uploadCrop.php?subj=4&form=1&name=' . $fileName);
	}
	function SureRemoveDir($dir, $DeleteMe) {
		if(!$dh = @opendir($dir)) return;
			while (false !== ($obj = readdir($dh))) {
			if($obj=='.' || $obj=='..') continue;
			if (!@unlink($dir.'/'.$obj)) SureRemoveDir($dir.'/'.$obj, true);
		}
		 
		closedir($dh);
		if ($DeleteMe){
		@rmdir($dir);
		}
	}
	function find_selected_page() {
		global $sel_subject;
		global $sel_subj;
		global $sel_sect;
		global $page;
		global $section;
		if (isset($_GET['subj'])){
			$sel_subj = $_GET['subj'];
			} else {
				$sel_subj = 1;
				}	
		if (isset($_GET['sect'])){
			$sel_sect = $_GET['sect'];
			} else {
				$sel_sect = 1;
				}
		$sel_subject = get_subject_by_id($sel_subj);
		$page = get_pages_by_subject($sel_subj);
		$sectionPages = get_pages_by_section($sel_sect, $sel_subj);
		$section = get_section_title_by_subject($sel_subj);
		$all_sections = get_all_sections($sel_subj);
		
	}
	
	function navigation($sel_subject, $public = false) {
		
		global $sel_subj;
		$output = "<div id='nav'>
			<ul>";			
			$subject_set = get_all_subjects();			
			while($subject = mysql_fetch_array($subject_set)) {
				$output .= "<li"; 
					if ($subject['id'] == $sel_subj){
						$output .= " class='selected'";
						}
					$output .= "><a href='editSubject.php?subj=" . urlencode($subject['id']) . "&form=1'"; 
					$output .= ">" . $subject['menu_name']; 
					$output .= "</a></li>";
			}
			$output .= "<li";
						if ($sel_subj == 4) { 
							$output .= " class='selected'";
							}
						$output .= "><a href='uploadCrop.php?subj=4&form=1'>image</a></li>
									<li><a href='newSubject.php?subj=3&form=1'>++Add Menu</a></li>
						
			
						<div id='admin'><li>  <a class='logIn' href='logOut.php'>logOut</a></li>
						<li><span class='logIn'>/</span></li><li";
						if ($sel_subj == 3) { 
							$output .= " class='selectedAdmin'";
							}
						$output .= "><a class='logIn' href='staff.php?subj=3&form=1'>admin</a></li>
						</div>
						</ul></div> <!-- end div nav -->";		
		return $output;
	}
	
	function public_navigation ($sel_subject, $public = true) {
		
		$output = "";
		find_selected_page();
		global $sel_subj;
		global $sel_sect;					
		$output .= "<div id='nav'>
			<ul>";			
			$subject_set = get_all_subjects();			
			while($subject = mysql_fetch_array($subject_set)) {
				$output .= "<li"; 
					if ($subject['id'] == $sel_subj){
						$output .= " class='selected'";
						}				
					$output .= "><a href='index.php?subj=" . urlencode($subject['id']); 
					$output .= "'>" . $subject['menu_name']; 
					$output .= "</a></li>";
			}	
			$output .= "<div id='admin'><li";
						if ($sel_subj == 3) { 
							$output .= " class='selectedAdmin'>";
							} else {
								$output .= ">";
								}
						$output .= "<a class='logIn' href='logIn.php?subj=3'>admin</a>
						</li></div> <!-- end div admin -->
						</ul></div> <!-- end div nav -->";
			
		return $output;
	}
	function rotateImage($sourceFile,$destImageName,$degreeOfRotation)
{
  //function to rotate an image in PHP
  //developed by Roshan Bhattara (http://roshanbh.com.np)

  //get the detail of the image
  $imageinfo=getimagesize($sourceFile);
  switch($imageinfo['mime'])
  {
   //create the image according to the content type
   case "image/jpg":
   case "image/jpeg":
   case "image/pjpeg": //for IE
        $src_img=imagecreatefromjpeg("$sourceFile");
                break;
    case "image/gif":
        $src_img = imagecreatefromgif("$sourceFile");
                break;
    case "image/png":
        case "image/x-png": //for IE
        $src_img = imagecreatefrompng("$sourceFile");
                break;
  }
  //rotate the image according to the spcified degree
  $src_img = imagerotate($src_img, $degreeOfRotation, 0);
  //output the image to a file
  imagejpeg ($src_img,$destImageName);
	}
?>