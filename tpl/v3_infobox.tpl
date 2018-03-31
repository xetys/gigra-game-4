<div class="hiddenInfo v3Info_neu" id="hidden{$PHPPre}{$id}">


        
            <table style="width: 172px;margin: 0 auto;position: absolute;margin-left: 70px;margin-top: -11px;">
            <tr>
            <td>
                    <img src="design/2-0/Gigra-Bauuebersicht_BG_v3_big.png">
        		
            </td>
            </tr>
            </table>
        
                <table style="width:145px; margin:0 auto;">
                <tr>
                <td>
                	{* <div id="v3_infoBox_image"></div> *}
                        <img src="design/items/{$imgPre}{$kon['id']}.gif">
            		
                </td>
                </tr>
                </table>
	
    <table style="width:100%;">
	<tr>
        <td>
            <div class="tleft">
            {$res}<br />
            {:l('v3_duration')}: <b>{$time}</b>
        </td>
    </tr>
    
    <tr>
                <td>
                {if $kon['bld'] == "no"}
                <input type="button" value="{$linktxt}" class="v3_build_inactive">             
            {elseif $kon['bld'] == "yes"}
                <input type="button" value="{$linktxt}" onclick="buildIt('{$PHPPre}',{$kon['id']});" class="v3_build_btn">  
                	</div>
            {elseif $kon['bld'] == "in"}
                <div id="bx{$count_anz}" class="z" title="{$kon['resttime']}">
                    <input type="button" value="{:l('v3_cancel')}" onclick="stopIt('{$PHPPre}',{$kon['id']});" class="v3_cancel_btn">             
                </div>
            {else}
                              -
            {/if}
        </td>
    </tr>
    
    </table>
    {:showInfo(strtolower($PHPPre) . $id, true)}
    {* info.tpl *}

</div>


    <!--
    <table class="DivInfoContent">
        <tr>
        	<td class="DivInfoContent_img"><img src="design/items/{$imgPre}{$kon['id']}.gif" style="position:relative;top:-1px;left:-1px;"></td>
    	</tr>
    	<tr>
    		<td class="DivInfoContent_ress">
    			<a href=info.php?obj={$imgPre}{$kon['id']}>{$kon[name]}</a>{if $kon['lvl'] > 0}{:l('v3_level_x',$kon['lvl'])}{/if}<br>{$kon["desc"]}<br>{$res} {:l('v3_duration')}: <b>{$time}</b>
    		</td>
    	</tr>
    	<tr>
    		<td class="DivInfoContent_cancel">   
                    {if $kon['bld'] == "no"}
                     <font color=red>{$linktxt}</font>
                    {elseif $kon['bld'] == "yes"}
                      <a href="javascript:void(0);" onclick="buildIt('{$PHPPre}',{$kon['id']});"><font color=#00FF00>{$linktxt}</font></a>
                    {elseif $kon['bld'] == "in"}
                      <div id="bx{$count_anz}" class="z" title="{$kon['resttime']}"><a class="DivInfoContent_cancel_a" href="javascript:void(0);" onclick="stopIt('{$PHPPre}',{$kon['id']});">{:l('v3_cancel')}</a></div>
                    {else}
                      -
                    {/if}
    		</td>
    	</tr>
    </table>
    -->