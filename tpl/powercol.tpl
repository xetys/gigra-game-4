<h1>{:l('powercol')} - {$pto|coordFormat}</h1>
<input type="hidden" id="pc_coords" value="{$pto}">
<table width="100%">
<tr>
    <td>
        <h2 class="green">{:l('powercol_resprio')}</h2>
        <p>
             {:l('powercol_resprio_desc')}   
        </p>
        <a href="javascript:void(0)" onclick="pcSelectRes(0);"><div class="class_btn transparent resbn" id="rb0">{:l('res1')}<span></span></div></a><br>
        <a href="javascript:void(0)" onclick="pcSelectRes(1);"><div class="class_btn transparent resbn" id="rb1">{:l('res2')}<span></span></div></a><br>
        <a href="javascript:void(0)" onclick="pcSelectRes(2);"><div class="class_btn transparent resbn" id="rb2">{:l('res3')}<span></span></div></a><br>
        <a href="javascript:void(0)" onclick="pcSelectRes(3);"><div class="class_btn transparent resbn" id="rb3">{:l('res4')}<span></span></div></a><br>
        <br>
        <br>
        <a href="javascript:void(0)" onclick="pcResetRes()"><div class="class_btn red">{:l('powercol_reset')}</div></a><br>
    </td>
    <td>
    <h2 class="green">{:l('powercol_selship')}</h2>
        <p>
             {:l('powercol_selship_desc')}   
        </p>
        <a href="javascript:void(0)" onclick="pcSelectShip(12)"><div class="class_btn transparent shipbn" id="sb12" style="width:100px;"><img src="design/items/s12.gif" width="100"><span></span></div></a><br>
        <a href="javascript:void(0)" onclick="pcSelectShip(13)"><div class="class_btn transparent shipbn" id="sb13" style="width:100px;"><img src="design/items/s13.gif" width="100"><span></span></div></a><br>
        <a href="javascript:void(0)" onclick="pcSelectShip(16)"><div class="class_btn transparent shipbn" id="sb16" style="width:100px;"><img src="design/items/s16.gif" width="100"><span></span></div></a><br>
        <br>
        <br>
        <a href="javascript:void(0)" onclick="pcResetShip()"><div class="class_btn red">{:l('powercol_reset')}</div></a><br>
    </td>
</tr>
</table>

<a href="javascript:void(0)" id="pc_start"><div class="class_btn green transparent">{:l('powercol_start')}</div></a><br>
<a href="javascript:void(0)" id="pc_list"><div class="class_btn green transparent">{:l('powercol_list')}</div></a><br>
<div id="successlist">
</div>
<a href='javascript:void(0)' onclick="tb_remove()"><div class="class_btn einst_btn_save">{:l('close')}</div></a>