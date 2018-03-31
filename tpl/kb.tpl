</div>
<table id="kampfbericht_table_main">
    <tr>
    	<td>

			<!-- Kampfbericht -->
			<table class="kb_first kb_global">
				<tr>
					<td colspan="100%">{$title}</td>
				</tr>
				{if !$hide}
				<!-- Eine Flotte erreichte die Koordinaten am xx.xx.xxxx um xx:xx:xx und eine Schlacht ist entstanden -->
				<tr>
					<td colspan="100%">{:l('kb_intro',$toc,$date,$time)}</td>
				</tr>
                {/if}
			</table>


			{foreach $ks as $runde => $values}
			<table class="kb_runde kb_global">
				<tr>
					<td style="text-align:center !important;"> <!-- Hier beginnt eine ganze Runde -->
								{foreach $values["a"] as $a => $data}
									<table class="kb_angreifer"> <!-- Der oder die Angreifer -->
										<tr>
											<td>
													{php}
													$f = $k["atter_data"][$a]['f'];
													for($i=5;$i<=9;$i++)
													if($i == 8) continue;
													else if(!isset($f['f'.$i])) $f['f'.$i] = 0;
													$aProz = (1 + $f['f5'] * 0.1 ) * 100;
                                                    $sProz = (1 + $f['f6'] * 0.1 ) * 100;
													$hProz = (1 + $f['f9'] * 0.1 ) * 100;
													{/php}
													
													
														<table class="kampfbericht_table">
																<tr>
																	<td colspan="100%" class="angreifer_td_head">{:l('kb_atter')} {$k["atter_data"][$a]["name"]}{if !$hide}({$k["atter_data"][$a]["coords"]|coordFormat}){/if}<br />
                                                                        {:l('kb_weapons')}:{$aProz}%{if $k["atter_data"][$a]["bonus"] > 1}<span class="gray">(+{echo($k["atter_data"][$a]["bonus"]*100 - 100)}%)</span>{/if},
                                                                        {:l('kb_shields')}:{$sProz}%{if $k["atter_data"][$a]["bonus"] > 1}<span class="gray">(+{echo($k["atter_data"][$a]["bonus"]*100 - 100)}%)</span>{/if},
                                                                        {:l('kb_hull')}:{$hProz}%{if $k["atter_data"][$a]["bonus"] > 1}<span class="gray">(+{echo($k["atter_data"][$a]["bonus"]*100 - 100)}%)</span>{/if}
                                                                        </td>
																</tr>
																	{if is_array($data) && count($data) > 0}
																		{php}
																			$r1 = "<tr class=\"kb_zeile_a\"><td class=\"kb_spalte_a\">".l('kb_type')."</td>";
																			$r2 = "<tr class=\"kb_zeile_b\"><td class=\"kb_spalte_b\">".l('kb_amount')."</td>";
																			$r3 = "<tr class=\"kb_zeile_a\"><td class=\"kb_spalte_a\">".l('kb_attack')."</td>";
																			$r4 = "<tr class=\"kb_zeile_b\"><td class=\"kb_spalte_b\">".l('kb_shields')."</td>";
                                                                            $r5 = "<tr class=\"kb_zeile_b\"><td class=\"kb_spalte_b\">".l('kb_hull')."</td>";
																			foreach ($data as $sid => $vars)
																			{
																			$r1 .= "<td class=\"kb_spalte_a\">".l('item_s'.$sid)."</td>";
																			$r2 .= "<td class=\"kb_spalte_b\">".nicenum($vars['c'])."</td>";
																			$r3 .= "<td class=\"kb_spalte_a\">".nicenum($vars['a'])."</td>";
																			$r4 .= "<td class=\"kb_spalte_b\">".nicenum($vars['v'])."</td>";
                                                                            $r5 .= "<td class=\"kb_spalte_b\">".nicenum($vars['h'])."</td>";
																			}
																			$r1 .= "</tr>";
																			$r2 .= "</tr>";
																			$r3 .= "</tr>";
																			$r4 .= "</tr>";
                                                                            $r5 .= "</tr>";
																		{/php}
																		{$r1}
																		{$r2}
																		{$r3}
																		{$r4}
                                                                        {$r5}
																		{else}
																	<tr>
																		<td>{:l('kb_destructed')}</td>
																	</tr>
																{/if}
														</table>
													
											</td>
										</tr>
									</table>
								{/foreach}
								<!-- Ende Angreifer -->
								<div style="clear:both"></div>

								<!-- Verteidiger -->
								{foreach $values["sv"] as $a => $data}
									<table class="kb_verteidiger">
										<tr>
													{php}
													$f = $k["deffer_data"][$a]['f'];
													for($i=5;$i<=9;$i++)
													if($i == 8) continue;
													else if(!isset($f['f'.$i])) $f['f'.$i] = 0;
    												$aProz = (1 + $f['f5'] * 0.1 ) * 100;
                                                    $sProz = (1 + $f['f6'] * 0.1 ) * 100;
													$hProz = (1 + $f['f9'] * 0.1 ) * 100;

													{/php}
											<td>
												<table class="kampfbericht_table">
															<tr>
																<td colspan="100%" class="angreifer_td_head">{:l('kb_deffer')} {$k["deffer_data"][$a]["name"]}{if !$hide}({$k["deffer_data"][$a]["coords"]|coordFormat}){/if}<br />
                                                                    {:l('kb_weapons')}:{$aProz}%{if $k["deffer_data"][$a]["bonus"] > 1}<span class="gray">(+{echo($k["deffer_data"][$a]["bonus"]*100 - 100)}%)</span>{/if},
                                                                    {:l('kb_shields')}:{$sProz}%{if $k["deffer_data"][$a]["bonus"] > 1}<span class="gray">(+{echo($k["deffer_data"][$a]["bonus"]*100 - 100)}%)</span>{/if},
                                                                    {:l('kb_hull')}:{$hProz}%{if $k["deffer_data"][$a]["bonus"] > 1}<span class="gray">(+{echo($k["deffer_data"][$a]["bonus"]*100 - 100)}%)</span>{/if}
                                                                    </td>
															</tr>
													
													{if (is_array($data) && count($data) > 0) or ($a == 1 && is_array($values["vv"][1]) and count($values["vv"][1]) > 0)}
																	{php}
																	$r1 = "<tr class=\"kb_zeile_a\"><td class=\"kb_spalte_a\">".l('kb_type')."</td>";
																	$r2 = "<tr class=\"kb_zeile_b\"><td class=\"kb_spalte_b\">".l('kb_amount')."</td>";
																	$r3 = "<tr class=\"kb_zeile_a\"><td class=\"kb_spalte_a\">".l('kb_attack')."</td>";
																	$r4 = "<tr class=\"kb_zeile_b\"><td class=\"kb_spalte_b\">".l('kb_shields')."</td>";
                                                                    $r5 = "<tr class=\"kb_zeile_b\"><td class=\"kb_spalte_b\">".l('kb_hull')."</td>";
																	foreach ($data as $sid => $vars)
																	{
																	$r1 .= "<td class=\"kb_spalte_a\">".l('item_s'.$sid)."</td>";
																	$r2 .= "<td class=\"kb_spalte_b\">".nicenum($vars['c'])."</td>";
																	$r3 .= "<td class=\"kb_spalte_a\">".nicenum($vars['a'])."</td>";
																	$r4 .= "<td class=\"kb_spalte_b\">".nicenum($vars['v'])."</td>";
                                                                    $r5 .= "<td class=\"kb_spalte_b\">".nicenum($vars['h'])."</td>";
																	}
																	if($a == 1 & isset($values["vv"][1]))
																	{
    																	foreach ($values["vv"][1] as $sid => $vars)
    																	{
    																		$r1 .= "<td>".l('item_v'.$sid)."</td>";
        																	$r2 .= "<td>".nicenum($vars['c'])."</td>";
        																	$r3 .= "<td>".nicenum($vars['a'])."</td>";
        																	$r4 .= "<td>".nicenum($vars['v'])."</td>";
                                                                            $r5 .= "<td>".nicenum($vars['h'])."</td>";
    																	}
																	}
																	$r1 .= "</tr>";
																	$r2 .= "</tr>";
																	$r3 .= "</tr>";
																	$r4 .= "</tr>";
                                                                    $r5 .= "</tr>";
																	 {/php}
																	{$r1}
																	{$r2}
																	{$r3}
																	{$r4}
                                                                    {$r5}
																	 {else} 
																<tr>
																	<td>{:l('kb_destructed')}</td>
																</tr>
												
												{/if}
												</table>
											</td>		
										</tr>
									</table>
								{/foreach}
								<!-- Ende Verteidiger -->
								<div style="clear:both"></div>
								  </div>  
								<!-- Rundenergebnisse -->
								{if $runde < count($k['kampf'])}

								<table class="kb_zwischen_erg">
										<tr>
											 <td colspan=100%>
												<div class="kb_erg_text_atter">{:l('kb_atter_fleet')} {:l('kb_shoot_text',nicenum($values["a_schuss"]),nicenum($values["a_tref"]),nicenum($values["a_ang"]),nicenum($values["a_def"]))}</div>
												<div class="kb_erg_text_deffer">{:l('kb_deffer_fleet')} {:l('kb_shoot_text',nicenum($values["v_schuss"]),nicenum($values["v_tref"]),nicenum($values["v_ang"]),nicenum($values["v_def"]))}</div>
											</td>
										</tr>
								</table>
								{/if}
								<!-- Ende Rundenergebnisse -->  


					</td> <!-- Hier endet die ganze Runde -->
				</tr>
			</table> <!-- Tabelle Rundenende -->
			{/foreach}

			
			<!-- KB Ausertung -->
			<table class="kb_erg kb_global">
				<tr>
					<td>
						<div class="kb_erg_text">{:l('kb_the_winner_is')} {if $k["winner"] == "n"}{:l('kb_noone')}{elseif $k["winner"] == "a"}{:l('kb_the_atter')}{else}{:l('kb_the_deffer')}{/if}</div>

							<div class="kb_erg_text">{:l('kb_atter_units_lost',nicenum($k["a_lost"]))}.</div>

							<div class="kb_erg_text"> {:l('kb_deffer_units_lost',nicenum($k["v_lost"]))}.</div>

							<div class="kb_erg_text">{:l('kb_tf',nicenum($k['tf'][1]),nicenum($k['tf'][2]),nicenum($k['tf'][3]),nicenum($k['tf'][4]))}.</div>

							
							{if isset($k['repaired']) && count($k['repaired']) > 0}
							<div class="kb_erg_text_2">
							{:l('kb_repaired')}:<br />
								{foreach $k['repaired'] as $id => $anz}
                                    {if $id == 15}
									{$anz|nicenum} {:l('item_s'.$id)}<br />
                                    {else}
                                    {$anz|nicenum} {:l('item_v'.$id)}<br />
                                    {/if}
								{/foreach}
							</div>
							{/if}
							
							<div class="kb_erg_text_3">
							{:l('kb_moonchance')}: {$k["mondchance"]} %<br />
							
							{php}
								$k['pr0'] = isset($k['pr0']) ? round($k['pr0']) : 0;
								$k['pr1'] = isset($k['pr1']) ? round($k['pr1']) : 0;
								$k['pr2'] = isset($k['pr2']) ? round($k['pr2']) : 0;
								$k['pr3'] = isset($k['pr3']) ? round($k['pr3']) : 0;
								$prSum = $k['pr0'] + $k['pr1'] + $k['pr2'] + $k['pr3']; 
							{/php}
							
							{if $prSum > 0}
								{:l('kb_farmed',nicenum($k['pr0']),nicenum($k['pr1']),nicenum($k['pr2']),nicenum($k['pr3']))}<br />
							
							{/if}
							{if $k['mond']}
								{:l('kb_moon')}
							{/if}
							</div>
							{if $k['inva'] == 1}
								{:l('kb_inva')}.<br />
							<hr class="exp_hr">
							{/if}
                            {if $k['inva'] != 0}
                                {:l('kb_inva_chance')} {$k['inva_chance']|nicenum}&percnt;<br />
                                <hr class="exp_hr">
                            {/if}
							{if $k['dest']}
								{:l('kb_dest')}<br />
							<hr class="exp_hr">
							{/if}
							
							 <div class="kb_erg_text_4">               
											{if isset($k["a_krieg"]) && $k["a_krieg"] > 0}
											
												{if count($k["atter_data"]) > 2}
													{:l('kb_atters_krieg',$k["a_krieg"])}<br />
												{else}
													{:l('kb_atter_krieg',$k["a_krieg"])}<br />
												{/if}
											

											{/if}
											
											{if isset($k["v_krieg"]) && $k["v_krieg"] > 0}
												{if count($k["deffer_data"]) > 1}
													{:l('kb_deffers_krieg',$k["v_krieg"])}<br />
												{else}
													{:l('kb_deffer_krieg',$k["v_krieg"])}<br />
												{/if}

											{/if}
							</div>
					</td>
				</tr>

			</table>   

			<!-- KB Ausertung Ende -->

		</td>
	</tr>
</table> <!-- Ende id KB -->
