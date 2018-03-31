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
date_default_timezone_set('Europe/Berlin');

define("GG_VERSION","4.7.0");

session_start();
if(!defined("GIGRA_INTERN"))
	die("antihack");
include 'configs/config.php';

if(isset($_RUNDE))
{
    $_SESSION["runde"] = $_RUNDE;
}
else
{
    if(isset($_SESSION["runde"]))
        $_RUNDE = $_SESSION["runde"];
    else
    {
        foreach($_CONFIG as $liRunde => $laConfig)
            if(strpos($laConfig['url'],$_SERVER['SERVER_NAME']) !== false)
                $_RUNDE = $liRunde;
        if(!isset($_RUNDE))
            $_RUNDE = 1;
        
        $_SESSION["runde"] = $_RUNDE;
    }
}




$_ACTCONF = $_CONFIG[$_RUNDE];

include_once ROOT_PATH . '/lang/'.$_ACTCONF['lang'] . ".lang.php";


include 'classes/gigraMC.class.php';
include 'classes/gigraDB.class.php';
include 'classes/templatesystem.class.php';
include 'classes/bauliste.class.php';
include 'classes/tconfig.class.php';

//Kamlfsysten
include_once 'c_combat/combat.php';

include_once 'funcs/system_funcs.php';
include_once 'funcs/lang_funcs.php';
include_once 'funcs/ikf_funcs.php';
if($_ACTCONF['const_old'])
    include_once 'configs/const.php';
else
    include_once 'configs/const_neu.php';
include_once 'funcs/bbcode_funcs.php';
include_once 'funcs/bonus_funcs.php';
include_once 'funcs/build_funcs.php';
include_once 'funcs/diplomatie_funcs.php';
include_once 'funcs/event_funcs.php';
include_once 'funcs/flotten_funcs.php';
include_once 'funcs/formel_funcs.php';
//include_once 'funcs/kampf_funcs.php';
include_once 'funcs/modules_funcs.php';
include_once 'funcs/mail_funcs.php';
include_once 'funcs/msg_funcs.php';
include_once 'funcs/planet_funcs.php';
include_once 'funcs/prod_funcs.php';
include_once 'funcs/random_funcs.php';
include_once 'funcs/res_funcs.php';
include_once 'funcs/tr_funcs.php';
include_once 'funcs/user_funcs.php';
include_once 'funcs/usermode_funcs.php';
include_once 'funcs/v3_funcs.php';

if(!defined("HANDLER_MODE"))
{
    if(defined("GIGRA_MUSTSESSION") && GIGRA_MUSTSESSION && !defined("GIGRA_NODB"))
    {
        if(!loggedIn())
    	    redirect("login.php");	
        else
        {
            onPageLoad();
        }
    }
}



?>