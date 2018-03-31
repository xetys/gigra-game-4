<script type="text/javascript">
tutList = [
    {foreach $list as $i => $data}
    {
        el: {$data[0]},
        text: '{$data[1]}'
    }{if $i+1 != count($list)},{/if}
    {/foreach}
];
</script>