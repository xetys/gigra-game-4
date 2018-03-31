
<table id="galaxie_tabele">
<tr>
    <th></th>
    <th></th>
    <th>{:l('galaxy_planet')}</th>
    <th>{:l('galaxy_ally')}</th>
    <th>{:l('galaxy_tf')}</th>
    <th>{:l('galaxy_moon')}</th>
    <th>{:l('galaxy_funcs')}</th>
</tr>
{foreach $laPos as $i => $laData}

    {if !is_array($laData)}
    {if $laData == -1}
        <tr class="galaxie_tr">
            <td class="galaxy_td" colspan="7">
            {:l('galaxy_destructed')}
            </td>
            
        </tr>
    {else}
    {$coAr = explode(":",$laData)}
        <tr class="galaxie_tr">
            <!-- Auswahl des Planeten -->
            <td class="galaxie_td"><a href='javascript:toFleetCommand({$coAr[0]},{$coAr[1]},{$coAr[2]},{$coAr[3]})'><img src="{$gameURL}/design/2-0/Gigra-Arrow_Galaxie.png"></a></td>
                                            {* Unbewohnt *}
            <!-- Planetenbild -->
        	<td class="galaxie_td"><!-- {:l('galaxy_unsetteled')}-->                     <!-- Tooltip anfang -->
                        <div>
                            <a href='javascript:void(0);'><img class="galaxie_planibox_verlassen_img transparent" src="{$gameURL}/design/Planeten/kein_planet.png" width="40"><!-- Hier beginnt der Tooltip Link -->
                                <div class="tooltip" style="text-align:left">
                                <a href="#"></a> <!-- Sowas wie ein Reset für den Tooltip Inhalt ;)  -->
                                    <h1 class="tooltip_h1">{:l('galaxy_unsetteled')}</h1> <!-- Tooltip Header -->
                                    <div class="tooltip_content"> <!-- Tooltip Content -->
                                            
                                    </div><!-- Tooltip Content Ende -->
                                </div>
                            </a><!-- Hier endet der Tooltip Link -->
                        </div>
                    <!-- Tooltip Ende --></td>
            
            <!-- Von wem besiedelt -->
            <td id="planet_{:str_replace(':','_',$laData)}">
                <div class="planbox gray">
                    [{$laData|coordFormat}] <br />
                    
                </div>
            </td>
            
            <!-- Allianz -->
            <td class="galaxie_td">-</td>
            
            <!-- Trümmerfeld -->
            <td class="galaxie_td">-</td>
            
            <!-- Mond -->
           <td class="galaxie_td">-</td>
            
            <!-- Funktionen -->
            <td class="galaxie_td">-</td>
        </tr>
    {/if}
    {elseif $laData["coords"] == "1:1:10:1"}
    {* GIGRANIA *}
    <tr class="galaxie_tr_player">
        <!-- Auswahl des Planeten -->
        <td class="galaxie_td"><a href='javascript:toFleetCommand({$laData["coAr"][0]},{$laData["coAr"][1]},{$laData["coAr"][2]},{$laData["coAr"][3]})'><img src="{$gameURL}/design/2-0/Gigra-Arrow_Galaxie.png"></a></td>
        <td>
            <div>
            <a href='javascript:void(0);'>
                <img src="{$gameURL}/design/Planeten/{$laData['pbild']}" width="40" />
            </a>
            </div>
        </td>
        
        <td colspan="4">
        {$laData["pname"]}
        </td>
        
         <!-- Funktionen -->
        <td class="galaxie_td">
        <a href="javascript:void(0);" onclick="sendProbes('{$laData['coords']}')"><img src="{$gameURL}/design/2-0/Gigra_spy_small.png"></a>
        {if $inRange}
            <a href="sensorturm.php?height=600&width=300&modal=true&scann={$laData['coords']}" class="thickbox"><img width="20" src="{$gameURL}/design/2-0/SenSorTurm_small.png" /></a>
        {/if}
        </td>
    {* GIGRANIA *}
    </tr>    
    {else}
    {$coAr = explode(":",$laData["coords"])}
    <tr class="galaxie_tr_player">
        <!-- Auswahl des Planeten -->
        <td class="galaxie_td"><a href='#sendFleetA' onclick="toFleetCommand({$laData["coAr"][0]},{$laData["coAr"][1]},{$laData["coAr"][2]},{$laData["coAr"][3]})"><img src="{$gameURL}/design/2-0/Gigra-Arrow_Galaxie.png"></a></td>
        
        <!-- Planetenbild -->
        <td class="galaxie_td">
                     <!-- Tooltip anfang -->
                        <div>
                            <a href='javascript:void(0);'><img src="{$gameURL}/design/Planeten/{$laData['pbild']}" width="40" /> <!-- Hier beginnt der Tooltip Link -->
                                <div class="tooltip" style="text-align:left">
                                <a href="#"></a> <!-- Sowas wie ein Reset für den Tooltip Inhalt ;)  -->
                                    <h1 class="tooltip_h1">{$laData["name"]}</h1> <!-- Tooltip Header -->
                                    <div class="tooltip_content"> <!-- Tooltip Content -->
                                        {:l('galaxy_rank')}: {$laData["rank"]}<br />
                                        {:l('galaxy_ally')}: [<a href="allianzen.php?ally={$laData["allyid"]}">{$laData["tag"]}</a>]
                                    </div><!-- Tooltip Content Ende -->
                                </div>
                            </a><!-- Hier endet der Tooltip Link -->
                        </div>
                    <!-- Tooltip Ende -->
        </td>
        
        <!-- Von wem besiedelt -->
        <td id="planet_{:str_replace(':','_',$laData['coords'])}">
            <div class="planbox">
    		
                    <b>{$laData["pname"]|htmlentities}[{$laData["coords"]|coordFormat}]</b><br />
                    {$umod = checkUMOD($laData["uid"])}
                    {$inaktiv = isInactive($laData["uid"])}
                    {$gesperrt = isGesperrt($laData["uid"])}
                    <a href="playercard.php?u={$laData["uid"]}"{if $umod} class="blue"{elseif $inaktiv} class="gray"{/if}>{if $gesperrt}<strike>{/if}{$laData["name"]}{if $gesperrt}</strike>{/if}{if $umod}(u){/if}{if $inaktiv}(i){/if}</a>







      
            </div>
        </td>
            <!-- Allianz -->
            <td class="galaxie_td">{if !empty($laData["tag"])}[<a href="allianzen.php?ally={$laData["allyid"]}">{$laData["tag"]}</a>]{else}-{/if}</td>
            
            <!-- Trümmerfeld -->
            <td class="galaxie_td" id="planet_{$coAr[0]}_{$coAr[1]}_{$coAr[2]}_3">
            {if ($laData['tf1']+$laData['tf2']+$laData['tf3']+$laData['tf4']) > 1000}
                    <!-- Tooltip anfang -->
                        <div>
                            <a href='javascript:toFleetCommand({$laData["coAr"][0]},{$laData["coAr"][1]},{$laData["coAr"][2]},3)'><img src="{$gameURL}/design/2-0/Gigra-Arrow_Galaxie.png"></a>
                            <a href='javascript:void(0);'><img src="{$gameURL}/design/Planeten/debris.png" width="40"> <!-- Hier beginnt der Tooltip Link -->
                                <div class="tooltip" style="text-align:left">
                                <a href="#"></a> <!-- Sowas wie ein Reset für den Tooltip Inhalt ;)  -->
                                    <h1 class="tooltip_h1">Trümmerfeld</h1> <!-- Tooltip Header -->
                                    <div class="tooltip_content"> <!-- Tooltip Content -->
                                        {if $laData['tf1'] > 0.999}{:l('res1')}: {$laData['tf1']|nicenum}<br>{/if}
                                        {if $laData['tf2'] > 0.999}{:l('res2')}: {$laData['tf2']|nicenum}<br>{/if}
                                        {if $laData['tf3'] > 0.999}{:l('res3')}: {$laData['tf3']|nicenum}<br>{/if}
                                        {if $laData['tf4'] > 0.999}{:l('res4')}: {$laData['tf4']|nicenum}<br>{/if}
                                    </div><!-- Tooltip Content Ende -->
                                </div>
                            </a><!-- Hier endet der Tooltip Link -->
                        </div>
                    <!-- Tooltip Ende -->
            {/if}
            </td>
    
            <!-- Mond -->
           <td class="galaxie_td" id="planet_{$coAr[0]}_{$coAr[1]}_{$coAr[2]}_2">
           {if isset($laData['mond'])}
