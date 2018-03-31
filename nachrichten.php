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

$laTplExport = array();
$lbMsgsend = false;
$lbSendefehler = false;
$lodb = gigraDB::db_open();

//Nachrichten loeschen
if(isset($_POST['a']) && $_POST['a'] == 'd')
{
    foreach($_POST as $i => $w)
    {
        if($w == 'mark')
        {
            if(!isset($list))
                $list = "id='".$lodb->escape($i)."'";
            else
                $list .= "OR id='".$lodb->escape($i)."'";
        }
    }
    if($_POST['q'] == 1)
    {
        if($list !='')
            $list = "($list) AND";
        $qry = "DELETE FROM msg WHERE $list userid='$_SESSION[uid]'";
    }
    else if($_POST['q'] == 2)
    {
        if($list !='')
            $list = "!($list) AND";
        $qry = "DELETE FROM msg WHERE $list userid='$_SESSION[uid]'";
    }
    else if($_POST['q'] == 3)
    {
        $qry = "DELETE FROM msg WHERE userid='$_SESSION[uid]'";
    }
    else if($_POST['q'] == 4)
    {
        $qry = "DELETE FROM msg WHERE userid='$_SESSION[uid]' AND fromuid='0'";
    }
    else if($_POST['q'] == 5)
    {
           $qry = "DELETE FROM msg WHERE userid='$_SESSION[uid]' AND fromuid!='0'";
    }
    $lodb->query($qry);
}
//Nachrichten als gelesen markieren
else if(isset($_POST['a']) && $_POST['a'] == 'r')
{
    foreach($_POST as $i => $w)
    {
        if($w == 'mark')
        {
        if(!isset($list))
            $list = "id='".$lodb->escape($i)."'";
        else
            $list .= "OR id='".$lodb->escape($i)."'";
        }
    }
    if($_POST['q'] == 1)
    {
        if($list !='')
            $list = "($list) AND";
        $qry = "UPDATE msg SET red='yes' WHERE $list userid='$_SESSION[uid]'";
    }
    else if($_POST['q'] == 2)
    {
        if($list !='')
            $list = "!($list) AND";
        $qry = "UPDATE msg SET red='yes' WHERE $list userid='$_SESSION[uid]'";
    }
    else if($_POST['q'] == 3)
    {
        $qry = "UPDATE msg SET red='yes' WHERE userid='$_SESSION[uid]'";
    }
    else if($_POST['q'] == 4)
    {
        $qry = "UPDATE msg SET red='yes' WHERE userid='$_SESSION[uid]' AND fromuid='0'";
    }
    else if($_POST['q'] == 5)
    {
        $qry = "UPDATE msg SET red='yes' WHERE userid='$_SESSION[uid]' AND fromuid!='0'";
    }
    $lodb->query($qry);
}
//Nachricht absenden
else if(isset($_POST['a']) && $_POST['a'] == 'n')
{
  
  if(!send_text_msg($lodb->escape($_POST["to"]),$_SESSION["coords"],(utf8_decode($_POST['subj'])),(utf8_decode($_POST['text'])),Uid()))
  {
    $lbSendefehler = true;
  }
  else
  {
      $lbMsgsend = true;
  }
}


