<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">

<div class="emp_center tcenter">
    <h1>{:l('empire')}</h1>

<table class="imp_main_table">
<tr>
    <td class="">
        <table class="imp_item_table">
            <tr><td height="120" class="planinfo">{:l('empire_planet_info')}</td></tr>
            <tr><th>{:l('nav_resources')}</th></tr>
            {* Rohstoffe *}
            <tr><td class="tleft">{:l('res1')}</td></tr>
            <tr><td class="tleft">{:l('res2')}</td></tr>
            <tr><td class="tleft">{:l('res3')}</td></tr>
            <tr><td class="tleft">{:l('res4')}</td></tr>
            <tr><td class="tleft">{:l('energy')}</td></tr>
            <tr><th>{:l('v3_buildings')}</th></tr>
            {* Gebauede *}
            {foreach $_BAU as $id => $xXx}
                <tr><td class="tleft">{:l('item_b'.$id)}</td></tr>
            {/foreach}
            
            <tr><th>{:l('v3_ships')}</th></tr>
            {* Schiffe *}
            {foreach $_SHIP as $id => $xXx}
                <tr><td class="tleft">{:l('item_s'.$id)}</td></tr>
            {/foreach}
            
            <tr><th>{:l('v3_defense')}</th></tr>
            {* Verteidigung *}
            {foreach $_VERT as $id => $xXx}
                <tr><td class="tleft">{:l('item_v'.$id)}</td></tr>
            {/foreach}
            
            <tr><th>{:l('v3_research')}</th></tr>
            {* Forschung *}
            {foreach $_FORS as $id => $xXx}
                <tr><td class="tleft">{:l('item_f'.$id)}</td></tr>
            {/foreach}
        </table>
    </td>
{foreach $planets as $i => $data}
<td valign="top"{if $i == 0} style="margin-left:150px;"{/if}>
    <table class="imp_item_table">
    {* Planeten Info *}
    <tr>
        <td align="center" class="planinfo">
            <img src="{$gameURL}/design/Planeten/{$data['pbild']}" width="50"><br>
            {$data['pname']}<br>
            {$data['coords']|coordFormat}<br>
            {$data['tempFrom']}&deg;C {:l('v3_to')} {$data['tempTo']}&deg;C<br>
            {$data['dia']|nicenum}km
        </td>
    </tr>
    <tr><th><a href="powercol.php?pto={$data['coords']}&modal=true&width=960&height=600" class="thickbox"><span class="class_btn">{:l('powercol')}</a></a></th></tr>
    {* Rohstoffe *}
    {for $i=0;$i<4;$i++}
    <tr><td>{$data['res'][$i]|nicenum}</td></tr>
    {/for}
    <tr><td>{$data['res'][4]|nicenum}/{$data['res'][5]|nicenum}</td></tr>
    
    <tr><th>&nbsp;</th></tr>
    {* Gebaeude *}
    {foreach $_BAU as $id => $xXx}
    <tr><td title="{:l('item_b'.$id)}">
    {if $data['k'.$id] > 0}
        {$data['k'.$id]}
    {else}
        -
    {/if}
    </td></tr>
    {/foreach}
    
    {* Schiffe *}
    <tr><th>&nbsp;</th></tr>
    {foreach $_SHIP as $id => $xXx}
    <tr><td title="{:l('item_s'.$id)}">
    {if isset($data['schiffe'][$id]) and $data['schiffe'][$id] > 0}
        {$data['schiffe'][$id]|nicenum}
    {else}
        -
    {/if}
    </td></tr>
    {/foreach}
    
     
    {* Verteidigung *}
    <tr><th>&nbsp;</th></tr>
    {foreach $_VERT as $id => $xXx}
    <tr><td title="{:l('item_v'.$id)}">
    {if isset($data['deff'][$id]) and $data['deff'][$id] > 0}
        {$data['deff'][$id]|nicenum}
    {else}
        -
    {/if}
    </td></tr>
    {/foreach}
    
    {* Forschung *}
    <tr><th>&nbsp;</th></tr>
    {foreach $_FORS as $id => $xXx}
    <tr><td>
    {if isset($forschung['f'.$id]) and $forschung['f'.$id] > 0}
        {$forschung['f'.$id]|nicenum}
    {else}
        -
    {/if}
    </td></tr>
    {/foreach}
    
    </table>
