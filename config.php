<?php
//magic numbers
define("elemSize",30);
define("width", 10);
define("height", 20);
define("spawnX", 5);
define("spawnY", 2);
//controll keycodes
define("RIGHT", '100');
define("LEFT", '97');
define("STARTNEW", '48');
define("ROTRIGHT", '119');
define("ROTLEFT", '115');

//the blocks
$im = imageCreate((width+1)*elemSize,(height+1)*elemSize);
$I = array(array(0,-1),array(0,1),array(0,2),imagecolorallocate($im, 255, 20, 12));
$L = array(array(0,-1),array(0,1),array(1,1),imagecolorallocate($im, 1, 255, 1));
$iL = array(array(0,-1),array(0,1),array(-1,1),imagecolorallocate($im, 10, 0, 255));
$S = array(array(1,0),array(0,1),array(-1,1),imagecolorallocate($im, 160, 200, 0));
$Z = array(array(-1,0),array(0,1),array(1,1),imagecolorallocate($im, 180, 200, 120));
$Q = array(array(1,0),array(0,1),array(1,1),imagecolorallocate($im, 10, 209, 255));
$T = array(array(0,-1),array(-1,0),array(1,0),imagecolorallocate($im, 210, 100, 200));
 
$BLOCKS = array($I, $L, $iL, $S, $Z, $Q, $T);

//initialize
if(session_id()=="") session_start();
if(!isset($_SESSION['gameOver'])) $_SESSION['gameOver']=false;
if(!isset($_SESSION['gravity'])) $_SESSION['gravity']=2;
if(!isset($_SESSION['x'])) $_SESSION['x']=spawnX;
if(!isset($_SESSION['y'])) $_SESSION['y']=spawnY;
if(!isset($_SESSION['block'])) $_SESSION['block']=$BLOCKS[rand(0,6)];
if(!isset($_SESSION['field'])){
	for($i=0;$i<width;$i++){
		for($q=0;$q<height;$q++){
			$arr[$i][$q][0]=false;
			$arr[$i][$q][1]=false;
		}
	}
	$_SESSION['field']=$arr;
}
?>