if( (isset($_GET['ans']) and !empty($_GET['ans'])) or (isset($_GET['to']) and !empty($_GET['to'])) )
{
    $to='';
    $empf='';
    $subj='';  
    $txtr='';
    if(isset($_GET['ans']))
    {
        $lsQry = "SELECT msg.*, users.name AS fromname FROM msg LEFT JOIN users ON (msg.fromuid = users.id) WHERE msg.userid='".$_SESSION['uid']."' AND msg.id='".$lodb->escape($_GET['ans'])."' LIMIT 1;";
    	$lodb->query($lsQry);
		if($lodb->numrows() > 0)
		{
			$row = $lodb->fetch();
			//$x = mysql_fetch_array(mysql_query("SELECT name FROM users WHERE id='".$lodb->escape($row[4])."'"));
			//$absender = $x[0];

			$txt = stripslashes($row['text']);
			$txtr = "\n\n[quote=".$row['fromname']."]\n".$txt."[/quote]\n";
			if(preg_match("/^Re:/",$row['subj']))
			{
				$subj = stripslashes($row['subj']);
			}
				else
			{
				$subj = "Re: ".stripslashes($row['subj']);
			}
			$to = $row['fromuid'];
            $empf = $row['fromname'];
		}
		else
		{
			die("Diese Nachricht existiert nicht");
		}
	}
	else if(isset($_GET['to']))
	{
		$lodb->query("SELECT name,id FROM users WHERE id='".$lodb->escape($_GET['to'])."'");
		if($lodb->numrows() > 0)
		{
			$row = $lodb->fetch();
			$to = $row['id'];
			$empf = $row['name'];
		}
	}
    $laTplExport['to'] = $to;
    $laTplExport['subj'] = htmlentities(stripslashes($subj));
    $laTplExport['txtr'] = htmlentities($txtr);
    $laTplExport['empf'] = $empf;
    $lsTplExport['sendefehler'] = $lbSendefehler;

    
    buildPage("nachrichten_schreiben.tpl", $laTplExport);    
}
else if (isset($_GET['ajax']) and $_GET['ajax'] == 1)
{
    $liUnread = getUnreadCount();
    echo '{';
    echo '"msg": "'.$liUnread.'",';
    $laRes = read_res($_SESSION['coords']);
    echo '"res1": "'.$laRes[0].'",';
    echo '"res2": "'.$laRes[1].'",';
    echo '"res3": "'.$laRes[2].'",';
    echo '"res4": "'.$laRes[3].'",';
    echo '"time": "'.time().'"';
    echo '}';
} 
else if (isset($_GET['ajax']) and !empty($_GET['ajax']))
{
    $lodb->query("UPDATE msg SET red='yes' WHERE userid='$_SESSION[uid]' AND id='".$lodb->escape($_GET['ajax'])."'");
    echo 'true';
}
else
{
    //Nachrichten anzeigen
    $idC = 0;
    $laMsgs = array();
    $lbNomsgs = false;
    $lsQry = "SELECT m.*, u.name AS fromname FROM msg m LEFT OUTER JOIN users u ON (m.fromuid = u.id) WHERE m.userid='$_SESSION[uid]' ORDER BY time DESC LIMIT 30";
    $lodb->query($lsQry);
    if ($lodb->numrows() >= 1)
    {
        while($row = $lodb->fetch())
        {
            $row['time'] = date('D, j.n.Y - H:i:s',$row['time']);
            $row['subj'] = empty($row['subj']) ? l('msg_nosubj') : htmlentities(stripslashes($row['subj']));
            if($row['mode'] == 'cmd')
            {
                $row['text'] = decode_cmd_msg(ikf2array($row['text']));
            }
            elseif($row['mode'] == 'text')
            {
                //echo bb_decode2html($row['text'])."<b>--------</b><br>";
                $row['text'] = bb_decode2html($row['text']);
            }
            $laMsgs[] = $row;
            //als gelesen makieren
            if($row['fromuid'] == '0' and $row['red'] == 'no')
            {
                if(!isset($lsList))
                {
                $lsList = "id='".$row['id']."'";
                }
                else
                {
                $lsList .= " OR id='".$row['id']."'";
                }
            }
        }
        if(isset($lsList))
            $lodb->query("UPDATE msg SET red='yes' WHERE $lsList");
    }
    else
    {
       $lbNomsgs = true;
    }
    $laTplExport['laMsgs'] = $laMsgs;
    $laTplExport['lbNomsgs'] = $lbNomsgs;
    $laTplExport['lbMsgsend'] = $lbMsgsend;
    if(isset($_GET['resbar']) and $_GET['resbar'] == 1)
    {
        echo fromTemplate("nachrichten.tpl", $laTplExport);  
    } 
    else
    {
        buildPage("nachrichten.tpl", $laTplExport);
    }
}
?>