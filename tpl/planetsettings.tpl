<div  style="background:url({$gameURL}/design/Planeten/{$pbild}) no-repeat center;height:150px;">
<table width="100%">
{if !$gigrania}
<tr>
    <td>
    {:l('v3_planetname')}
    </td>
    <td>
        <input type="text" class="div_konstruktion_input" value="{$pname}" id="planet-rename">
    </td>
    <td>
        <a href='javascript:void(0)' onclick="renamePlanet()"><span class="class_btn einst_btn_save">{:l('planset_rename')}</span></a>
    </td>
</tr>
{/if}
{if $hp}
<tr>
    <td>
    
    <a href='javascript:void(0)' onclick="hpPlanet()"><div class="class_btn einst_btn_save">{:l('planset_makeHP')}</div></a>
    
    </td>
    <td>
    </td>
    <td>
    <a href='javascript:void(0)' onclick="leavePlanet()"><span class="class_btn einst_btn_save">{:l('planset_leave')}</span></a>
    </td>
</tr>
{/if}
</table>
<a href='javascript:void(0)' onclick="tb_remove()"><div class="class_btn einst_btn_save">{:l('close')}</div></a>
</div>