
      <!-- Schiffe und Deff -->
	   {$smallpre = strtolower($PHP_Prefix)}
	   
				<form action="v3_konstruktion.php" method="post" name="{$PHP_Prefix}schiffForm" onsubmit="production('{$PHP_Prefix}','{$PHP_Prefix}schiffForm');return false;">
					<table>			
						{foreach $laObjects as $index => $laO}
							{$laO["header"]}
							{if is_string($laO)}
							{else}
							
								<tr>

									<td>
									
									<div style="background: url(design/items/s2.gif);background:url({$laO["sbild"]})" class="bau_cube">
										<div class="bau_overpic_schiffe">
										<div class="cube_php">
										<canvas id="c_{$smallpre}{$index}" width="145"></canvas>
										</div>
									</div></div>
									</td>
									<td>
									<a href="info.php?obj={$smallpre}{$index}">{:l('item_'.$smallpre.$index)}</a> 
									<span id="n{$index}" title="{$laO['s_ikf']}">{if isset($laO['s_ikf']) and $laO['s_ikf'] > 0}{:l('v3_have_x',$laO['s_ikf'])}{/if}</span><br />
									{:l('item_'.$smallpre.$index.'_shortdesc')}<br />
									{$laO["res"]} {:l('v3_duration')} : <b>{$laO["time"]}</b><br>
                                    <span class="small"><a href="javascript:void(0);" onclick="$('#in_{$smallpre}{$index}').val({$laO['max']});">(max {$laO['max']|nicenum} = {$laO['pts']|nicenum} {:l('v3_score')})</a></span>
									</td>
									<td>
                                        <input id="in_{$smallpre}{$index}"class="div_konstruktion_input" type=text name="p{$index}" size=6 maxlength=6 value="0" tabindex="{$index}" onfocus="if(this.value=='0')this.value='';" onblur="if(this.value=='')this.value='0';">
                                    </td>
								</tr>
							
							{/if}
						{/foreach}
					
					

					<tr><td colspan="3" align="center"><input class="div_konstruktion_submit" type="submit" value="{:l('v3_do_build')}"></td></tr>
					</table>
				</form>
				
				
				
				<!-- Hier fängt bauschleife an -->
				{if count($laListe) > 0}
				
				<form id="prod_v3" action="" method="post" name="{$PHP_Prefix}abortForm" onsubmit="production('{$PHP_Prefix}','{$PHP_Prefix}abortForm');return false;">
				  <input type="hidden" name="del" value="a">
				  <table class="table_produktion">
				  <tr class="class_small_header">
					<td colspan="3">{:l('v3_in_product')}</td>
				  </tr>
				  
				  
				  {foreach $laListe as $laItem}
					<tr id="{$PHP_Prefix}line{$laItem['anz_rows']}" class="class_small_content" title="{$laItem['time']}">
						<td>
							<div id="{$PHP_Prefix}time{$laItem['anz_rows']}" title="{$laItem['rest']}">{:format_zeit($laItem['rest'])}</div>
						</td>
						
						<td>
							<div id="{$PHP_Prefix}count{$laItem['anz_rows']}" title="{$laItem['count']}">{$laItem['count']} {$laItem['name']}</div>
						</td>
						
						<td>
							<a href="#" onclick="cancelBS({$laItem['id']});" name="__a" title="{$laItem['count']} {$laItem['name']} {:l('v3_cancel')}"><div class="global_cancel"></div></a>

						</td>
					</tr>
				  {/foreach}
				  
					<!-- <tr id="{$PHP_Prefix}sum_line1" class="class_btn prod_btn_cancel">
						<td colspan="3">
							<input class="prod_btn_cancel" type="submit" name="__a" value="{:l('v3_cancel')}">
						</td>
					</tr> -->
					</table>				
<hr class="class_hr">
<table class="table_produktion">
					<tr class="prod_ges" id="{$PHP_Prefix}sum_line2">
						<td>
							<div id="{$PHP_Prefix}sum_time" title="<?=$time_ges?>">{:format_zeit($time_ges)}</div>
						</td>
						<td colspan="2">
							<div id="{$PHP_Prefix}sum_count" title="<?=$count_ges?>">{$count_ges} {:l('v3_items_all')}</div>
						</td>
					  </tr>
				  </table>
				  </form>
				{/if}
		
	<div id="{$JS_BauEval}" style="display:none">{$bauEval}</div>

	{if $mode_ajax}
		<script type='text/javascript'>eval(ID('{$JS_BauEval}').innerHTML);</script>
	{/if}
