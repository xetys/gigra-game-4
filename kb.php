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
define("GIGRA_MUSTSESSION",false);

include 'core/core.php';

$lodb = gigraDB::db_open();
$laTplExport = array();



$laKampf = $lodb->getOne("SELECT * FROM bericht LEFT JOIN planets ON toc=coords WHERE id='".$lodb->escape($_GET['id'])."'");
$laKampf['titel'] = $laKampf['titel'] == '' ? l('kb_kb') : strip_tags($laKampf['tite']);
if(isset($_GET['restore']) && strlen($_GET['restore']) > 0)
{
    $lsRestoreString = $_GET['restore'];
    
    $lsFilePath = ROOT_PATH . "/tmp/".$lsRestoreString;
    if(file_exists($lsFilePath))
    {
        $lsContent = file_get_contents($lsFilePath);
        
        $laKampf['b'] = $lsContent;
        
    }
}

$lbHide = true;
//Darf dier Aktuelle User den Bericht sehen?
if(loggedIn())
{
    $laRow = $lodb->getOne("SELECT COUNT(*) FROM bericht_recht WHERE user_id = '".Uid()."' AND bericht_id = '".$lodb->escape($_GET['id'])."'");   
    
    $lbHide = !isAdmin() && $laRow[0] == 0;
}

if(isset($_GET["export"]) && $_GET["export"] == 1)
    die("<pre><code>".var_export(unserialize($laKampf["b"]),true)."</code></pre>");

$echo = getHeader();
$echo .= showKB(unserialize($laKampf['b']),$laKampf["fromc"],$laKampf["toc"],$laKampf["time"],$lbHide,$laKampf['title']);

$laTplExport = array();
//KB kommentare - bearbeiten
$laTplExport["canEdit"] = false;
if(!$lbHide && ($laKampf['is_public'] == 0 || $laKampf['publisher'] == Uid()))
{
    $laTplExport["canEdit"] = true;
    
    if(isset($_POST['edit']))
    {
        $lsTitel = $lodb->escape($_POST["title"]);
        $lsHauptKommentar = $lodb->escape($_POST["hauptkommentar"]);
        $lodb->query("UPDATE bericht SET is_public = 1, publisher = '".Uid()."', hauptkommentar = '{$lsHauptKommentar}', title = '{$lsTitel}' WHERE id = '{$laKampf['id']}'");
        
        
        redirect("kb.php?id=".$laKampf['id']."#bottom");
        //$laKampf = $lodb->getOne("SELECT * FROM bericht LEFT JOIN planets ON toc=coords WHERE id='".$lodb->escape($_GET['id'])."'");
        
    }
    
}

$laTplExport['title'] = $laKampf['title'];
$laTplExport["is_public"] = $laKampf["is_public"] == 1;
$laTplExport["hauptkommentar"] = $laKampf["hauptkommentar"];
$laTplExport["loggedIn"] = loggedIn();

$liPage = isset($_GET["page"]) ? (int)$lodb->escape($_GET["page"]) : 1;

$liStartFrom = ($liPage - 1) * 10;// 1 -> 0, 2 -> 10, 3 -> 20 usw

///kommentare - rest
if(loggedIn() && isset($_POST['new_comment']))
{
    $lsKomName = $_SESSION["name"];
    $lsText = $lodb->escape($_POST['comment']);
    
    $lodb->query("INSERT INTO bericht_kommentar (kom_name,kom_time,kom_text,kom_bericht) VALUES('$lsKomName',UNIX_TIMESTAMP(),'$lsText','{$laKampf['id']}')");
}
$lodb->query("SELECT kom_id,kom_name,kom_time,kom_text FROM bericht_kommentar WHERE kom_bericht = '{$laKampf['id']}' ORDER BY kom_time DESC LIMIT $liStartFrom, 10");
$laComments = array();
while($laRow = $lodb->fetch("assoc"))
    $laComments[] = $laRow;
    
$laRow = $lodb->getOne("SELECT COUNT(kom_id) FROM bericht_kommentar WHERE kom_bericht = '{$laKampf['id']}'");

$laTplExport["comments"] = $laComments;
$laTplExport["page"] = $liPage;
$laTplExport["pages"] = ceil($laRow[0] / 10); // 1 => 0.1 -> 1, 6 => 0.6 -> 1, 11 => 1.1 => 2 usw

$echo .= fromTemplate("kb_footer.tpl",$laTplExport);




echo $echo;
?>