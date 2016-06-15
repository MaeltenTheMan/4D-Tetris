<script>
/*function rel(){
	window.location = "http://localhost/test/tetris.php";
}
setInterval(rel, 1000);*/

function sendInput(e){
	window.location = "http://localhost/test/tetris.php"+"?in="+e.keyCode;
}
window.addEventListener("keypress", sendInput, false);
</script>

<img src="pic.jpg" />

<?php

//magic numbers
define("elemSize",30);
define("width", 10);
define("height", 20);

//the blocks
define("I", array(array(0,-1),array(0,1),array(0,2)));
define("L", array(array(0,-1),array(0,1),array(1,1)));
define("iL", array(array(0,-1),array(0,1),array(-1,1)));
define("S", array(array(1,0),array(0,1),array(-1,1)));
define("Z", array(array(-1,0),array(0,1),array(1,1)));
define("Q", array(array(1,0),array(0,1),array(1,1)));
define("T", array(array(0,-1),array(-1,0),array(1,0)));
 
define("BLOCKS", array(I, L, iL, S, Z, Q, T));

//initialize
if(session_id()=="") session_start();
if(!isset($_SESSION['x'])) $_SESSION['x']=5;
if(!isset($_SESSION['y'])) $_SESSION['y']=0;
if(!isset($_SESSION['block'])) $_SESSION['block']=BLOCKS[rand(0,6)];
if(!isset($_SESSION['field'])){
	for($i=0;$i<width;$i++){
		for($q=0;$q<height;$q++){
			$arr[$i][$q]=false;
		}
	}
	$_SESSION['field']=$arr;
}

//game-state relevant variables
	//x,y,block,field,score
$x = $_SESSION['x'];
$y = $_SESSION['y'];
$block = $_SESSION['block'];
$field = $_SESSION['field'];

//move block
$y++;

//respond to player input
function isNotLegal(){
	global $x, $y, $block, $field;
	if($x<0 or $x>=width)return true;
	if($field[$x][$y])return true;
	for($i=0;$i<3;$i++){
		if($x+$block[$i][0]<0 or $x+$block[$i][0]>=width)return true;
		if($field[$x+$block[$i][0]][$y+$block[$i][1]])return true;
	}
}

if(isset($_GET['in'])){
	$in = $_GET['in'];
	if($in==100){
		$x++;				//////////////////
		if(isNotLegal())$x--;
	}
	else if($in==97){
		$x--;			//////////////////
		if(isNotLegal())$x++;
	}
	else if($in==114){
		$x=5;
		$y=0;
		$block=BLOCKS[rand(0,6)];

		for($i=0;$i<width;$i++){
			for($q=0;$q<height;$q++){
				$arr[$i][$q]=false;
			}
		}
		$field=$arr;
	}								//////////////////
	else if($in==115){
		rotRight();
		if(isNotLegal())rotLeft();
	}
	else if($in==119){
		rotLeft();
		if(isNotLegal())rotRight();
	}
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

//check if block is lying on something
function isResting(){
	global $field, $x, $y, $block;
	
	if($y>=height-1) return true;
	if($field[$x][$y+1]) return true;
	for($i=0;$i<3;$i++){
		if($y+$block[$i][1]>=height-1) return true;
		if($field[$x+$block[$i][0]][$y+$block[$i][1]+1]) return true;
	}
	return false;
}

if(isResting()){
	//make block part of field
	$field[$x][$y]=true;
	for($i=0;$i<3;$i++){
		$field[$x+$block[$i][0]][$y+$block[$i][1]]=true;
	}
	
	//remove full rows
	for($q=0;$q<height;$q++){
		//check if row is full
		$full=true;//reset
		for($i=0;$i<width;$i++){
			$full = $full && $field[$i][$q];
		}
		//remove full row, ie slide everything above full row down 1
		if($full){
			for($h=$q;$h>0;$h--){
				for($i=0;$i<width;$i++){
					$field[$i][$h]=$field[$i][$h-1];
				}
			}
			//empty uppermost row
			for($i=0;$i<width;$i++)
				$field[$i][0]=false;
		}
	}

	//spawn new block
	$y=0;
	$x=5;
	$block=BLOCKS[rand(0,6)];
	
	//check if game is done
	if(isNotLegal())echo "GAME OVER";
}

//draw graphics
//draw game field
$im = imageCreate((width+1)*elemSize,(height+1)*elemSize);
$bgColor = imageColorAllocate($im,255,230,255);
$gridColor = imageColorAllocate($im, 0, 0, 0);
$blockColor = imagecolorallocate($im, 100, 100, 250);
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
		if($field[$i][$q]){
			imagefilledrectangle($im, $i*elemSize, $q*elemSize, ($i*elemSize)+elemSize, ($q*elemSize)+elemSize, $blockColor);
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

//update $_SESSION game-state variables
$_SESSION['x'] = $x;
$_SESSION['y'] = $y;
$_SESSION['block'] = $block;
$_SESSION['field'] = $field;

?>
<!-- TODO: split into more files, is session started each time? -->

<!--
====================================
=== Feedback Alpers, 2016-06-015 ===
====================================

Bitte beachten Sie, dass bei den Projekten in diesem Semester kein JavaScript zugelassen ist.

Außerdem wirkt es so, als wenn Sie mit PHP strukturelle Elemente erzeugen. Das soll aber in HTML passieren. Einzige Ausnahme sind Fälle wie beispielsweise Tabellen, in denen Sie anhand einer verschachtelten Schleife die Zellen der Tabelle leicht generieren können. Aber auch in diesem Fällen sollen HTML-Container (z.B. <td>) ins HTML-Dokument eingefügt werden.

PHP soll dagegen nur dafür verwendet werden, um (neben der eigentlichen Funktionalität des Programms) die strukturellen Elemente zu ändern.

Der Sinn besteht darin, dass Sie zeigen, dass Sie im Stande sind, mit den einfachen Mitteln, die wir in der Veranstaltung besprochen haben ein Programm umzusetzen. Denn später werden Sie immer wieder in Situationen kommen, in denen ein Framework Ihnen nicht das bietet, was Sie haben wollen. Und dann müssen Sie eine eigene Lösung für Ihr Problem entwickeln.

Außerdem sollen Sie klar zwischen strukturellen Elemente und dynamischer Änderung dieser Elemente im Code unterscheiden. Hier geht es um die Fähigkeit, systematisch zwischen unterschiedlichen Aufgabenbereichen zu unterscheiden.
-->