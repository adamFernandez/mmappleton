<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php  
     // INCLUDE THE BASE CLASS FILE  
     include('includes/ImageLib.Class.php');  
	if(isset($_POST['submitRotate'])){
			 $fileName =  $_POST['fileName'];
			 $oldName = $_POST['oldFileName'];
			 $name = $_POST['name'];
			rotateImage('uploads/' . $fileName , 'uploads/' . $fileName  , -90);
			$fileName = $oldName;
	}
     // UPLOAD THE FILE TO DESTINATION  
     if(isset($_POST['submit'])){  
			if(file_exists('uploads/')){
				SureRemoveDir('uploads/', true);
			}
			
			if(!empty($_POST['name'])){
				$tempName = $_FILES['file']['tmp_name'];
				$fileName = $_FILES['file']['name'];
				$dirtyName = mysql_prep($_POST['name']);
				$name = str_replace(' ', '', $dirtyName);
				list($width, $height) = getimagesize($tempName);		
				$dirRoot = 'uploads/';
				echo "tn: " . $tempName . ", fn: " . $fileName . ", dn: " . $dirtyName . ", name: " . $name . ".";
				if($width > $height){
						
						if(!file_exists($dirRoot)){
							mkdir('uploads/');
							ImageLib::getInstance()->upload('file','uploads/' . $name . '')->resize(1300,1125)->save();
							
						}else {
						ImageLib::getInstance()->upload('file','uploads/' . $name . '')->resize(1300,1125)->save();
						
						}		
				}
				if($width < $height){
					if(!file_exists($dirRoot)){
					mkdir('uploads/');
					ImageLib::getInstance()->upload('file','uploads/' . $name . '')->resize(1125,1300)->save(); 
					}else {
					
						ImageLib::getInstance()->upload('file','uploads/' . $name . '')->resize(1125,1300)->save(); 
						
						}	
				
				}
			} else {
				echo "Name is a required field!";
				}
	} // end if set submit
	 if ((isset($_POST['submitCropping']))){
		
		// FIRST CROPPING ACTION
        // Initialize the size of the output image
        $h_boundary = 254;
		$w_boundary = 188;
        $dst_w = $_POST['width'];
        $dst_h = $_POST['height'];
		$name = $_POST['name'];
		$fileName = $_POST['fileName'];
		if(file_exists('uploads/' . $name . $fileName)){
			if ($dst_w > $dst_h){
				$dst_h = $dst_h * $h_boundary / $dst_w;
				$dst_w = $h_boundary;
			}else{
				$dst_w = $dst_w * $h_boundary / $dst_h;
				$dst_h = $h_boundary;
			}
			// Initialize the quality of the output image
			$quality = 80;
			// Set the source image path
			$src_path = "uploads/" . $name . "" . $fileName . "" ;
			// Create a new image from the source image path
			$src_image = imagecreatefromjpeg($src_path);
			// Create the output image as a true color image at the specified size
			$dst_image = imagecreatetruecolor($dst_w, $dst_h);	
			// Copy and resize part of the source image with resampling to the
			// output image
			imagecopyresampled($dst_image, $src_image, 0, 0, $_POST['x'],
							   $_POST['y'], $dst_w, $dst_h, $_POST['width'],
							   $_POST['height']);	
			 //Destroy the source image
			imagedestroy($src_image);
			$croppedDir = 'uploads/';
			if(!file_exists($croppedDir)){
				mkdir('uploads/');
				imagejpeg($dst_image, $croppedDir . "" . $name . 'S.jpg');
				} else {
					imagejpeg($dst_image, $croppedDir . "" . $name .  'S.jpg');
			}
			
			// SECOND	CROPPING 	ACTION
				
			// Initialize the size of the output image
			$h_boundary = 1000;
			$w_boundary = 825;
			$dst_w = $_POST['width'];
			$dst_h = $_POST['height'];
		
			if ($dst_w > $dst_h){
				$dst_h = $dst_h * $w_boundary / $dst_w;
				$dst_w = $w_boundary;
			} else {
				$dst_w = $dst_w * $h_boundary / $dst_h;
				$dst_h = $h_boundary;
			}
			// Initialize the quality of the output image
			$quality = 80;
			// Set the source image path
			$src_path = "uploads/" . $name . "" . $fileName . "" ;
			// Create a new image from the source image path
			$src_image = imagecreatefromjpeg($src_path);
			// Create the output image as a true color image at the specified size
			$dst_image = imagecreatetruecolor($dst_w, $dst_h);	
			// Copy and resize part of the source image with resampling to the
			// output image
			imagecopyresampled($dst_image, $src_image, 0, 0, $_POST['x'],
							   $_POST['y'], $dst_w, $dst_h, $_POST['width'],
							   $_POST['height']);	
			 //Destroy the source image
			imagedestroy($src_image);
			$croppedDir = 'uploads/';
			if(!file_exists($croppedDir)){
				mkdir('uploads/');
				imagejpeg($dst_image, $croppedDir . "" . $name . 'L.jpg');
				} else {
					imagejpeg($dst_image, $croppedDir . "" . $name . 'L.jpg');
			}		
				unlink($src_path);
				
		} // end if submitCropping
		}
		if((isset($_POST['submitDownloadS'])) || (isset($_POST['submitDownloadL']))){
			$name = $_POST['fileName'];
				header('Content-Description: File Transfer');		
				header('Content-disposition: attachment; filename=' . $name . '');	
				header('Content-type: application/jpeg');
				readfile('uploads/' . $name);
				
				unlink('uploads/' . $name);
				
				$name = substr_replace($name, '',-5,5);
		}
