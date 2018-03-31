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



if(isset($_POST["selfunc"]))
{
    if($_POST["selection"] == "sel")
    {
        //$lsQuery = $_POST["func"] == "del" ? "UPDATE msg SET delby = CONCAT(delby,',','".Uid()."') WHERE msg_id = '%s';" : "UPDATE msg SET readby = CONCAT(readby,',','".Uid()."') WHERE id = '%s'";
        //$lsQuery = $_POST["func"] == "del" ? "UPDATE msg SET delby = CONCAT(delby,',','".Uid()."') WHERE msg_id = '%s';" : "INSERT INTO msgread (msg_id,user_id) SELECT msg_id,'".Uid()."' as user_id FROM msg WHERE (SELECT COUNT(*) FROM msgread mr WHERE mr.user_id = '".Uid()."' AND mr.msg_id = msg.msg_id) = 0 AND msg.msg_id = '%s'";
        switch($_POST["func"])
        {
            case "del":
                $lsQuery = "UPDATE msg SET deleted = 1 WHERE msg_id = '%s';";
                break;
            case "mar":
                $lsQuery = "UPDATE msg SET red = 'yes' WHERE msg_id = '%s';";
                break;
            case "arc":
                $lsQuery = "UPDATE msg SET ordner = 'archive' WHERE msg_id = '%s';";
                break;
            default:
                
                break;
        }
        //$lsQuery = $_POST["func"] == "del" ? "UPDATE msg SET deleted = 1 WHERE msg_id = '%s';" : "UPDATE msg SET red = 'yes' WHERE msg_id = '%s';";
        foreach($_POST['msgs'] as $liMsgId => $x)
        {
            //die(sprintf($lsQuery,$liMsgId));
            $lodb->query(sprintf($lsQuery,$liMsgId));
        }
    }
    else
    {
        $lsQuery = $_POST["func"] == "del" ? "UPDATE  msg SET deleted = 1 WHERE userid = '".Uid()."';" : "UPDATE msg SET red = 'yes' WHERE userid = '".Uid()."';";
        
        switch($_POST["func"])
        {
            case "del":
                $lsQuery = "UPDATE  msg SET deleted = 1 WHERE userid = '".Uid()."' AND ordner != 'archive';";
                break;
            case "mar":
                $lsQuery = "UPDATE msg SET red = 'yes' WHERE userid = '".Uid()."';";
                break;
            case "arc":
                //$lsQuery = "UPDATE msg SET ordner = 'archive' WHERE msg_id = '%s';";
                break;
            default:
                
                break;
        }
        
        $lodb->query($lsQuery);
    }
    unset($_SESSION['msg']);
}
$liStart = isset($_GET["start"]) && is_numeric($_GET["start"]) ? $_GET["start"] : 0;
$lsPage = isset($_GET["page"]) && in_array($_GET["page"],array("all","player","build","fleet","combat","spy","other")) ? $_GET["page"]  : null;

$laMsg = getMsgArray(10,$liStart,$lsPage);
$lsActiveTab = "all";
$liNewest = 0;
if(isset($_GET["id"]) && !empty($_GET["id"]))
{
    $laMsgItem = false;
    foreach($laMsg["sorted"]["player_msg"] as $msgItem)
        if($msgItem["msg_id"] == $_GET["id"])
            $laMsgItem = $msgItem;
    foreach($laMsg["sorted"]["archive"] as $msgItem)
        if($msgItem["msg_id"] == $_GET["id"])
            $laMsgItem = $msgItem;
    if(!$laMsgItem)
        die("no_msg");
    if(isset($_POST["reply"]))
    {
        send_text_msg($laMsgItem["fromuid"],$_SESSION['coords'],'Re:'.$laMsgItem['subj'],$_POST['text'],Uid());
        die("ok");
    }
    
    $laTplExport["composeMode"] = false;
    $laTplExport["fromuser"] = $laMsgItem["uname"];
    $laTplExport["text"] = bb_decode2html($laMsgItem["text"]);
    $laTplExport["raw"] = $laMsgItem["text"];
    $laTplExport["time"] = date("d.m.Y. H:i:s",$laMsgItem["time"]);
    $laTplExport["msg_id"] = $laMsgItem["msg_id"];
    
    //gelesen
   
    if($laMsgItem['red'] == 'no')
        $lodb->query("UPDATE msg SET red = 'yes' WHERE msg_id = '{$laMsgItem["msg_id"]}'");
    
    echo fromTemplate("msg_dialog.tpl",$laTplExport);
    die();
}
if(isset($_GET["to"]) && !empty($_GET["to"]))
{
    $laRecipient = $lodb->getOne("SELECT 1 FROM users WHERE id = '".$lodb->escape($_GET['to'])."'");
    if(!$laRecipient || count($laRecipient) == 0)
        die("error");
    
   
    if(isset($_POST["compose"]))
    {
        send_text_msg($_GET["to"],$_SESSION['coords'],'Re:'.$_POST['subj'],$_POST['text'],Uid());
        die("ok");
    }
    
    $laTplExport["composeMode"] = true;
    $laTplExport["msg_to"] = $_GET["to"];
    
    echo fromTemplate("msg_dialog.tpl",$laTplExport);
    die();
}


$laTplExport = $laMsg;
$laTplExport["start"] = $liStart;
$laTplExport["page"] = $lsPage;

$laUnread = $laMsg["unread"];


$laReadMsgs = array();
foreach($laMsg["sorted"] as $lsSortCat => $laMsgs)
{
    $lsKey = str_replace("_msg","",$lsSortCat);
    foreach($laMsgs as $msgItem)
    {
        if($msgItem["red"] == "no")
        {
            $laReadMsgs[] = $msgItem["msg_id"];
            if($msgItem["time"] > $liNewest)
            {
                $lsActiveTab = $lsKey;
                $liNewest = $msgItem['time'];
            }
        }
    }
}

$laTplExport["unread"] = $laUnread;
$laTplExport["activeTab"] = $lsPage == null ? $lsActiveTab : $lsPage;

if(count($laReadMsgs)>0)
    $lodb->query("UPDATE msg SET red = 'yes' WHERE mode = 'cmd' AND msg_id IN(".join(",",$laReadMsgs).")");
//$lodb->query("INSERT INTO msgread (msg_id,user_id) SELECT msg_id,userid FROM msg WHERE userid = '".Uid()."' AND mode='cmd'");

buildPage("msg.tpl", $laTplExport);
?>