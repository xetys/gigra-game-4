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

function showGebs($mode_forsch = false,$mode_ajax = false)
{
    global $_BAU;
    global $_FORS;
    global $_ACTCONF;
    startProfile("showGebs");
    
    $SPEED = $_ACTCONF["speed_build"];
    $ldbo = gigraDB::db_open();
    $laTplExport = array();
    
    $bauEval = "";
    
    if(!$mode_forsch)
        unset($mode_forsch);
    
    if(isset($mode_forsch))
    {
        $_BAU_or_FORSCH = $_FORS;
        $JS_Prefix = "F";
        $PHP_Prefix = "F";
    } 
    else 
    {
        $_BAU_or_FORSCH = $_BAU;
        $PHP_Prefix = "B";
        $JS_Prefix = "";
    }
    
    $laTplExport["JSPre"] = $JS_Prefix;
    $laTplExport["PHPPre"] = $PHP_Prefix;
    
    $laTplExport['laRes'] = read_res($_SESSION['coords']);
    
    $plani_type = coordType($_SESSION['coords']);
    //Gebaude des Users kriegen...
    $k = $ldbo->getOne("SELECT * FROM gebaeude WHERE coords='$_SESSION[coords]'");

    if(isset($mode_forsch))
    {
        if($k['k2']==0) {
            return "";
        }
    }
    $f = $ldbo->getOne("SELECT f FROM forschung WHERE uid='$_SESSION[uid]'");
    $f = ikf2array($f[0]);
    if(isset($mode_forsch))
    {  
        $k_or_f = $f;
    } 
    else
    {
        $k_or_f = $k; 
    }
    
    //Res kriegen
    $_RES = read_res($_SESSION['coords']); //Rohstoffe neu auslesen, wenn nicht gesetzt

    if(isset($mode_forsch)) {
      $cmd = 'forsch'; 
      $whr = "uid='$_SESSION[uid]'";
    } else {
      $cmd = 'build';
      $whr = "coords='$_SESSION[coords]'"; 
    }
    //Events kriegen
    $events = array();
    $event_qry = "SELECT id,starttime,time,param,coords FROM events WHERE $whr AND command='$cmd'";

    $ldbo->query($event_qry);
    while($row = $ldbo->fetch())
    {
       $events[] = $row;
       $forsch_coords = $row['coords'];
    }
    

    //Tabelle aufbauen
    $konst_tbl = build_build_tbl($_BAU_or_FORSCH,$mode_forsch,$k_or_f,$k,$f,$events,$plani_type);

    //Bau abbrechen, nur wenn im Bau und noch nicht fertig
    if(isset($_GET['s']))
    {
      $sid = (int)$_GET['s'];
    
      if($konst_tbl[$sid]['bld'] == 'in')
      {
        if(isset($mode_forsch) && $forsch_coords != $_SESSION['coords']) {
          $fehler = "Diese Forschung wurde auf $forsch_coords gestartet";
        } else {
          add_res($konst_tbl[$sid]['res'],$_SESSION['coords']);  //Kosten zurueckgeben
    
          $ldbo->query("DELETE FROM events WHERE id='".$konst_tbl[$sid]['event_id']."'");
          foreach($events as $eid => $event) {
            if($event['id'] ==  $konst_tbl[$sid]['event_id']) {
              unset($events[$eid]); 
            }
          }
          $_RES = read_res($_SESSION['coords']); //Rohstoffe neu auslesen
          $konst_tbl = build_build_tbl($_BAU_or_FORSCH, $mode_forsch,$k_or_f,$k,$f,$events,$plani_type); //TBL neu aufbauen
          unset($res);  //Aufraeumen    
        }
      }
    }

    if(isset($_GET['B']))  //Auf Bauen geklickt
    {
      $id = (int)$_GET['B']; //Zu Bauendes
      $invalid = false;
      if($konst_tbl[$id]['bld'] == "yes")  //Wenn nicht baubar...
      {
            $fs = formel_stufe($k_or_f[(isset($mode_forsch)?'f':'k').$id]);
        
            if(!isset($_RES))
                $_RES = read_res($_SESSION['coords']);  //Rohstoffe des Users auslesen
              
            $res = $konst_tbl[$id]['res'];
            $time = time()+$konst_tbl[$id]['time'];
            //In die Tabelle damit!
            $ikf[$id] = $k_or_f[(isset($mode_forsch)?'f':'k').$id]+1;
            event_add((isset($mode_forsch)?'forsch':'build'),array2ikf($ikf),$time,2);
            sub_res($res,$_SESSION['coords']);  //Und Rostoffe abbuchen
            
            unset($events);
            //Events & Res aktualisieren
            $ldbo->query($event_qry);
            while($row = $ldbo->fetch())
            {
                $events[] = $row;
            }
            $_RES = read_res($_SESSION['coords']); //Rohstoffe neu auslesen
            $konst_tbl = build_build_tbl($_BAU_or_FORSCH,$mode_forsch,$k_or_f,$k,$f,$events,$plani_type); //TBL neu aufbauen
        
            unset($res);
        }
    }
    
	// So nun noch mal die selbe Kacke mit hiddenInfo! Fuer alle die kein plan haben Ajax Professional->Pre loading dynamic Content! Lernt das!

    $lsInfoBoxes .= "";
    if(!isset($_RES))
    {
      $_RES = read_res($_SESSION['coords']); //Rohstoffe neu auslesen, wenn nicht gesetzt
    }
    $count_anz = 0;
    foreach ($konst_tbl as $id => $kon) //Zeile um Zeile...
    {
        $laInfoTpl = array();
        $laInfoTpl["kon"] = $kon;
        $laInfoTpl["PHPPre"] = $PHP_Prefix;
        $laInfoTpl["id"] = $id;
        
        if(is_string($kon))
            continue;
        /* ---Benoetigte Rohstofe--- */
        $res = "";
        //$res = l('v3_need').": ";
        
        $color = $kon['res'][0]> $_RES[0] ? " class='red'" : "";
        if ($kon['res'][0]>0) $res.= l('res1').': <b'.$color.'>' .nicenum($kon['res'][0]).'</b><br> ';
        $color = $kon['res'][1]> $_RES[1] ? " class='red'" : "";
        if ($kon['res'][1]>0) $res.= l('res2').': <b'.$color.'>' .nicenum($kon['res'][1]).'</b><br> ';
        $color = $kon['res'][2]> $_RES[2] ? " class='red'" : "";
        if ($kon['res'][2]>0) $res.= l('res3').': <b'.$color.'>' .nicenum($kon['res'][2]).'</b><br> ';
        $color = $kon['res'][3]> $_RES[3] ? " class='red'" : "";
        if ($kon['res'][3]>0) $res.= l('res4').': <b'.$color.'>' .nicenum($kon['res'][3]).'</b><br> ';
        $color = $kon['res'][4]> $_RES[4] ? " class='red'" : "";
        if ($kon['res'][4]>0) $res.= l('energy').': <b'.$color.'>' .nicenum($kon['res'][4]).'</b><br> ';
        /* ------------------------ */
    
        $laInfoTpl["res"] = $res;
        //Zeit:
        $time = format_zeit($kon['time']);
        $laInfoTpl["time"] = $time;
    
        if($kon['lvl'] == 0) {
          if(isset($mode_forsch)) {
            $linktxt = l('v3_do_research');
          } else {
            $linktxt = l('v3_do_build');
          }
        } else {
          if(isset($mode_forsch)) {
            $linktxt = l('v3_do_research_to',($kon['lvl']+1));
          } else {
            $linktxt = l('v3_do_build_to',($kon['lvl']+1));
          }
        }
        
        $laInfoTpl["linktxt"] = $linktxt;
        $laInfoTpl["imgPre"] = isset($mode_forsch)?'f':'b';
        
        if($kon['bld'] == "in")
        {
            $count_anz++;
            $bauEval .= $JS_Prefix.'anz = '.$count_anz.';'.$JS_Prefix.'bxs.push('.$kon['resttime'].');'.$JS_Prefix.'ids.push('.$kon["id"].');'.$JS_Prefix.'starttimes.push('.$kon["starttime"].');'.$JS_Prefix.'endtimes.push('.$kon["endtime"].');';
        }
        
        $laInfoTpl["count_anz"] = $count_anz;
        //Zeile anzeigen
    
        $lsInfoBoxes .= fromTemplate("v3_infobox.tpl",$laInfoTpl);
        unset($link);
    
        unset($vne);
        unset($stufe);
    }

    $laTplExport["konst_tbl"] = $konst_tbl;
    $laTplExport["lsInfoBoxes"] = $lsInfoBoxes;

    $JS_Func = (!isset($mode_forsch) ? "tb();" : "tf();");
    $JS_BauEval = (!isset($mode_forsch) ? "evalB" : "evalF");
    if(strlen($bauEval)>0)
    	$bauEval .= $JS_Func;
        
        
    
    $laTplExport["JS_BauEval"] = $JS_BauEval;
    $laTplExport["bauEval"] = $bauEval;
    
    $laTplExport["mode_ajax"] = $mode_ajax;


    /*
    if(!isset($_SESSION['v3_bau_tpl']))
        $_SESSION['v3_bau_tpl'] = "scrippi";
    if(isset($_GET['view']))
        $_SESSION['v3_bau_tpl'] = $_GET['view'];
    switch($_SESSION['v3_bau_tpl'])
    {
        case "eXe":
            $lsTpl = "v3_bau_exe.tpl";
            break;
        case "scrippi":
        default:
            $lsTpl = "v3_bau.tpl";
            break;
        case "new":
            $lsTpl = "v3_bau_new.tpl";
            break;
    }
    */
    
    
    $lsTpl = "v3_bau_new.tpl";
    endProfile("showGebs");
    return fromTemplate($lsTpl,$laTplExport);
}

