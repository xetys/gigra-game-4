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

$_RUNDE = isset($_POST['runde']) ? $_POST['runde'] : 1;

include 'core/core.php';


$glAPI = false;



$laRunden = array();
foreach ($_CONFIG as $liRunde => $laData)
	$laRunden[$liRunde] = $laData["name"];

if(!isset($_POST['i']))
{

	$lbSendForm = false;
	$lbRegPW = false;
	$laSaveForm = array();
}
else
{
	
  $lbSendForm = true;
  $lodb = gigraDB::db_open();

  
  $laFehler=array();
  $pw_by_reg = false;
  if (strlen($_POST['ln'])<3)
  {
    array_push($laFehler,l('reg_min_3_chars'));
  }
  else if (strlen($_POST['ln'])>30)
  {
    array_push($laFehler,l('reg_max_chars'));
  }
  elseif (preg_match("/[^a-zA-Z0-9\-_]/",$_POST['ln']))
  {
    array_push($laFehler,l('reg_username_valid'));
  }
  elseif ($lodb->getOne('SELECT name FROM users WHERE name=\''.$_POST['ln'].'\';'))
  {
    array_push($laFehler,l('reg_user_exists'));
  }
  if (strtolower($_POST['m1'])!=strtolower($_POST['m2']))
  {
    array_push($laFehler,l('reg_emails_not_match'));
  }
  elseif (preg_match("/[\n\r\t]/",$_POST['m1']))
  {
    array_push($laFehler,l('reg_invalid_email'));
  }
  elseif($lodb->getOne("SELECT 1 FROM users WHERE email = '{$_POST['m1']}'"))
    array_push($laFehler,l('reg_mail_exists'));
  elseif (!preg_match("/^.+\@.+\..+$/",$_POST['m1']))
  {
    array_push($laFehler,l('reg_invalid_email'));
  }
  if (strlen($_POST['pname'])<2)
  {
    array_push($laFehler,l('reg_planet_min_2_chars'));
  }
  elseif (strlen($_POST['pname'])>20)
  {
    array_push($laFehler,l('reg_planet_max_20_chars'));
  }
  elseif (preg_match("/[^a-zA-Z0-9\-_]/",$_POST['pname']))
  {
    array_push($laFehler,l('reg_planet_valid'));
  }

  if (!isset($_POST['agb']))
  {
    array_push($laFehler,l('reg_accept_termin'));
  }
  $count = $lodb->getOne("SELECT COUNT(id) FROM users");
  
  if($count[0] >= $_ACTCONF["maxuser"]) {
    $laFehler[] = l('reg_max_user',$_ACTCONF['maxuser']);
  } 
  //Closed Universe
  if(!$_ACTCONF["reg_allowed"] && $_ACTCONF["reg_pw"] == "")
  {
  	array_push($laFehler,l('reg_closed'));
  }
  if(!$_ACTCONF["reg_allowed"] && $_ACTCONF["reg_pw"] != "" && $_POST['regpw'] != $_ACTCONF["reg_pw"])
  {
  	array_push($laFehler,l('reg_closed_pw'));
  	$pw_by_reg = true;
  }
  if($_ACTCONF["uni_start"] > time() && $_POST['regpw'] != $_ACTCONF["reg_pw"])
  {
    array_push($laFehler,l('reg_closed_starts_on',date("d.m.Y",$_ACTCONF["uni_start"]),date("H:i:s",$_ACTCONF["uni_start"])));
    $pw_by_reg = true;   
  }
  
  if (count($laFehler) > 0)
  {
  	$lsFehler = implode("<br>-",$laFehler);
  	$lbSendForm = true;
    
	if($pw_by_reg)
	{
		$lbRegPW = true;
		$laSaveForm = $_POST;
	}
  }
  else 
  {
		do
		{
		  $lsID = genrs(5);
		  $lodb->query("SELECT id FROM users WHERE id='$lsID'");
		}
		while($lodb->numrows() > 0);
		  
		$lsPassword = genrs(6);
	  
		$coords = createPlanet($lsID);
		
		//User hinzufuegen
		unset($lodb);
		$lodb = gigraDB::db_open();
		
		$lodb->query("INSERT INTO users SET id='$lsID',name='$_POST[ln]',pw='$lsPassword',email='$_POST[m1]',allianz='',mainplanet='$coords'");
		$lodb->query("INSERT INTO user_punkte SET uid='$lsID'");
				  
		$lodb->query("INSERT INTO forschung SET uid='$lsID',f='',punkte=0");
		$lodb->query("INSERT INTO erfahrung SET uid='$lsID',infra=0,krieg=0,forsch=0,ehrenpunkte=0");
		$lodb->query("INSERT INTO einstellungen SET uid='$lsID'");
		$lodb->query("UPDATE planets SET pname='$_POST[pname]' WHERE coords = '$coords'");
        
        //22.01.2012 Werber eintragen
        if(isset($_POST["werber"]))
        {
            $laRow = $lodb->getOne("SELECT id FROM users WHERE id = '".$lodb->escape($_POST['werber']) ."'");   
            if($laRow[0] == $_POST['werber'])
                $lodb->query("UPDATE users SET werberid = '{$laRow[0]}' WHERE id = '$lsID'");
        }
	  
	
	  	  	
	    $subj = l('regmail_subject',$_ACTCONF['name']);
	    $body = l('regmail_text',$_POST[ln],$_ACTCONF['name'],$_POST[ln],$lsPassword,$_ACTCONF["url"]);
	    
		$hdr = "From: {$_ACTCONF['mailfrom']}\n";
		$hdr .= "Content-type: text/html;\n";
		mail($_POST['m1'],$subj,$body,$hdr);
		
	
		//Versuche foren anmeldung �ber http://www.gigra-game.de/forum/gigrareg.php?from_gigra=yes&username=test&pw=test&email=test@testtest.de
		$decodeString = base64_encode(serialize(array("from_gigra" => "yes", "username" => $_POST[ln], "pw" => $lsPassword, "email" => $_POST[m1])));
	  	$sForumLink = "http://www.gigra-game.de/forum/gigrareg.php?s=$decodeString";
	  	#die($sForumLink);
	  	$ret = @file_get_contents($sForumLink);
	  	if($ret == "success")
	  		send_cmd_msg($lsID, $coords, array("x" => 100,"username" => $_POST[ln], "pw" => $lsPassword, "email" => $_POST[m1]),time());
              
        //Direktes registrieren
        $x = $lsID;
        $_SESSION['uid'] = $x;
        //$_SESSION['uid']  = $uid[0];
        $_SESSION['name'] = $_POST['ln']; 
        $x = $lodb->getOne("SELECT mainplanet,allianz,admin FROM users WHERE id='$_SESSION[uid]'");
        $_SESSION['coords'] = $x[0];
        $_SESSION['mainplan'] = $x[0];
        $_SESSION['ally'] = $x[1];
        $_SESSION["runde"] = $_RUNDE;
        $_SESSION["active"] = 0;
        $_SESSION['last_useragent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['last_ip'] = $_SERVER['REMOTE_ADDR'];
        
        //Beta Einstellung
        if(isset($_ACTCONF["start_account"]) && $_ACTCONF['start_account'])
        {
            //Wir starten mitten drin!
            $lsCoords = $x[0];
            
            $lodb->query("UPDATE gebaeude SET k1=15,k2=15, k3=25, k4=23, k5=25, k6=10, k7=5, k8=28, k9=15, k10=15, k11=15, k12=15, k13=15, k14=18, k18=5 WHERE coords = '{$lsCoords}'");
            $lodb->query("UPDATE planets SET temp = 150 WHERE coords = '{$lsCoords}'");
            
            $lodb->query("UPDATE forschung SET f = 'f1=5, f2=5, f3=8, f5=8, f6=12, f7=10, f8=10, f9=6, f10=5, f14=1, f15=8' WHERE uid = '".Uid()."'");
            
            $lodb->query("UPDATE erfahrung SET infra = 244, forsch = 78 WHERE uid = '".Uid()."'");
            
            resRecalc($lsCoords);
        }
        
        redirect('v3.php');
	  	
	}
 }
 buildPage("register.tpl", array(
 	"lbSendForm" => $lbSendForm,
 	"lsFehler" => $lsFehler,
 	"laSaveForm" => $laSaveForm,
 	"lbRegPW" => $lbRegPW,
 	"laRunden" => $laRunden
 ))
?>
