<?php

define("GIGRA_INTERN", true);
define("GIGRA_MUSTSESSION",false);

include 'core/core.php';

$_BAU_ALT=array(
  //'g1' => 'Hauptgeb&auml;ude',
  1 => array(1, l('item_b1'),150,95,0,0,60,'Koordiniert den Bau von Geb&auml;uden','',0,1,0,0),
  2 => array(2, l('item_b2'),150,50,0,0,500,'Erm&ouml;glicht das Erforschen neuer Technologien','B1=3',0,1,0,0),
  19 => array(19, l('item_b19'),5000,5000,5000,0,35000,'Erm&ouml;glicht das Besiedeln eines Mondes','',0,2,6,12),
  20 => array(20, l('item_b20'),100000,200000,200000,100000,50000,'Ermittelt Flottenbewegungen im Umkreis von Stufe^3 - 1','B19=3, F8=20',0,2,6,12),
  21 => array(21, l('item_b21'),5000000,10000000,15000000,1000000,160000,'Erm&ouml;glicht das Erzeugen von Sternentore durch den Sensorturm','B19=15, F3=20',0,2,6,12),
  //'g2' => 'Rohstoffgeb&auml;ude',
  3 => array(3, l('item_b3'),125,10,0,0,20,'F&ouml;rderung von Eisen','',0,1,0,0),
  4 => array(4, l('item_b4'),65,10,0,0,30,'F&ouml;rderung von Titan','',0,1,0,0),
  5 => array(5, l('item_b5'),50,10,0,0,22,'F&ouml;rdert Wasser','',0,1,0,0),
  6 => array(6, l('item_b6'),175,10,0,0,40,'Wandelt Wasser in Wasserstoff um (Verh&auml;ltnis 5:1)','B5=1',0,1,0,0),
  7 => array(7, l('item_b7'),10000,7500,0,500,400,'Wandelt Wasser in Wasserstoff um (Verh&auml;ltnis 2:1)','B5=10, B1=6',0,1,1,1), 
  8 => array(8, l('item_b8'),125,125,0,0,55,'Erzeugt Energie','',0,1,0,0),
  9 => array(9, l('item_b9'),1000,1000,0,0,2440,'Zur Lagerung von Eisen','B3=3|B19=1',0,0,1,1),
  10 => array(10, l('item_b10'),1000,1000,0,0,2440,'Zur Lagerung von Titan','B4=3|B19=1',0,0,1),
  11=> array(11,l('item_b11'),1000,0,0,0,2440,'Zur Lagerung von Wasser','B5=3|B19=1',0,0,1),
  12=> array(12,l('item_b12'),1000,1000,0,0,2440,'Zur Lagerung von Wasserstoff','B6=3|B7=1|B19=1',0,0,1),
  //'g3' => 'Angriff / Verteidigung',
  13=> array(13,l('item_b13'),1000,2000,0,10,2400,'Zum Bau von Schiffen','B1=3',0,0,0,0),
  14=> array(14, l('item_b14'),200,200,0,10,900,'Die Verteidigungsanlage ihres Planeten. Kann mit Verteidigungst&uuml;rmen best&uuml;ckt werden','B13=2',0,0,1,4),
  15=> array(15, l('item_b15'),5000,0,1000,5000,2200,'Erh&ouml;ht den Verteidigungswert Ihrers Planeten','B13=1, B14=1, B16=1, F9=15',0,0,3,6),
  16=> array(16, l('item_b16'),5000,0,1000,5000,2200,'Energieversorgung f&uuml;r planetares Schild','B6=5',0,0,3,6),
  17=> array(17, l('item_b17'),5000000,10000000,2000000,2500000,172800,'Verk&uuml;rzt die Bau- und Produktionszeiten pro Stufe um 50 Prozent','B1=20, F14=20',0,0,4,10),
  18=> array(18, l('item_b18'),10000,10000,0,10000,1800,'Erweitert die Anzahl der parallel baubaren Geb&auml;ude pro Stufe um 1','B1=3, F14=1|B19=5, F14=1',0,0,1,4)
);

