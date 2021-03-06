<!DOCTYPE html>

<html lang="de">
<html>
<head>
        <title>4D-Tetris</title>
        <meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="final.css"/>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
<body>
	 <header>
				<h1>Hier wird gespielt</h1> <!--hier kann man gegebenenfalls ein Logo erstellen -->
        </header> 
		<nav>
			<ul>
				<li><a href='index.html'>Wilkommen</a></li>
				<li><li><a href=''>Game</a></li>
				<li><a href='regeln.html'>Regeln</a></li>
				<li><a href='dokumentation.html'>Dokumentation</a></li>
			</ul>
<script>
function sendInput(e){
	window.location = window.location.href.split("?")[0]+"?in="+e.keyCode;
}
window.addEventListener("keypress", sendInput, false);
</script>
<div class="tetrispic">				
			<img src="pic.jpg" />
		</div>


<?php

include 'functions.php';
include 'config.php';
include 'graphics.php';

//game-state relevant variables
	//x,y,block,field,gravity, score
$gameOver = $_SESSION['gameOver'];
$grav = $_SESSION['gravity'];
$x = $_SESSION['x'];
$y = $_SESSION['y'];
$block = $_SESSION['block'];
$field = $_SESSION['field'];
$score = $_SESSION['score'];

//check for reset
if(isset($_GET['in'])){
	if($_GET['in']==STARTNEW){
		$gameOver = false;
		$x=5;
		$y=0;
		$block=$BLOCKS[rand(0,6)];
		$score = 0;
	
		for($i=0;$i<width;$i++){
			for($q=0;$q<height;$q++){
				$arr[$i][$q]=false;
			}
		}
		$field=$arr;
	}
}

if(!$gameOver){
	header("Refresh:1; url=".explode("?","http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]")[0]);
	
	//move block
	if($grav==0)$y--;
	else if($grav==1)$x++;
	else if($grav==2)$y++;
	else $x--;

	//respond to player input
	if(isset($_GET['in'])){
		$in = $_GET['in'];
		if($in==RIGHT){
			$x++;
			if(isNotLegal())$x--;
		}
		else if($in==LEFT){
			$x--;
			if(isNotLegal())$x++;
		}
		else if($in==ROTRIGHT){
			rotRight();
			if(isNotLegal())rotLeft();
		}
		else if($in==ROTLEFT){
			rotLeft();
			if(isNotLegal())rotRight();
		}
	}

	//check if block is lying on something
	if(isResting()){
		//make block part of field
		$field[$x][$y][0]=true;
		$field[$x][$y][1]=$block[3];
		for($i=0;$i<3;$i++){
			$field[$x+$block[$i][0]][$y+$block[$i][1]][0]=true;
			$field[$x+$block[$i][0]][$y+$block[$i][1]][1]=$block[3];
		}
		
		//remove full rows & count score
		$rowRemoveCount = 0;
		for($q=0;$q<height;$q++){
			//check if row is full
			$full=true;//reset
			for($i=0;$i<width;$i++){
				$full = $full && $field[$i][$q][0];
			}
			//remove full row, ie slide everything above full row down 1
			if($full){
				$rowRemoveCount++;
				for($h=$q;$h>0;$h--){
					for($i=0;$i<width;$i++){
						$field[$i][$h][0]=$field[$i][$h-1][0];
						if(isset($field[$i][$h-1][1])) $field[$i][$h][1]=$field[$i][$h-1][1];
					}
				}
				//empty uppermost row
				for($i=0;$i<width;$i++)
					$field[$i][0][0]=false;
			}
		}
		$score += floor(pow(2,$rowRemoveCount-1));
	
		//spawn new block
		$y=spawnY;
		$x=spawnX;
		$block=$BLOCKS[rand(0,6)];
		
		//check if game is done
		if(isNotLegal())$gameOver = true;
	}

	//draw graphics
	draw();
	
	//update $_SESSION game-state variables
	$_SESSION['gameOver'] = $gameOver;
	$_SESSION['x'] = $x;
	$_SESSION['y'] = $y;
	$_SESSION['block'] = $block;
	$_SESSION['field'] = $field;
	$_SESSION['score'] = $score;
}
	
?>
</body>
</html>