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
define("GIGRA_MUSTSESSION",true);

include 'core/core.php';

$laTplExport = array();
$lbMsgsend = false;
$lbSendefehler = false;
$lodb = gigraDB::db_open();




if((isset($_SESSION['ally']) && !empty($_SESSION['ally'])))//prufen ob es die eigene Ally gibt
{

    
    $larow = $lodb->getOne("SELECT COUNT(*) FROM allianz WHERE id='$_SESSION[ally]' LIMIT 1;");
    if ($larow[0] == 0 or isset($_GET['leave']))
    {
        $lsAllyID = $_SESSION['ally'];
        $_SESSION['ally'] = '';
        $lodb->query("UPDATE users SET allianz = '' WHERE id = '$_SESSION[uid]' LIMIT 1;");
        $lodb->query("DELETE FROM allianzmember WHERE id = '$_SESSION[uid]' LIMIT 1;");
        
        $laRow = $lodb->getOne("SELECT COUNT(id) FROM users WHERE allianz = '{$lsAllyID}'");
        if($laRow[0] == 0)
        {
            $lodb->query("UPDATE users SET allianz = '' WHERE allianz = '$lsAllyID'");
            $lodb->query("DELETE FROM allianz WHERE id = '$lsAllyID'");
        }
    }

}
else
{
    $larow = $lodb->getOne("SELECT allianz FROM users WHERE id='".Uid()."';");
    if ($larow[0] != '')
    {
        $_SESSION['ally'] = $larow[0];
    }     
}
if (isset($_GET['ally']) && !empty($_GET['ally']))//ally ermitteln
{
    $lsallyid = $_GET['ally'];
    $lbmyally = false;
    if ($_SESSION['ally'] == $_GET['ally'])
    {
        $lbmyally = true;
        //$lsallyrechte = getAllyRechte($_SESSION['uid'],$_SESSION['ally']);
        $laRechte = meineAllianzRechte();
    }
    if ($_SESSION['admin'] == 1)
    {
        $lbmyally = true;
        $laRechte = array("name" => "!ADMIN!","memberlist" => true, "rundmail" => true, "admin" => true, "delete" => true);
    }
}
elseif (isset($_SESSION['ally']) &&!empty($_SESSION['ally']))
{
    $lsallyid = $_SESSION['ally'];
    $lbmyally = true;
    //ally reche ermitteln
    //$lsallyrechte = getAllyRechte($_SESSION['uid'],$_SESSION['ally']);
    $laRechte = meineAllianzRechte();
    
    if(isset($_GET['abtreten']))
    {
        $lsId = $_POST["newFounder"];
        
        $laRow = $lodb->getOne("SELECT users.id,status FROM users LEFT JOIN allianzmember am ON users.id = am.id WHERE users.id = '".$lodb->escape($lsId)."' AND allianz = '".$lsallyid."'");
        
        if(is_array($laRow))
        {
            $lodb->query("UPDATE allianz SET founder = '{$lsId}' WHERE id = '$lsallyid'");
            $lodb->query("UPDATE allianzmember SET status = '{$laRow[1]}' WHERE id = '".Uid()."'");
            $lodb->query("UPDATE allianzmember SET status = '0' WHERE id = '{$laRow[0]}'");
            
            redirect("/allianzen.php");
        }

    }
}
else
{
    $lsallyid = 0;
    $lballyrang = false;
}

