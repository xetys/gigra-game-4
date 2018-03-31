<div id="osx-modal-content">
    		<div id="osx-modal-title">Ich bin nicht da</div>
			<div class="close"><a href="#" class="simplemodal-close">x</a></div>
			<div id="osx-modal-data">
            <p>Ich bin Gar nicht Da</p>
            <div id="hidden{$PHPPre}{$id}">

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


</div>
            
            <p>Über mir müsste Content sein</p>

<div id="osx-modal-footer">
	<p><button class="simplemodal-close">Close</button> <span>(or press ESC or click the overlay)</span></p>
</div>
			</div>
		</div>
        
        