$_BAU_NEU=array(
  //'g1' => 'Hauptgeb&auml;ude',
  1 => array(1, l('item_b1'),150,95,0,0,60,'Koordiniert den Bau von Geb&auml;uden','',0,1,0,0),
  2 => array(2, l('item_b2'),150,50,0,0,500,'Erm&ouml;glicht das Erforschen neuer Technologien','B1=3',0,1,0,0),
  19 => array(19, l('item_b19'),5000,5000,5000,0,35000,'Erm&ouml;glicht das Besiedeln eines Mondes','',0,2,6,12),
  20 => array(20, l('item_b20'),100000,200000,200000,100000,50000,'Ermittelt Flottenbewegungen im Umkreis von Stufe^3 - 1','B19=3, F8=20',0,2,6,12),
  21 => array(21, l('item_b21'),5000000,10000000,15000000,1000000,160000,'Erm&ouml;glicht das Erzeugen von Sternentore durch den Sensorturm','B19=15, F3=20',0,2,6,12),
  //'g2' => 'Rohstoffgeb&auml;ude',
  3 => array(3, l('item_b3'),125,50,0,0,20,'F&ouml;rderung von Eisen','',0,1,0,0),
  4 => array(4, l('item_b4'),130,55,0,0,30,'F&ouml;rderung von Titan','',0,1,0,0),
  5 => array(5, l('item_b5'),70,25,0,0,22,'F&ouml;rdert Wasser','',0,1,0,0),
  6 => array(6, l('item_b6'),175,75,20,0,80,'Wandelt Wasser in Wasserstoff um (Verh&auml;ltnis 5:1)','B5=1',0,1,0,0),
  7 => array(7, l('item_b7'),10000,7500,2500,500,600,'Wandelt Wasser in Wasserstoff um (Verh&auml;ltnis 2:1)','B5=10, B1=6',0,1,1,1), 
  8 => array(8, l('item_b8'),125,75,0,0,55,'Erzeugt Energie','',0,1,0,0),
  9 => array(9, l('item_b9'),1000,1000,0,0,2440,'Zur Lagerung von Eisen','B3=3|B19=1',0,0,1,1),
  10 => array(10, l('item_b10'),1000,1000,0,0,2440,'Zur Lagerung von Titan','B4=3|B19=1',0,0,1),
  11=> array(11,l('item_b11'),1000,0,0,0,2440,'Zur Lagerung von Wasser','B5=3|B19=1',0,0,1),
  12=> array(12,l('item_b12'),1000,1000,0,0,2440,'Zur Lagerung von Wasserstoff','B6=3|B7=1|B19=1',0,0,1),
  //'g3' => 'Angriff / Verteidigung',
  13=> array(13,l('item_b13'),1000,2000,0,500,2400,'Zum Bau von Schiffen','B1=3',0,0,0,0),
  14=> array(14, l('item_b14'),600,600,0,300,900,'Die Verteidigungsanlage ihres Planeten. Kann mit Verteidigungst&uuml;rmen best&uuml;ckt werden','B13=2',0,0,1,4),
  15=> array(15, l('item_b15'),4000,1000,4000,1000,2200,'Erh&ouml;ht den Verteidigungswert Ihrers Planeten','B13=1, B14=1, B16=1, F9=15',0,0,3,6),
  16=> array(16, l('item_b16'),5000,0,1000,5000,2200,'Energieversorgung f&uuml;r planetares Schild','B6=5',0,0,3,6),
  17=> array(17, l('item_b17'),5000000,10000000,2000000,2500000,1728000,'Verk&uuml;rzt die Bau- und Produktionszeiten pro Stufe um 50 Prozent','B1=20, F14=20',0,0,4,10),
  18=> array(18, l('item_b18'),10000,10000,0,10000,1800,'Erweitert die Anzahl der parallel baubaren Geb&auml;ude pro Stufe um 1','B1=3, F14=1|B19=5, F14=1',0,0,1,4)
  );


