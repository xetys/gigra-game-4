<?php

define("ROOT_PATH","/var/www/refact.gigra.stytex.de");

$_CONFIG = array(
 0 => array(
                "name" => "669. Universum[BETA] - Gigra-Game.de",
                "mysql" => array("localhost","root","GXnXyVSj","gigra_u669"),
                "url" => "http://uni669.gigra-game.de",
                "forum" => "http://forum.gigra-game.de",
                "maxgala" => 5,
                "maxsys" => 500,
                "noobschutz" => 1000000,
                "noobfaktor" => 5,
                "noobspeed" => 1,
                "lang" => "de",
                "mailfrom" => "info@gigra-game.de",
                "maxuser" => 1000,
                "reg_allowed" => true,
                "reg_pw" => "gigrarefact",
                "speed_res" => 3,
                "speed_build" => 3,
                "speed_fleet" => 3,
                "sensor_cost" => 15000,
                "angriffsperre" => mktime(18,0,0,2,25,2012),
                "offline" => false,
                "debris_faktors" => array(0.3,0.3,0.1,0),
                "defense_to_debris" => false,
                "start_account" => true,
                "uni_start" => mktime(23,59,59,12,31,2012),
        )
);

?>