if($lsallyid != '0')
{
    if((isset($_GET['forum']) || isset($_POST['forum'])) && $lbmyally == true)//fixme noch nicht fertig
    {//forum
        $laTplExport['allyid'] = $lsallyid;
        $laTplExport['allyrechte'] = $laRechte;
        //$laTplExport['myally'] = $lbmyally;
        //buildPage("ally_forum.tpl", $laTplExport);
        echo fromTemplate("ally_forum.tpl", $laTplExport);  
    }
    elseif((isset($_GET['verwalten']) || isset($_POST['verwalten'])) && $lbmyally == true && $laRechte['admin'])//fixme noch nicht fertig
    {//verwalten
        if (isset($_POST['mod']))
        {
            $lsmod = $_POST['mod'];
        } 
        elseif (isset($_GET['mod']))
        {
            $lsmod = $_GET['mod'];
        }
        if(isset($lsmod))
        {
            switch ($lsmod)
            {
                case 'text':
                    if (isset($_POST['text']))
                        $lodb->query("UPDATE allianz SET text='".$lodb->escape(stripslashes(($_POST['text'])))."' WHERE id='$lsallyid'");     
                    break;
                case 'del':
                    if (isset($_POST['pw']))
                    {
                        $lapw = $lodb->getOne("SELECT COUNT(*) FROM users WHERE name = '$_SESSION[name]' AND pw = '".$lodb->escape($_POST['pw'])."' ;");
                        if($lapw[0] == 1)
                        {
                            ally_del_msg($lsallyid,$_SESSION['name']);
                            $lodb->query("UPDATE users SET allianz = '' WHERE allianz = '$lsallyid'");
                            $lodb->query("DELETE FROM allianz WHERE id = '$lsallyid'");
                            $lodb->query("DELETE FROM allianzrecht WHERE aid = '$lsallyid'");
                            unset($_SESSION['ally']);
                            redirect("/allianzen.php");
                        }
                    }
                    break;
                case 'link':
                    if (isset($_POST['link']))
                        $lodb->query("UPDATE allianz SET hp='".$lodb->escape($_POST['link'])."' WHERE id='$lsallyid'");
                    break;
                case 'logo':
                    
                    /*
                    if (isset($_FILES['bild']) and $_FILES['bild']['error'] == 0)
                    {
                        if($_FILES['bild']['size'] > 256000)
                        {
                            $lsuploadfehler = 1;
                        }
                        else
                        {
                            $filename = md5($_SESSION['ally'].time());
                            $liType = exif_imagetype($_FILES['bild']['tmp_name']);*/
                            /*
                            1     IMAGETYPE_GIF
                            2     IMAGETYPE_JPEG
                            3     IMAGETYPE_PNG
                            4 	IMAGETYPE_SWF
                            5 	IMAGETYPE_PSD
                            6 	IMAGETYPE_BMP
                            7 	IMAGETYPE_TIFF_II (intel byte order)
                            8 	IMAGETYPE_TIFF_MM (motorola byte order)
                            9 	IMAGETYPE_JPC
                            10 	IMAGETYPE_JP2
                            11 	IMAGETYPE_JPX
                            12 	IMAGETYPE_JB2
                            13 	IMAGETYPE_SWC
                            14 	IMAGETYPE_IFF
                            15 	IMAGETYPE_WBMP
                            16 	IMAGETYPE_XBM
                            17 	IMAGETYPE_ICO
                            *//*
                            $laType = array("", ".gif", ".jpg", ".png", "", "", ".bmp", ".tiff", ".tiff","","","","","","","","","");
                            $lsType = $laType[$liType];
                            move_uploaded_file($_FILES['bild']['tmp_name'], "./upload/$filename$lsType");
                            $lodb->query("UPDATE allianz SET logo='$filename$lsType' WHERE id='$lsallyid'");
                        }
                    }
                    elseif($_FILES['bild']['error'] == 4)
                    {
                        $lodb->query("UPDATE allianz SET logo='' WHERE id='$lsallyid'");
                    }
                    elseif($_FILES['bild']['error'])
                    {
                        echo $_FILES['bild']['error'];
                        $lsuploadfehler = 2;
                    }
                    */
                    if (isset($_POST['bild']))
                        $lodb->query("UPDATE allianz SET logo='".$lodb->escape($_POST['bild'])."' WHERE id='$lsallyid'");
                    break;
                case 'adduser':
                    $lsuid = $lodb->escape($_GET['uid']);
                    $labeworben = $lodb->getOne("SELECT * FROM bewerbungen WHERE uid='$lsuid' AND aid='$lsallyid' LIMIT 1;");// hat sich der user uberhaupt beworben
                    $lsallyname = $lodb->getOne("SELECT tag FROM allianz WHERE id ='$lsallyid' LIMIT 1;");
                    $lsallyname = $lsallyname['tag'];
                    if ($labeworben['uid'] == $lsuid)
                    {
                        if($_GET['add'] == 'false')
                        {
                            $lodb->query("DELETE FROM bewerbungen WHERE uid='$lsuid' AND aid='$lsallyid'"); //Bewerbung loeschen
                            send_cmd_msg($lsuid,'',array('x' => 20, 'name' => $lsallyname),time());  //Nachricht schicken: Bewerbung abgelehnt
                            $libewerbunginfo = 1;
                        }
                        else if($_GET['add'] == 'true')
                        {
                            $lodb->query("DELETE FROM bewerbungen WHERE uid='$lsuid' AND aid='$lsallyid'"); //Bewerbung loeschen
                            $lodb->query("UPDATE users SET allianz='$lsallyid' WHERE id='$lsuid'") or die(mysql_error()); //Allianz aendern
                            $lodb->query("DELETE FROM allianzmember WHERE id='$lsuid'") or die(mysql_error()); //Evtl. alten Eintrag loeschen
                            $lodb->query("REPLACE INTO allianzmember SET id='$lsuid', status=1") or die(mysql_error()); //Rechte (1=Standard fuer neue Mitglieder)
                            send_cmd_msg($lsuid,'',array('x' => 21, 'name' => $lsallyname),time());  //Nachricht schicken: Bewerbung angenommen
                            $libewerbunginfo = 2;
                        }
                    }
                    break;
                case 'kickuser':
                    $lsuid = $lodb->escape($_GET['uid']);
					if($lsuid == Uid()) break;
                    $labeworben = $lodb->getOne("SELECT * FROM users WHERE id='$lsuid' AND allianz='$lsallyid' LIMIT 1;");// ist der user uberhaupt in der ally
                    if ($labeworben['id'] == $lsuid)
                    {
                        $lodb->query("DELETE FROM allianzmember WHERE id='".$lsuid."'");
                        $lodb->query("UPDATE users SET allianz='' WHERE id='".$lsuid."'");
                        send_cmd_msg($lsuid,'',array('x' => 23 , 'n' => $_SESSION['name']),time());
                        $lodb->query("DELETE FROM allianz WHERE (SELECT COUNT(*) FROM users WHERE users.allianz=allianz.id)=0"); //(Alle) Allianzen mit 0 Usern loeschen
                    }
                    break;
                case 'rechte':
                    foreach ($_POST as $lsuid => $lir)
                    {
                        if($lsuid != 'mod')
                        {
                            $lauserally = $lodb->getOne("SELECT allianz FROM users WHERE id='".$lodb->escape($lsuid)."' LIMIT 1;");
                            if( $lauserally['allianz'] == $lsallyid)
                            {
                                $lodb->query("UPDATE allianzmember SET status = '".$lodb->escape($lir)."' WHERE id = '".$lodb->escape($lsuid)."';");

                            }
                            else
                            {
                                echo "wtf! cheater oder was?";
                            }
                        }
                    }


                    break;
                case 'rechte2':
                    //print_r($_POST);

                    $laNeueRechte = $_POST['recht'];
                    
                    foreach($laNeueRechte as $id => $laRecht)
                    {
                        if($id == 0)//Super krasser Mega Admin, dem wird nix entzogen, der darf allet, immer!
                        {
                            //Aber name aendern....
                            $lodb->query("UPDATE allianz SET founderName = '".$lodb->escape($laRecht['name'])."' WHERE id = '{$lsallyid}'");
                        }
                        else
                        {
                            $liMemberlist = isset($laRecht['memberlist']) ? 1 : 0;
                            $liRundmail = isset($laRecht['rundmail']) ? 1 : 0;
                            $liAdmin = isset($laRecht['admin']) ? 1 : 0;
                            $liDelete = isset($laRecht['delete']) ? 1 : 0;
                            
                            $lodb->query("UPDATE allianzrecht SET rang = '".$lodb->escape($laRecht['name'])."',recht_memberlist = $liMemberlist, recht_rundmail = $liRundmail, recht_admin = $liAdmin, recht_delete_ally = $liDelete WHERE id = '{$id}'");
                        }
                        
                    }
                    if(!empty($_POST['new_rang']))
                    {
                        $lodb->query("INSERT INTO allianzrecht SET rang = '".$lodb->escape($_POST['new_rang'])."', aid = '{$lsallyid}'");
                    }
                    //neuer rang?
                    /*
                    $lsneuerechte = '';
                    $lafind = array(',','=');
                    foreach ($_POST as $lsnummer => $lsname)
                    {
                        if (is_numeric($lsnummer))
                        {
                            if(!empty($lsname))
                            {
                                $lsbla = isset($_POST['c'.$lsnummer]) ? $_POST['c'.$lsnummer] : (($lsnummer == 0) ? 'admin' : '');
                                if(!empty($lsneuerechte)) 
                                $lsneuerechte .= ", $lsnummer=".$lodb->escape(str_replace($lafind,'',$lsname)).", ".$lsnummer."r=".$lsbla;
                                else
                                $lsneuerechte = "$lsnummer=".$lodb->escape(str_replace($lafind,'',$lsname)).", ".$lsnummer."r=".$lsbla;
                            }
                        }
                    }
                    if(!empty($lsneuerechte))
                        $lodb->query("UPDATE allianz SET stati = '$lsneuerechte' WHERE id = '$lsallyid';");
                    */
                    break;
            }            
        }
        //ally rechte liste
        $lastati = $lodb->getOne("SELECT stati FROM allianz WHERE id='$lsallyid' LIMIT 1;");
        $lastati = ikf2array($lastati['stati']);
        foreach ($lastati as $ka => $kb)
        {
            if (strpos($ka,"r") === false)
            {
                $lastati2[$ka]['name'] = $kb;
                $lastati2[$ka]['rechte'] = $lastati[$ka.'r'];
                //$lastati2[$ka]['rechte1'] = $lastati[$ka];
            }
        }
        //userliste ally verwalten
        $laMember = memberRaenge($lsallyid);
        foreach($laMember as $laRow)
        {
            $lslast = time() - $laRow['lastclick'] - 3600;
            $laRow['last'] = ($lslast < 600) ? date("H:i:s",$lslast) : false;
            
            //$laRow['rang1'] = getAllyRechte($laRow['id'], $lsallyid,0);
            $laAllymember[] = $laRow;
        }
        //bewerber check
        $laBewerbungen = false;
        $lodb->query("SELECT bewerbungen.* , users.name FROM bewerbungen LEFT JOIN users ON users.id = bewerbungen.uid WHERE aid = '$lsallyid';");//gibts kein $lodb->getAll?
        while( $laRow = $lodb->fetch())
        {
            $laBewerbungen[] = $laRow;
        }        
        
        $laTplExport['rechte'] = getAllianzRaenge($lsallyid);
        $laTplExport['bewerbunginfo'] =$libewerbunginfo;
        $laTplExport['bewerbungen'] =$laBewerbungen;
        $laTplExport['allymember'] = $laAllymember;
        $laTplExport['uploadfehler'] = $lsuploadfehler;
        $laTplExport['allyid'] = $lsallyid;
        $laTplExport['allyrechte'] = meineAllianzRechte();
        $laTplExport['allyinfo'] = $lodb->getOne("SELECT * FROM allianz WHERE id='$lsallyid' LIMIT 1;");
        //$laTplExport['myally'] = $lbmyally;
        buildPage("ally_verwalten.tpl", $laTplExport);
    }
    elseif(isset($_GET['rundmail']) && $lbmyally == true)//fertig
    {//rundmail
        $lbSend = false;
        if (isset($_POST['text']) && !empty($_POST['text']))
        {
            $subj = isset($_POST['subj']) ? $_POST['subj'] : '';
            $lbSend = ally_msg($lsallyid,$_POST['text']);
            //$lbSend= true;
        }
        $laTplExport['send'] = $lbSend;
        $laTplExport['allyid'] = $lsallyid;
        $laTplExport['allyrechte'] = meineAllianzRechte();
        $laTplExport['allyinfo'] = $lodb->getOne("SELECT * FROM allianz WHERE id='$lsallyid' LIMIT 1;");
        //$laTplExport['myally'] = $lbmyally;
        buildPage("ally_rundmail.tpl", $laTplExport);
    }
     elseif(isset($_GET['rundmailall']) && $_SESSION['admin'] == 1)//fertig
    {
        //rundmail
        $lbSend = false;
        if (isset($_POST['text']) && !empty($_POST['text']))
        {
            $subj = isset($_POST['subj']) ? $_POST['subj'] : '';
            $lbSend = send_global_msg($subj,$_POST['text'],Uid());
            //$lbSend= true;
        }
        $laTplExport['send'] = $lbSend;
        $laTplExport['mod'] = 'all';
        //$laTplExport['allyid'] = $lsallyid;
        //$laTplExport['allyrechte'] = $lsallyrechte;
        //$laTplExport['allyinfo'] = $lodb->getOne("SELECT * FROM allianz WHERE id='$lsallyid' LIMIT 1;");
        //$laTplExport['myally'] = $lbmyally;
        buildPage("ally_rundmail.tpl", $laTplExport);
    }
    elseif(isset($_GET['member']) && $lbmyally == true)//fertig
    {   //userliste
        //$loErg = $lodb->query("SELECT * FROM users WHERE allianz = '$lsallyid';");
        $laMember = memberRaenge($lsallyid);
        
        foreach($laMember as $laRow)       
        {
            $lslast = time() - $laRow['lastclick'] - 3600;
            $laRow['last'] = ($lslast < 600) ? date("H:i:s",$lslast) : false;
            
            $laAllymember[] = $laRow;
        }
        $laTplExport['allymember'] = $laAllymember;
        $laTplExport['myally'] = $lbmyally;
        //buildPage("ally_member.tpl", $laTplExport);
        echo fromTemplate("ally_member.tpl", $laTplExport);  
    }
    else//fertig
    {
        //diplo
        $diplRes = $lodb->query("SELECT * FROM v_diplomatie WHERE a_id = '{$allyrow['id']}' OR b_id = '{$allyrow['id']}'") or die(mysql_error());
        $statArray = array(
            0 => "Angefragt",
        	1 => "Parteien zugestimmt",
        	2 => "Genehmigt",
        	3 => "Sieglos beendet",
        );
        
        while( $larow = $lodb->fetch() )
        {
            $ladip[] = $larow;
            
        }
        print_r($ladip);
        	/*while($dirow = mysql_fetch_array($diplRes))
        	{
        		$otherone = $dirow["a_id"] == $allyrow["id"] ? $dirow["b"] : $dirow["a"];
        		switch($dirow['diplotyp'])
        		{
        			
        			case "war":
        				{
        					if($dirow["status"] > 3)
        					{
        						if($dirow["status"] == 4 && $dirow["a_id"] == $allyrow["id"])
        							$status = "Sieg!";
        						else if($dirow["status"] == 5 && $dirow["b_id"] == $allyrow["id"])
        							$status = "Sieg!";
        						else 
        							$status = "Niederlage";
        					}
        					else 
        					$status = $statArray[$dirow["status"]];
        					if($dirow["begin"] == 0)
        					{
        						$zeitraum = "aussthenend";
        					}
        					else 
        					{
        						$zeitraum = date("d.m.Y H:i",$dirow["begin"]);
        						if($dirow["end"] > 0)
        							$zeitraum .= " bis " . date("d.m.Y H:i",$dirow["end"]);
        						if($dirow["begin"] < time() && $dirow["end"] == 0)
        						{
        							$status = "<font color='red'>laufend</font>";
        						}
        							
        					}
        					
        					
        					echo "<tr><th>{$otherone}</th><th>Krieg</th><th>{$zeitraum}</th><th>{$status}</th></tr>";
        					break;
        				}
        		}
        	
        }*/
        //diplo ende        
        
        $laBewerbungen = $lodb->getOne("SELECT COUNT(*) FROM bewerbungen WHERE aid = '$lsallyid'");
        $laTplExport['bewerbungen'] =$laBewerbungen[0];
        $laTplExport['member'] = memberRaenge($lsallyid);
        $laTplExport['allyinfo'] = $lodb->getOne("SELECT * FROM allianz WHERE id='$lsallyid' LIMIT 1;");
        $laTplExport['allyid'] = $lsallyid;
        $laTplExport['myally'] = $lbmyally;
        $laTplExport['admin'] = $_SESSION['admin'];
        $laTplExport['allyrechte'] = meineAllianzRechte();
        buildPage("ally_page.tpl", $laTplExport);
    }
}
elseif (isset($_GET['g']))//fertig
{//Ally grunden
    $lbgegruendet = false;
    if (isset($_POST['tag']) && !empty($_POST['tag']) && isset($_POST['name']) && !empty($_POST['name']))
    {
        if(0 == preg_match('/^[0-9_a-zA-Z ]{1,8}$/', $_POST['tag']))
        {//fehler (sonderzeichen, zu lang...)
            $laFehler[] = 'tag1';
        }
        elseif(0 == preg_match('/^[0-9_a-zA-Z ]{1,35}$/', $_POST['name']))
        {//fehler (sonderzeichen, zu lang...)
            $laFehler[] = 'name1';
        }
        else
        {
            $larow = $lodb->getOne("SELECT * FROM allianz WHERE tag LIKE '$_POST[tag]' OR name LIKE '$_POST[name]' LIMIT 1;");
            if (isset($larow['id']) && !empty($larow['id']))
            {
                $laFehler[] = 'exists';  
            }
            else
            {   //ally erstellen
                $lsnewallyid = genrs(10);
                $r = $lodb->getOne("SELECT COUNT(*) FROM allianz WHERE id='$lsnewallyid'");
                while($r[0] != 0)
                {
                  $lsnewallyid = genrs(10);
                  $r = $lodb->getOne("SELECT COUNT(*) FROM allianz WHERE id='$lsnewallyid'");
                }
                $lodb->query("INSERT INTO allianz SET id='$lsnewallyid', tag='".$lodb->escape(utf8_decode($_POST['tag']))."', name='".$lodb->escape(utf8_decode($_POST['name']))."', stati='0=Admin, 1=Mitglied, 0r=admin, 1r='");
                $_SESSION['ally'] = $lsnewallyid;
                $lodb->query("UPDATE users SET allianz='$lsnewallyid' WHERE id='$_SESSION[uid]'");
                $lodb->query("REPLACE INTO allianzmember SET id='$_SESSION[uid]', status='0'");
                $lodb->query("DELETE FROM bewerbungen WHERE uid = '$_SESSION[uid]';");
                $lbgegruendet = true;
            }
        }
    }
    elseif (isset($_POST['tag']) || isset($_POST['name']))
    {//bitte alle felder ausfuhlen   
        $laFehler[] = 'empty';  
    }
    $laTplExport['gegruendet'] = $lbgegruendet;
    $laTplExport['fehler'] = $laFehler;
    buildPage("ally_gruenden.tpl", $laTplExport);
}
elseif (isset($_GET['b']))//fertig
{   //Ally bewerben
    if($_POST['allyid'])
    {
        $laBla = $lodb->getOne("SELECT * FROM allianz WHERE id='".$lodb->escape($_POST['allyid'])."' LIMIT 1;");
        if($laBla['id'] == $_POST['allyid'])
        {
            $lodb->query("INSERT INTO bewerbungen SET uid='$_SESSION[uid]', aid='$laBla[id]', time='".time()."', text='".$lodb->escape(utf8_decode(stripslashes($_POST['text'])))."'");
            //fixme ally rundmail das sich beworben wurde
        }
        $laTplExport['beworben'] = true;

    }
    else
    {
        $laBla = $lodb->getOne("SELECT * FROM allianz WHERE id='".$lodb->escape($_GET['b'])."' LIMIT 1;");
        $laTplExport['allyid'] = $laBla['id'];
        $laTplExport['tag'] = $laBla['tag'];   
    }
    $laTplExport['allyinfo'] = $lodb->getOne("SELECT * FROM allianz WHERE id='".$lodb->escape($_GET['b'])."' LIMIT 1;");
    buildPage("ally_bewerben.tpl", $laTplExport);
}

