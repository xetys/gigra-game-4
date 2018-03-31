<?php

$_GAMEDATA = array(

	
	1 => array(
		"name" => "Planetare Zitadelle",
		"r1" => 150,
		"r2" => 95,
		"r3" => 0,
		"r4" => 0,
		"energie" => 0,
		"faktor" => 1.5,
		"info" => "Koordiniert den Bau von Geb&auml;uden",
		"req" => array(),
		"erfahrung" => array(0,0,0),
		"laborstufe" => 0,
		"planettype" => 0,
	),
	2 => array(
		"name" => "Forschungszentrum",
		"r1" => 150,
		"r2" => 50,
		"r3" => 0,
		"r4" => 0,
		"energie" => 0,
		"faktor" => 1.5,
		"info" => "Erm&ouml;glicht das Erforschen neuer Technologien",
		"req" => array(1 => 3),
		"erfahrung" => array(0,0,0),
		"laborstufe" => 0,
		"planettype" => 0,		
	)
);




$_BAU=array(
  'g1' => 'Hauptgeb&auml;ude',
  1 => array(1, 'Planetare Zitadelle',150,95,0,0,1000,'Koordiniert den Bau von Geb&auml;uden','',0,1,0,0),
  2 => array(2, 'Forschungszentrum',150,50,0,0,2200,'Erm&ouml;glicht das Erforschen neuer Technologien','B1=3',0,1,0,0),
  19 => array(19, 'Mondbasis',5000,5000,5000,0,1000000,'Erm&ouml;glicht das Erforschen neuer Technologien','',0,2,6,12),
  20 => array(20, 'Sensorturm',150,50,0,0,2200,'Erm&ouml;glicht das Erforschen neuer Technologien','B19=3',0,2,6,12),
  21 => array(21, 'Hypertransmitter',150,50,0,0,2200,'Erm&ouml;glicht das Erforschen neuer Technologien','B19=20',0,2,6,12),
  'g2' => 'Rohstoffgeb&auml;ude',
  3 => array(3, 'Eisenmine',125,10,0,0,460,'Abbau von Eisen','',0,1,0,0),
  4 => array(4, 'Titanmine',65,10,0,0,460,'Abbau von Titan','',0,1,0,0),
  5 => array(5, 'Bohrturm',50,10,0,0,400,'F&ouml;rdert Wasser','',0,1,0,0),
  6 => array(6, 'Chemiefabrik',175,10,0,0,1400,'Wandelt Wasser in Wasserstoff um (Verh&auml;ltnis 5:1)','B5=1',0,1,0,0),
  7 => array(7, 'Erweiterte Chemiefabrik',10000,7500,0,500,7000,'Wandelt Wasser in Wasserstoff um (Verh&auml;ltnis 2:1)','B5=10, B1=6',0,1,1,1), 
  8 => array(8, 'Kraftwerk',125,125,0,0,920,'Erzeugt Energie','',0,1,0,0),
  9 => array(9, 'Eisenspeicher',1000,1000,0,0,14400,'Zur Lagerung von Eisen','B3=3|B19=5',0,0,1,1),
  10 => array(10, 'Titanspeicher',1000,1000,0,0,14400,'Zur Lagerung von Titan','B4=3|B19=5',0,1,1),
  11=> array(11,'Wassertanks',1000,0,0,0,14400,'Zur Lagerung von Wasser','B5=3|B19=5',0,1,1),
  12=> array(12,'Wasserstoffspeicher',1000,1000,0,0,14400,'Zur Lagerung von Wasserstoff','B6=3|B7=1|B19=5',0,1,1),
  'g3' => 'Angriff / Verteidigung',
  13=> array(13,'Schiffsfabrik',1000,2000,0,10,7400,'Zum Bau von Schiffen','B1=3',0,0,0,0),
  14=> array(14, 'Verteidigungsindustrie',200,200,0,10,900,'Die Verteidigungsanlage ihres Planeten. Kann mit Verteidigungst&uuml;rmen best&uuml;ckt werden','B13=2',0,0,1,4),
  15=> array(15, 'Schildstation',5000,0,1000,5000,2200,'Erh&ouml;ht den Verteidigungswert Ihrers Planeten','B13=1, B14=1, B16=1, F9=15',0,0,3,6),
  16=> array(16, 'Schildreaktor',5000,0,1000,5000,2200,'Energieversorgung f&uuml;r planetares Schild','B6=5',0,0,3,6),
  17=> array(17, 'MicroCybot Fabrik',1000000,2000000,10000,500000,172800,'Verk&uuml;rzt die Bau- und Forschzeiten pro Stufe um 60 Prozent','B1=20, F14=20',0,0,4,10),
  18=> array(18, 'Multi Line Core Computer',10000,10000,0,10000,1800,'Erweitert die Anzahl der parallel baubaren Geb&auml;ude pro Stufe um 1','B1=3, F14=1',0,0,1,4)
  );
?>