</td>
{/foreach}
{* Durchschnitt *}
<td valign="top">
    <table class="imp_item_table">
    <tr>
        <td class="planinfo tcenter">
        <div style="font-size:50px !important; width:60px;">&Sigma;</div>
        </td>
    </tr>
    <tr><th>&nbsp;</th></tr>
    {* Rohstoffe *}
    {for $i=0;$i<4;$i++}
    <tr><td>{$ave['res'][$i]|nicenum}</td></tr>
    {/for}
    <tr><td>{$ave['res'][4]}/{$ave['res'][5]}</td></tr>
    
     <tr><th class="tcenter">&empty;</th></tr>
    {* Gebaeude *}
    {foreach $_BAU as $id => $xXx}
    <tr><td>
    {if $ave['k'.$id] > 0}
        {$ave['k'.$id]}
    {else}
        -
    {/if}
    </td></tr>
    {/foreach}
    
     {* Schiffe *}
    <tr><th>&nbsp;</th></tr>
    {foreach $_SHIP as $id => $xXx}
    <tr><td>
    {if isset($ave['schiffe'][$id]) and $ave['schiffe'][$id] > 0}
        {$ave['schiffe'][$id]|nicenum}
    {else}
        -
    {/if}
    </td></tr>
    {/foreach}
    
     
    {* Verteidigung *}
    <tr><th>&nbsp;</th></tr>
    {foreach $_VERT as $id => $xXx}
    <tr><td>
    {if isset($ave['deff'][$id]) and $ave['deff'][$id] > 0}
        {$ave['deff'][$id]|nicenum}
    {else}
        -
    {/if}
    </td></tr>
    {/foreach}
    
    {* Forschung *}
    <tr><th>&nbsp;</th></tr>
    {foreach $_FORS as $id => $xXx}
    <tr><td>
    {if isset($forschung['f'.$id]) and $forschung['f'.$id] > 0}
        {$forschung['f'.$id]|nicenum}
    {else}
        -
    {/if}
    </td></tr>
    {/foreach}
    </table>
    
</td>
    <td class="">
        <table class="imp_item_table">
            <tr><td height="120" class="planinfo">{:l('empire_planet_info')}</td></tr>
            <tr><th>{:l('nav_resources')}</th></tr>
            {* Rohstoffe *}
            <tr><td class="tleft">{:l('res1')}</td></tr>
            <tr><td class="tleft">{:l('res2')}</td></tr>
            <tr><td class="tleft">{:l('res3')}</td></tr>
            <tr><td class="tleft">{:l('res4')}</td></tr>
            <tr><td class="tleft">{:l('energy')}</td></tr>
            <tr><th>{:l('v3_buildings')}</th></tr>
            {* Gebauede *}
            {foreach $_BAU as $id => $xXx}
                <tr><td class="tleft">{:l('item_b'.$id)}</td></tr>
            {/foreach}
            
            <tr><th>{:l('v3_ships')}</th></tr>
            {* Schiffe *}
            {foreach $_SHIP as $id => $xXx}
                <tr><td class="tleft">{:l('item_s'.$id)}</td></tr>
            {/foreach}
            
            <tr><th>{:l('v3_defense')}</th></tr>
            {* Verteidigung *}
            {foreach $_VERT as $id => $xXx}
                <tr><td class="tleft">{:l('item_v'.$id)}</td></tr>
            {/foreach}
            
            <tr><th>{:l('v3_research')}</th></tr>
            {* Forschung *}
            {foreach $_FORS as $id => $xXx}
                <tr><td class="tleft">{:l('item_f'.$id)}</td></tr>
            {/foreach}
        </table>
    </td>
</tr>
</table>
<br><br>
</div>
</div>