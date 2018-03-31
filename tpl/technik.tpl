<div id="V3_Content">
    <div class="class_content_wrapper">
    <div id="build_research">
    <table class="highrscore_table">
    <tr><td class="DivInfoContent_cancel">
        <a href="javascript:void(0);" onclick="switchTech()">
            <img src="design/3/gg_hs_infra.png" width="80" class="transparent">
            <img src="design/3/gg_hs_forschung.png" width="80" class="transparent">
            <img src="design/3/gg_hs_flotten.png" width="80">
            <img src="design/3/gg_hs_deff.png" width="80">
             
    </td></tr>
    </table>
    <table class="highrscore_table left" style="width:50%">
        <tr>
            <th>{:l('tech_object')}</th>
            <th>{:l('tech_requirements')}</th>
        {foreach $laTechnik['b'] as $lid => $laData}
        <tr class="highrscore_table_tr_a">
            <td style="width:50px">
                <img src="{$laData["img"]}" width="50">
            </td>
            
            <td class="tleft vtop">
            <a href="{$gameURl}/{$laData['link']}">{$laData["name"]}</a><br>
            {$laData['reqs']}</td>
        </tr>
        {/foreach}
        </table>
        
        <table class="highrscore_table left" style="width:50%">
        <tr>
            <th>{:l('tech_object')}</th>
            <th>{:l('tech_requirements')}</th>
        {foreach $laTechnik['f'] as $lid => $laData}
        <tr class="highrscore_table_tr_a">
            <td style="width:50px">
                <img src="{$laData["img"]}" width="50">
            </td>
            
            <td class="tleft vtop">
            <a href="{$gameURl}/{$laData['link']}">{$laData["name"]}</a><br>
            {$laData['reqs']}</td>
        </tr>
        {/foreach}
        </table>
        <div class="clear"></div>
    </div>
    
    <div id="military" class="hidden">
    <table class="highrscore_table">
    <tr><td class="DivInfoContent_cancel">
    <a href="javascript:void(0);" onclick="switchTech()" title="{:l('tech_to_build_research')}">
        <img src="design/3/gg_hs_infra.png" width="80">
        <img src="design/3/gg_hs_forschung.png" width="80">
            <img src="design/3/gg_hs_flotten.png" width="80" class="transparent">
            <img src="design/3/gg_hs_deff.png" width="80" class="transparent">
    </a>
    </td></tr>
    </table>
    
    <table class="highrscore_table left" style="width:50%">
        <tr>
            <th>{:l('tech_object')}</th>
            <th>{:l('tech_requirements')}</th>
        {foreach $laTechnik['s'] as $lid => $laData}
        <tr class="highrscore_table_tr_a">
            <td style="width:50px">
                <img src="{$laData["img"]}" width="50">
            </td>
            
            <td class="tleft vtop">
            <a href="{$gameURl}/{$laData['link']}">{$laData["name"]}</a><br>
            {$laData['reqs']}</td>
        </tr>
        {/foreach}
        </table>
        
        <table class="highrscore_table left" style="width:50%">
        <tr>
            <th>{:l('tech_object')}</th>
            <th>{:l('tech_requirements')}</th>
        {foreach $laTechnik['v'] as $lid => $laData}
        <tr class="highrscore_table_tr_a">
            <td style="width:50px">
                <img src="{$laData["img"]}" width="50">
            </td>
            
            <td class="tleft vtop">
            <a href="{$gameURl}/{$laData['link']}">{$laData["name"]}</a><br>
            {$laData['reqs']}</td>
        </tr>
        {/foreach}
        </table>
        <div class="clear"></div>
    </div>
    </div>
</div>