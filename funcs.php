<?php

function isNotLegal(){
	global $x, $y, $block, $field;
	if($x<0 or $x>=width)return true;
	if($field[$x][$y][0])return true;
	for($i=0;$i<3;$i++){
		if($x+$block[$i][0]<0 or $x+$block[$i][0]>=width)return true;
		if($field[$x+$block[$i][0]][$y+$block[$i][1]][0])return true;
	}
}

function isResting(){
	global $field, $x, $y, $block;
	
	if($y>=height-1) return true;
	if($field[$x][$y+1][0]) return true;
	for($i=0;$i<3;$i++){
		if($y+$block[$i][1]>=height-1) return true;
		if($field[$x+$block[$i][0]][$y+$block[$i][1]+1][0]) return true;
	}
	return false;
}

function rotRight(){
	global $block;
	for($i=0;$i<3;$i++){
		$tmp = $block[$i][0];
		$block[$i][0]=-$block[$i][1];
		$block[$i][1]=$tmp;
	}
}

function rotLeft(){
	global $block;
	for($i=0;$i<3;$i++){
		$tmp=$block[$i][0];
		$block[$i][0]=$block[$i][1];
		$block[$i][1]=-$tmp;
	}
}

?>