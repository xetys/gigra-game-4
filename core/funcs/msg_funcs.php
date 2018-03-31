<?php

  /**
   * Nachricht schreiben
   */
  function send_text_msg($touid,$tocoords,$subj,$text,$fromuid)
  {
    $lodb = gigraDB::db_open();
    
    //html abfangen
    $text = strip_tags($text);
    
    //Spaam attacke
    //$text = stripNoGigraUrl($text);
    
    //Last Spam protext
    if(strlen($text) > 10 or $text == "-stopped-attemp-to-spam-you-")
    {
        $laRow = $lodb->getOne("SELECT COUNT(*) FROM msg WHERE MD5(CONCAT(fromuid,text)) = '".md5(Uid().$text)."' AND (time > UNIX_TIMESTAMP() - 60)");
        
        if($laRow[0] > 2)
        {
            $lodb->query("INSERT INTO verwarnung SET uid = '".Uid()."', wertigkeit = 5, uname = '".$_SESSION['name']."', admin = 'System', verwarntext = '".l('anti_spam')."', verwarndat = UNIX_TIMESTAMP()");
            redirect("verwarnung.php");
        }
    }
    
    $lodb->query("INSERT INTO msg SET id='_', userid='$touid', time='".time()."', coords='$tocoords'".
    ", fromuid='$fromuid', mode='text', subj='".$lodb->escape($subj)."'".
    ", text='".$lodb->escape(utf8_decode($text))."', ordner='player', red='no'");
    
    return true;
  }
  function send_ally_msg($allyid,$text,$fromuid)
  {
    $lodb = gigraDB::db_open();
    
    //check ally
    $laRow = $lodb->getOne("SELECT COUNT(*) FROM users LEFT JOIN allianz ON users.allianz = allianz.id WHERE users.id = '".Uid()."' AND allianz.id = '$allyid'");
    if($laRow[0] == 1)
    {    
        $lodb->query("INSERT INTO msg (userid,time,fromuid,mode,subj,text,ordner,red)".
                    "SELECT id,UNIX_TIMESTAMP(),'".$fromuid."','text','".l('ally_circ_mail')."','".$lodb->escape(utf8_decode($text))."','player','no'".
                    "FROM users WHERE allianz = '$allyid'");
    }
  }
  function send_global_msg($subj,$text,$fromuid)
  {
    $lodb = gigraDB::db_open();
    
    //check admin
    if(isAdmin())
    {
        $lodb->query("INSERT INTO msg (userid,time,fromuid,mode,subj,text,ordner,red)".
                    "SELECT id,UNIX_TIMESTAMP(),'".$fromuid."','text','".$lodb->escape($subj)."','".$lodb->escape(utf8_decode($text))."','player','no'".
                    "FROM users");
        return true;
    }
    else
        return false;
  }
  function stripNoGigraUrl($text)
    {
        //www.spam.de
        if(preg_match("/.*([a-zA-Z0-9\-\.\:\/]+\.([a-z]{1,3})).*/Ssi",$text,$out))
        {
            if(!preg_match("/(gigra-game|stytex|pr0game|google|youtube|facbook)/",$text))
            {
                $text = str_replace($out[0],"-stopped-attemp-to-spam-you-",$text);
            }
            
        }
        
        return $text;
    }
  function send_cmd_msg($touid,$tocoords,$array_text,$time)
  {
      
	  $db = gigraDB::db_open();
      
      $ordner = getOrdner($array_text['x']);

      $qry = "INSERT INTO msg SET userid='$touid', time='$time', coords='$tocoords'".
      ", fromuid='0', mode='cmd', subj=''".
      ", text='".$db->escape(array2ikf($array_text))."', ordner='$ordner', red='no'";

      
      $db->query($qry);
  }
  function send_cmd_msg_eh($touid,$tocoords,$array_text,$time)
  {
     send_cmd_msg($touid,$tocoords,$array_text,$time);
  }
  function decode_cmd_msg($array_text)
  {
    $laAngColors = array("a" => "lime", "v" => "red", "n" => "white");
    $laDefColors = array("v" => "lime", "a" => "red", "n" => "white");
    
    switch ($array_text['x'])
    {
      case 1:
      global $_BAU;
      $txt = l('msg_construction_complete',$_BAU[$array_text['b']][1],$array_text['s']);
      break;
      case 2:
      global $_FORS;
      $txt = l('msg_research_complete',$_FORS[$array_text['f']][1],$array_text['s']);
      break;
      case 3:
      global $_SHIP;
      $txt = 'Produktion von '.$_SHIP[$array_text['s']][0].' abgeschlossen';
      break;
      case 4:
      global $_VERT;
      $txt = 'Konstruktion der Verteidigungsanlage '.$_VERT[$array_text['v']][0].' abgeschlossen';
      break;
	  case 8:
	  global $RESNAME;
      $a = &$array_text;
      $txt = l('msg_recycled',nicenum($a['cap']),coordFormat($a['c']),nicenum($a[0]),nicenum($a[1]),nicenum($a[2]),nicenum($a[3]));
      break;
      case 10:
      $txt = l('msg_kb',
                        $laAngColors[$array_text["winner"]],
                        nicenum($array_text["a_lost"]),
                        nicenum($array_text["v_lost"]),
                        $array_text['id']);
      break;
      case 11:
      global $RESNAME;
      $a = &$array_text;
      $txt = l('msg_back_with_res',nicenum($a[0]),nicenum($a[1]),nicenum($a[2]),nicenum($a[3]));
      break;
      case 12:
      $txt = l('msg_back');
      break;
      case 13:
      $txt = l('msg_attacked',
                        $laDefColors[$array_text["winner"]],
                        nicenum($array_text["a_lost"]),
                        nicenum($array_text["v_lost"]),
                        $array_text['id']);
      break;
      case 14:
      $txt = l('msg_kolo',coordFormat($array_text['c']));
      break;
      case 15:
      $txt = l('msg_kolo_fail',coordFormat($array_text['c']));
      break;
      case 16:
      $txt = l('msg_inva',
                        $laDefColors[$array_text["winner"]],
                        nicenum($array_text["a_lost"]),
                        nicenum($array_text["v_lost"]),
                        $array_text['id']);
      break;
      case 17:
      global $RESNAME;
      $a = &$array_text;
      $txt = l('msg_trans',coordFormat($a['c']),nicenum($a[0]),nicenum($a[1]),nicenum($a[2]),nicenum($a[3]));
      break;
      case 18:
      $txt = l('msg_stat');
      break;
      /*
      case 19:
      global $_BAU;
      $a = &$array_text;
      $txt = 'Ein Asteroid zerst&ouml;rte folgende Geb&auml;ude:<br>';
      $x = explode(';',$a['d']);
      foreach($x as $y)
      {
        $z[] = $_BAU[$y][1]; 
      }
      $txt .= implode(', ',$z);
      break;
      
      */
      
      case 20:
      $a = &$array_text;
      $txt = l('msg_ally_denied',$a['name']);
      break;
      case 21:
      $a = &$array_text;
      $txt = l('msg_ally_accepted',$a['name']);
      break;
      case 22:
      $a = &$array_text;
      $txt = l('msg_ally_deleted',$a['n']);
      break;
      case 23:
      $a = &$array_text;
      $txt = l('msg_ally_kicked',$a['n']);
      break;
      case 24:
      $txt = l('msg_kb',
                        $laAngColors[$array_text["winner"]],
                        nicenum($array_text["a_lost"]),
                        nicenum($array_text["v_lost"]),
                        $array_text['id']);
      	if($array_text['pt']==1)
            $txt .= "<br>" . l('msg_dest_win');
      	else 
      		$txt .= "<br>" . l('msg_dest_fail');
      	if($array_text['st']==1)
      		$txt .= "<br>" . l('msg_dest_epic_fail');
      $txt .= ".<br /><br />". l('msg_dest_chances',$array_text['ptc'], $array_text['stc']);
      break;
      case 25:
      $txt = l('msg_attacked',
                        $laDefColors[$array_text["winner"]],
                        nicenum($array_text["a_lost"]),
                        nicenum($array_text["v_lost"]),
                        $array_text['id']);
          if($array_text['pt']==1)
            $txt .= "<br>" . l('msg_dest_win');
      	else 
      		$txt .= "<br>" . l('msg_dest_fail');
      	if($array_text['st']==1)
      		$txt .= "<br>" . l('msg_dest_epic_fail');
      $txt .= ".<br /><br />". l('msg_dest_chances',$array_text['ptc'], $array_text['stc']);
      break;
      case 26:
        $txt = l('msg_spio',coordFormat($array_text[f]),$array_text['name'],coordFormat($array_text['t']));
      	break;
      case 27:
        $txt = showSpio($array_text['id']);
        break;
      case 28:
          $txt = l('msg_gigron_earned',nicenum($array_text['a']));
          break;
      case 99:
          $txt = l('msg_mikroton');
          break;
      case 100:
          $txt = l('msg_board_regitration',$array_text["username"],$array_text["pw"],$array_text["email"]);
          break;
      default:
      $txt = 'Fehler bei Dekodierung der Kommandonachricht mit der CMD-ID '.$array_text['x'];
      break;
    }
    return $txt;
  }
  function getUnreadCount()
  {
      $laRow = gigraDB::db_open()->getOne("SELECT COUNT(*) FROM msg WHERE userid = '".Uid()."' AND deleted = 0 AND red = 'no'");
      
      return $laRow[0];
  }
  function getMsgRows()
  {
  
      $lodb = gigraDB::db_open();
      
      $lsQuery = "SELECT msg_id, userid, msg.id, time, coords, fromuid, mode, subj, text, red,ordner, IF(fromuid = '0','sys',(SELECT name FROM users WHERE id = fromuid)) as uname FROM msg WHERE userid = '".Uid()."' AND deleted = 0 ORDER BY time DESC";
      $lodb->query($lsQuery);
      //echo $lsQuery;
      
      $laRows = array();
      while($laRow = $lodb->fetch("assoc"))
        $laRows[] = $laRow;
      

    
      return $laRows;
  }
  function getOrdner($asX)
  {
      switch($asX)
      {
          //Baunachrichten
          case 1:
          case 2:
          case 3:
          case 4:
              return 'build';
              break;
          //Flottennachrichten
          case 8:
          case 11:
          case 12:
          case 14:
          case 15:
          case 17:
          case 18:
          case 26:
              return 'fleet';
              break;
          //Kampfberichte
          case 10:
          case 13:
          case 16:
          case 24:
          case 25:
              return 'combat';
              break;
          //Spio
          case 27:
              return 'spy';
              break;
          default:
              return 'other';
              break;
      }
  }
  function getMsgArray($aiLimit = 10, $aiStart = 0,$asStartOn = "all")
  {
      
      {
          $lodb = gigraDB::db_open();
          $liUnread = 0;//Globaler wert für die ressbar
          $laReturnSorted = array(
                "build_msg" => array(),
                "fleet_msg" => array(),
                "combat_msg" => array(),
                "spy_msg" => array(),
                "player_msg" => array(),
                "other_msg" => array(),
                "archive" => array()
              );
          $laRetunAll = array();
          $laUnread = array(
                "all" => 0,
                "player" => 0,
                "combat" => 0,
                "spy" => 0,
                "fleet" => 0,
                "build" => 0,
                "archive" => 0,
                "other" => 0
            );
          /*
          $lsQuery = empty($_SESSION["ally"]) ? 
                "SELECT SQL_CACHE msg_id, userid, msg.id, time, coords, fromuid, mode, subj, text, ordner, red,readby,ally, IF(fromuid = '0','sys',(SELECT name FROM users WHERE id = fromuid)) as uname  FROM msg LEFT JOIN users ON msg.userid = users.id WHERE (userid = '".Uid()."' OR msg.ally = 'all') AND delby NOT LIKE '%".Uid()."%' ORDER BY time DESC" : 
                "SELECT SQL_CACHE msg_id, userid, msg.id, time, coords, fromuid, mode, subj, text, ordner, red,readby,ally, IF(fromuid = '0','sys',(SELECT name FROM users WHERE id = fromuid)) as uname  FROM msg LEFT JOIN users ON msg.userid = users.id WHERE (userid = '".Uid()."' OR (msg.ally = '".$_SESSION['ally']."') OR msg.ally = 'all') AND delby NOT LIKE '%".Uid()."%'  ORDER BY time DESC";
          $lodb->query($lsQuery);
          $aiLimit = $aiLimit == 0 ? $lodb->numrows() : $aiLimit;
          */
          $laRows = getMsgRows();
          $liMsgAll = $liMsgBuild = $liMsgFleet = $liMsgCombat = $liMsgSpy = $liMsgPlayer = $liMsgOther = $liMsgArchiv = 0;
          
          //while($laRow = $lodb->fetch("assoc"))
          foreach($laRows as $laRow)
          {
              
              $liStart = $asStartOn == "all" ? $aiStart : 0;
              //echo "$asStartOn : $liStart<br>";
              
              /*$laRow['red'] = $laRow['red'] == 0 ? "no" : "yes";*/
              if($laRow['red'] == "no")
                    $laUnread["all"]++;
              
              if($liMsgAll >= $liStart && count($laRetunAll) <= $aiLimit)
              {
                $laRetunAll[] = $laRow;
                
              }
              $liMsgAll++;
              //Sortieren
              $liStart = $asStartOn == "player" ? $aiStart : 0;
              if($laRow['mode'] == "text" && $laRow['ordner'] == "player")
              {
                if($liMsgPlayer >= $liStart && count($laReturnSorted["player_msg"]) <= $aiLimit)
                  { 
                    $laReturnSorted["player_msg"][] = $laRow;
                    if($laRow['red'] == "no")
                        $laUnread["player"]++;
                  }
                  $liMsgPlayer++;
              }
              else
              {
                  switch($laRow['ordner'])
                  {
                      //Baunachrichten
                      case 'build':
                          $liStart = $asStartOn == "build" ? $aiStart : 0;
                          if($laRow['red'] == "no")
                                $laUnread["build"]++;
                          if($liMsgBuild >= $liStart && count($laReturnSorted["build_msg"]) <= $aiLimit)
                          {
                              $laReturnSorted["build_msg"][] = $laRow;
                              
                          }
                          $liMsgBuild++;
                          break;
                      //Flottennachrichten
                      case 'fleet':
                          $liStart = $asStartOn == "fleet" ? $aiStart : 0;
                          if($laRow['red'] == "no")
                            $laUnread["fleet"]++;
                          if($liMsgFleet >= $liStart && count($laReturnSorted["fleet_msg"]) <= $aiLimit)
                          {
                            $laReturnSorted["fleet_msg"][] = $laRow;
                            
                          }
                          $liMsgFleet++;
                          break;
                      //Kampfberichte
                      case 'combat':
                            $liStart = $asStartOn == "combat" ? $aiStart : 0;
                            if($laRow['red'] == "no")
                                $laUnread["combat"]++;
                          if($liMsgCombat >= $liStart && count($laReturnSorted["combat_msg"]) <= $aiLimit)
                          {
                            $laReturnSorted["combat_msg"][] = $laRow;
                            
                          }
                          $liMsgCombat++;
                          break;
                      //Spio
                      case 'spy':
                          $liStart = $asStartOn == "spy" ? $aiStart : 0;
                              if($laRow['red'] == "no")
                                $laUnread["spy"]++;
                          if($liMsgSpy >= $liStart && count($laReturnSorted["spy_msg"]) <= $aiLimit)
                          {
                            $laReturnSorted["spy_msg"][] = $laRow;
                            
                          }
                          $liMsgSpy++;
                          break;
                      //archiv
                      case 'archive':
                          $liStart = $asStartOn == "archive" ? $aiStart : 0;
                              if($laRow['red'] == "no")
                                $laUnread["archive"]++;
                          if($liMsgSpy >= $liStart && count($laReturnSorted["archive"]) <= $aiLimit)
                          {
                            $laReturnSorted["archive"][] = $laRow;
                            
                          }
                          $liMsgArchiv++;
                          break;
                      default:
                          $liStart = $asStartOn == "other" ? $aiStart : 0;
                              if($laRow['red'] == "no")
                                $laUnread["other"]++;
                          if($liMsgOther >= $liStart && count($laReturnSorted["other_msg"]) <= $aiLimit)
                          {
                              $laReturnSorted["other_msg"][] = $laRow;
                              
                          }
                          $liMsgOther++;
                          break;
                  }
                  #echo $aiStart . "<br>";
              }
          }
          
          return array(
                "all" => $laRetunAll,
                "sorted" => $laReturnSorted,
                "all_count" => $liMsgAll,
                "player_count" => $liMsgPlayer,
                "build_count" => $liMsgBuild,
                "fleet_count" => $liMsgFleet,
                "combat_count" => $liMsgCombat,
                "spy_count" => $liMsgSpy,
                "other_count" => $liMsgOther,
                "archive_count" => $liMsgArchiv,
                "unread" => $laUnread,
              );
         }
  }
?>
