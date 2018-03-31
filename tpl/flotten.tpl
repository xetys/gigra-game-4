{$sPHPSelf = $_SERVER['PHP_SELF']}
<div id="fleet-panel">
<div id="fleet-select">
    <a name="sendFleetA"></a>
    <form action="ajax.php" method="post" name="chPlan">
    <input type="hidden" name="type" value="flotten">
    <select name="coords" onchange="changeFleetPlan()">
    {foreach $laCoords as $lsCoords => $lsFormatted}
        <option value="{$lsCoords}"{if $lsCoords == $lsSelected} selected{/if}>{$lsFormatted}</option>
    {/foreach}
    </select>
        
    </form>
    
    <input type="hidden" id="fromG" value="{$coAr[0]}">
    <input type="hidden" id="fromS" value="{$coAr[1]}">
    <input type="hidden" id="fromP" value="{$coAr[2]}">
    <input type="hidden" id="fromT" value="{$coAr[3]}">
    
    <!-- Flottenliste im Tab Anfang -->
    <table class="flottenliste_tab">
    <tr>
    {$i=0}
    {foreach $laSchiffe as $lsID => $laData}
    {$i = $i+1}
        {$lsLangKey = "item_s".$lsID}
        <input type="hidden" id="speed_s{$lsID}" value="{$laData["speed"]}">
        <input type="hidden" id="consum_s{$lsID}" value="{$laData["consum"]}">
        <input type="hidden" id="capa_s{$lsID}" value="{$laData["capa"]}">
        <td width="40"><img src="{$gameURL}/design/items/s{$lsID}.gif" width="40"></td>
        <td width="290">{:l($lsLangKey)}({$laData["count"]|nicenum})</td>
        {if $lsID != 15}
            <td style="width:15px"><a href="javascript:void(0);" onclick="ID('s{$lsID}').value='{$laData["count"]}';" class="maxlink"> max </a></td>
            <td style="width:10px"><input class="div_konstruktion_input shipSelect" id="s{$lsID}" type="text" size="4" name="s{$lsID}" value="0" onfocus="if(this.value=='0')this.value='';" onblur="if(this.value=='')this.value='0';"></td>
        {else}
            <td style="text-align:right;" colspan="2"></td>
        {/if}
    {if $i%2 == 0}
        </tr>
    {/if}
    {/foreach}
    </table>
    <!-- Flottenliste im Tab Ende -->

        
        <input type="button" class="div_konstruktion_input" value="{:l('fleet_noship')}" onclick="noShip();">
        <input type="button" class="div_konstruktion_input" value="{:l('fleet_allship')}" onclick="allShip();">

