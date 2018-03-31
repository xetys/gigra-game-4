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

define("GIGRA_INTERN", true);
define("GIGRA_MUSTSESSION",true);

include 'core/core.php';
$mode_ajax = true;

$typ = "B";
if(isset($_GET["type"]) && in_array($_GET["type"],array("B","F","S","V")))
    $typ = $_GET["type"];

if(isset($_GET["mode_forsch"]) && $_GET["mode_forsch"] == 1)
    $mode_forsch = true;

$mode_forsch = !isset($mode_forsch) ? false : $mode_forsch;
switch($typ)
{
    case "B":
    default :
        {
            echo showGebs(false,true);
            break;   
        }
    case "F":
        {
            echo showForsch();
            break;   
        }
    case "S":
        {
            echo showSchiffe(false);
            break;
        }

    case "V":
        {
            echo showSchiffe(true);
            break;
        }
}

?>