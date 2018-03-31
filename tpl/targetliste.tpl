<div class="tcenter" id="targetConfig">
<input type="text" class="div_konstruktion_input" size="1" id="targetG">:<input type="text" class="div_konstruktion_input" size="3" id="targetS">:<input type="text" class="div_konstruktion_input" size="2" id="targetP">
<select id="targetT">
    <option value="1">{:l('galaxy_planet')}</option>
    <option value="2">{:l('galaxy_moon')}</option>
</select>
<br>
<input type="text" class="div_konstruktion_input" id="targetComment" size="33"><br>
<a href='javascript:void(0)' onclick="saveTarget()"><div class="class_btn einst_btn_save">{:l('fleet_save_target')}</div></a>

<table class="table_produktion" id="einstellungen">
{:showMyTargets()}
</table>
<a href='javascript:void(0)' onclick="tb_remove();window.location.reload();"><div class="class_btn einst_btn_save">{:l('close')}</div></a>
</div>