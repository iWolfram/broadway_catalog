<?php 
//Let's create a re-usable function to resize images
function resizeImage($file, $folder, $newWidth, $filename) { // path/filename, output folder, new width
	list ($width, $height) = getimagesize($file);

	$imgRatio = $width/$height;

	$newHeight = $newWidth/$imgRatio;

	$thumb = imagecreatetruecolor($newWidth, $newHeight);
	$source = imagecreatefromjpeg($file);

	imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

	$newFileName = $folder.$filename;

	imagejpeg($thumb, $newFileName, 80); // source, output, quality(out of 100)

	imagedestroy($thumb);
	imagedestroy($source);
}

function createSquareImageCopy($file, $folder, $newWidth){

	$thumb_width = $newWidth;
	$thumb_height = $newWidth;// tweak this for ratio

	list($width, $height) = getimagesize($file);

	$original_aspect = $width / $height;
	$thumb_aspect = $thumb_width / $thumb_height;

	if($original_aspect >= $thumb_aspect) {
	   // If image is wider than thumbnail (in aspect ratio sense)
	   $new_height = $thumb_height;
	   $new_width = $width / ($height / $thumb_height);
	} else {
	   // If the thumbnail is wider than the image
	   $new_width = $thumb_width;
	   $new_height = $height / ($width / $thumb_width);
	}

	$source = imagecreatefromjpeg($file);
	$thumb = imagecreatetruecolor($thumb_width, $thumb_height);

	// Resize and crop
	imagecopyresampled($thumb,
					   $source,0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
					   0 - ($new_height - $thumb_height) / 2, // Center the image vertically
					   0, 0,
					   $new_width, $new_height,
					   $width, $height);
	
	$newFileName = $folder. "/" .basename($file);
	imagejpeg($thumb, $newFileName, 80);

	imagedestroy($thumb);
	imagedestroy($source);
}

function resizePNGImage($file, $folder, $newWidth, $filename) { // path/filename, output folder, new width
	list ($width, $height) = getimagesize($file);

	$imgRatio = $width/$height;

	$newHeight = $newWidth/$imgRatio;

	$thumb = imagecreatetruecolor($newWidth, $newHeight);
	$source = imagecreatefrompng($file);

	imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

	$newFileName = $folder.$filename;

	imagepng($thumb, $newFileName, 9); // source, output, quality(out of 100)

	imagedestroy($thumb);
	imagedestroy($source);
}

function createSquarePNGImageCopy($file, $folder, $newWidth){

	$thumb_width = $newWidth;
	$thumb_height = $newWidth;// tweak this for ratio

	list($width, $height) = getimagesize($file);

	$original_aspect = $width / $height;
	$thumb_aspect = $thumb_width / $thumb_height;

	if($original_aspect >= $thumb_aspect) {
	   // If image is wider than thumbnail (in aspect ratio sense)
	   $new_height = $thumb_height;
	   $new_width = $width / ($height / $thumb_height);
	} else {
	   // If the thumbnail is wider than the image
	   $new_width = $thumb_width;
	   $new_height = $height / ($width / $thumb_width);
	}

	$source = imagecreatefrompng($file);
	$thumb = imagecreatetruecolor($thumb_width, $thumb_height);

	// Resize and crop
	imagecopyresampled($thumb,
					   $source,0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
					   0 - ($new_height - $thumb_height) / 2, // Center the image vertically
					   0, 0,
					   $new_width, $new_height,
					   $width, $height);
	
	$newFileName = $folder. "/" .basename($file);
	imagepng($thumb, $newFileName, 9);

	imagedestroy($thumb);
	imagedestroy($source);
}?>