function sum_stufe($asId, $aiStufe, $KONF)
{
    
    $laRet = array(0,0,0,0);
    for($i = 1;$i<=$aiStufe;$i++)
    {
        $x = formel_stufe($i);
        $laRet[0] += $KONF[$asId][2] * $x;
        $laRet[1] += $KONF[$asId][3] * $x;
        $laRet[2] += $KONF[$asId][4] * $x;
        $laRet[3] += $KONF[$asId][5] * $x;
        
    }
    
    return $laRet;
}


function f_stufe($asId, $aiStufe, $KONF)
{
    
    $laRet = array(0,0,0,0);
    
    {
        $x = formel_stufe($aiStufe);
        $laRet[0] += $KONF[$asId][2] * $x;
        $laRet[1] += $KONF[$asId][3] * $x;
        $laRet[2] += $KONF[$asId][4] * $x;
        $laRet[3] += $KONF[$asId][5] * $x;
        
    }
    
    return $laRet;
}


$laMap = array();
$liR1 = $liR2 = $liR3 = $liR4 = 0;

foreach($_BAU_NEU as $id => $laGeb)
{
    if(!isset($laMap["b".$id])) $laMap["b".$id] = array();
    
    //fuer 100 Stufen
    $r1 = array(0,0,0,0);
    $r2 = array(0,0,0,0);
    for($i=1;$i<=50;$i++)
    {
        $j = $i;
        $r1 = sum_stufe($id,$i,$_BAU_ALT);
        $r2 = sum_stufe($id,$j,$_BAU_NEU);
        
        while($r2[0] > $r1[0] or $r2[1] > $r1[1] or $r2[2] > $r1[2] or $r2[3] > $r1[3])
        {
            $j--;
            $liFS = formel_stufe($j);
            $r2[0] -= $_BAU_NEU[$id][2] * $liFS;
            $r2[1] -= $_BAU_NEU[$id][3] * $liFS;
            $r2[2] -= $_BAU_NEU[$id][4] * $liFS;
            $r2[3] -= $_BAU_NEU[$id][5] * $liFS;
            
            $r2 = sum_stufe($id,$j,$_BAU_NEU);
            
            
            //var_export($r1);
            /*
            echo "g".$id." Stufe $i , $j ";
            var_export($r2);
            var_export($r1);*/
            if($j == 0) break;
        }
        
        //Stufe ermittelt...abziehen
        $liR1 = $r1[0] - $r2[0];
        $liR2 = $r1[1] - $r2[1];
        $liR3 = $r1[2] - $r2[2];
        $liR4 = $r1[3] - $r2[3];
        $liNeueStufe = $j;
        
        $laMap["b".$id][$i] = array($liNeueStufe,$liR1,$liR2,$liR3,$liR4);
        
        
    }
}
$lodb = gigraDB::db_open();

if(!isset($_GET['sim'])) die("keine coords");
$lbOptimize = isset($_GET['optimize']) && $_GET['optimize'] == 1;


