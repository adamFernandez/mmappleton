<?php
/*************************************************************************************************************
 * Class  		: ImageLib (Singleton)
 * 
 * Version		: 1.0.0
 * 
 * Author 		: Rahul Kate ( rahul@rahulkate.com | Www.RahulKate.Com )
 *  
 * Created on 	: 27 Aug 2011
 * 
 * Description	: A PHP / GD library class to upload, resize and trim Images.
 * 				  This is just an initial draft. Yet to come is Error Handling and Internationalization.
 *  
 * Copyrights	: Please use it freely as and where you like. 
 * 				  The Author does not provide any gurantee or warrranty of any sorts.
 *************************************************************************************************************/
class ImageLib{
	
	private static $instance;
	
	private $img;
	
	private $width;
	private $height;
	private $type;
	private $ext;
	private $trim;
	
	private $newWidth;
	private $newHeight; 
	
	private function __construct(){
		$this->trim = false;
	}
	
	public static function getInstance($chain = true){
		if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
	}
	
	public function upload($name, $dest, $unify = false){
		
		// GET THE UPLOADED TEMP NAME
		$tName = $_FILES[$name]['tmp_name'];
		
		// GET THE ORIGNALE FILE NAME
		$fName = $_FILES[$name]['name'];
	
		// GET THE EXTENSION IN LOWER CASE
		$this->ext = strtolower(end(explode('.',$fName)));
		
		// GENERATE A UNIQUE NAME AND APPEND EXTENSION TO IT
		if($unify) $nName = uniqid().'.'.$this->ext;
		else $nName = $fName;
		
		// SET THE PATH AND FILE WHERE TO MOVE 
		$dFile = $dest . $nName;
		
		if(move_uploaded_file($tName, $dFile)){
			return $this->image($dFile);
		}else
			return false;
	}
	
	public function image($file){
		
		// CHECK IF THE FILE EXISTS
		if(!file_exists($file)) return false;
		
		// ASSIGN THE FILE NAME / AND PATH TO THE VARIABLE
		$this->img = $file;
		
		$this->ext = strtolower(end(explode('.',$this->img)));
		
		// GET THE EXISTING WIDTH AND HEIGHT
		list($this->width, $this->height, $this->type) = getimagesize($this->img);
		
		return self::$instance;
	}
	
	public function resize($nWidth, $nHeight, $scale = true, $trim = false){
		
		if($this->width < $nWidth && $this->height<$nHeight){
			$this->newWidth = $this->width;
			$this->newHeight = $this->height;
			return self::$instance;
		}
		
		if($scale == false || $trim == true){
			
			$this->newWidth = $nWidth;
			$this->newHeight = $nHeight;
			
			if($trim) $this->trim = true;
			return self::$instance;
		}
		
		if($this->width > $this->height) {
			$this->newWidth = $nWidth;
			$this->newHeight = round(($nWidth / $this->width) * $this->height);
		}else{
			$this->newHeight = $nHeight;
			$this->newWidth = round(($nHeight / $this->height) * $this->width);
		}
		return self::$instance;
	}
	
	public function save($path = false){
		
		$image = imagecreatetruecolor ($this->newWidth, $this->newHeight);
		$background = imagecolorallocate($image, 255, 255, 255);
		imagefill($image, 0, 0, $background);
		
		if($this->ext == 'jpg')
			$src = imagecreatefromjpeg($this->img);
		elseif($this->ext == 'gif')
			$src = imagecreatefromgif($this->img);
		elseif($this->ext == 'png')
			$src = imagecreatefrompng($this->img);
		
		if($this->trim){
			if($this->width > $this->height){
				$srcX = abs(round(($this->height - $this->width) / 2));
				$srcY = 0;
				$srcW = $this->width - ($srcX*2);
				$srcH = $this->height;
			}else{
				$srcX = 0;
				$srcY = abs(round(($this->width - $this->height) / 2));
				$srcW = $this->width;
				$srcH = $this->height - ($srcY*2);
			}
			imagecopyresampled($image, $src, 0, 0, $srcX, $srcY, $this->newWidth, $this->newHeight, $srcW, $srcH);
		}else
			imagecopyresampled($image, $src, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $this->width, $this->height);
		
		$saveTo = ($path==false)?$this->img:trim($path);

		// SAVE IMAGE BY EXTENSION
		if($this->ext == 'jpg')
			imagejpeg($image, $saveTo, 100);
		elseif($this->ext == 'gif')
			imagegif($image, $saveTo);
		elseif($this->ext == 'png')
			imagepng($image, $saveTo, 0, null);
		
		imagedestroy($image);
		imagedestroy($src);
		
		return end(explode('/',$this->img));
	}
	
	public function render(){
				
		$image = imagecreatetruecolor ($this->newWidth, $this->newHeight);
		$background = imagecolorallocate($image, 255, 255, 255);
		imagefill($image, 0, 0, $background);
		
		if($this->ext == 'jpg')
			$src = imagecreatefromjpeg($this->img);
		elseif($this->ext == 'gif')
			$src = imagecreatefromgif($this->img);
		elseif($this->ext == 'png')
			$src = imagecreatefrompng($this->img);
		
		if($this->trim){
			if($this->width > $this->height){
				$srcX = abs(round(($this->height - $this->width) / 2));
				$srcY = 0;
				$srcW = $this->width - ($srcX*2);
				$srcH = $this->height;
			}else{
				$srcX = 0;
				$srcY = abs(round(($this->width - $this->height) / 2));
				$srcW = $this->width;
				$srcH = $this->height - ($srcY*2);
			}
			imagecopyresampled($image, $src, 0, 0, $srcX, $srcY, $this->newWidth, $this->newHeight, $srcW, $srcH);
		}else
			imagecopyresampled($image, $src, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $this->width, $this->height);
		
		// POPULATE THE HEADER
		header('Content-type: '.image_type_to_mime_type($this->type));
		
		// OUTPUT IMAGE BY EXTENSION
		if($this->ext == 'jpg'){
			imagejpeg($image, null, 100);
		}elseif($this->ext == 'gif'){
			imagegif($image, null);
		}elseif($this->ext == 'png'){
			imagepng($image, null, 0, null);
		}
		
		imagedestroy($image);
		imagedestroy($src);
	}
}