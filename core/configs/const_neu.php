<?php
if(!isset($INC['const']))
{
  $INC['const'] = true;
  /**
* Dies File enthaelt fuer das Spiel relevante Konstanten
* (hauptsaechlich Gebaude, Forschungen, Schiffe und Verteidigung)
*/

/**
*Rohstoffe
*/
$RESNAME[0] = 'Eisen';
$RESNAME[1] = 'Titan';
$RESNAME[2] = 'Wasser';
$RESNAME[3] = 'Wasserstoff';
$RESNAME["e"] = 'Energie';
  /**
* Gebaeude:
* Jeweils ein Element ist ein Gebaude oder eine Gebaeudegruppe.
* Jedes Element muss eine eindeutige ID kriegen. Jede Gruppe 
* einen String der mit 'g' begint und einer Zahl endet. Jedes
* Gebaude eine Zahl.
* Jedes Gebaude erhaelt einen Array, der wie folgt aufgebaut ist.
* 
* $x[0] muss gleich dem mit $y => vorangestellten Index sein. (z.B. 7)
* $x[1] muss der Name als String sein.  (z.B. 'Erweiterte Chemiefabrik')
* $x[2] sind die Kosten an $RESNAME[0]. (z.B. 10000)
* $x[3] sind die Kosten an $RESNAME[1]. (z.B. 7500)
* $x[4] sind die Kosten an $RESNAME[2]. (z.B. 0)
* $x[5] sind die Kosten an $RESNAME[3]. (z.B. 500)
* $x[6] ist die Bauzeit in Sekunden.    (z.B. 7000)
* $x[7] ist eine kurze Beschreibung.    (z.B. 'Wandelt Wasser in Wasserstoff um (Verh?ltnis 2:1)')
* $x[8] sind die Vorrausetzungen als IKF:
* $x[9] Energie
* $x[10] spezial typ(0=alle,1=planet,2=Mond,3=Luna)
* $x[11] forschungserfahrungsstufe zum sehen unter technik
* $x[12] laborstufe zum sehen unter technik
* Jede Vorrausetzung wird mit ', ' von der naechsten getrennt.
* ID und Stufe werden mit einem '=' getrennt.
*  Beispiel: '5=10, 1=6'
* Bedeutet, dass das Gebaude die Stufe 10 des Gebaudes mit der ID 5 braucht (Bohrturm 10)
* und Stufe 6 von dem Gebaeude mit der ID 1 (Kommandozentrale 6)
*  (!) Sind keine Vorrausetzungen, wird $x[8] ausgelassen.   
* Die Gebaeude werden in der Reihenfolge ihrer Auflistung hier
* in den Menues (Konstruktion, Technik) angezeigt.
*/
  $_BAU=array(
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
  /**
* Forschungen:
* Jeweils ein Element ist eine Forschung oder eine Gruppe.
* Jedes Element muss eine eindeutige ID kriegen. Jede Gruppe 
* einen String der mit 'g' begint und einer Zahl endet. Jede
* Forschung eine Zahl.
* Jede Forschung erhaelt einen Array, der wie folgt aufgebaut ist:
* 
* $x[0] muss gleich dem mit $y => vorangestellten Index sein. (z.B. 1)
* $x[1] muss der Name als String sein.  (z.B. 'Verbrennungsantrieb')
* $x[2] sind die Kosten an $RESNAME[0]. (z.B. 500)
* $x[3] sind die Kosten an $RESNAME[1]. (z.B. 0)
* $x[4] sind die Kosten an $RESNAME[2]. (z.B. 0)
* $x[5] sind die Kosten an $RESNAME[3]. (z.B. 0)
* $x[6] ist die Bauzeit in Sekunden.    (z.B. 1000)
* $x[7] ist eine kurze Beschreibung.    (z.B. 'Antriebstechnik f&uuml;r kleine Raumschiffe')
* $x[8] sind die Vorrausetzungen als IKF:
* $x[9] forsch erfarhung
* $x[10] LabLevel
* Jede Vorrausetzung wird mit ', ' von der naechsten getrennt.
* ID und Stufe werden mit einem '=' getrennt.
*
*  (!) Sind keine Vorrausetzungen, wird NICHT $x[8] ausgelassen.   
* $x[9] ist Energie
* Die Gebaeude werden in der Reihenfolge ihrer Auflistung hier
* in den Menues (Konstruktion, Technik) angezeigt.
*/
  $_FORS = array(
  //'g1' => 'Antriebsforschung',
  1 => array(1,l('item_f1'),500,0,0,0,1000,'Antriebstechnik f&uuml;r kleine Raumschiffe',"B2=1",0,0),
  2 => array(2,l('item_f2'),2000,2000,0,1000,3000,'Sehr schnelle Antriebstechnik','B2=5, F1=1',0,1),
  3 => array(3,l('item_f3'),0,0,0,15000,1000,'Sparsamer und schneller Antrieb, f&uuml;r gro&szlig;e Raumschiffe','B2=14, F1=5, F2=5',2,10),
  4 => array(4, l('item_f4'),0,0,10000,50000,6000,'Modernste Antriebsform der Galaxie','B2=20, F3=19, F15=19',3,14),
  //'g2' => 'Waffenforschung',
  5 => array(5,l('item_f5'),4750,2875,0,225,2400,'Ionenwaffen erforschen','B2=4, F15=2',1,5), //5->6
  6 => array(6,l('item_f6'),8500,6625,0,620,3000,'Erh&ouml;ht die Effizienz von Ionenwaffen','B2=6, F5=1, F15=4'),
  7 => array(7,l('item_f7'),2000,0,0,0,1000,'Waffenst&auml;rke durch magnetisierte plasmaartige Neodymmolek&uuml;le','B2=10, F5=2, F6=1, F15=8',3,8),
 // 'g3' => 'Schiffsforschung',
  8 => array(8,l('item_f8'),200,50,0,0,1500,'Verbessert die Funktion von Spionagesonden','B2=3',0,2),
  9 => array(9,l('item_f9'),2000,2000,2000,2000,3000,'Erh&ouml;ht die Panzerung aller Raumschiffe','B2=5, F15=5'), //9->13
  10=> array(10,l('item_f10'),0,5000,0,100,2000,'Erh&ouml;ht die Ladekapazit&auml;t aller Raumschiffe','B2=6',1,3),
  //'g4' => 'Imperiumsforschung',
  14 => array(14,l('item_f14'),7500,15000,0,5000,3500,'Erweitert pro Stufe die maximale Planetenanzahl um 2','B2=4',0,1),
  15 => array(15,l('item_f15'),0,10000,0,10000,3300,'Wird Ben&ouml;tigt f&uuml;r alle Energietechiken','B2=1',0,0),
  //'g5' => 'Superwaffenforschung',
  18 => array(18,l('item_f18'),0,0,0,0,0,'Extrem starke Waffe','B2=25, F5=20, F6=20, F7=20, F15=20',0,0,'en' => 3000000)    
  );
  /**
* Schiffe:
* Jeweils ein Element ist ein Schiff.
* Jedes Element muss eine eindeutige ID kriegen.
* Jedes Schiff erhaelt einen Array, der wie folgt aufgebaut ist.
* $y => Index des Schiffs, sollte NIE geaendert werden.
* $x[0] muss der Name als String sein.  (z.B. 'Schakal')
* $x[1] sind die Kosten an $RESNAME[0]. (z.B. 250)
* $x[2] sind die Kosten an $RESNAME[1]. (z.B. 0)
* $x[3] sind die Kosten an $RESNAME[2]. (z.B. 0)
* $x[4] sind die Kosten an $RESNAME[3]. (z.B. 75)
* $x[5] ist die Bauzeit in Sekunden.    (z.B. 480)
* $x[6] ist eine kurze Beschreibung.    (z.B. 'Schwaches aber ....')
* $x[7] sind die Vorrausetzungen als IKF:
* Jede Vorrausetzung wird mit ', ' von der naechsten getrennt.
* ID und Stufe werden mit einem '=' getrennt.
*  Beispiel: '1=1'
* Bedeutet, dass das Schiff die Stufe 1 der Forschung mit der ID 1 braucht (Verbrennungsantrieb 1)
*  (!) Sind keine Vorrausetzungen, wird $x[7] NICHT ausgelassen, sondern leer
 $x[8] ist der Angriffswert             (z.B. 100)
 $x[9] ist der Verteidigungswert        (z.B. 50)
 $x[10] ist die Ladekapazitaet          (z.B. 250)
 $x[11] ist die Geschwindigkeit         (z.B. 800)
 $x[12] ist der Wasserstoffverbrauch    (z.B. 10)   
 $x[13] ist der Antrieb des Schiffes    (z.B. 1)
* $x[14] erfahrungslevel
* $x[15] laborleve
* Die Gebaeude werden in der Reihenfolge ihrer Auflistung hier
* in den Menues (Produktion, Technik) angezeigt.
*/     
   $_SHIP=array(
  //       Name                              Res1    Res2    Res3    Res4    Zeit    Beschreibung                                                                                                                                           Technik                          Angriff  Vert.       Ladekap. Speed   Verbr.  Antrieb  Erfahrung  Labor    Rapidfire
  1=>array('Tri Fighter',                    2500,    0,      0,     750,     600,    'Schwaches aber g&uuml;nstiges Kampfschiff der Aufkl&auml;rerklasse. Bewaffnet mit 2 Laserbordkanonen.',                                              'B13=1, F1=1',                   100,     50,         250,     3500*4,     5,       1,        0,        0,    "rf" => array(3 => 5, 9 => 5, 15 => 5)),
  2=>array('Recycler',                       850,    850,    100,    0,      2480,   'Sammelt nach Schlachten die Wracks ein und recycled 10-20% der Rohstoffe',                                                                           'B13=3, F1=2',                    1,       5,          10000,   1700*4,     10,       1,        2,        4,    'rf' => array(3 => 5, 15 => 5)),
  3=>array('Spionagesonde',                  0,      1000,    0,      0,      400,     'Schnelles unbemanntes Schiff, liefert Informationen &uuml;ber einen Planeten ',                                                                      'B13=5, F1=5, F8=3',            0,       1,          30,      9000000*4,  1,        1,        1,        4),
  4=>array('Stormfighter',                   10000,  0,      0,      0,      1000,   'Schiff der Aufkl&auml;rerklasse. Ausgestattet mit 3 Laserbordkanonen und mit einer verbesserten Tri Fight Panzerung',                                  'B13=5, F1=4',                  250,     120,        200,     2000*4,     10,       1,        1,        2,    'rf' => array(3 => 5, 10 => 5, 15 => 5, 16 => 15)),
  5=>array('Raider',                         12000,  5000,   2000,      0,    10000,   'Der Raider besitzt 8 leichte Laserbordkanonen und 2 starke Laserbordkanonen. Die 5-fache Akzentrinpanzerung h&auml;lt sogar Plasmasch&uuml;sse aus', 'B13=8, F2=3, F6=6',            450,     200,        900,     3200*4,     40,      2,        3,        5,    'rf' => array(1 => 6, 3 => 5, 15 => 5),         "rfv" => array(1 => 15)),
  6=>array('Tarnbomber',                     10000,  5000,   5000,   0,   14000,  'Wird erst beim Kampf entdeckt',                                                                                                                      'B13=9, F2=2, F8=10, F6=7',          500,     100,        4000,    1000*4,     50,      2,        3,        8,    'rf' => array(3 => 5, 15 => 5)),
  7=>array('Kolonisationsschiff',            5000,  10000,  2000,   5000,  50000, 'Zum besiedeln anderer Planeten. Enth&auml;lt eine Planetare Zitadelle Stufe 1',                                                                         'B13=6, F2=4, F14=1',             1,       1500,       100000,  800*4,      800,      2,        0,        2),
  8=>array('Invasionseinheit',               200000, 150000, 20000,  55000,  5660000, 'Zum einnehmen bereits besiedelter Planeten',                                                                                                         'B13=15, F2=13, F8=10, F14=10',  10,      65,         100000,  500*4,      1000,     2,        4 ,       10),
  9=>array('C-Force',                        15000,   35000,   0,   10000,    20000,  'Schnellstes Kriegsschiff',                                                                                                                           'B13=12, F3=4, F5=7',            2600,    750,        2000,    3500*4,     50,      3,        2,        10,   'rf' => array(2 => 10, 3 => 5, 4 => 10, 5 => 7, 6 => 2, 7 => 5, 10 => 3, 12 => 10, 13 => 5, 15 => 5, 16 => 9)),
  10=>array('Imperialer Zerst&ouml;rer',     40000,   15000, 30000,   15000,     40000,  'Sehr stark gepanzertes Raumschiff',                                                                                                                  'B13=15, F3=8, F9=6, F6=11',   3200,    1000,       24000,   2100*4,    150,      3,        4,        12,   'rf' => array(3 => 5, 15 => 5) , 'rfv' => array(1 => 25, 2 => 12, 3 => 8, 4 => 5, 5 => 5)),
  11=>array('Imperiale Sterneneinheit',      80000,  40000,   0,      10000, 60000,  'St&auml;rkstes Raumschiff',                                                                                               'B13=20, F4=4, F5=18, F6=18, F7=18, F8=10, F9=15',           4500,    1000,       34000,   2700*4,     85,      4,        5,        15,   'rf' => array(3 => 5, 9 => 3, 15 => 5), 'rfv' => array(2 => 8, 4 => 3)),
  12=>array('Kleines Handelsschiff',         2000,   2000,   0,      0,      1000,   'Kleines Raumschiff fast ohne Bewaffnung',                                                                                                            'B13=3, F1=1',                    1,       5,          10000,   5900*4,     10,       1,        0,        0,    'rf' => array(3 => 5, 15 => 5)),
  13=>array('Gro&szlig;es Handelsschiff',    8000,   2000,   0,      5000,   5000,   '',                                                                                                                                                   'B13=6, F3=5',                    5,       50,         50000,   4500*4,     30,       3,        1,        0,    'rf' => array(3 => 5, 15 => 5)),
  14=>array('Lunares Sternenschiff',         10000000,8000000,  40000000,    12000000,      12000000,  'M&auml;chtigste aber langsamste Kampfstation des Universums',                                    'B13=25, F1=15, F2=15, F3=15, F4=15, F5=22, F6=22, F7=22, F18=1',   200000,  150000,     2500000, 400,       1,        4,        10,       10,   'rf' => array(1 => 1250, 2 => 1250, 3 => 1250, 4 => 500, 5 => 200, 6 => 200, 7 => 1000, 8 => 1250, 9 => 50, 10 => 35, 11 => 25, 12 => 1000, 13 => 250, 15 => 1250, 16 => 5, 101 => 35, 102 => 5), 'rfv' => array(1 => 1250, 2 => 1000, 3 => 333, 4 => 60, 5 => 50, 6 => 35, 7 => 25)),
  15=>array('Solar Satelit',                   0,      2000, 500,   1000,      600, 'Erzeugt Energie',                                                                                                                                        'B13=1',                       1,       10,         0,       1,        0,        1,        0,        0),
  16=>array('Imperialer Transporter',        300000,   200000,   100000,     150000,   50000,   '',                                                                                                                         'B13=22, F4=6, F9=15, F10=15',                   50,      200,        1000000, 3200*4,     55,       4,        1,        0,    'rf' => array(3 => 5, 15 => 5)),
  101=>array('EMP-Bomber',                   25000,   50000,        0, 15000, 50000,  'Schnellstes Kriegsschiff',                                                                                                                           'B13=1',                         3000,    1500,       2000,    2700*4,     75,       3,        2,        10,   'rf' => array(2 => 2, 3 => 5, 7 => 2, 8 => 2, 9 => 2, 10 => 2, 12 => 2, 13 => 2, 15 => 5, 16 => 2, 102 => 20), 'rfv' => array(1 => 2, 2 => 2, 3 => 2, 4 => 2, 5 => 2, 6 => 2, 7 => 2)),
  102=>array('War-Drainer',                  150000,  250000,   1000000, 50000, 75000,  'St&auml;rkstes Raumschiff',                                                                                                                        'B13=1',                         30000,   10000,      1400,    1500*4,     400,      4,        5,        15,   'rf' => array(1 => 3, 2 => 3, 3 => 5, 4 => 3, 5 => 3, 6 => 3, 7 => 3, 8 => 3, 9 => 3, 10 => 3, 12 => 3, 13 => 3, 15 => 5, 16 => 3),            'rfv' => array(1 => 3, 2 => 3, 3 => 3, 4 => 3, 5 => 3, 6 => 3, 7 => 3)),
  );
  /**
* Verteidungstuerme:
* Jeweils ein Element ist ein Turm.
* Jedes Element muss eine eindeutige ID kriegen.
* Jeder Turm erhaelt einen Array, der wie folgt aufgebaut ist.
* $y => Index des Schiffs, sollte NIE geaendert werden.
* $x[0] muss der Name als String sein.  (z.B. 'Raks')
* $x[1] sind die Kosten an $RESNAME[0]. (z.B. 15000)
* $x[2] sind die Kosten an $RESNAME[1]. (z.B. 2000)
* $x[3] sind die Kosten an $RESNAME[2]. (z.B. 2000)
* $x[4] sind die Kosten an $RESNAME[3]. (z.B. 0)
* $x[5] ist die Bauzeit in Sekunden.    (z.B. 2000)
* $x[6] ist eine kurze Beschreibung.    (z.B. '')
* $x[7] sind die Vorrausetzungen als IKF:
* Jede Vorrausetzung wird mit ', ' von der naechsten getrennt.
* ID und Stufe werden mit einem '=' getrennt.
*  Beispiel: '7=10'
* Bedeutet, dass der Turm die Stufe 10 der Forschung mit der ID 7 braucht (VExplosivgeschosse 1)
*  (!) Sind keine Vorrausetzungen, wird $x[7] NICHT ausgelassen, sondern leer
 $x[8] ist der Angriffswert             (z.B. 4500)
 $x[9] ist der Verteidigungswert        (z.B. 8100)
* $x[10] erfahrungslevel
* $x[11] labor
*  Die Gebaeude werden in der Reihenfolge ihrer Auflistung hier
* in den Menues (Produktion, Technik) angezeigt.
*/     
  $_VERT=array(
  //        Name                    Res1    Res2    Res3    Res4    Bauzeit    Beschreibung                            Techs                    Att        Deff    erfahrung    Labor
  1=>array('Raketengesch&uuml;tz',  2000,    0,     0,        0,        200,    'Billigste Verteidigunsanlage',        'B14=1',                 80,        40,     0,            0),
  2=>array('Leichter Laserturm',    3000,    1000,  0,        0,        1000,    'Leichter Abwehrturm',                'B14=3',                 160,       100,    1,            2),
  3=>array('Schwerer Laserturm',    7000,    3000,  2000,     0,        5000,    'Verbesserter Abwehrturm',            'B14=5, F5=5',           350,       150,    0,            0),
  4=>array('Elektronenkanone',      50000,   10000, 30000,    0,        10000,    'Neues verbessertes Gesch&uuml;tz',    'B14=6, F5=8',         3000,      1500,   0,            0),
  5=>array('EMP-Werfer',            10000,   30000, 30000,    5000,        20000,    'Sendet EMP-Wellen aus',            'B14=9, F6=8',         2000,      1000,   0,            0),
  6=>array('Plasmaturm',            15000,   30000, 45000,    10000,        30000,    'Sehr effektiv, kurze Bauzeit',        'B14=15, F6=12',   4000,      2000,   0,            0),
  7=>array('Nukleargesch&uuml;tz',  50000,   50000, 15000,    15000,    50000,    'Sehr starkes Gesch&uuml;tz',        'B14=18, F7=10',         5000,      2500,  0,            0),
  8=>array('Mikrotonenkanone',      5000000, 5000000,10000000,2500000,75000,    'St&auml;rkste Anlage',        'B14=25, F7=20, F18=1',          50000,     10000,   0,           0,  'rf' => array(1 => 550, 2 => 550, 3 => 550, 4 => 250, 5 => 100, 6 => 100, 7 => 500, 8 => 550, 9 => 25, 10 => 15, 11 => 12, 12 => 500, 13 => 125, 15 => 550, 16 => 2, 101 => 15, 102 => 2))
  );
}


$_BONUSPACKS = array(
    //Rohstoffbooster S
    1 => array(
            "type" => "resboost",
            "duration" => (3600 * 24),
            "percent" => 5,
            "cost" => 2000,
        ),
    //Rohstoffbooster M
    2 => array(
            "type" => "resboost",
            "duration" => (3600 * 24 * 7),
            "percent" => 5,
            "cost" => 10000
        ),
    //Rohstoffbooster L
    3 => array(
            "type" => "resboost",
            "duration" => (3600 * 24 * 7),
            "percent" => 10,
            "cost" => 50000
        ),    
    //Rohstoffbooster XL
    4 => array(
            "type" => "resboost",
            "duration" => (3600 * 24 * 28),
            "percent" => 10,
            "cost" => 150000
        ),
    //Cybotbeschleuniger S    
    5 => array(
            "type" => "buildspeed",
            "duration" => (3600 * 24),
            "percent" => 5,
            "cost" => 2000,
        ),
    //Cybotbeschleuniger M
    6 => array(
            "type" => "buildspeed",
            "duration" => (3600 * 24 * 7),
            "percent" => 5,
            "cost" => 10000
        ),
    //Cybotbeschleuniger L
    7 => array(
            "type" => "buildspeed",
            "duration" => (3600 * 24 * 7),
            "percent" => 10,
            "cost" => 50000
        ),    
    //Cybotbeschleuniger XL
    8 => array(
            "type" => "buildspeed",
            "duration" => (3600 * 24 * 28),
            "percent" => 10,
            "cost" => 150000
        ),
   
    //Forschbonus
    //Forschungsbeschleuniger S    
    9 => array(
            "type" => "researchspeed",
            "duration" => (3600 * 24),
            "percent" => 5,
            "cost" => 2000,
        ),
    //Forschungsbeschleuniger M
    10 => array(
            "type" => "researchspeed",
            "duration" => (3600 * 24 * 7),
            "percent" => 5,
            "cost" => 10000
        ),
    //Forschungsbeschleuniger L
    11 => array(
            "type" => "researchspeed",
            "duration" => (3600 * 24 * 7),
            "percent" => 10,
            "cost" => 50000
        ),    
    //Forschungsbeschleuniger XL
    12 => array(
            "type" => "researchspeed",
            "duration" => (3600 * 24 * 28),
            "percent" => 10,
            "cost" => 150000
        ),
    
    //Kampfbonus
    //Kampfbooster S    
    13 => array(
            "type" => "battleboost",
            "duration" => (3600 * 24),
            "percent" => 5,
            "cost" => 2000,
        ),
    //Kampfbooster M
    14 => array(
            "type" => "battleboost",
            "duration" => (3600 * 24 * 7),
            "percent" => 5,
            "cost" => 10000
        ),
    //Kampfbooster L
    15 => array(
            "type" => "battleboost",
            "duration" => (3600 * 24 * 7),
            "percent" => 10,
            "cost" => 50000
        ),    
    //Kampfbooster XL
    16 => array(
            "type" => "battleboost",
            "duration" => (3600 * 24 * 28),
            "percent" => 10,
            "cost" => 150000
        ),
);
?>
