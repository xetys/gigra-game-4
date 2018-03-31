{*
<div id="planeten-box">

{$liPos = 1}
{$liStartPos = 1}
<ul class="planet-slide jcarousel-skin-tango" id="planet-carousel">
{foreach $laPlaneten as $laPlanet}
<li class="cloudcarousel{if $laPlanet["coords"] == $_SESSION["coords"]} planet-active{/if}">
    [{$laPlanet["coords"]|substr::0,-2}]<br><a href="{$sPHPSelf}?change={$laPlanet["coords"]}"><img src="{$laPlanet["bild"]}"></a><br>{$laPlanet["name"]}
    <!--
    <div class="planet-overlay">
        {foreach $laPlanet["events"] as $bID => $bS}
            <img src="{$gameURL}/design/items/b{$bID}.gif" width="25" height="25">
        {/foreach}
    </div>
    -->
</li>
{if $laPlanet["coords"] == $_SESSION["coords"]}
    {$liStartPos = $liPos}
{/if}
{$liPos = $liPos+1}
{/foreach}
</ul>
<div class="hidden" id="planetStartPos">{$liStartPos}</div>
<div class="hidden" id="planetCount">{$liPos}</div>
<div class="clear"></div>

<!--
{foreach $laPlaneten as $laPlanet}
<div class="cloudcarousel{if $laPlanet["coords"] == $_SESSION["coords"]} planet-active{/if}" style="text-align:center;">
    [{$laPlanet["coords"]|substr::0,-2}]<br><a href="{$sPHPSelf}?change={$laPlanet["coords"]}"><img src="{$laPlanet["bild"]}" width="80"></a>  
</div>
{/foreach}
-->
</div>
*}
<div id="planeten-box">
{foreach $laPlaneten as $k => $laPlanet}
{if $laPlanet["coords"] == $_SESSION["coords"]}
    {if $k == 0}
        {$laPrevPlanet = $laPlaneten[count($laPlaneten)-1]}
    {else}
        {$laPrevPlanet = $laPlaneten[$k-1]}
    {/if}
    {$laCrntPlanet = $laPlanet}
    {if $k+1 == count($laPlaneten)}
        {$laNextPlanet = $laPlaneten[0]}
    {else}
        {$laNextPlanet = $laPlaneten[$k+1]}
    {/if}
{/if}
{/foreach}
<table width="100%">
<tr>
    <td class="tcenter" width="33%">
    {if count($laPlaneten) > 2}
        <a href="{$sPHPSelf}?change={$laPrevPlanet["coords"]}">
            <img src="{$laPrevPlanet["bild"]}" width="40"><br>
            [{$laPrevPlanet["coords"]|substr::0,-2}] - {$laPrevPlanet['name']}
            <div class="planet-overlay">
            {foreach $laPrevPlanet["events"] as $bID => $bS}
                <img src="{$gameURL}/design/items/b{$bID}.gif" width="25" height="25">
            {/foreach}
            </div>
        </a>
    {/if}
    </td>
    <td class="tcenter" width="34%">
        <img src="{$laCrntPlanet["bild"]}" width="80">
    </td>
    <td class="tcenter" width="33%">
        {if count($laPlaneten) > 1}
        <a href="{$sPHPSelf}?change={$laNextPlanet["coords"]}">
            <img src="{$laNextPlanet["bild"]}" width="40"><br>
            [{$laNextPlanet["coords"]|substr::0,-2}] - {$laNextPlanet['name']}
            <div class="planet-overlay">
            {foreach $laNextPlanet["events"] as $bID => $bS}
                <img src="{$gameURL}/design/items/b{$bID}.gif" width="25" height="25">
            {/foreach}
            </div>
        </a>
        {/if}
    </td>
</tr>
<tr>
    <td colspan="3" class="tcenter">
    {if count($laPlaneten) > 1}    
        <select onchange="window.location.href='{$sPHPSelf}?change=' + this.value;">
        {foreach $laPlaneten as $k => $laPlanet}
            <option value="{$laPlanet["coords"]}"{if $laPlanet["coords"] == $_SESSION["coords"]} selected{/if}>{$laPlanet["coords"]|substr::0,-2} - {$laPlanet['name']}{if count($laPlanet['events']) > 0}&nbsp;&bull;{/if}</option>
        {/foreach}
    {else}
        <p>{$laPlanet["coords"]|substr::0,-2} - {$laPlanet['name']}{if count($laPlanet['events']) > 0}&nbsp;&bull;{/if}</p>
    {/if}
    </select>
    </td>
</tr>
</table>
</div>