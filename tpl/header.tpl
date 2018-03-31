<!doctype html> 
<html>
<head>
{* Wichtige Meta-Angaben *}
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="description" content="Gigra-Game.de ist eine Weltraum Simulation gepaart mit einem Echtzeit-Strategiespiel in welchem sich tausende Spieler gleichzeig gegenüber stehen. Ziel ist es in der Highscore immer weiter nach oben auf zu steigen und das Universum zu Beherrschen, zu handeln und zu regieren. Schließe Bündnisse oder Führe Krieg. Werde ein Teil von Gigra !" />
<meta name="keywords" content="Gigra, xnova, x-Nova, Weltraumsimulation, Browsergame, online, kostenlos, legendär, MMOG, Science fiction, Weltraum, Raumschiff">
<meta name="robots" content="index,follow" />
<meta name="Revisit" content="After 5 days" />
<meta name="language" content="de" />
<meta name="author" content="scrippi" />
<meta name="publisher" content="stytex.de" />
<meta name="copyright" content="stytex.de" />

{* Wichtig für !!Pinterest deaktivieren!! *}
<meta name=”pinterest” content=”nopin” />

	<title>{$title}</title>
    <script type="text/javascript" src="{$gameURL}/js/jq_1_8_3.js"></script>
    <script type="text/javascript" src="{$gameURL}/js/all_in_one.js.php"></script>

    <script type="text/javascript" src="{$gameURL}/js/langfuncs.js.php"></script>
    <script src="js/jquery-ui-1.9.1.custom.js" type="text/javascript"></script>
    
    <script type="text/javascript">
    giServerTime = {echo(time()*1000)};
    giLocalTime = new Date().getTime();
    
    function getServerTime()
    {
        //diff
        var liDiff = new Date().getTime() - giLocalTime;
        
        return giServerTime + liDiff;
    }
    </script>
    <!-- <script src="ticker.js" type="text/javascript"></script> -->
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->

<!-- ScrollPane - Anfang -->


<!--<script type="text/javascript" src="{$gameURL}/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="{$gameURL}/js/jquery.jscrollpane.min.js"></script>-->
<!-- ScrollPane - Ende -->
    
	<link rel="stylesheet" type="text/css" href="{$gameURL}/design/gigra-config.css?cache=2" />
    
    {* mobile *}
   
    <script type="text/javascript">
if (screen.width <= 699) {
    document.write(' <link rel="stylesheet" href="{$gameURL}/design/gigra-mobile.css?cache=2" /> ')
}

</script>
    
    
    <link rel="stylesheet" type="text/css" href="{$gameURL}/design/css-files/thickbox.css" />
    
    <link rel="stylesheet" type="text/css" href="{$gameURL}/design/css-files/dark-hive/jquery-ui-1.9.1.custom.min.css" />
    
    
	
	
		<!-- Begin JavaScript Slider -->
		{*<script type="text/javascript" src="slider/javascripts/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="slider/javascripts/jquery.coda-slider-2.0.js"></script>*}
		 <script type="text/javascript">
			/*
				Wichtig, bei Änderungen: Die letzte einstellung darf kein , am Ende haben :D
			*/
$(document).ready(function() {
    
    //
    $('.fleetMissionButton').mouseenter(function () {
        $('#mouseOverMission').html($(this).attr('title'));    
    });
    $('.fleetMissionButton').mouseleave(function () {
        $('#mouseOverMission').html("-");    
    });
    
	$('#coda-slider-1').codaSlider(
			{
			crossLinking: false,
			firstPanelToLoad: {if $sPHPSelf == "/galaxie.php"}4{else}3{/if}, // Welcher Container sol lals erstes angezeigt werden
			autoHeightEaseDuration: 300,
			slideEaseDuration: 600 // Je höher der Wert, umso langsamer wird es
			});
            
            
            $('#planet-carousel').jcarousel({
                // Configuration goes here
                wrap: Number($('#planetCount').html()) <= 12 ? 'first' : 'circular',
                scroll : 4,
                visible:8,
                start : Number($('#planetStartPos').html()) - 2
        });
    $('.tab2 > a').click(function () {
            chatActive = true;
            switchTab(chatActiveChannel);
            $('.tab2 > a').html(l('v3_chat'));
        });
});
        maxGal = {$actConf["maxgala"]};
        maxSys = {$actConf["maxsys"]};
        confSpeedFl = {$actConf["speed_fleet"]};
        userFlugzeitBonus = {:getFlugzeitBonus($_SESSION["uid"])};
		 </script>
	<!-- End JavaScript Slider -->
    

	
	<!-- Begin Stylesheets -->
		<link rel="stylesheet" href="slider/stylesheets/coda-slider-2.0.css" type="text/css" media="screen" />
	<!-- End Stylesheets -->


</head>
<body class="coda-slider-no-js">
{*<script type="text/javascript" src="http://refact.gigra.stytex.de/js/wz_tooltip/wz_tooltip.js"></script>
<script type="text/javascript" src="{$gameURL}/js/tooltip.js"></script>*}
<div id="wrapper">
{if loggedIn() && !simpleHeader() && !isGesperrt(Uid())}
<div id="headimage">
{include "ressbar.tpl"}
</div>
<div id="main-wrapper">

{*
<div style="height: 72px; width: 675px; font-weight: 500 ! important; background: url('http://refact.gigra.stytex.de/design/2-0/Gigra-Galaxie_bg.jpg') repeat scroll center top rgb(0, 0, 0); color: yellow; border: 2px dotted yellow;">
<center>
Werbe neue Spieler und verdiene Gigronen!
<br>
Dein pers&ouml;nlicher Werbelink: <a href="{$myURL}">{$myURL}</a>
<br>
<a href="http://bgs.gdynamite.de/charts_vote_1146.html" target="_blank"><img src="http://voting.gdynamite.de/images/gd_animbutton.gif" border="0"></a>
<a href="http://de.mmofacts.com/gigra-2077#track" class="mmofacts-widget" data-style="horizontal-counter" data-id="2077">mmofacts</a><script type="text/javascript" src="http://www.mmofacts.com/static/js/widget.js"></script>
<!-- GamesSphere Button --> <a href="http://gamessphere.de/vote/vote_365.html" target="_blank"><img src="http://gamessphere.de/vote.gif" width="88" height="31" border="0" alt="GamesSphere.de | Onlinegames für den Browser" title="Jetzt voten!"></a> <!-- GamesSphere Button --> 
</center>
</div>
*}
<div id="left-nav">
{include "navigation.tpl"}
</div>

<div id="buildInfo-box">
{:showInfoBox()}
</div>

<div id="flotten-events">
{:showFleetList()}
</div>

{if $sPHPSelf != "/galaxie.php"}
<div id="overview-box">
{:showOverview()}
</div>
{/if}
<div id="tabsystem">
{include "tabsystem.tpl"}
</div>
{if isNotify()}
<div class="notifybox">
{:showNotifies()}
</div>
{/if}
{include "notifications.tpl"}
{/if}