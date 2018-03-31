<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">
    <div class="class_content_wrapper">
        <div class="exp_desc_div">
            <div class="exp_desc_ausrufezeichen"></div>
                <div class="exp_desc_content">{:l('exp_desc')}</div>
        </div>
        <div class="exp_wrapper">
        <div class="class_small_header">{:l('exp_skillpoints')}: {$allSP|nicenum}</h2>
        <div class="class_small_header">{:l('v3_level')}: {$all_level|nicenum}</h2>
        <div class="class_small_header">{:l('exp_to_next_level')}: {$all_points_left|nicenum}</h2>
        <div class="class_small_header">{:l('exp_expoints')}: {$all_points|nicenum}</h2>
        
        </div>
        
    <div class="exp_content">
    <div class="ally_page_btn"><h1>{:l('exp_infra')}</h1></div>
    <div>
        {:l('exp_infra_desc')}
    </div>
    <!-- Infrastrukturerfahrung - Anfang -->
        <div style="background:url(design/2-0/exp_infra.png);" class="bau_cube left">
    		<div class="bau_overpic">
    
    
    			<b class="bau_top">{$infra_level}</b>
    
    			<div class="res_bottom">
    				<table width="100%">
    					<tr><td>{:l('exp_to_next_level')}</td><td style="text-align:right">{$infra_left}</td></tr>
    					<tr><td>{:l('exp_skillpoints')}</td><td style="text-align:right">{$infra_skillpoints}</td></tr>
    				</table>
    
    			</div>
    		</div>
    	</div>
    	<div style="floar:left">
    		<div class="exp_wrapper">
    		{*		
    				<div class="class_small_header exp_infraname_head">{:l('exp_infra_planeten')} ({$infra_planeten}) </div>
    					<div class="class_small_content exp_infracontent">{:l('exp_infra_planeten_desc')}{if $allSP > 0}<span class="class_btn exp_level_up_btn"><a href="erfahrung.php?add=infra_planeten" style="color:lime">+</a></span>{/if}</div>
    		*}
    				<div class="class_small_header exp_infraname_head">{:l('exp_infra_rohstoff')} ({$infra_rohstoff})</div>
    					<div class="class_small_content exp_infracontent">{:l('exp_infra_rohstoff_desc')}{if $allSP > 0}<span class="class_btn exp_level_up_btn"><a href="erfahrung.php?add=infra_rohstoff" style="color:lime">+</a></span>{/if}</div>
    		
    				 <div class="class_small_header exp_infraname_head">{:l('exp_infra_bauzeit')} ({$infra_bauzeit})</div>
    					 <div class="class_small_content exp_infracontent">{:l('exp_infra_bauzeit_desc')}{if $allSP > 0}<span class="class_btn exp_level_up_btn"><a href="erfahrung.php?add=infra_bauzeit" style="color:lime">+</a></span>{/if}</div>
    				 
    		</div>
    				
    	<div class="clear"></div>
    <!-- Infrastrukturerfahrung - Ende -->
    <hr class="exp_hr" />
    <!-- Kriegserfahrung - Anfang -->
    	<div style="background:url(design/2-0/exp_krieg.png);" class="bau_cube left">
    		<div class="bau_overpic">
    
    
    			<b class="bau_top">{$krieg_level}</b>
    
    			<div class="res_bottom"><span>{:l('exp_krieg')}</span>
    				<br>
    				<table width="100%">
    					<tr><td>{:l('exp_to_next_level')}</td><td style="text-align:right">{$krieg_left}</td></tr>
    					<tr><td>{:l('exp_skillpoints')}</td><td style="text-align:right">{$krieg_skillpoints}</td></tr>
    				</table>
    
    			</div>
    		</div>
    	</div>
    	<div style="floar:left">
    		<div class="exp_wrapper">
    				
    				<div class="class_small_header exp_infraname_head">{:l('exp_krieg_treffer')} ({$krieg_treffer})</div>
    					<div class="class_small_content exp_infracontent">{:l('exp_krieg_treffer_desc')}{if $allSP > 0}<span class="class_btn exp_level_up_btn"><a href="erfahrung.php?add=krieg_treffer" style="color:lime">+</a></span>{/if}</div>
    		
    				<div class="class_small_header exp_infraname_head">{:l('exp_krieg_flugzeit')} ({$krieg_flugzeit})</div>
    					<div class="class_small_content exp_infracontent">{:l('exp_krieg_flugzeit_desc')}{if $allSP > 0}<span class="class_btn exp_level_up_btn"><a href="erfahrung.php?add=krieg_flugzeit" style="color:lime">+</a></span>{/if}</div>
    				 
    		</div>
    				
    	<div class="clear"></div>
    <!-- Infrastrukturerfahrung - Ende -->
    <hr class="exp_hr" />
    <!-- Forschungserfahrung - Anfang -->
    	<div style="background:url(design/2-0/exp_forsch.png);" class="bau_cube left">
    		<div class="bau_overpic">
    
    
    			<b class="bau_top">{$forsch_level}</b>
    
    			<div class="res_bottom"><span>{:l('exp_forsch')}</span>
    				<br>
    				<table width="100%">
    					<tr><td>{:l('exp_to_next_level')}</td><td style="text-align:right">{$forsch_left}</td></tr>
    					<tr><td>{:l('exp_skillpoints')}</td><td style="text-align:right">{$forsch_skillpoints}</td></tr>
    				</table>
    
    			</div>
    		</div>
    	</div>
    	<div style="floar:left">
    		<div class="exp_wrapper">
    				
    				<div class="class_small_header exp_infraname_head">{:l('exp_forsch_zeit')} ({$forsch_zeit})</div>
    					<div class="class_small_content exp_infracontent">{:l('exp_forsch_zeit_desc')}{if $forsch_zeit > 0}<span class="class_btn exp_level_up_btn"><a href="erfahrung.php?sub=forsch_zeit" style="color:red">-</a></span>{/if}{if $allSP > 0 && $forsch_zeit < 10}<span class="class_btn exp_level_up_btn"><a href="erfahrung.php?add=forsch_zeit" style="color:lime">+</a></span>{/if}</div>
    		
    				<div class="class_small_header exp_infraname_head">{:l('exp_forsch_geheimschiff1')} ({$forsch_geheimschiff1})</div>
    					<div class="class_small_content exp_infracontent">{:l('exp_forsch_geheimschiff1_desc')}{if $forsch_geheimschiff1 > 0 and $forsch_geheimschiff1 < 13}<span class="class_btn exp_level_up_btn"><a href="erfahrung.php?sub=forsch_geheimschiff1" style="color:red">-</a></span>{/if}{if $allSP > 0}<span class="class_btn exp_level_up_btn"><a href="erfahrung.php?add=forsch_geheimschiff1" style="color:lime">+</a></span>{/if}</div>
    				
    				<div class="class_small_header exp_infraname_head">{:l('exp_forsch_geheimschiff2')} ({$forsch_geheimschiff2})</div>
    					<div class="class_small_content exp_infracontent">{:l('exp_forsch_geheimschiff2_desc')}{if $forsch_geheimschiff2 > 0 and $forsch_geheimschiff2 < 15}<span class="class_btn exp_level_up_btn"><a href="erfahrung.php?sub=forsch_geheimschiff2" style="color:red">-</a></span>{/if}{if $allSP > 0}<span class="class_btn exp_level_up_btn"><a href="erfahrung.php?add=forsch_geheimschiff2" style="color:lime">+</a></span>{/if}</div>
    				 
    		</div>
    				
    	<div class="clear"></div>
    <!-- Forschungserfahrung - Ende -->
    
    </div>
    </div>
</div>

<table width="100%">
<tr>
    <th>Stufe</th>
    <th>Punkte bis levelUp</th>
    <th>Summe</th>
</tr>
{for $i = 1;$i<=100;$i++}
{$sum = getNextLevelSum($i-1)}
{$points = $sum - getNextLevelSum($i-2)}
<tr>
    <td>{$i}</td>
    <td>{$points|nicenum}</td>
    <td>{$sum|nicenum}</td>
</tr>
{/for}

</table>
