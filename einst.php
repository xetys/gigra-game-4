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
//Daten holen
$laError = array();
$lbSuccess = false;
$laRow = $lodb->getOne("SELECT name as uname,email,umod,lastmailchange,lastnamechange, pw FROM users WHERE id = '{$_SESSION['uid']}'");
$laSettings = getSettings(Uid());
$lbBauMsgOn = $laSettings["baumsg"] == "1";
$liSpioAnz = $laSettings["spioanz"];
//Daten verarbeiten
if(count($_POST) > 0)
{
    if($_POST["uname"] != $laRow["uname"])
    {
        //Check PW
        if($_POST["old_pw"] != $laRow['pw'])
            $laError[] = l("einst_error_submit_with_pw");
        else
        {
            if (strlen($_POST['uname'])<3)
            {
                array_push($laError,l('reg_min_3_chars'));
            }
            else if (strlen($_POST['uname'])>30)
            {
                array_push($laError,l('reg_max_chars'));
            }
            elseif (preg_match("/[^a-zA-Z0-9\-_]/",$_POST['uname']))
            {
                array_push($laError,l('reg_username_valid'));
            }
            elseif ($lodb->getOne('SELECT name FROM users WHERE name=\''.$lodb->escape($_POST['uname']).'\';'))
            {
                array_push($laError,l('reg_user_exists'));
            }
            else if($laRow['lastnamechange'] + (3600 * 24 * 7) > time())
                array_push($laError,l('eins_error_namechange_7_days'));
            else
            {
                changeNick(Uid(),$_POST["uname"]);
                $lbSuccess = true;
            }
        }
    }
    
    if($_POST['email'] != $laRow['email'])
    {
        //Check PW
        if($_POST["old_pw"] != $laRow['pw'])
            $laError[] = l("einst_error_submit_with_pw");
        else
        {
            if($lodb->getOne("SELECT 1 FROM users WHERE email = '{$_POST['email']}'"))
                $laError[] = l('reg_mail_exists');
            elseif (preg_match("/[\n\r\t]/",$_POST['email']))
            {
                array_push($laError,l('reg_invalid_email'));
            }
            elseif (!preg_match("/^.+\@.+\..+$/",$_POST['email']))
            {
                array_push($laError,l('reg_invalid_email'));
            }
            else if($laRow['lastnamechange'] + (3600 * 24 * 7) > time())
                array_push($laError,l('eins_error_namechange_7_days'));
            else
            {
                changeMail(Uid(),$_POST["email"]);   
                $lbSuccess = true;
            }
        }
    }
    if(strlen($_POST['pw1']) > 0)
    {
            //Check PW
        if($_POST["old_pw"] != $laRow['pw'])
            $laError[] = l("einst_error_submit_with_pw");
        else
        {
            if($_POST['pw1'] != $_POST['pw2'])
                $laError[] = l("einst_error_pw_nomatch");
            else
            {
                $lodb->query("UPDATE users SET pw = '".$lodb->escape($_POST['pw1'])."' WHERE id = '".Uid()."'");
                $lbSuccess = true;
            }
        }
    }
    //Spieler ist NICHT im UMOD, und will in den Umod
    if(isset($_POST["umod"]) && canGoUmod(Uid()) && !checkUMOD(Uid()))
    {
        $lodb->query("UPDATE users SET umod = UNIX_TIMESTAMP() WHERE id = '".Uid()."'");
        //Runter mit der Produ!
        allMinesDown(Uid());
        $lbSuccess = true;
    }
    //anders rum, ist in umod und will raus
    if(!isset($_POST['umod']) && checkUMOD(Uid()) && canLeaveUmod(Uid()))
    {
        $lodb->query("UPDATE users SET umod = 0 WHERE id = '".Uid()."'");
        $lbSuccess = true;
    }
    
    //traurig traurig, spieler will nicht mehr
    if(isInDeletion(Uid()) == 0 && isset($_POST["accdel"]))
    {
        //Check PW
        if($_POST["old_pw"] != $laRow['pw'])
            $laError[] = l("einst_error_submit_with_pw");
        else
        {
            setDeletion(Uid());
            $lbSuccess = true;
        }
    }
    
    //Juhu du bleibst ja doch!
    if(isInDeletion(Uid()) > 0 && !isset($_POST["accdel"]))
    {
        unsetDeletion(Uid());
        $lbSuccess = true;
    }
    
    //BauNachrichten
    if(isset($_POST["baumsg"]) && !$lbBauMsgOn)
    {
        $lbBauMsgOn = true;
        $lodb->query("UPDATE einstellungen SET baumsg = 1 WHERE uid = '".Uid()."'");
    }
    else if(!isset($_POST["baumsg"]) && $lbBauMsgOn)
    {
        $lbBauMsgOn = false;
        $lodb->query("UPDATE einstellungen SET baumsg = 0 WHERE uid = '".Uid()."'");
    }
    
    //Spioanzahl
    if(isset($_POST["spioanz"]) && $_POST["spioanz"] != $liSpioAnz && $_POST["spioanz"] > 0)
    {
        $liSpioAnz = $_POST['spioanz'];
        $lodb->query("UPDATE einstellungen SET spioanz = ".$lodb->escape($_POST['spioanz'])." WHERE uid = '".Uid()."'");
    }
        
}


$laRow = $lodb->getOne("SELECT name as uname,email,umod,lastmailchange,lastnamechange FROM users WHERE id = '{$_SESSION['uid']}'");




$laTplExport = $laRow;
$laTplExport["bauMsgOn"] = $lbBauMsgOn;
$laTplExport["spioanz"] = $liSpioAnz;
$laTplExport['canUmod'] = canGoUmod(Uid());
$laTplExport['umodOn'] = checkUMOD(Uid());
$laTplExport["delOn"] = isInDeletion(Uid()) > 0;

if(checkUMOD(Uid()) && !canLeaveUmod(Uid()))
{
    $laRow = $lodb->getOne("SELECT umod FROM users WHERE id = '" . Uid() . "'");
    $laTplExport['umodUntil'] = $laRow[0] + (3600 * 48);
}
if(count($laError) > 0)
{
    $laTplExport["ERROR"] = join("<br>",$laError);
}
if($lbSuccess)
{
    $laTplExport["SUCCESS"] = l('eins_success');
    $lodb->query("UPDATE users SET active = 1 WHERE id = '".Uid()."'");
    $_SESSION['active'] = 1;
}
buildPage("einst.tpl", $laTplExport);
?>