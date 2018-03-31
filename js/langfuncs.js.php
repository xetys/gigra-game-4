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
define("GIGRA_MUSTSESSION",false);

include '../core/core.php';
header("Content-type:text/javascript");

?>
function rt(sek)
{
  d=Math.floor(sek/86400);
  h=Math.floor((sek-d*86400)/3600);
  m=Math.floor((sek-d*86400-h*3600)/60);
  s=Math.floor((sek-d*86400-h*3600-m*60));
  if(s<10) s='0'+s;
  if(m<10) m='0'+m;
  if(h<10) h='0'+h;
  var lsDay = '<?php echo l('day')?>';
  var lsDays = '<?php echo  l('days')?>';
  return ((d>0)?(d+((d>1)? ' ' + lsDays:lsDay)+', '):'')+h+':'+m+':'+s;
}
<? 
$lang = getLangFull()
?>

lang = new Array();
<? 
foreach($lang as $k => $v)
{
?>
    lang['<?php echo $k?>'] = '<?php echo addslashes(str_replace(PHP_EOL,'',$v))?>';
<?
}
?>

function l(key)
{      
      return lang[key];
}