</div>
    <div id="fleet-target">
    <center>
        <table>
            <tr><td>{:l('fleet_from')}:</td><td>
            <!--
                <div class="planbox">
                <table>
                <tr>
                <td><img src="{$gameURL}/design/Planeten/{$laPlanet["pbild"]}" width="40"></td>
                <td>
                    <b>{$laPlanet["pname"]}[{$laPlanet["coords"]|coordFormat}]</b><br>
                    <a href="#">{$laPlanet["name"]}</a>
                </td>
                </tr>
                </table>
            </div>
            -->
            {:showPlanetInfo($laPlanet["coords"])}
            </td></tr>
            <tr><td>{:l('fleet_to')}:</td>
            <td id="fleet_command_to">
            {if $sPHPSelf != "/galaxie.php"}
                <br>
                <div id="manual">
                    <input type="text" class="div_konstruktion_input" size="1" id="toG">:<input type="text" class="div_konstruktion_input" size="3" id="toS">:<input type="text" class="div_konstruktion_input" size="2" id="toP">
                    <select id="toT" class="div_konstruktion_input">
                        <option value="1">{:l('galaxy_planet')}</option>
                        <option value="2">{:l('galaxy_moon')}</option>
                        <option value="3">{:l('tf')}</option>
                    </select>
                </div>
                <div id="fleetTargetSelector">
                
                    <select name="coords" onchange="selectCoordinates(this.value);" class="div_konstruktion_input">
                    <option value="0">{:l('fleet_pls_choose')}</option>
                    <optgroup label="{:l('fleet_my_planets')}">
                    {foreach $laCoords as $lsCoords => $lsFormatted}
                        {if $lsCoords != $lsSelected} 
                            <option value="{$lsCoords}">{$lsFormatted}</option>
                        {/if}
                    {/foreach}
                    </optgroup>
                    <optgroup label="{:l('fleet_my_targets')}">
                    {foreach $laTargets as $lsCoords => $lsDesc}
                        <option value="{$lsCoords}">{$lsDesc}</option>
                    {/foreach}
                    </optgroup>
                    </select>
                    <a href='targetliste.php?modal=true&width=650&height=300' class="thickbox"><span class="class_btn einst_btn_save">{:l('fleet_administer_targets')}</span></a>
                </div>
                <br>
                <a href='javascript:void(0)' onclick="fleetNext()"><span class="class_btn einst_btn_save">{:l('fleet_next')}</span></a>
            {/if}
            </td>
            </tr>
        </table>
    </center>
    </div>
    <div id="fleet-command" class="hidden">
        <table width="600">
            <tr><th colspan="2">{:l('fleet_sendfleet')}</th></tr>
            <tr><td>{:l('fleet_speed')}</td><td>
            <select id="speed_select" name="s" onchange="showFlyData()">
            {for $I=10;$I>0;$I--}
                <option value="{$I}">{$I}0%</option>
            {/for}
            </select>
            </td></tr>
            <tr><td>{:l('fleet_distance')}</td><td id="w">-</td></tr>
            <tr><td>{:l('fleet_duration')}</td><td id="x">-</td></tr>
            
            <tr><td>{:l('fleet_event_arrive')}</td><td id="arrive">-</td></tr>
            <tr><td>{:l('fleet_event_back')}</td><td id="back">-</td></tr>
            
            <tr><td>{:l('fleet_consumption')}</td><td id="z">-</td></tr>
            <tr><td>{:l('fleet_maxspeed')}</td><td id="mspeed">-</td></tr>
            <tr><td>{:l('fleet_capacity')}</td><td id="capaall">-</td></tr>
            <tr><th colspan="2">{:l('fleet_mission_options')}</th></tr>
            <tr><td colspan="2">
            <div class="left" id="fleetSendRessource">
                <table width="50%">
                {for $i=0;$i<4;$i++}
                    <tr>
                        <td>
                        {:l('res'.($i+1))}       
                        </td>
                        <td>
                            <input type="text" value="0" class="div_konstruktion_input" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');maxRes()" id="t{$i+1}" onfocus="if(this.value=='0')this.value='';" onblur="if(this.value=='')this.value='0';">
                        </td>
                        <td>
                        <a href="javascript:maxRes({$i+1})"><span class="class_btn">&lt;</span></a>{$laRes[$i]|nicenum}<input type="hidden" id="res{$i+1}_trans" value="{$laRes[$i]}">
                        </td>
                    </tr>
                {/for}
                </table>
                </div>
                <div class="left">
                    <div class="hidden fleetNonDefault" id="sc-hold">
                        {:l('fleet_hold_time')}:<select id="hold-time">
                        <option value="0">0 {:l('hours')}</option>
                        <option value="1">1 {:l('hour')}</option>
                        <option value="2">2 {:l('hours')}</option>
                        <option value="4">4 {:l('hours')}</option>
                        <option value="8">8 {:l('hours')}</option>
                        <option value="16">16 {:l('hours')}</option>
                        </select>
                        
                        <input type="button" onclick="sendFleet('hold','holdtime='+$('#hold-time').val());" value="{:l('fleet_send')}">
                    </div>
                    <div class="hidden fleetNonDefault" id="sc-aks">
                    {:l('fleet_aks_lead_info')}
                    <a href="javascript:void(0)" onclick="sendFleet('aks_lead');"><div class="class_btn tcenter">{:l('fleet_found_acs')}</div></a>
                    <br>
                    <select id="aks_fleetlist" class="hidden aksjoin">
                    </select>
                    <a href='javascript:void(0)' onclick="joinAKS()" class="hidden aksjoin"><div class="class_btn tcenter">{:l('fleet_join_acs')}</div></a>
                    </div>
                </div>
                <div class="clear"></div>
                <div id="fleet_options">
                    <ul>