else //fertig
{   //keine ally (Suche)
    $lsQuery = "SELECT * FROM allianz ORDER BY RAND() LIMIT 100;";


    if(isset($_POST['q']) && !empty($_POST['q']))
    {
        $lsQuery = "SELECT * FROM allianz WHERE tag LIKE '%".$lodb->escape($_POST['q'])."%' OR name LIKE '%".$lodb->escape($_POST['q'])."%' LIMIT 100;";
    }
    $lodb->query($lsQuery);
    while($laRow = $lodb->fetch())
    {
        $laAllyliste[] = $laRow;
    }   
    $laTplExport['Allyliste'] = $laAllyliste;
    buildPage("ally_suche.tpl", $laTplExport);
}




// functionen
function getAllyRechte($uid,$allyid,$mod=0)
{
    $lodb = gigraDB::db_open();
    
    $lastati = $lodb->getOne("SELECT stati FROM allianz WHERE id='$allyid';");
    $lastati = ikf2array($lastati['stati']);
    $lauserstati = $lodb->getOne("SELECT status FROM allianzmember WHERE id='$uid';");
    if ($mod==0)
        return $lastati[$lauserstati['status']."r"];
    if ($mod==1)
        return $lastati[$lauserstati['status']];
    
    
}

function meineAllianzRechte()
{
    $lodb = gigraDB::db_open();
    
    $laRechte = getAllianzRaenge($_SESSION['ally']);
    
    
    $laRow = $lodb->getOne("SELECT status FROM allianzmember WHERE id = '".Uid()."'");
    
    return isset($laRechte[$laRow[0]]) ? $laRechte[$laRow[0]] : defaultRang($laRechte);
}
function defaultRang($aaRechte)
{
    foreach($aaRechte as $laRecht)
        if($laRecht["default"])
            return $laRecht;
    
    return false;
}
function memberRaenge($asAllyId)
{
    $lodb = gigraDB::db_open();
    
    $lodb->query("SELECT u.id, u.name, IF((SELECT COUNT(*) FROM allianz a WHERE a.id = u.allianz AND a.founder = u.id) = 1,1,0) as isFounder,u.umod, IF(am.status = 0,(SELECT founderName FROM allianz WHERE id = u.allianz),(SELECT rang FROM allianzrecht WHERE id = am.status)) as rang, lastclick, p.pgesamt FROM users u LEFT JOIN allianzmember am ON u.id = am.id LEFT JOIN v_punkte p ON u.id = p.uid WHERE u.allianz = '$asAllyId'");
    
    $laMember = array();
    while($laRow = $lodb->fetch("assoc"))
    {
        $laRow['isFounder'] = $laRow['isFounder'] == 1;
        if($laRow['isFounder'] && $laRow['rang'] == '')
            $laRow['rang'] = l("ally_std_rang_founder");
        $laMember[] = $laRow;
    }
    
    
    return $laMember;
}
function getAllianzRaenge($asAllyId)
{
    $lodb = gigraDB::db_open();
    
    $laRow = $lodb->getOne("SELECT COUNT(*),founderName FROM allianzrecht,allianz WHERE allianz.id = aid AND aid = '$asAllyId'");
    if($laRow[0] == 0)
        createRechte($asAllyId);
        
    //_____________________________
    
    
    $laRechte = array();
    
    //Gruender Rang!
    $laRechte[0] =  array(
                "name" => $laRow[1] == '' ? l('ally_std_rang_founder') : $laRow[1],
                "founder" => true,
                "default" => false,
                "memberlist" => true,
                "rundmail" => true,
                "admin" => true,
                "delete" => true
    );
    
    $lodb->query("SELECT id, rang, recht_memberlist, recht_rundmail, recht_admin, recht_delete_ally FROM allianzrecht WHERE aid = '$asAllyId' ORDER BY id ASC");
    
    
    $i = 0;
    while($laRow = $lodb->fetch())
    {
        //Erster Rang = Neuling = standardrang
        $lbDefault = $i == 0;
        $i++;
        
        $laRechte[$laRow['id']] = array(
                "name" => $laRow['rang'],
                "founder" => false,
                "default" => $lbDefault,
                "memberlist" => $laRow['recht_memberlist'] == 1,
                "rundmail" => $laRow['recht_rundmail'] == 1,
                "admin" => $laRow['recht_admin'] == 1,
                "delete" => $laRow['recht_delete_ally'] == 1
        );
    }
    
    return $laRechte;
}
function ally_msg($allyid,$text)
{
  
    $text = utf8_decode($text);
    
    send_ally_msg($allyid,$text,Uid());
    
    return true;
}
function ally_del_msg($allyId,$asWho)
{
    $lodb = gigraDB::db_open();
    
    $lsQuery = "SELECT id,mainplanet FROM users WHERE allianz='$allyId';";
    $lodb->query($lsQuery);
    while($laRow = $lodb->fetch())
        send_cmd_msg($laRow['id'],$laRow['mainplanet'],array("x" => 22,"n" => $asWho),time());
    
}