<!-- Tooltip anfang -->

<div>
<a href='javascript:void(0);'><!-- Hier beginnt der Tooltip Link -->
           <a href='javascript:toFleetCommand({$coAr[0]},{$coAr[1]},{$coAr[2]},2)'><img src="{$gameURL}/design/2-0/Gigra-Arrow_Galaxie.png"></a>
           <a href="javascript:void(0);"> <img src="{$gameURL}/design/Planeten/mond.png" width="40"> </a>
             <div class="tooltip" style="text-align:left">
                                <a href="#"></a> <!-- Sowas wie ein Reset für den Tooltip Inhalt ;)  -->
                                     <h1 class="tooltip_h1">{$laData["name"]}</h1> <!-- Tooltip Header -->
                                    <div class="tooltip_content"> <!-- Tooltip Content -->
                                             {:l('v3_diameter')}: {$laData['mond']['dia']|nicenum}
                                    </div><!-- Tooltip Content Ende -->
            </div>
           {/if}
           </td>
            
            <!-- Funktionen -->
            <td class="galaxie_td">
            {if !$laData['myPlanet']}
                <a href="javascript:void(0);" onclick="sendProbes('{$laData['coords']}')"><img src="{$gameURL}/design/2-0/Gigra_spy_small.png"></a>
            {/if}
            {if $inRange}
                <a href="sensorturm.php?height=600&width=300&modal=true&scann={$laData['coords']}" class="thickbox"><img width="20" src="{$gameURL}/design/2-0/SenSorTurm_small.png" /></a>
            {/if}
            </td>
    </tr>
{/if}
{/foreach}
</table>

<!-- Tooltip anfang -->
                        <div>
                            <a href='javascript:void(0);'><!-- Hier beginnt der Tooltip Link -->
                                <div class="tooltip" style="text-align:left">
                                <a href="#"></a> <!-- Sowas wie ein Reset für den Tooltip Inhalt ;)  -->
                                    <h1 class="tooltip_h1">{:l('galaxy_unsetteled')}</h1> <!-- Tooltip Header -->
                                    <div class="tooltip_content"> <!-- Tooltip Content -->
                                            
                                    </div><!-- Tooltip Content Ende -->
                                    
                                    
</div>                                