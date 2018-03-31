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


//selbst gesperrt?
$verwarn = verwarnStatus($_SESSION["uid"]);
$vw = false;
if($verwarn["vw"] > 0)
{
    $vw = true;
}
if ($vw)
{
    $vrow = $lodb->getOne("SELECT * from verwarnung WHERE uid = '{$_SESSION["uid"]}' ORDER BY verwarndat DESC LIMIT 1");
	$info = "Ihr Account wurde am %s von %s verwarnt mit folgender Begr&uuml;ndung:<br><strong>%s</strong><br><br>Dies hat nun folgende Konsequenzen:<br><br> %s";
	$konseq = "";
	/*switch ($verwarn["vw"])
	{
		case 1: 
			$konseq = "Keine Konsequenzen, erst ab n&auml;chster Verwarnung";
			$class = "c";
			$headtext = "1 Verwarnung";
			break;
		case 2:
			$konseq = "Ihr Account ist bis ".date("d.m.Y H:i:s",$verwarn["free"]) . " gesperrt. Ihr Planet kann angegriffen werden";
			$class = "f";
			$headtext = "Ihr Account ist gesperrt";
			break;
		case 3:
			$konseq = "Ihr Account ist bis ".date("d.m.Y H:i:s",$verwarn["free"]) . " vom Chat ausgeschlossen.";
			$class = "f";
			$headtext = "Ihr Account nimmt nicht mehr am Chat teil !";
			break;
		case 4:
			$konseq = "Ihr Account ist bis ".date("d.m.Y H:i:s",$verwarn["free"]) . " gesperrt. Ihr Planet kann nicht angegriffen werden(Urlaubsmodus)";
			$class = "f";
			$headtext = "Ihr Account ist gesperrt";
			break;
		default:
			$konseq = "Sie wurden des Spiels verwiesen. Auf diesen Account haben Sie kein Zugriff mehr und er wird gel&ouml;scht.";
			$class = "f";
			$headtext = "Ihr Account ist gesperrt";
			break;
			
	}*/
	/*$info = sprintf($info,date("d.m.Y H:i:s",$vrow["verwarndat"]),$vrow["admin"],$vrow["verwarntext"],$konseq);
	?>
	<table class="tabelle_v3">
		<tr><td class="<?=$class?>"><?=$headtext?></th></tr>
		<tr>
			<th>
				<?=$info?>
			</th>
		</tr>
	</table>
	<?*/
    $gesperrt['verwarndat'] = date("d.m.Y H:i:s",$vrow["verwarndat"]);
    $gesperrt['admin'] = $vrow["admin"];
    $gesperrt['verwarntext'] = $vrow["verwarntext"];
    $gesperrt['stufe'] = $verwarn["vw"];
    $gesperrt['bis'] = date("d.m.Y H:i:s",$verwarn["free"]);
    
	$lodb->query("UPDATE verwarnung SET verwarnung.read = 1 WHERE uid = '{$_SESSION["uid"]}'") or die(mysql_error());
}


//Pranger
$lodb->query("SELECT v1.uid,v1.uname as name,v1.wertigkeit,v1.verwarndat,v1.verwarntext,v1.admin,(SELECT SUM(wertigkeit) FROM verwarnung v2 WHERE v2.verwarndat <= v1.verwarndat AND v2.verwarndat > (v1.verwarndat - (3600 * 24 * 28 * 6)) AND v2.uid = v1.uid) AS verwarnbevor FROM verwarnung v1 LEFT JOIN users ON v1.uid = users.id ORDER BY v1.verwarndat DESC");
while ($row = $lodb->fetch())
{
	extract($row);
	$verwarnStatus = verwarnStatus($uid);
	$bis = -1;
	if($verwarnbevor > 1)
		$bis = $verwarndat + (3600 * 24);
	
	if($verwarnbevor > 2)
		$bis = $verwarndat + (3600 * 24 * 7);
	
	if($verwarnbevor > 3)
		$bis = $verwarndat + (3600 * 24 * 14);
	if($verwarnbevor > 4)
		$bis = -2;
	
	
	$verwarndat = date("d.m.Y H:i:s",$verwarndat);
		
	$bisD = $bis > -1 ? date("d.m.Y H:i:s",$bis) : ($bis == -1 ? "-" : "Ausschluss");
    
    $verwarnungen[] = array('name'=> $name, 'wertigkeit' => $wertigkeit, 'verwarndat' => $verwarndat, 'verwarntext' => $verwarntext, 'admin' => $admin, 'bisD' => $bisD);
    
	//echo "<tr><th>$name</th><th>$wertigkeit</th><th>$verwarndat</th><th>$verwarntext</th><th>$admin</th><th>$bisD</th></tr>";
}
$laTplExport['gesperrt' ]= $gesperrt;
$laTplExport['verwarnungen'] = $verwarnungen;

buildPage("verwarnung.tpl", $laTplExport);
?>
</table>
<b>SEI EHRLICH!</b>
