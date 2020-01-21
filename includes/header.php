<?php
if (!isset($_GET['subj'])){
redirect_to("index.php?subj=1");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>mmappleton - home</title>
<?php
find_selected_page();
	if (isset($_GET['form'])) {
		echo "<link href='css/ifFormIsSet.css' rel='stylesheet' type='text/css' />";
		} elseif ((isset($_GET['subj'])) && (!isset($_GET['form']))) {
			echo "<link href='css/mmappleton_main.css' rel='stylesheet' type='text/css' />";
		}
		
	if ($sel_subject['id'] == 2) {
	}
?>
<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>
<script type="text/javascript" src="js/zoomonclick.js" ></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="js/featuredimagezoomer.js">
/***********************************************
* Featured Image Zoomer (w/ adjustable power)- By Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/
</script>


  <script>
$(document).ready(function(){

	// hide #back-top first
	$("#back-top").hide();
	
	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});

});
</script>
<?php 
	if ($_GET['subj'] == 4) {
		echo "<link href='demo/style.css' media='screen' rel='stylesheet' type='text/css' />
		<link href='demo/resources/js/imageCrop/jquery.imagecrop.css' media='screen' rel='stylesheet' type='text/css' />
		<script src='demo/resources/js/jquery-1.6.2.min.js' type='text/javascript'></script>
		<script src='demo/resources/js/imageCrop/jquery.imagecrop.min.js' type='text/javascript'></script>
		<script type='text/javascript'>
			$(document).ready(function() {
				$('img#example').imageCrop({
					displayPreview : true,
					displaySize : true,
					overlayOpacity : 0.25,

					onSelect : updateForm
				});
			});

			var selectionExists;

			// Update form inputs
			function updateForm(crop) {
				$('input#x').val(crop.selectionX);
				$('input#y').val(crop.selectionY);
				$('input#width').val(crop.selectionWidth);
				$('input#height').val(crop.selectionHeight);

				selectionExists = crop.selectionExists();
			};

			// Validate form data
			function validateForm() {
				if (selectionExists)
					return true;

				alert('Please make a selection first!');

				return false;
			};
		</script>";
	}	
?>
<?php	
	if ($sel_subject['id'] == 1){
		echo "<link href='css/style.css' rel='stylesheet' type='text/css' />
<link href='css/accordion.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='js/jquery-1.4.3.min.js'></script>
<script type='text/javascript' src='js/jquery.gridAccordion.min.js'></script>

    
<script type='text/javascript'>
	
	$(document).ready(function(){
		$('.accordion').gridAccordion({width:700, height:450, columns:5, distance:2, closedPanelWidth:5, closedPanelHeight:5, alignType:			'centerCenter', slideshow:true,
		panelProperties:{
			
		}});
	});
	</script>";
		}
?>
</head>

<body id="">
	
	<div id="wholePage">	
<?php
	echo "<div class='wrap2'>";
	$section_set = get_section_title_by_subject($sel_subj);	
	if (($sel_subj == 2)  && (!isset($_GET['form']))){
		echo "<h2 class='leftNav'>" . $sel_subject['menu_name'] . "</h2>
					
					<div class='stack'>			
					
					<ul>";
						while ($section = mysql_fetch_array($section_set)) {
							$titleName = str_replace(" ", "", $section['title_name']);
							if ($section['visible'] != 0){
								echo "<li><a href='#" . $titleName . "'><span>" . $section['title_name'] . "</span></a></li>";							
							}
						}						
					echo "</ul>
					
					</div>
					<div class='image-decorator'>
					<p class='padding10pxlr'>Hover on the images to see in detail.</p>
					</div> ";
					
		
		
					}
					if (($sel_subject['id'] != 1) && ($sel_subject['id'] != 6) && (!isset($_GET['form']))){
						echo "<p id='back-top'>
								<a href='#top'><span></span></a>
								</p>";
					}
		echo "</div>";// end_wrap2
		
?>
    <h1>M M APPLETON</h1>