{if $error}
<div class="red">{$errorText}</div>
{*:getSensorRange()*}
{else}
    {foreach $laFids as $fid}
        {:showFleetInfo($fid,true)}
    {/foreach}
    <input type="hidden" id="timeNow" value="{:time()}">
    <script id="flscript" type="text/javascript">
    eventTimer();
    </script>
{/if}
<a href='javascript:void(0)' onclick="tb_remove()"><div class="class_btn einst_btn_save">{:l('close')}</div></a>
