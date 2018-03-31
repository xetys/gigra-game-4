<div id="V3_Content">
    
    <div class="class_content_wrapper">
    
        <div class="info_first_head">{:l('pinfo_info')}</div>
        
        <div class="info_head">{:l('pinfo_punkte_ges')}</div>
        <div class="info_content">{:nicenum($pforsch+$pgesamt+$pflotte+$pvert)}</div>
        
        <div class="info_head">{:l('pinfo_punkte_planet')}</div>
        <div class="info_content">{$pgesamt|nicenum}</div>
        
        <div class="info_head">{:l('pinfo_punkte_forsch')}</div>
        <div class="info_content">{$pforsch|nicenum}</div>
        
        <div class="info_head">{:l('pinfo_punkte_flotte')}</div>
        <div class="info_content">{$pflotte|nicenum}</div>
        
        <div class="info_head">{:l('pinfo_punkte_deff')}</div>
        <div class="info_content">{$pvert|nicenum}</div>
        
        <div class="info_head">{:l('pinfo_midTrefferQuoteKampf')}</div>
        <div class="info_content">{$minTreQuo}%</div>
        
        <div class="info_head">{:l('pinfo_AngriffKampf')}</div>
        <div class="info_content">{$angProz}%</div>
        
        <div class="info_head">{:l('pinfo_DeffKampf')}</div>
        <div class="info_content">{$defProz}%</div>
        
        <div class="info_head">{:l('pinfo_Max_planets')}</div>
        <div class="info_content">{$phave}/{$pmax}</div>
        
        <div class="info_head">{:l('pinfo_vw')}</div>
        <div class="info_content">{$verwarn}</div>
        
        <div class="info_head">{:l('pinfo_save_stat')}</div>
        <div class="info_content">{$saveStat} {:l('pinfo_von_5')}({$savedProz}% {:l('pinfo_der_res_save')}</div>

        <div class="info_head">{:l('hkurse')}</div>
        <div class="info_content">{$kurse[1]} : {$kurse[2]} : {$kurse[3]} : {$kurse[4]}</div>
        
    </div>
</div>