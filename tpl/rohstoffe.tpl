<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">

    <div class="class_content_wrapper">
    <form action="rohstoffe.php" method="post" id="recalcForm"><input type="hidden" name="recalc" value="1">
    <!-- Bauen und Forschen -->
    <div class="info_first_head">{:l('nav_resources')} - <a href='javascript:void(0)' onclick="ID('recalcForm').submit();"><div class="class_btn res_btn_reload">{:l('res_recalc')}</div></a></div> 
    {if $boost_until > time()}
    <div class="green">{:l('res_active_boost',$boost_percent,date("d.m.Y",$boost_until),date("H:i:s",$boost_until))}</div>
    {/if}
    <div>{:l('res_mine_bonus',l('item_f7'),$mine_bonus)}</div>
    <div>{:l('res_energy_bonus',l('item_f15'),$energy_bonus)}</div>
    <div>{:l('res_skill_bonus',$skill_bonus)}</div>
    <div>{:l('res_bonus_all',($boost_percent + $mine_bonus + $skill_bonus),($boost_percent + $energy_bonus + $skill_bonus))}</div>
            {foreach $resProdList as $index => $prodData}
            {if $index == 1}<div class="clear"></div><div class="info_head">{:l('res_basic')}</div>{/if}
            {if $index == 4}<div class="clear"></div><div class="info_head">{:l('res4')}</div>{/if}
            {if $index == 6}<div class="clear"></div><div class="info_head">{:l('energy')}</div>{/if}
                <div style="background:url({$prodData['img']});" class="bau_cube left">
                    <div class="bau_overpic">
                	{if !$prodData['active']}
                        <div class="bau_unable">
                    {/if}
        
        		    <b class="bau_top">{$prodData['lvl']|nicenum} 
                    {if isset($prodData['faktor'])}
                    <select name="prod{$index}">
                        {for $ri=100;$ri>=0;$ri-=10}
                            <option value="{$ri/10}"{if $ri / 10 == $prodData['faktor']} selected{/if}>{$ri}%</option>
                        {/for}
                    </select>
                    {/if}
                    </b>
        		    <div class="res_bottom"><span{if $index==5} style="font-size:12px"{/if}>{$prodData['title']}</span>
                    <br>
                    <table width="100%">
                    {foreach $prodData['prod'] as $lsRes => $liAmount}
                    {$color = 'lime'}
                    {if $liAmount < 0}{$color = 'red'}{/if}
                    <tr><td>{:l($lsRes)}</td><td style="text-align:right"><font color='{$color}'>{$liAmount|nicenum}</font></td></tr>
                    {/foreach}
                    {if $index < 4 && isset($prodData["capa"])}
                    <tr><td>{:l('res_capa')}</td><td style="text-align:right">{$prodData['capa']|nicenum}</td></tr>
                    {/if}
                    </table>
                    </div>
            		{if !$prodData['active']}
                        </div>
                    {/if}
        	        </div>
				</div>
                {if $index == 3}
                <div class="bau_cube left">
                    <div class="bau_overpic">
            	    <b class="bau_top"></b>
                    
        		    <div class="res_bottom"><span style="font-size:10px">{:l('res_all_basic_prod')}</span>
                    <br>
                    <table width="100%">
                    {$color = 'lime'}
                    <tr><td>{:l('res1')}</td><td style="text-align:right"><font color='{$color}'>{$laProd[0]|nicenum}</font></td></tr>
                    <tr><td>{:l('res2')}</td><td style="text-align:right"><font color='{$color}'>{$laProd[1]|nicenum}</font></td></tr>
                    <tr><td>{:l('res3')}</td><td style="text-align:right"><font color='{$color}'>{$laProd[2]|nicenum}</font></td></tr>
                    </table>
                    </div>
        	        </div>
                </div>
                
                {elseif $index == 5}
				
                <div class="bau_cube left">
                    <div class="bau_overpic">
        		    <b class="bau_top"></b>
                    
        		    <div class="res_bottom"><span style="font-size:10px">{:l('res_h2o_h2')}</span>
                    <br>
                    <table width="100%">
                    {$color = 'lime'}
                    {if $laProd[2] < 1}{$color = 'red'}{/if}
                    <tr><td>{:l('res3')}</td><td style="text-align:right"><font color='{$color}'>{$laProd[2]|nicenum}</font></td></tr>
                    {$color = 'lime'}
                    {if $laProd[3] < 0}{$color = 'red'}{/if}
                    <tr><td>{:l('res4')}</td><td style="text-align:right"><font color='{$color}'>{$laProd[3]|nicenum}</font></td></tr>
                    <tr><td>{:l('res_capa')}</td><td style="text-align:right">{$prodData['capa']|nicenum}</td></tr>
                    </table>
                    </div>
        	        </div>
                </div>

                {elseif $index == 7}
                <div style="" class="bau_cube left">
                    <div class="bau_overpic">
            	    <b class="bau_top"></b>
                    
        		    <div class="res_bottom"><span>{:l('res_energy_bil')}</span>
                    <br>
                    <table width="100%">
                    {$color = 'lime'}
                    {if $laProd[5] < 0}{$color = 'red'}{/if}
                    <tr><td style="font-size:12px">{:l('energy_left')}</td><td style="text-align:right"><font color='{$color}'>{$laProd[5]|nicenum}</font></td></tr>
                    {$color = 'lime'}
                    {if $laProd[4] < 0}{$color = 'red'}{/if}
                    <tr><td style="font-size:12px">{:l('energy_all')}</td><td style="text-align:right"><font color='{$color}'>{$laProd[4]|nicenum}</font></td></tr>
                    </table>
                    </div>
        	        </div>
                </div>

                {/else}
            {/foreach}
             
            <div class="clear"></div>
            <div style="width:650px" class="tcenter">
                <a href='rohstoffe.php?amu=1'><span class="class_btn">{:l('res_all_mines_up')}</span></a>
                <br><br>
            </div>
            
</div>

</div>
    