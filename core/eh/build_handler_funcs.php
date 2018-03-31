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

function onBuild($uid,$coords,$bid,$blevel)
{
    $lodb = gigraDB::db_open();
    
    
    //16.August 2009, Spielerfahrung + 1
    $lodb->query("UPDATE erfahrung SET infra = infra+1 WHERE uid='{$uid}'");
}

 /**
  * Handler fuer Aktion build (Fertigstellung eines Gebaeudes).
  * @param $id int ID des Events (DB)
  * @param $coords string Koordinaten (DB)
  * @param $uid string UserID (DB)
  * @param $time_lost int Zeit, die der Handler zu spaet ausgefuehrt wird
  * @param $command string Aktion (DB)
  * @param $param Zusatzparameter (DB)
  * @param $prio Priroitaet
  * @return void
  */
function handle_build($ev_id,$coords,$uid,$time,$command,$param,$prio)
{
    $lodb = gigraDB::db_open();
    
    $x = ikf2array($param);
    foreach($x as $i => $l)
    {
      $id = (int)$i;
      $lvl = (int)$l;
      $lodb->query("UPDATE gebaeude SET k$id=k$id+1 WHERE coords='$coords'");
      
      //Event anstoßen
      onBuild($uid,$coords,$id,$lvl);
      resRecalc($coords);
    }
    
    
    
    $laSettings = getSettings($uid);
    if($laSettings["baumsg"] == 1)
        send_cmd_msg($uid,$coords,array('b' => $id, 's' => $lvl, 'x' => 1),$time);
    ehLog("Bauauftrag auf [$coords] erfolgreich abgeschlossen","green");
    
    $lodb->query("LOCK TABLES events WRITE;");
    $lodb->query("DELETE FROM events WHERE id = '{$ev_id}'");
    $lodb->query("UNLOCK TABLES;");
  }

function onForsch($uid,$coords,$bid,$blevel)
{
    $lodb = gigraDB::db_open();
    
    //16.August 2009, Spielerfahrung + 1
    $lodb->query("UPDATE erfahrung SET forsch = forsch+1 WHERE uid='{$uid}'");
    
    
    if($bid == 18)
    {
        destroyPlanet($coords);
        send_cmd_msg($uid,$coords,array('x' => 99),time());
        $lodb->query("UPDATE erfahrung SET forsch = forsch+99 WHERE uid='{$uid}'");
    }
}


/**
* Handler fuer Aktion prod (Fertigstellung einer Forschung).
* @param $id int ID des Events (DB)
* @param $coords string Koordinaten (DB)
* @param $uid string UserID (DB)
* @param $time int Zeit, zu der der Handler ausgefuehrt werden soll (DB)
* @param $command string Aktion (DB)
* @param $param Zusatzparameter (DB)
* @param $prio Priroitaet
* @return void
*/
function handle_forsch($ev_id,$coords,$uid,$time,$command,$param,$prio)
{
    $lodb = gigraDB::db_open();
    $row = $lodb->getOne("SELECT f FROM forschung WHERE uid='$uid'");
    
    $fikf = ikf2array($row[0]);
    $x = ikf2array($param);
    foreach($x as $i => $l)
    {
      $fikf['f'.$i]=$l;
      onForsch($uid,$coords,$i,$l);
      resRecalc($coords);
    }
    $lodb->query("UPDATE forschung SET f='".array2ikf($fikf)."' WHERE uid='$uid'");
    
    
    $laSettings = getSettings($uid);
    if($laSettings["baumsg"] == 1)
        send_cmd_msg($uid,$coords,array('f' => $i, 's' => $l, 'x' => 2),$time);
    
    $lodb->query("LOCK TABLES events WRITE;");
    $lodb->query("DELETE FROM events WHERE id = '{$ev_id}'");
    $lodb->query("UNLOCK TABLES;");
  
 }
 
 
/**
* Handler fuer Produktion.
* @param $id int ID des Events (DB)
* @param $coords string Koordinaten (DB)
* @param $uid string UserID (DB)
* @param $time int Zeit (DB)
* @param $command string Aktion (DB)
* @param $param Zusatzparameter (DB)
* @param $prio Priroitaet
* @return void
*/
function handle_prod($id,$coords,$uid,$time,$command,$param,$prio)
{
    $lodb = gigraDB::db_open();
    
    
    $b = new Bauliste($coords,true,$command);
    $b->from_event($coords,$time,$param,$id);
    $build = $b->run_to($time);
    $b->update_event();
    unset($b);
    if($command == 'vert') {
      $field = 'v';
      $tbl   = 'verteidigung'; 
    } else {
      $field = 's';
      $tbl   = 'schiffe'; 
    }
    $srow = $lodb->getOne("SELECT $field FROM $tbl WHERE coords='$coords'");
    $sikf = ikf2array($srow[0]); //IKF einlesen
    foreach($build as $sid => $count) //IKF aendern
    {
      $sikf[$sid]+=$count;
    }
    //IKF schreiben
    $lodb->query("UPDATE $tbl SET $field='".array2ikf($sikf)."' WHERE coords='$coords'");
    return;
}
