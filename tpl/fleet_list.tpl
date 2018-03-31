{foreach $laFids as $fid}
    {:showFleetInfo($fid)}
{/foreach}
<input type="hidden" id="timeNow" value="{:time()}">
<script id="flscript" type="text/javascript">
eventTimer();
</script>