function showForsch()
{
    return showGebs(true,false);
}
function showSchiffe($mode_vert = false)
{
    global $_SHIP;
    global $_VERT;
    
    global $_ACTCONF;
    
    $SPEED = $_ACTCONF["speed_build"] * getNoobSpeed(Uid());
    $lodb = gigraDB::db_open();
    $laTplExport = array();
    
    /*
    if(!$mode_vert)
        unset($mode_vert);
    */
    
    $blAJAX = false;
    $blGoOn = true;
    if(isset($_GET["ajax"]) && $_GET['ajax'] != "")
    {
	    $blAJAX = true;
    }
    //Variablen fuer entsprechenden mode setzen
    if($mode_vert)
    {
        $JS_Prefix = "V";
        $PHP_Prefix = "V";
    } 
    else
    {
        $PHP_Prefix = "S";
        $JS_Prefix = "S";
    }
    if($mode_vert)
    {
      $kid = 'k14'; //Id es baugebaeudes
      $e_cmd = 'vert'; //Event cmd
      $filename = 'verteidigung.php';
      $prod_typ = 'v';
    }
    else
    {
      $kid = 'k13';
      $e_cmd = 'prod';
      $filename = 'produktion.php';
      $prod_typ = 'p';
    }


    $bauEval = "";

    
    $k = $lodb->getOne("SELECT * FROM  gebaeude WHERE coords='$_SESSION[coords]'");
    if(!isset($k[$kid]) || $k[$kid]<1)
    {
        if($mode_vert)
        {
            #require('inc/pages/keineverteidigung.html.php'); 
            #if(!$blAJAX)
            return "no deff";
            $blGoOn = false;
        }
        else
        {
            
            #if(!$blAJAX)
            return "no ships";
            $blGoOn = false;
        }
    }
    if($blGoOn)
    {
	    $k = $lodb->getOne("SELECT * FROM gebaeude WHERE coords='$_SESSION[coords]'");
	    $f = $lodb->getOne("SELECT f FROM forschung WHERE uid='$_SESSION[uid]'");
	    $f = ikf2array($f[0]);
	    if(!isset($_RES))
	    {
	        $_RES = read_res($_SESSION['coords']);
	    }
	    if($mode_vert)
	    {
	        $s_ikf = read_vert($_SESSION['coords'],$k); //TODO: entfernen
	    }
    	else
    	{
    	  $s_ikf = read_schiffe($_SESSION['coords'],$k); //TODO: entfernen
    	}
	
	    //$b = new Bauliste($_SESSION['coords'],false,$e_cmd);
	    $loBauliste = new v3Bauliste($_SESSION["coords"],false);
        $lsProdType = $mode_vert ? "V" : "S";
	
	   //Passenden Array: $_SHIP oder $_VERT
    	if($mode_vert)
    	    $_SHIP_or_VERT = &$_VERT;
    	else
    	    $_SHIP_or_VERT = &$_SHIP;
	    //---------------------------------------------------------------------
	    foreach($_SHIP_or_VERT as $i => $ship)
	    {
	        if(isset($_POST["p$i"]) && $_POST["p$i"]>0 && !checkUMOD(Uid())) //AKTION: Soll diesen Schiffstyp bauen
	        {
    	        $prod = true;
    	        if(!isset($_RES))
    	        {
    	            $_RES = read_res($_SESSION[coords]);
    	        }
    	        $d = false;
    	        $maxprod = 0;
    	        //Maximale Produktionsmenge berechnen
    	        for($j=0;$j<4;$j++)
    	        {
    	            if($ship[$j+1] != 0)
    	            {
    	                if(!$d)
    	                {
    	                    $maxprod = $_RES[$j]/$ship[$j+1];
    	                    $d = true;
    	                }
    	                else
    	                {
    	                    $maxprod = min($_RES[$j]/$ship[$j+1],$maxprod);
    	                }
    	            }
                }
    	        $maxprod = floor($maxprod);
    	        $p = (int)min($maxprod,$_POST["p$i"]);
    	        if($p>0)
    	        {
    	            $NOW = time();
    	
    	            unset($_RES);
    	
    	            
                    //Werft
                      //$time = (max(1,((10*$ship[5]/foe($k[$kid]))/$SPEED)));
                      //$time = max(1,((10 * $ship[5] / (foe($k[$kid]) / 2) )/$SPEED));
                      $time = ($ship[1] + $ship[2] + $ship[3] + $ship[4]) / (1 * (1 + $k[$kid]) * pow(2,$k['k17']));
                      
                      //MCF
                      //$time = $time / pow(2,$k['k17']);
                      //Baubonus
                      $time = $time * getBauzeitBonus(Uid());
                      
                      //mindestens 1 Sekunde
                      $time = max(1,$time);
    	
                    
    	            ##$b->add($i,$p,$NOW,$prod_zeit);
                    $loBauliste->add($i,$p,$time,$lsProdType);
    	
    	            //Res abziehen
    	            $res[0] = $_SHIP_or_VERT[$i][1]*$p;
    	            $res[1] = $_SHIP_or_VERT[$i][2]*$p;
    	            $res[2] = $_SHIP_or_VERT[$i][3]*$p;
    	            $res[3] = $_SHIP_or_VERT[$i][4]*$p;
    	            sub_res($res,$_SESSION['coords']);
    	        }
	        }
	   }
	//---------------------------------------------------------------------
    $laTplExport["mode_vert"] = $mode_vert;
	$laTplExport["PHP_Prefix"] = $PHP_Prefix;
    
	
	
	if(!isset($_RES))
	{
	  $_RES = read_res($_SESSION['coords']);  //Rohstoffe auslesen, wenn nicht gesetzt
	}
	
    $laObjects = array();
    
    
	foreach ($_SHIP_or_VERT as $index => $s)  //Tabelle Zeile um Zeile...
	{
        $laObj = array();
	  if(is_string($s))  //Kein Schiff sondern Gruppe
	  {
        $laObj = $s;
	  }
	  else
	  {
	    $vne = baubar(!$mode_vert ? "S" : "V",$index, $k, $f) == true ? false : true;
        if(!$vne)
            unset($vne);
	
	    //TODO: Einstellungen: Alle Techniken anzeigen
	    if(!isset($vne))  //Wenn vorrausetzungen erfuellt
	    {
	
	      /* ---Benoetigte Rohstofe--- */
	      $res = l('v3_need').": ";
	      if ($s[1]>0) $res.= l('res1').': <b>'.nicenum($s[1]).'</b> ';
	      if ($s[2]>0) $res.= l('res2').': <b>'.nicenum($s[2]).'</b> ';
	      if ($s[3]>0) $res.= l('res3').': <b>'.nicenum($s[3]).'</b> ';
	      if ($s[4]>0) $res.= l('res4').': <b>'.nicenum($s[4]).'</b> ';
           
          /* Baubare Einheiten*/
          $liMaxBuildable = -1;
          
          for($i=0;$i<4;$i++)
             if($s[$i+1] > 0)
                $liMaxBuildable = $liMaxBuildable == -1 ? floor($_RES[$i] / $s[$i+1]) : min($liMaxBuildable,floor($_RES[$i] / $s[$i+1]));
           
           //Punkte?
           $liPunkte = round($liMaxBuildable * (($s[1] + $s[2] + $s[3] + $s[4]) / 1000));
           $laObj['max'] = $liMaxBuildable;
           $laObj['pts'] = $liPunkte;
          
	      /* ------------------------ */
          
          $laObj["res"] = $res;
	
	      //Zeit:
          //Werft
	      $time = (max(1,((10*$s[5]/foe($k[$kid]))/$SPEED)));
          $time = max(1,((10 * $s[5] / (foe($k[$kid]) / 2) )/$SPEED));
          //MCF
          $time = $time / pow(2,$k['k17']);
          
          //was sagt ogamedazu? = (Kristall + Metall) / (2500 * (1 + "Stufe Raumschiffwerft") * 2 ^ "Stufe Nanitenfabrik")
          $time = ($s[1] + $s[2] + $s[3] + $s[4]) / (1 * (1 + $k[$kid]) * pow(2,$k['k17']));
          
          //Baubonus
          $time = $time * getBauzeitBonus(Uid());
          
          //Mindestens 1 Sekunde
          $time = max(1,$time);
          
          $time = format_zeit($time);     
          $laObj["time"] = $time;
          
	      $laObj["sname"] = $s[0];
          $laObj["sbild"] = $mode_vert ? "design/items/v$index.gif" : "design/items/s$index.gif";
          $laObj["desc"] = $s[6];
          
          $laObj["s_ikf"] = ((int)$s_ikf[$index]);
          
	      $laObjects[$index] = $laObj;  
	    }
	    unset($vne);
	  }
      
	}
    
    $laTplExport["laObjects"] = $laObjects;
    //Events
	if(!isset($NOW))
	    $NOW = time();
	
    /*
	$c = $b->list_items();
	if($c!=0)
	{
	    if(isset($_POST['del']))
	    {
	        foreach($_POST as $i => $v)
	        {
	            if(substr($i,0,2)=='__')
	            {
	                $pos = substr($i,2);
	                
	                if($pos == 'a') //Alles Abbrechen
	                {
	                    $list = $b->cancel(NULL,NULL,true);
	                    foreach($list as $sid => $count);
	                    {
	                        //Baukosten berechnen
	                        for($rid=0;$rid<4;$rid++)
	                        {
	                            $res_add[$rid] += ($_SHIP_or_VERT[$sid][$rid+1]*$count);
	                        }
	                    }
	                    add_res($res_add,$_SESSION['coords']);
	                }
	                else
	                {
	                    $pos = (int)$pos;
	                    $list = $b->cancel($pos);
	                    if(is_array($list)) foreach($list as $sid => $count);
	                    {
	                        //Baukosten berechnen
	                        for($rid=0;$rid<4;$rid++)
	                        {
	                            $res_add[$rid] += ($_SHIP_or_VERT[$sid][$rid+1]*$count);
	                        }
	                    }
	                    add_res($res_add,$_SESSION['coords']);
	                }
	            }
	        }          
            //echo "<script language='JavaScript'>window.setTimeout('top.location=\"produktion.php\"',999);</script>";
	
	    }
	}*/
    $loBauliste = new v3Bauliste($_SESSION["coords"],false);
	$liste = $loBauliste->listItems($lsProdType);
    $laTplExport["laListe"] = array();
    
	if($liste!=NULL)
	{
	  $anz_rows=0;
	  $time_ges=0;  //Gesamtzeit
	  $count_ges=0; //Gesamtzahl

	  foreach($liste as $pos => $item)
	  {
        $laItem = array();
        
        $laItem["name"] = $_SHIP_or_VERT[$item['sid']][0];
        $laItem["rest"] = $item["rest"];
        $laItem["count"] = $item["count"];
        $laItem["time"] = $item["time"];
        $laItem['id'] = $item['id'];
        $laItem["anz_rows"] = $anz_rows;
        
	    $names[] = '"'.$laItem["name"].'"'; //fuer's JS
	    $ids[]   = '"'.$item['sid'].'"';
	    $time_ges+=$item['rest'];
	    $time_ges+=$item['time']*($item['count']-1);
	    $count_ges+=$item['count'];

	    $anz_rows++;
        
        $laTplExport["laListe"][] = $laItem;
	  }

      $laTplExport["time_ges"] = $time_ges;
      $laTplExport["count_ges"] = $count_ges;
     
	
	  $bauEval .= "
    	{$PHP_Prefix}a=$anz_rows;
    	{$PHP_Prefix}names = new Array(".implode(", ",$names).");
    	{$PHP_Prefix}ids   = new Array(".implode(", ",$ids).");
    	{$PHP_Prefix}stop=0;";
	} 
    //$liste != NULL

	$JS_Func = ($PHP_Prefix == "S" ? "ts();" : "tv();");
	$JS_BauEval = ($PHP_Prefix == "S" ? "evalS" : "evalV");
	if(strlen($bauEval)>0)
		$bauEval .= $JS_Func;
    $laTplExport["JS_BauEval"] = $JS_BauEval;
    $laTplExport["bauEval"] = $bauEval;
	//echo '<div id="'.$JS_BauEval.'">'.$bauEval.'</div>';
	
	if(!isset($_GET["ajax"]) || !$_GET["ajax"])
		$laTplExport["mode_ajax"] = true;
    }
    else
        $laTplExport["mode_ajax"] = false;
        
    
    return fromTemplate("v3_produktion.tpl",$laTplExport);
}
function showVert()
{
    return showSchiffe(true);
}
function showRaketen()
{
    
}
function build_build_tbl($_BAU_or_FORSCH,$mode_forsch,$k_or_f,$k,$f,$events,$type = 1)
{
    global $_ACTCONF;
    
    $SPEED = $_ACTCONF["speed_build"] * getNoobSpeed(Uid());
    $_RES = read_res($_SESSION['coords']);
    //Tabelle mit den Konstruktionen
    
    
    $konst_tbl = array();

    foreach ($_BAU_or_FORSCH as $bwid => $bw) //Geabudetabelle Zeile um Zeile...
    {
        if(is_string($bw)) //Kein Gebaude sondern Gruppe
        {
          $konst_tbl[$bwid] = $bw;
        }
        else
        {
            $konst = array();
            if(!$mode_forsch && (isset($bw[10]) && $bw[10] != 0 && $bw[10] != $type))
                $vne = true;
            else 
            {
      	        $vne = baubar(!$mode_forsch ? "B" : "F",$bwid, $k, $f) == true ? false : true;
                if(!$vne)
                	unset($vne);
            }
            //TODO: Einstellungen: Alle Techniken anzeigen    
            if(!isset($vne))  //Wenn vorrausetzungen erfuellt
            {
                if(isset($k_or_f[(isset($mode_forsch)?'f':'k').$bw[0]]))  //Wenn Stufe Eintrag in DB hat...
                {        
                    $konst['lvl'] = $k_or_f[(isset($mode_forsch)?'f':'k').$bw[0]];//Stufe = Eintrag
                }
                else  //hat keinen Eintrag
                {
                    $konst['lvl'] = 0;
                }
                /* ---Benoetigte Rohstofe--- */
                $fs = formel_stufe($konst['lvl']);
                $konst['res'][0] = /*(int)expoFunktion($bw[2],$konst['lvl']);*/(int)($fs*$bw[2]);
                $konst['res'][1] = /*(int)expoFunktion($bw[3],$konst['lvl']);*/(int)($fs*$bw[3]);
                $konst['res'][2] = /*(int)expoFunktion($bw[4],$konst['lvl']);*/(int)($fs*$bw[4]);
                $konst['res'][3] = /*(int)expoFunktion($bw[5],$konst['lvl']);*/(int)($fs*$bw[5]);
                $konst['res'][4] = /*(int)expoFunktion($bw[9],$konst['lvl']);*/(int)($fs*$bw['en']);
                /* ------------------------ */
        
                //Zeit:
        		#echo !$mode_forsch ? 'k1':'k2';
                $time = $type == 2 ? (int)(((10*$bw[6]*$fs/foe($k["k19"]+1))/$SPEED)) : 
                                     (int)(((10*$bw[6]*$fs/foe($k[(!$mode_forsch ? 'k1':'k2')]))/$SPEED));
                if(!$mode_forsch && $type==1 && $bwid != 17)
                	$time = $time / pow(2,$k['k17']); 	
                
                //Bonus!
                $liBonusFaktor = $mode_forsch ? getForschzeitBonus($_SESSION["uid"]) : getBauzeitBonus($_SESSION["uid"]);
                $time = $time * $liBonusFaktor;
                
                $konst['time'] = $time;
                
                $konst['bld'] = "no";

                if($_RES[0]-$fs*$bw[2] >=0 &&
                   $_RES[1]-$fs*$bw[3] >=0 &&
                   $_RES[2]-$fs*$bw[4] >=0 &&
                   $_RES[3]-$fs*$bw[5] >=0 &&
                   $_RES[4]-$fs*$bw['en'] >=0 &&
                   !isset($vne))
                {
                    $konst['bld'] = "yes";
                }
                if(checkUMOD(Uid()))
                    $konst['bld'] = "no";
                $konst['count_events'] = count($events);
          		$multi_line = (!$mode_forsch) ? $k['k18'] : 0;
          		$paralel_baubar = $multi_line;
                if(is_array($events) && count($events)>$paralel_baubar)
                {
                    $konst['bld'] = "other";
                }
                if(is_array($events) && count($events) >0) foreach($events as $event)
                {
                    $p = ikf2array($event['param']);
                    foreach($p as $bid => $s)
                    {
                        if($bid == $bw[0])
                        {
                              $konst['bld'] = "in";
                              $konst['resttime'] = (int)($event['time']-time());
                              $konst['starttime'] = (int)($event['starttime']);
                              $konst['endtime'] = (int)($event['time']);
                              $konst['event_id'] = $event['id'];
                        } 
                    } 
                }
                $konst['id'] = $bw[0];
                $konst['name'] = $bw[1];
                $konst['desc'] = $bw[7];
                $konst_tbl[$bw[0]] = $konst;
                unset($konst);
            }
            unset($vne);
        }
    }
    return $konst_tbl;
}