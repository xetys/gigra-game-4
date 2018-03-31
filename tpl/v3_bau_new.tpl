<div class="bau_info_part" id="bau_info_part_{$PHPPre}">
<table class="bau_info_part_table">
<tr><th class="info_head_th">
{:l('infoBox_text_h1')}
</th></tr>
<tr>
<td>
{:l('infoBox_text')}
</td>
</tr>
</table>

</div>
<!-- Gebäude und Forschung ! -->
{foreach $konst_tbl as $id => $kon}
    


 
        {if is_string($kon)}
        {php}continue;{/php}
    {/if}
    
    {php}/**
    {if $kon['lvl'] > 0}
      {$stufe = '(Stufe '.$kon['lvl'].')}
    {/if}
    **/{/php}
    
        {if $PHPPre == "F" and $id == 18}
            {$isMikro = true}
            <div class="hidden">{:question(666,'')}</div>
        {else}
            {$isMikro = false}
        {/if}
        <div  style="background:url(design/items/{$PHPPre|strtolower}{$id}.gif);" class="bau_cube" id="{$PHPPre|strtolower}{$id}">
            <div onclick="showItemInfo('{$PHPPre}','{$id}');" ondblclick="{if $isMikro}$('#question_666').click();{else}buildIt('{$PHPPre}',{$id}){/if}" class="bau_info_show">    
                    <div class="bau_overpic">
                    {if $kon['bld'] == "in"}
                        <div class="cube_php" >
                        <canvas id="bau{$JSPre}{$id}">
                        </canvas>
                    {/if}
                    {if $kon['bld'] == "no"}
                        <div class="bau_unable">
                    {/if}
                    
                    
            
                	<b class="bau_top{if $kon['bld'] == "in"} onprogress{/if}">{$kon['lvl']} {$kon['name']} 
                    {if $kon['bld'] == "in"}
                        <br><br>
                        <center><span id="bau{$JSPre}{$id}time" style="font-size:19px;font-weight:bold;background:#000;color:yellow;">{:format_zeit($kon['resttime'])}</span></center>
                        <div class="bau_top_right_bottom">
                            <!-- <img onclick="buildIt('{$PHPPre}',{$id})" src="http://refact.gigra.stytex.de/design/2-0/Gigra-Build-UP.png" width="28" height="21" /> -->
    						<!--<a onclick="stopIt('{$PHPPre}',{$kon['id']});" href="#" style="color:red; font-size:20px;"> X </a>-->
                        </div>
                    {/if}
                    
                    </b>
                    <!-- <div class="bau_res">

                    </div> -->
            		<div class="{if $kon['bld'] != "in"}res_bottom{else}bau_bottom{/if}{if $kon['bld'] == "in"} onprogress{/if}">
                   {if $kon['bld'] != "in"}
                     {foreach $kon['res'] as $liKey => $liRes}
                        {if $liRes > 0}
                            {$lsResKey = 'shortres'.($liKey+1)}
                            {$color = $liRes > $laRes[$liKey] ? 'red' : 'green'}
                            <span class="{$color}">{$liRes|nicenum} {:l($lsResKey)}</span><br>
                        {/if}
                    {/foreach}
                    {$kon['time']|format_zeit}
                    {else}
                        <!--<a href='javascript:void(0)' onclick="stopIt('{$PHPPre}',{$kon['id']});"><div class="class_btn einst_btn_save">{:l('v3_cancel')}</div></a>-->
                    {/if}
                    </div> 
            		{if $kon['bld'] != "yes" AND $kon['bld'] != "other"}
                        </div>
                    {/if}
            	    </div>
                    
            </div>

    </div>
    


	
{/foreach}


{$lsInfoBoxes}


<div id="{$JS_BauEval}">{$bauEval}</div>

{if !$mode_ajax}
    <script type='text/javascript'>eval(ID('{$JS_BauEval}').innerHTML);</script>
{/if}