<?php
error_reporting(0);

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
$percent = 100;
$sizeS = $size * $percent;

$img = imagecreate($sizeS,$size);

$white = imagecolorallocate($img, 255, 255, 255);
$red   = imagecolorallocate($img, 255, 0, 0);
$green = imagecolorallocate($img, 0, 255, 0);
$blue  = imagecolorallocate($img, 0, 0, 255);
$black = imagecolorallocate($img, 0, 0, 0);
$color = imagecolorallocate($img, $colors[0], $colors[1], $colors[2]);
imagecolortransparent($img,$white);

$stepsAll = 50;
for ($i = 1; $i <= $stepsAll;$i++)
	percent_cube($img, $i/($stepsAll/100), $color,$size);
function percent_cube($img,$percent,$color,$size = 250)
{
	$hyp = sqrt(2 * pow($size,2)) / 2;
	$float_percent = $percent / 100;
	$h_size = $size / 2;
	$start_x = $size * ($percent - 1);
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
		$points = array($start_x + 0,0, $start_x + $h_size,0, $start_x + $h_size,$h_size, $start_x + $x,$y, $start_x + $size,0, $start_x + $size,$size, $start_x + 0,$size);
	}
	elseif($float_percent <= 0.375)
	{ 
		$x = $size; 
		$y = $y_k;
		$points = array($start_x + 0,0, 
						$start_x + $h_size,0, 
						$start_x + $h_size,$h_size, 
						$start_x + $x,$y, 
						$start_x + $size,$size, 
						$start_x + 0,$size);
	}
	elseif($float_percent <= 0.625)
	{ 
		$x = $x_k; 
		$y = $size; 
		$points = array($start_x + 0,0, 
						$start_x + $h_size,0, 
						$start_x + $h_size,$h_size, 
						$start_x + $x,$y, 
						$start_x + 0,$size);
	}
	elseif($float_percent <= 0.875)
	{ 
		$x = 0; 
		$y = $y_k; 
		$points = array($start_x + 0,0, 
						$start_x + $h_size,0, 
						$start_x + $h_size,$h_size, 
						$start_x + $x,$y);
	}
	else 
	{
		$x = $x_k;
		$y = 0;
		$points = array($start_x + $x,0, 
						$start_x + $h_size,0, 
						$start_x + $h_size,$h_size);
	}

	$points_count = count($points) / 2;
	
	imagefilledpolygon($img,$points,$points_count,$color);
}

header ("Content-type: image/gif");
#header ("Content-type: text/plain");
#echo 'test';
ImageGif($img);
imagedestroy($img);
?>