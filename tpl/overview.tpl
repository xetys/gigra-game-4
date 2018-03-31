							<!-- �bersicht -->		
                            <!-- 
										<div class="left div_overview" style="background:#000 url({$lsPlanetBild}) no-repeat center;">
											<table style="font-size:9px;width:100%;" width="100%" class="tbl_overview">
                            -->
                                        {*<div id="BuildOver-Box" style="background:#000 url({$lsPlanetBild}) no-repeat center;">*}
                                        <div id="BuildOver-Box" style="background:#000 url(design/3/plani-1-bg.jpg) no-repeat center;">
                                        <table width="650">
                                        <tr>
                                            
                                            <td width="450" height="250">
    											<table class="BuildOver-Box_Table">
                                                    <!-- <th colspan="2">{:l('v3_overview')}</th> -->
                                                    
    												<tr>
    													<td width="100" class="gray">{:l('v3_planetname')}</td>
    													<td >{$lsPlanetName|htmlentities} <a href="planetsettings.php?modal=true&width=330&height=190" class="thickbox"><pan class="class_btn">{:l('nav_settings')}</span></a></td>
    												</tr>
    												<tr>
    													<td class="gray">{:l('v3_accountname')}</td>
    													<td >{$_SESSION['name']|htmlentities}</td>
    												</tr>
    												<tr>
    													<td class="gray">{:l('v3_coords')}</td>
    													<td >{$_SESSION['coords']|coordFormat}</td>
    												</tr>
    												<tr>
    													<td class="gray">{:l('v3_diameter')}</td>
    													<td >{$liDia} km</td>
    												</tr>
    												<tr>
    													<td class="gray">{:l('v3_temp')}</td>
    													<td >{$liTempFrom}&deg;C {:l('v3_to')} {$liTempTo}&deg;C</td>
    												</tr>
    												<!--
    												<tr>
    													<td >Bewohner</td>
    													<td ><i>in Arbeit</i></td>
    												</tr>
    												<tr>
    													<td >Soldaten</td>
    													<td ><i>in Arbeit</i></td>
    												</tr>
    												-->
    												<tr>
    													<td class="gray">{:l('v3_score')}</td>
    													<td ><a href="v3_highscore.php?start={$liRank}">{$liScore|nicenum}({:l('v3_rank')} {$liRank|nicenum} {:l('v3_of')} {$liUserCount|nicenum})</a></td>
    												</tr>
    												<tr>
    													<td class="gray">{:l('v3_level')}</td>
    													<td >{$liLevel} ({$liHasXP|nicenum}/{$liAllXP|nicenum} EP)</td>
    												</tr>
                                                    {*
                                                    <tr>
                                                        <td class="gray">{:l('v3_resbonus')}</td>
                                                        <td>+{$resbonus_skill}% {:l('exp_skills')}
                                                            {if isset($boost_percent)}, 
                                                                <span class="green">+{$boost_percent}%({$boost_left|format_zeit})</span>
                                                            {else}
                                                                <span class="red">{:l('v3_inactive')}</span>
                                                            {/if}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="gray">{:l('v3_buildbonus')}</td>
                                                        <td>
                                                        +{$baubonus_skill}% {:l('exp_skills')}
                                                        {if isset($bau_percent)},
                                                                <span class="green">+{$bau_percent}%({$bau_left|format_zeit})</span>
                                                        {else}
                                                                <span class="red">{:l('v3_inactive')}</span>
                                                            {/if}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="gray">{:l('v3_forschbonus')}</td>
                                                        <td>+{$forschbonus_skill}% {:l('exp_skills')}
                                                        {if isset($forsch_percent)},
                                                                <span class="green">+{$forsch_percent}%({$forsch_left|format_zeit})</span>
                                                        {else}
                                                            <span class="red">{:l('v3_inactive')}</span>
                                                            {/if}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="gray">{:l('v3_combatbonus')}</td>
                                                        <td>
                                                        {if isset($kampf_percent)}
                                                            <span class="green">+{$kampf_percent}%({$kampf_left|format_zeit})</span>
                                                        {else}
                                                            <span class="red">{:l('v3_inactive')}</span>
                                                        {/if}</td>
                                                    </tr>
                                                    *}
                                                    
    											</table>
                                                <br>
                                                <br>
                                                <br>
                                               
                                                <div class="left{if !isset($boost_percent)} lowvisible{/if}" onmouseover="$('#bonus_tooltip').show();$('#bonus_tooltip_res').show();" onmouseout="$('.bonus_tt_i').hide();">
                                                    <a href="javascript:void();" onclick="showBonusBox(1)"><img src="design/3/gg_item_res.png" width="40"></a>
                                                </div>
                                                <div class="left{if !isset($bau_percent)} lowvisible{/if}" onmouseover="$('#bonus_tooltip').show();$('#bonus_tooltip_cybot').show();" onmouseout="$('.bonus_tt_i').hide();">
                                                    <a href="javascript:void();" onclick="showBonusBox(5)"><img src="design/3/gg_item_cybot.png" width="40"></a>
                                                </div>
                                                <div class="left{if !isset($forsch_percent)} lowvisible{/if}" onmouseover="$('#bonus_tooltip').show();$('#bonus_tooltip_forsch').show();" onmouseout="$('.bonus_tt_i').hide();">
                                                    <a href="javascript:void();" onclick="showBonusBox(9)"><img src="design/3/gg_item_forsch.png" width="40"></a>
                                                </div>
                                                <div class="left{if !isset($kampf_percent)} lowvisible{/if}" onmouseover="$('#bonus_tooltip').show();$('#bonus_tooltip_kampf').show();" onmouseout="$('.bonus_tt_i').hide();">
                                                    <a href="javascript:void();" onclick="showBonusBox(13)"><img src="design/3/gg_item_kampf.png" width="40"></a>
                                                </div>
                                                <div class="clear">
                                                <div id="bonus_tooltip">
                                                    <!-- Rohstoffbonus --->
                                                    <div class="bonus_tt_i hidden" id="bonus_tooltip_res">
                                                        {:l('v3_resbonus')}:
                                                        +{$resbonus_skill}% {:l('exp_skills')}
                                                        {if isset($boost_percent)}, 
                                                            <span class="green">+{$boost_percent}%({$boost_left|format_zeit})</span>
                                                        {else}
                                                            <span class="red">{:l('v3_inactive')}</span>
                                                        {/if}
                                                    </div>
                                                    
                                                    <!-- Baubonus --->
                                                    <div class="bonus_tt_i hidden" id="bonus_tooltip_cybot">
                                                    {:l('v3_buildbonus')}:
                                                    +{$baubonus_skill}% {:l('exp_skills')}
                                                        {if isset($bau_percent)},
                                                                <span class="green">+{$bau_percent}%({$bau_left|format_zeit})</span>
                                                        {else}
                                                                <span class="red">{:l('v3_inactive')}</span>
                                                        {/if}
                                                    </div>
                                                    
                                                    <!-- Forschbonus --->
                                                    <div class="bonus_tt_i hidden" id="bonus_tooltip_forsch">
                                                        {:l('v3_forschbonus')}:
                                                        +{$forschbonus_skill}% {:l('exp_skills')}
                                                        {if isset($forsch_percent)},
                                                                <span class="green">+{$forsch_percent}%({$forsch_left|format_zeit})</span>
                                                        {else}
                                                            <span class="red">{:l('v3_inactive')}</span>
                                                        {/if}
                                                    </div>
                                                    
                                                    <!-- Kampfbonus -->
                                                    <div class="bonus_tt_i hidden" id="bonus_tooltip_kampf">
                                                        {:l('v3_combatbonus')}:
                                                        {if isset($kampf_percent)}
                                                            <span class="green">+{$kampf_percent}%({$kampf_left|format_zeit})</span>
                                                        {else}
                                                            <span class="red">{:l('v3_inactive')}</span>
                                                        {/if}
                                                    </div>
                                                </div>
                                                </td>
                                                <td align="right" style="text-align:right;vertical-align:top">
                                                {if hasMoon()}
                                                    <a href="{$sPHPSelf}?change={:substr($_SESSION["coords"],0,-1)}2"><img src="{$gameURL}/design/3/mond-bg.png" width="80"></a>
                                                {/if}
                                                </td>
                                            </tr>
                                        </table>
										</div>
							<!-- �bersicht Ende -->