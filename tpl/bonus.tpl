<div class="left">
    {$i = 0}
    {$bonusChar = array("","S","M","L","XL")}
    {foreach $bonuspacks as $id => $value}
    {$i=$i+1}
    <a href="javascript:void(0);" class="tcenter bonus_item_a" onclick="showBonusBox({$id});">
        {if in_array($id,array(1,2,3,4))}
            <img width="50" src="design/3/gg_item_res.png">
        {elseif in_array($id,array(5,6,7,8))}
            <img width="50" src="design/3/gg_item_cybot.png">
        {elseif in_array($id,array(9,10,11,12))}
            <img width="50" src="design/3/gg_item_forsch.png">
        {elseif in_array($id,array(13,14,15,16))}
            <img width="50" src="design/3/gg_item_kampf.png">
        {/else}
        <div>
            <span class="bonus_char">{$bonusChar[$i]}</span>
            <br>
            <br>
            <span class="bonus_count" id="bonus_have_{$id}">{$bonusitems[$id]}</span>
        </div>
    </a>
    {if $i==4}
        {$i = 0}
        </div>
        {if $id < 16}
        <div class="left">
        {/if}
    {/if}
    
    {/foreach}

{*
{$i = 0}
{foreach $bonuspacks as $id => $value}
{$i=$i+1}
    <a href='bonusinfo.php?id={$id}&width=660&height=200&modal=true' onclick="tb_remove();" class="thickbox"><div class="class_btn btn_bonus">
    {:l('bonus_'.$id)}
    <br>
    {:l('v3_have_x',$bonusitems[$id])}
    </div></a><br>
    {if $i % 4 == 0}
    </div>
    <div class="left">
    {/if}
{/foreach}
*}


<div class="left" id="bonus_info">
   
</div>
<div class="clear"></div>