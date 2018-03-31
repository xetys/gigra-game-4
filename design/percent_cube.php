<?php

$percent = (float)$_GET['percent'];
if(!is_float($percent) && !is_int($percent))
	die("Wrong Percent Parameter");
if(isset($_GET['color']))
{
	$hexes = str_split(trim($_GET['color'],"#"),2);
	foreach ($hexes as $k => $v) $hexes[$k] = hexdec($v);
	$colors = $hexes;
}
else 
{
	$colors = array(0,0,0);
}
$size = isset($_GET['size']) ? $_GET['size'] : 250;

$img = imagecreate($size,$size);

$white = imagecolorallocate($img, 255, 255, 255);
$red   = imagecolorallocate($img, 255, 0, 0);
$green = imagecolorallocate($img, 0, 255, 0);
$blue  = imagecolorallocate($img, 0, 0, 255);
$black = imagecolorallocate($img, 0, 0, 0);
$color = imagecolorallocate($img, $colors[0], $colors[1], $colors[2]);
imagecolortransparent($img,$white);

percent_cube(& $img, $percent, $color,$size);
function percent_cube($img,$percent,$color,$size = 250)
{
	$hyp = sqrt(2 * pow($size,2)) / 2;
	$float_percent = $percent / 100;
	$h_size = $size / 2;
	$w_percent = 1 - $float_percent;
	$winkel = pi() * 2 * (0.5 + $w_percent);
	$x = sin($winkel);
	$y = cos($winkel);
	$x_k = ($h_size + ($x*($hyp)));
	$y_k = ($h_size + ($y*($hyp)));


	if($float_percent <= 0.125)
	{ 
		$x = $x_k;
		$y = 0;
		$points = array(0,0, $h_size,0, $h_size,$h_size, $x,$y, $size,0, $size,$size, 0,$size);
	}
	elseif($float_percent <= 0.375)
	{ 
		$x = $size; 
		$y = $y_k;
		$points = array(0,0, $h_size,0, $h_size,$h_size, $x,$y, $size,$size, 0,$size);
	}
	elseif($float_percent <= 0.625)
	{ 
		$x = $x_k; 
		$y = $size; 
		$points = array(0,0, $h_size,0, $h_size,$h_size, $x,$y, 0,$size);
	}
	elseif($float_percent <= 0.875)
	{ 
		$x = 0; 
		$y = $y_k; 
		$points = array(0,0, $h_size,0, $h_size,$h_size, $x,$y);
	}
	else 
	{
		$x = $x_k;
		$y = 0;
		$points = array($x,y, $h_size,0, $h_size,$h_size);
	}

	$points_count = count($points) / 2;
	
	imagefilledpolygon(& $img,$points,$points_count,$color);
}

header ("Content-type: image/gif");
ImageGif($img);
imagedestroy($img);
?>