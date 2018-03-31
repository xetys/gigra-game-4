<?php
/**
 * 
 * Gigra Refact V3
 * @copyright 2011 (c) stytex.de 
 * @author David Steiman @ stytex.de
 * 
 * 2011: This code was rewritten completely for replacing Empire Space Source 
 * with new Source based on template system an mutlilanguage solution.
 * 
 * 
 * All rights reserved to David Steiman and under the rights of stytex.de
 */
/*
define("GIGRA_INTERN", true);
define("GIGRA_MUSTSESSION",false);

include '../core/core.php';
header("Content-type:text/javascript");*/
//header('Content-type: application/javascript');

$dauer = 1;
$exp_gmt = gmdate("D, d M Y H:i:s", time() + $dauer * 60) ." GMT";
$mod_gmt = gmdate("D, d M Y H:i:s", getlastmod()) ." GMT";

header("Expires: " . $exp_gm);
header("Last-Modified: " . $mod_gmt);
header("Cache-Control: public, max-age=" . $dauer * 60);
// Speziell für MSIE 5
header("Cache-Control: pre-check=" . $dauer * 60, FALSE);


header('Content-type: text/javascript');


//readfile('jquery.js');
readfile('ajax.js');
readfile('msg.js');
readfile('jcarousel.js');
readfile('v3.js');
readfile('flotten.js');//flotten.js?no_cache=4
readfile('bau.js');
readfile('thickbox.js');
readfile('../slider/javascripts/jquery.easing.1.3.js');
readfile('../slider/javascripts/jquery.coda-slider-2.0.js');
//readfile('jquery-ui-1.9.1.custom.min.js');
?>