?>  
<?php find_selected_page(); ?>
<?php include ("includes/header.php"); ?>
<?php echo navigation ($sel_subject); ?>
    <div id="main">
		<h2>Image Upload &amp; Editing</h2>
		
		<form method="post" enctype="multipart/form-data" action="uploadCrop.php?subj=4&form=1">  
          <label for="file">Select Image :</label>   
          <input type="file" name="file" size="10" />  
		  <label for="name">Name it:</label>
		  <input type="text" name="name" />
          <input type="submit" name='submit' value="Upload File" />  
     </form>  
     
<div class="spacer">
				<!-- -->
			</div><!-- .spacer -->
			<?php
				if((isset($fileName) && isset($name)) && (file_exists('uploads/' . $name . $fileName))){
				echo "
				<p>
					To Rotate an Image clockwise click the &quot;Rotate&quot; button. 
				</p>
				 <p>
					To Crop an Image make a selection and click the &quot;Crop Image&quot; button to see the result. </p>					
				
				<div class='clearFloat'></div>
				<span class='left'>
					<form class='borderLess' method='post' action='uploadCrop.php?subj=4&form=1'>
					<input type='hidden' name='fileName' value='" . $name . $fileName . "' />
					<input type='hidden' name='oldFileName' value='" . $fileName . "' />					
					<input type='hidden' name='name' value='" . $name . "' />
					<input type='submit' name='submitRotate' value='Rotate " . $name ."' />
					</form>
					</span>
					<span class='left'>
					<form class='borderLess' id='cropping' action='uploadCrop.php?subj=4&form=1&name=" . $name . "' method='post' onsubmit='return validateForm();'>
						<input id='x' name='x' type='hidden' />
						<input id='y' name='y' type='hidden' />
						<input id='width' name='width' type='hidden' />
						<input id='height' name='height' type='hidden' />
						<input type='hidden' name='name' value='" . $name . "' />						
						<input type='hidden' name='fileName' value='" . $fileName . "' />
						<input type='submit' name='submitCropping' value='Crop " . $name . "' />
					</form>
					</span>
					
					
				
				<div class='clearFloat'></div>
				<div class='clearFloat'></div>
				<div class='image-decorator'>					
					<img src='uploads/" . $name . $fileName ."'  id='example' />
					</div><!-- .image-decorator -->
			";
					
					} 
			?>
			
			<?php 
				if(isset($_GET['name'])){
					$name = $_GET['name'];
				}else{
					$name = "";
				}	
				
				if(file_exists('uploads/' . $name . 'L.jpg')){
				echo "<div class='leftWrapper'>
					<p class='small'>
					This image its being displayed half its size
					</p>
					<form method='post'  >
					<input type='hidden' name='name' value='" . $name . "' />
					<input type='hidden' name='fileName' value='" . $name . "L.jpg' />
					<input type='submit' name='submitDownloadL' value='Download_L_" . $name . "' />
					</form>
					<div class='imageDecoratorHalf' >					
					<span class='clearFloat'><img src='uploads/" . $name . "L.jpg' width='100%' /></span>
					</div><!-- .image-decorator -->
					
					
					</div><!-- end leftWrapper -->";
					
					}
				
				if(file_exists('uploads/' . $name . 'S.jpg')){
					
				echo "<div class='rightWrapper'>
					<form  class='borderLess' method='post'  >					
					<input type='hidden' name='name' value='" . $name . "' />
					<input type='hidden' name='fileName' value='" . $name . "S.jpg' />
					<input type='submit' name='submitDownloadS' value='Download_S_" . $name . "' />
					</form>
					<span class='right'><div class='image-decorator'>					
					<span class='clearFloat'><img src='uploads/" . $name . "S.jpg' /></span>
					</div><!-- .image-decorator -->
					
					</span>
					</div>";
				}
			
				
			?>
			
			<div class="clearFloat">
					<!-- -->
				</div><!-- .spacer -->
			
				

				
		<p><a href="uploadCrop.php?subj=4&form=1">Cancel</a></p>
    	
<?php include ("includes/footer.php"); ?>
