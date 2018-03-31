
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
            <div ondblclick="{if $isMikro}$('#question_666').click();{else}buildIt('{$PHPPre}',{$id}){/if}" class="bau_info_show">    
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
                        <span id="bau{$JSPre}{$id}time">{:format_zeit($kon['resttime'])}</span>
                        <div class="bau_top_right_bottom">
                            <!-- <img onclick="buildIt('{$PHPPre}',{$id})" src="http://refact.gigra.stytex.de/design/2-0/Gigra-Build-UP.png" width="28" height="21" /> -->
    						<a onclick="stopIt('{$PHPPre}',{$kon['id']});" href="#" style="color:red; font-size:20px;"> X </a>
                        </div>
                    {/if}
                    
                    </b>
                    <!-- <div class="bau_res">

                    </div> -->
            		<div class="res_bottom{if $kon['bld'] == "in"} onprogress{/if}">
                   
                     {foreach $kon['res'] as $liKey => $liRes}
                        {if $liRes > 0}
                            {$lsResKey = 'shortres'.($liKey+1)}
                            {$color = $liRes > $laRes[$liKey] ? 'red' : 'green'}
                            <span class="{$color}">{$liRes|nicenum} {:l($lsResKey)}</span><br>
                        {/if}
                    {/foreach}
                    {$kon['time']|format_zeit}
                    {if $kon['bld'] == "in"}
                    <a href='javascript:void(0)' onclick="stopIt('{$PHPPre}',{$kon['id']});"><div class="class_btn einst_btn_save">{:l('v3_cancel')}</div></a>
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