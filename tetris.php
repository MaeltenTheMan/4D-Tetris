<script>
function rel(){
	window.location = window.location.href.split("?")[0];
}
setInterval(rel, 1000);

function sendInput(e){
	window.location = window.location.href.split("?")[0]+"?in="+e.keyCode;
}
window.addEventListener("keypress", sendInput, false);
</script>

<img src="pic.jpg" />

<?php
include 'funcs.php';
include 'config.php';
include 'graphics.php';

//game-state relevant variables
	//x,y,block,field,gravity, score
$grav = $_SESSION['gravity'];
$x = $_SESSION['x'];
$y = $_SESSION['y'];
$block = $_SESSION['block'];
$field = $_SESSION['field'];

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
	else if($in==STARTNEW){
		$x=5;
		$y=0;
		$block=$BLOCKS[rand(0,6)];

		for($i=0;$i<width;$i++){
			for($q=0;$q<height;$q++){
				$arr[$i][$q]=false;
			}
		}
		$field=$arr;
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
	
	//remove full rows
	for($q=0;$q<height;$q++){
		//check if row is full
		$full=true;//reset
		for($i=0;$i<width;$i++){
			$full = $full && $field[$i][$q][0];
		}
		//remove full row, ie slide everything above full row down 1
		if($full){
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

	//spawn new block
	$y=spawnY;
	$x=spawnX;
	$block=$BLOCKS[rand(0,6)];
	
	//check if game is done
	if(isNotLegal())echo "GAME OVER";
}

//draw graphics
draw();

//update $_SESSION game-state variables
$_SESSION['x'] = $x;
$_SESSION['y'] = $y;
$_SESSION['block'] = $block;
$_SESSION['field'] = $field;

?>