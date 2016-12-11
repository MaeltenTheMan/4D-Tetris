<?php
function draw(){
	global $im, $block, $x, $y, $field;
	
	//draw game field
	$bgColor = imageColorAllocate($im,255,230,255);
	$gridColor = imageColorAllocate($im, 0, 0, 0);
	$blockColor = $block[3];
	imageFilledRectangle($im,0,0,(width+1)*elemSize,(height+1)*elemSize,$bgColor);
	//draw block
	imagefilledrectangle($im, $x*elemSize, $y*elemSize, ($x*elemSize)+elemSize, ($y*elemSize)+elemSize, $blockColor); //draw pivot element
	for($i = 0; $i < 3; $i++){
		$elemX = ($x*elemSize)+(elemSize*$block[$i][0]);
		$elemY = ($y*elemSize)+(elemSize*$block[$i][1]);
		imagefilledrectangle($im, $elemX, $elemY, $elemX+elemSize, $elemY+elemSize, $blockColor);
	}
	//draw field
	for($i=0;$i<width;$i++){
		for($q=0;$q<height;$q++){
			if($field[$i][$q][0]){
				imagefilledrectangle($im, $i*elemSize, $q*elemSize, ($i*elemSize)+elemSize, ($q*elemSize)+elemSize, $field[$i][$q][1]);
			}
		}		
	}
	//draw grid
	for($i=0;$i<=width*elemSize;$i+=elemSize)
		imageline($im, $i, 0, $i, height*elemSize, $gridColor);
	for($i=0;$i<=height*elemSize;$i+=elemSize)
		imageline($im, 0, $i, width*elemSize, $i, $gridColor);
	//make image
	imagejpeg($im, "pic.jpg", 100);
}
?>