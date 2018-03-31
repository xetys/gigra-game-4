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


$lsID = $lodb->escape($_GET['u']);
$laRow = $lodb->getOne("SELECT u.name,u.mainplanet,p.pgesamt,p.rank,a.tag,GROUP_CONCAT(r.rangname) as rang, (SELECT COUNT(*) FROM planets WHERE owner = u.id AND coords LIKE '%:1') as planetCount FROM users u LEFT JOIN v_punkte p ON u.id = p.uid LEFT JOIN allianz a ON u.allianz = a.id LEFT JOIN user_rang ur ON u.id = ur.userID LEFT JOIN rang r ON ur.rangID = r.id WHERE u.id = '$lsID' GROUP BY u.name");

if(!$laRow)
    redirect('v3.php');

$laTplExport["uid"] = $lsID;
foreach($laRow as $k => $v)
{
    if(!is_numeric($k))
        if($k == 'rang')
            $laTplExport[$k] = $v == null ? l('player') : join('<br>&amp;<br>',explode(",",$v));
        else
            $laTplExport[$k] = $v;
}

buildPage("playercard.tpl", $laTplExport);
?>