$laPrio = array("13","14","7","6","3","4","5","8");
$laUpdate = array();
$lodb->query("SELECT p.coords,k1, k2, k3, k4, k5, k6, k7, k8, k9, k10, k11, k12, k13, k14, k15, k16, k17, k18, k19, k20 FROM v_planets p LEFT JOIN gebaeude g ON p.coords = g.coords WHERE type = 1 AND p.coords = '{$_GET['sim']}'");
while($laRow = $lodb->fetch("assoc"))
{
    $laAddRes = array(0,0,0,0);
    
    $lsUpdate = "";
    $lsKommentar = "";
    
    //Erster Durchgang, runter formen
    foreach($laMap as $lsMapId => $laData)
    {
        $lsDBId = str_replace("b","k",$lsMapId);
        $liStufe = isset($laRow[$lsDBId]) ? $laRow[$lsDBId] : 0;
        
        if($liStufe == 0) continue;
        
        //ab hier wird es interessant...
        $laNeueStufe = $laData[$liStufe];//hat array(neuestufe,r1,r2,r3,r4)
        //dbg($lsMapId . "($liStufe -> $laNeueStufe[0])");
        //dbg($laNeueStufe);
        $liNeueStufe = $laNeueStufe[0];
        $laAddRes[0] += $laNeueStufe[1];
        $laAddRes[1] += $laNeueStufe[2];
        $laAddRes[2] += $laNeueStufe[3];
        $laAddRes[3] += $laNeueStufe[4];
        
        //$lsUpdate .= ", $lsDBId = $liNeueStufe";
        $laUpdate[$lsDBId] = $liNeueStufe;
        //$lsKommentar .= l('item_'.$lsMapId)." Stufe $liStufe wird Stufe $liNeueStufe<br>";
        
    }
    
    if($lbOptimize)
    {
        //Zweiter Durchgang, Rohstoffe umsetzen
        foreach($laPrio as $lsId)
        {
            if(!isset($laUpdate["k".$lsId]))continue;
            
            $liCurrStufe = $laUpdate["k".$lsId];
            $laResNeeded = f_stufe($lsId,$liCurrStufe+1,$_BAU_NEU);
            while($liCurrStufe < $laRow['k'.$lsId] && ($laAddRes[0] >= $laResNeeded[0] && $laAddRes[1] >= $laResNeeded[1] && $laAddRes[2] >= $laResNeeded[2] && $laAddRes[3] >= $laResNeeded[3]))
            {
                $laAddRes[0] -= $laResNeeded[0];
                $laAddRes[1] -= $laResNeeded[1];
                $laAddRes[2] -= $laResNeeded[2];
                $laAddRes[3] -= $laResNeeded[3];
                
                $liCurrStufe++;
                $laUpdate['k'.$lsId] = $liCurrStufe;
                
                $laResNeeded = f_stufe($lsId,$liCurrStufe+1,$_BAU_NEU);
            }
        }
    }
    
    foreach($laUpdate as $lsDbId => $liNeueStufe)
    {
        $liStufe = $laRow[$lsDbId];
        $lsUpdate .= ", $lsDBId = $liNeueStufe";
        $lsKommentar .= l('item_'.str_replace("k","b",$lsDbId))." Stufe $liStufe wird Stufe $liNeueStufe<br>";
    }
    
    
    //nen ordentliches Update bauen
    $lsUpdateSql = "UPDATE gebaeude SET ". substr($lsUpdate,1) . " WHERE coords = '{$laRow['coords']}'";
    //dbg($lsUpdateSql);
    //dbg($laAddRes);
    dbg("Simulation fuer {$laRow['coords']}");
    dbg($lsKommentar);
    dbg("Es werden ".nicenum($laAddRes[0])." Fe, ".nicenum($laAddRes[1])." Ti, ".nicenum($laAddRes[2])." H<sup>2</sup>O und ".nicenum($laAddRes[3])." H<sup>2</sup> gutgeschrieben.");
    dbg("Der Spieler verliert " . nicenum(array_sum($laAddRes) / 1000) . " Punkte an diesem Planeten");
    die();
}


die();
//var_export($laMap);
$x = 0;
foreach ($laMap as $lsId => $laTable)
{
    $x++;
    echo "<table border=1>";
    echo "<tr><th style='background-color:blue;color:white' colspan=6>".l('item_'.$lsId)."</th></tr>";
    echo "<tr><th>Stufe alt</th><th>Stufe neu</th><th>Gutschrift Eisen</th><th>Gutschrift Titan</th><th>Gutschrift Wasser</th><th>Gutschrift H<sup>2</sub></th></tr>";
    foreach($laTable as $liOld => $laData)
    {
        echo "<tr><td>$liOld</td><td>{$laData[0]}</td>"."<td>".nicenum($laData[1])."</td>"."<td>".nicenum($laData[2])."</td>"."<td>".nicenum($laData[3])."</td>"."<td>".nicenum($laData[4])."</td></tr>";
    }
    echo "</table>";
    
    //if($x % 2 == 0) 
    echo "<hr><br>";
    
}
?>