function createRechte($allyid)
{
    if($allyid == '') return;
    $lodb = gigraDB::db_open();
    $lodb2 = gigraDB::db_open();
    
    $laStatus = $lodb->getOne("SELECT stati FROM allianz WHERE id='$allyid';");
    $laStatus = ikf2array($laStatus[0]);
    
    
    $lodb->query("INSERT INTO allianzrecht SET aid = '$allyid', rang = '".l('ally_std_rang_neu')."'");
    foreach($laStatus as $k => $lsStatus)
    {
        if(is_numeric($k))
            $lodb->query("INSERT INTO allianzrecht SET aid = '$allyid', rang = '{$lsStatus}'");
    }
    
    
    $lodb->query("UPDATE allianz SET founder = (SELECT users.id FROM users WHERE users.allianz = allianz.id LIMIT 1) WHERE id = '$allyid'");
    
    //User zuordnen -.-
    $lsFounder = $lodb->getOne("SELECT founder FROM allianz WHERE id = '$allyid'");
    $lsFounder = $lsFounder[0];
    $laRechte = getAllianzRaenge($allyid);
    
    $lodb->query("SELECT id FROM users WHERE allianz = '$allyid'");
    while($laRow = $lodb->fetch())
    {
        $lsRang = $laRow['id'] == $lsFounder ? 0 : defaultRang($laRechte);
        $lodb2->query("REPLACE INTO allianzmember SET id = '{$laRow[0]}', status = '$lsRang'");
    }
}

?>