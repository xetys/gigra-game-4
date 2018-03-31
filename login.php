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
//Nur hier bei Login
define("LOCAL_LOGIN",false);

unset($_SESSION['runde']);
//print_r($_SESSION);
$lsCaptcha = $_SESSION['captcha'];
if(strlen($lsCaptcha)==0) {
  $lsCaptcha = "-1"; 
}


if(isset($_POST['runde']))
{
	$_RUNDE = $_POST['runde'];
}



include 'core/core.php';

//if(!LOCAL_LOGIN && $_SERVER["REQUEST_METHOD"] != "POST" and ($_GET["secret"] != 'r3f4ct2011'))
	//redirect("http://www.gigra-game.de");


$laRunden = array();
foreach ($_CONFIG as $liRunde => $laData)
	$laRunden[$liRunde] = $laData["name"];

$scripter = isset($_GET['i'])?true:false;

if(!isset($_POST['i']) && !$scripter)  //Kein Formular abgeschickt und kein Scripter.
{
  redirect("index.php");
}
if(isset($_POST['i']) || $scripter)  //Kein Forumlar abgeschickt oder Scripter
{
  if(isset($_GET['pw']))   {$_POST['pw']   = $_GET['pw'];   $scripter = true;}  //Scripter!
  if(isset($_GET['name'])) {$_POST['name'] = $_GET['name']; $scripter = true;}  //Scripter!
  $lodb = gigraDB::db_open();
  
  
  $uid = $lodb->getOne('SELECT id,pw,name FROM users WHERE name="'.$lodb->escape($_POST['name']).'"');
  if($uid == false)
  {
    $lsFehler = l('wrong_username');
    buildPage("index.tpl", array("laRunden" => $laRunden,"fehler" => $lsFehler));
  }
  /*
  else if((isset($_SESSION["must_captcha"]) && $_SESSION["must_captcha"] === true) && $lsCaptcha != $_POST['code']) {
    $lsFehler = l('wrong_code');
    buildPage("index.tpl", array("laRunden" => $laRunden,"fehler" => $lsFehler));
  }*/
  else if($uid["pw"]!=$_POST["pw"]) {
    $lsFehler = l('wrong_password');
    $_SESSION["must_captcha"] = true;
     buildPage("index.tpl", array("laRunden" => $laRunden,"fehler" => $lsFehler));
  }
  
  
    $x = $uid[0];
    $_SESSION['uid'] = $x;
    //$_SESSION['uid']  = $uid[0];
    $_SESSION['name'] = $uid[2]; 
    $x = $lodb->getOne("SELECT mainplanet,allianz,admin,active,lastclick,umod FROM users WHERE id='$_SESSION[uid]'");
    $liLastLogin = $x['lastclick'];
    
    $_SESSION['coords'] = $x[0];
    $_SESSION['mainplan'] = $x[0];
    $_SESSION['ally'] = $x[1];
    $_SESSION["runde"] = $_RUNDE;
    $_SESSION['active'] = $x[3];
    $liUmod = $x['umod'];
            $_SESSION['last_useragent'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['last_ip'] = $_SERVER['REMOTE_ADDR'];
    
    if($x[2] == 1)
    {
      $_SESSION['admin'] = true; 
    }
    unset($x);
    if($scripter)
    {
      //TODO: In DB eintragen und ganz unauffaellig tun. Spaeter sperren *hrhr*
      //echo 'Scripter!<br>';
    }
    if(!isset($_COOKIE['user']))
    {
      setcookie("user",$_SESSION['uid'],time()+60*60*24*14);
    }
    else if($_COOKIE['user'] != $_SESSION['uid'])
    {
      //TODO: Multi in DB eintragen!
     // echo "Multi!<br>"; die();
    }
    /*
    $_SESSION['einst'] = mysql_fetch_array(mysql_query("SELECT * FROM einstellungen WHERE uid='$_SESSION[uid]'"));
    $_SESSION['sp'] = ($_SESSION['einst']['skinpfad']!='')?$_SESSION['einst']['skinpfad'].'/':'';
	*/
    #wir machen mal log
    
    //PowerUP
    if($liLastLogin < (time() - (3600 * 24 * 14)) && $liUmod == 0 && $_SESSION['active'] == 1)
        powerUp(Uid());
    
    $lsSessionID = session_id();
    $lodb->query("UPDATE `users` SET `lastlogin` = '".time()."', lastsession = '$lsSessionID' WHERE `id` = '".$_SESSION['uid']."' LIMIT 1;");
    unset($_SESSION["captcha"]);
    redirect("v3.php");
}
?>
