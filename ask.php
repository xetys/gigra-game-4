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

$lodb = gigraDB::db_open();
$laTplExport = array();

//List of Questions
$laQuestion = array(
    1 => array(
        "text" => l('question_1'),
        "func" => "alert(1)",
    ),
    2 => array(
        "text" => l('question_2'),
        "func" => "location.href='allianzen.php?leave'"
    ),
    666 => array(
            "text" => l('question_mikroton'),
            "func" => "buildIt('F',18)"
        )
);
if(isset($_GET['id']) && isset($laQuestion[$_GET['id']]))
{

    $laTplExport['text'] = $laQuestion[$_GET['id']]['text'];
    $laTplExport['func'] = $laQuestion[$_GET['id']]['func'];

    echo fromTemplate("ask.tpl",$laTplExport);
}
else
    die("error");
?>