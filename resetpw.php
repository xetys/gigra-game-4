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

$_SESSION['runde'] = (isset($_GET['runde']) ? $_GET['runde'] : 1);

include 'core/core.php';




if(!isset($_POST['mail']))
{
	$lbSend = false;
	$lbError = false;
}
else
{
  $lodb = gigraDB::db_open();
  
  $mail = mysql_escape_string($_POST['mail']);
  $lodb->query("SELECT name,pw,email FROM users WHERE email='$mail'");
  
  if($lodb->numrows() == 0) {
    $lbSend = false;
    $lbError = true;
  }
  else 
  {
  	while($x = $lodb->fetch())
  	{
	    $subj = l("pwmail_subject",$_ACTCONF["name"]);
	    	
		$body = l('pwmail_text',$x['name'],$_ACTCONF["name"], $x['name'], $x['pw'],$_ACTCONF["url"],"Gigra");
	    $hdr = "From: {$_ACTCONF["mailfrom"]}\nContent-type: text/html";
	    
	    mail($x['email'],$subj,$body,$hdr);
	    $lbSend = true;
	    $lbError = false;
	  }
	}
}

buildPage("resetpw.tpl", array(
		"lbSend" => $lbSend,
		"lbError" => $lbError
));
?>