<!-- ------------------------------------------------------------------------------------------------------------- -->
                        <li class="inactive fleetMissionButton" id="inva" title="{:l('fleet_mission_inva')}">
                            <a onmouseover="Tip('Some text')" onmouseout="UnTip()" href="javascript:sendFleet('inva');">
                                <img class="flotten_incons_img" src="design/2-0/GigraConquer-Icon.png" alt="Invasion">
                            </a>
                        </li>
<!-- ------------------------------------------------------------------------------------------------------------- -->
                        <li class="inactive fleetMissionButton" id="dest" title="{:l('fleet_mission_dest')}">
                            <a alt="Zerstören" href="javascript:sendFleet('dest');">
                                <img class="flotten_incons_img" src="design/2-0/GigraDestroy-Icon.png" alt="Zerstören">
                            </a>
                        </li>
<!-- ------------------------------------------------------------------------------------------------------------- --> 
                        <li class="inactive fleetMissionButton" id="stat" title="{:l('fleet_mission_stat')}">
                            <a onmouseover="Tip('Some text')" onmouseout="UnTip()" alt="Stationieren" href="javascript:sendFleet('stat');">
                                <img class="flotten_incons_img" src="design/2-0/GigraStationieren-Icon.png" alt="Stationieren">
                            </a>
                        </li>
<!-- ------------------------------------------------------------------------------------------------------------- -->
                        <li class="inactive fleetMissionButton" id="spio" title="{:l('fleet_mission_spio')}">
                            <a alt="Spionieren" href="javascript:sendFleet('spio');">
                                <img class="flotten_incons_img" src="design/2-0/GigraSpionage-Icon.png" alt="Spionieren">
                            </a>
                        </li>
<!-- ------------------------------------------------------------------------------------------------------------- -->                       
                        <li class="inactive fleetMissionButton" id="recy" title="{:l('fleet_mission_recy')}">
                            <a alt="Recyclen" href="javascript:sendFleet('recy');">
                                <img class="flotten_incons_img" src="design/2-0/GigraRecycling-Icon.png" alt="Recycling">
                            </a>
                        </li>
<!-- ------------------------------------------------------------------------------------------------------------- -->                      
                        <li class="inactive fleetMissionButton" id="hold" title="{:l('fleet_mission_hold')}">
                            <a alt="Halten" href="javascript:void(0);" onclick="$('#sc-hold').show('fast');">
                                <img class="flotten_incons_img" src="design/2-0/GigraHalten-Icon.png" alt="Halten">
                            </a>
                        </li>
<!-- ------------------------------------------------------------------------------------------------------------- -->                       
                        <li class="inactive fleetMissionButton" id="aks" title="{:l('fleet_mission_aks')}">
                            <a alt="AKS" href="javascript:void(0);" onclick="showAKS();">
                                <img class="flotten_incons_img" src="design/2-0/GigraAKS-Icon.png" alt="AKS">
                            </a>
                        </li>
<!-- ------------------------------------------------------------------------------------------------------------- -->                        
                        <li class="inactive fleetMissionButton" id="kolo" title="{:l('fleet_mission_kolo')}">
                            <a alt="Kolonisieren" href="javascript:sendFleet('kolo');">
                                <img class="flotten_incons_img" src="design/2-0/GigraKolo-Icon.png" alt="Kolonisieren">
                            </a>
                        </li>
<!-- ------------------------------------------------------------------------------------------------------------- -->
                        <li class="inactive fleetMissionButton" id="ag_p" title="{:l('fleet_mission_ag')}">
                            <a alt="Angreifen" href="javascript:sendFleet('ag_p');">
                                <img class="flotten_incons_img" src="design/2-0/GigraFleet-Icon.png" alt="Angreifen">
                            </a>
                        </li>
<!-- ------------------------------------------------------------------------------------------------------------- -->                        
                        <li class="inactive fleetMissionButton" id="trans" title="{:l('fleet_mission_trans')}">
                            <a alt="Transportieren" href="javascript:sendFleet('trans');">
                                <img class="flotten_incons_img" src="design/2-0/GigraTransport-Icon.png" alt="Transportieren">
                            </a>
                        </li>
<!-- ------------------------------------------------------------------------------------------------------------- -->
                    </ul>
                    <div class="clear"></div>
                    <div id="mouseOverMission">-</div>
                    
                
                    
                </div>
            </td>
            </tr>
        </table>
        <a href="javascript:cancelFleet()"><span class="class_btn">{:l('fleet_cancel')}</span></a>
    </div>
</div>
