<?php
//$sourcefile = Filename of the picture into that $insertfile will be inserted. 
//$insertfile = Filename of the picture that is to be inserted into $sourcefile. 
//$targetfile = Filename of the modified picture. 
//$transition = Intensity of the transition (in percent) 
//$pos          = Position where $insertfile will be inserted in $sourcefile 
//                0 = middle 
//                1 = top left 
//                2 = top right 
//                3 = bottom right 
//                4 = bottom left 
//                5 = top middle 
//                6 = middle right 
//                7 = bottom middle 
//                8 = middle left 
// 
// 
function mergePix($sourcefile,$insertfile, $targetfile, $pos=0,$transition=50) 
{ 
    
//Get the resource id?s of the pictures 
    $insertfile_id = imagecreatefrompng($insertfile); 
    $sourcefile_id = imageCreateFromJPEG($sourcefile); 

//Get the sizes of both pix    
    $sourcefile_width=imageSX($sourcefile_id); 
    $sourcefile_height=imageSY($sourcefile_id); 
    $insertfile_width=imageSX($insertfile_id); 
    $insertfile_height=imageSY($insertfile_id); 

//middle 
    if( $pos == 0 ) 
    { 
        $dest_x = ( $sourcefile_width / 2 ) - ( $insertfile_width / 2 ); 
        $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
    } 

//top left 
    if( $pos == 1 ) 
    { 
        $dest_x = 0; 
        $dest_y = 0; 
    } 

//top right 
    if( $pos == 2 ) 
    { 
        $dest_x = $sourcefile_width - $insertfile_width; 
        $dest_y = 0; 
    } 

//bottom right 
    if( $pos == 3 ) 
    { 
        $dest_x = $sourcefile_width - $insertfile_width; 
        $dest_y = $sourcefile_height - $insertfile_height; 
    } 

//bottom left    
    if( $pos == 4 ) 
    { 
        $dest_x = 0; 
        $dest_y = $sourcefile_height - $insertfile_height; 
    } 

//top middle 
    if( $pos == 5 ) 
    { 
        $dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 ); 
        $dest_y = 0; 
    } 

//middle right 
    if( $pos == 6 ) 
    { 
        $dest_x = $sourcefile_width - $insertfile_width; 
        $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
    } 
        
//bottom middle    
    if( $pos == 7 ) 
    { 
        $dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 ); 
        $dest_y = $sourcefile_height - $insertfile_height; 
    } 

//middle left 
    if( $pos == 8 ) 
    { 
        $dest_x = 0; 
        $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
    } 
    
//The main thing : merge the two pix    
    imageCopyMerge($sourcefile_id, $insertfile_id,$dest_x,$dest_y,0,0,$insertfile_width,$insertfile_height,$transition); 

//Create a jpeg out of the modified picture 
    imagejpeg ($sourcefile_id,"$targetfile"); 
    
}

function mergePNGPix($sourcefile,$insertfile, $targetfile, $pos=0,$transition=50) 
{ 
    
//Get the resource id?s of the pictures 
    $insertfile_id = imagecreatefrompng($insertfile); 
    $sourcefile_id = imagecreatefrompng($sourcefile); 

//Get the sizes of both pix    
    $sourcefile_width=imageSX($sourcefile_id); 
    $sourcefile_height=imageSY($sourcefile_id); 
    $insertfile_width=imageSX($insertfile_id); 
    $insertfile_height=imageSY($insertfile_id); 

//middle 
    if( $pos == 0 ) 
    { 
        $dest_x = ( $sourcefile_width / 2 ) - ( $insertfile_width / 2 ); 
        $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
    } 

//top left 
    if( $pos == 1 ) 
    { 
        $dest_x = 0; 
        $dest_y = 0; 
    } 

//top right 
    if( $pos == 2 ) 
    { 
        $dest_x = $sourcefile_width - $insertfile_width; 
        $dest_y = 0; 
    } 

//bottom right 
    if( $pos == 3 ) 
    { 
        $dest_x = $sourcefile_width - $insertfile_width; 
        $dest_y = $sourcefile_height - $insertfile_height; 
    } 

//bottom left    
    if( $pos == 4 ) 
    { 
        $dest_x = 0; 
        $dest_y = $sourcefile_height - $insertfile_height; 
    } 

//top middle 
    if( $pos == 5 ) 
    { 
        $dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 ); 
        $dest_y = 0; 
    } 

//middle right 
    if( $pos == 6 ) 
    { 
        $dest_x = $sourcefile_width - $insertfile_width; 
        $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
    } 
        
//bottom middle    
    if( $pos == 7 ) 
    { 
        $dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 ); 
        $dest_y = $sourcefile_height - $insertfile_height; 
    } 

//middle left 
    if( $pos == 8 ) 
    { 
        $dest_x = 0; 
        $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
    } 
    
//The main thing : merge the two pix    
    imageCopyMerge($sourcefile_id, $insertfile_id,$dest_x,$dest_y,0,0,$insertfile_width,$insertfile_height,$transition); 

//Create a jpeg out of the modified picture 
    imagepng ($sourcefile_id,"$targetfile");   
}?>