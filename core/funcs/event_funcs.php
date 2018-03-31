<?php
if(!isset($INC['event']))
{
  $INC['event'] = true;
  function event_add($command,$param,$time,$prio,$coords=-1,$uid=-1)
  {
      $lodb = gigraDB::db_open();
    if($coords == -1)
    $coords = $_SESSION['coords'];
    if($uid    == -1)
    $uid    = $_SESSION['uid'];
    //id,coords,uid,time,command,param,prio
    $lodb->query("INSERT INTO events SET coords='$coords',uid='$uid',starttime=UNIX_TIMESTAMP(),time='$time',command='$command',param='$param',prio='$prio'");
  }
}
?>