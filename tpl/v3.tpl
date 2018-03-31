<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">

    <div class="class_content_wrapper">
    	<!-- Bauen und Forschen -->
		<div class="info_first_head">{:l('v3_build_research')}</div>

		<table class="Top-Nav">    
			<tr>
			<td>
				<ul id="Top-Nav">
					{if !isGigrania($_SESSION['coords'])}
                    <li><a href="javascript:;" title="{:l('v3_buildings')}" onclick="switchTo('B');">{:l('v3_buildings')}</a></li>
					<li{if !canForsch()} class="inactive">{else}><a href="javascript:;" title="{:l('v3_research')}" onclick="switchTo('F');">{/if}{:l('v3_research')}{if canForsch()}</a>{/if}</li>
					<li{if !canShip()} class="inactive">{else}><a href="javascript:;" title="{:l('v3_ships')}" onclick="switchTo('S');">{/if}{:l('v3_ships')}{if canShip()}</a>{/if}</li>
                    {/if}
					<li{if !canDeff()} class="inactive">{else}><a href="javascript:;" title="{:l('v3_defense')}" onclick="switchTo('V');">{/if}{:l('v3_defense')}{if canDeff()}</a>{/if}</li>
                    <!--
					<li class="inactive"><a href="javascript:;" title="{:l('v3_rockets')}" onclick="">{:l('v3_rockets')}</a></li>
                    -->
				</ul>
			</td>
			</tr>
		</table>   
			
		<table class="table_bauen">
			<tr>
				<td colspan="5" class="bauen">
                
                    
					<div id="bauliste">
							{:showGebs(false,false)}
					</div>
					
					<div id="forschliste" style="display:none">
					{if canForsch()}
						{:showForsch()}
					{/if}
					</div>
					
                    
					<div id="schiffliste" style="display:none">
						{:showSchiffe()}
					</div>
					
                    
					<div id="verteidigungsliste"{if !isGigrania($_SESSION['coords'])} style="display:none"{/if}>
						{:showVert()}
					</div>
					
					<div id="raketenliste" style="display:none">
						Baustelle
					</div>
					
				</td>
			</tr>
		</table>
		
	</div> <!-- Ende Content wrapper -->

</div> <!-- Ende Main-Wrapper -->
