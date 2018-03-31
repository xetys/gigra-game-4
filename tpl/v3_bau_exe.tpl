
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
    

        <div  style="background:url(design/items/{$PHPPre|strtolower}{$id}.gif);" class="bau_cube">
            <div ondblclick="buildIt('{$PHPPre}',{$id})" class="bau_info_show">    
                    <div class="bau_overpic">
                    {if $kon['bld'] == "in"}
                        <div class="cube_php" >
                        <canvas id="bau{$JSPre}{$id}">
                        </canvas>
                    {/if}
                    {if $kon['bld'] == "no"}
                        <div class="bau_unable">
                    {/if}
                    
                    <div class="bau_image_right_side gradient">
                        <div class="bau_top_right">
                            <img onclick="buildIt('{$PHPPre}',{$id})" src="http://refact.gigra.stytex.de/design/2-0/Gigra-Build-UP.png" width="28" height="21" />
                        </div>
                        <div class="bau_top_right_middle">
                        
<a class="bau_info" href="#">
    <img onclick="showInfo('{$PHPPre}',{$id})" src="http://refact.gigra.stytex.de/design/2-0/Gigra-Build-info.png" width="28" height="21"  />
         <span style="opacity:1 !important; z-index:100 !important;">
            <div class="class_small_header bau_tooltip_head"> {$kon['name']} ({$kon['lvl']}) </div>
            
            {foreach $kon['res'] as $liKey => $liRes}
                {if $liRes > 0}
                    {$lsResKey = 'shortres'.($liKey+1)}
                    {$color = $liRes > $laRes[$liKey] ? 'red' : 'green'}
                        <div class="{$color}">Benötigt: {$liRes|nicenum} {:l($lsResKey)}</div><br />
                {/if}
            {/foreach}
        </span>
                                </a>
                        </div>
        				<!-- 
						<div class="bau_top_right_bottom">
                        <a href="info.php?obj={$PHPPre}{$id}?keepThis=true&TB_iframe=true&height=300&width=400" title="info" class="thickbox">A</a>

						</div>
						-->
					

                    </div>
            
            		<b class="bau_top{if $kon['bld'] == "in"} onprogress{/if}">{$kon['lvl']} 
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
            		<div class="res_bottom_bau{if $kon['bld'] == "in"} onprogress{/if}">
                    <!--
                     {foreach $kon['res'] as $liKey => $liRes}
                        {if $liRes > 0}
                            {$lsResKey = 'shortres'.($liKey+1)}
                            {$color = $liRes > $laRes[$liKey] ? 'red' : 'green'}
                            <span class="{$color}">{$liRes|nicenum} {:l($lsResKey)}</span><br>
                        {/if}
                    {/foreach}-->
                    {$kon['name']}
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