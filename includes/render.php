<?php
	include('ImageLib.Class.php');
	 
	$image = $_GET['image'];
	$width = $_GET['width'];
	$height = $_GET['height'];
	
	ImageLib::getInstance()->image($image)->resize($width,$height)->render();
?>