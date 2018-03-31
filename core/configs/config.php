<?php

define("ROOT_PATH","/var/www/html");

$_CONFIG = array(
	1 => array(
		"name" => "ComeBackUni",
		"mysql" => array("mysql","root","root","gigra"),
		"url" => "http://gigra.stytex.cloud",
		"forum" => "http://forum.gigra-game.de",
		"maxgala" => 6,
		"maxsys" => 500,
		"noobschutz" => 2000000,
		"noobfaktor" => 5,
        "noobspeed" => 4,
		"lang" => "de",
		"mailfrom" => "info@gigra-game.de",
		"maxuser" => 1500,
		"reg_allowed" => true,
		"reg_pw" => "gigrarefact",
        "speed_res" => 1,
        "speed_build" => 1,
        "speed_fleet" => 100,
        "sensor_cost" => 15000,
        "angriffsperre" => mktime(18,0,0,5,11,2012),
        "offline" => false,
	    "debris_faktors" => array(0.7,0.5,0.8,0.1),
        "defense_to_debris" => false,
        "start_account" => false,
        "const_old" => false,
	)
);
