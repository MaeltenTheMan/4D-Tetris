<?php
function draw(){
	global $im, $block, $x, $y, $field, $gameOver, $score;
	global $deathTextColor, $scoreTextColor;
		
	//draw game field
	$bgColor = imageColorAllocate($im,255,230,255);
	$gridColor = imageColorAllocate($im, 0, 0, 0);
	$blockColor = $block[3];
	imageFilledRectangle($im,0,0,(width+2)*elemSize,(height+2)*elemSize,$bgColor);
	//draw block
	imagefilledrectangle($im, ($x+1)*elemSize, ($y+1)*elemSize, ($x+2)*elemSize, ($y+2)*elemSize, $blockColor); //draw pivot element
	for($i = 0; $i < 3; $i++){
		$elemX = ($x*elemSize)+(elemSize*$block[$i][0])+elemSize;
		$elemY = ($y*elemSize)+(elemSize*$block[$i][1])+elemSize;
		imagefilledrectangle($im, $elemX, $elemY, $elemX+elemSize, $elemY+elemSize, $blockColor);
	}
	//draw field
	for($i=0;$i<=width;$i++){
		for($q=0;$q<=height;$q++){
			if($field[$i][$q][0]){
				imagefilledrectangle($im, ($i+1)*elemSize, ($q+1)*elemSize, ($i+2)*elemSize, ($q+2)*elemSize, $field[$i][$q][1]);
			}
		}		
	}
	//draw grid
	for($i=0;$i<=width*elemSize;$i+=elemSize)
		imageline($im, $i+elemSize, elemSize, $i+elemSize, (height+1)*elemSize, $gridColor);
	for($i=0;$i<=height*elemSize;$i+=elemSize)
		imageline($im, elemSize, $i+elemSize, (width+1)*elemSize, $i+elemSize, $gridColor);
	
	//draw Score	
	imagettftext($im ,20 ,0 ,15 ,25 , $scoreTextColor , "Font.ttf" , "Score: ".strval($score));
	
	//game Over screen modifications
	if($gameOver){
		$rightMargin = 10;
		imagefilter($im, IMG_FILTER_BRIGHTNESS, -150);
		imagefilter($im, IMG_FILTER_GRAYSCALE);
		imagettftext($im , 40 , 0 , $rightMargin , height*elemSize/2 , $deathTextColor , "Font.ttf" , "DU BIST GAME OVERRRR");
	}
	
	//make image
	imagejpeg($im, "pic.jpg", 100);
}
?>