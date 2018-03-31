 <div id="bonus_res" style="width:439px;">
       
        <div class="info_first_head bonus_header" style="background-position:top center">{:l('bonus_'.$bid)}</div>
        <div class="tcenter">
        {if in_array($bid,array(1,2,3,4))}
            <img src="design/3/gg_item_res.png">
        {elseif in_array($bid,array(5,6,7,8))}
            <img src="design/3/gg_item_cybot.png">
        {elseif in_array($bid,array(9,10,11,12))}
            <img src="design/3/gg_item_forsch.png">
        {elseif in_array($bid,array(13,14,15,16))}
            <img src="design/3/gg_item_kampf.png">
        {/else}
        </div>
        
        <div class="tcenter">{:l('bonus_'.$bid."_desc")}</div>
        <br>
        {if $bid < 9}
        <div class="tcenter red">{:l('bonus_warning',coordFormat($_SESSION['coords']))}</div>
        {/if}
        <br>
        
        <input id="bonusActivate" type="button" class="{if $avaible}v3_build_btn{else}v3_build_inactive{/if}" value="{:l('bonus_activate')}" onclick="useBonusItem({$bid});">
        <input type="button" class="{if $buyable}v3_build_btn{else}v3_build_inactive{/if}" value="{:l('bonus_buy',nicenum($liCost))}" onclick="buyBonusItem({$bid});">
        
    </div>
{*
<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">

    <div class="class_content_wrapper">
        <div class="info_first_head">{:l('bonus_'.$bid)}</div>
        <div class="tcenter">{:l('bonus_'.$bid."_desc")}</div>
        {if $bid < 9}
        <div class="tcenter red">{:l('bonus_warning',coordFormat($_SESSION['coords']))}</div>
        {/if}
        <br>
        <div class="tcenter">
            <a{if $avaible} href='javascript:void(0)' onclick="useBonusItem({$bid});"{/if}><span id="bonusActivate" class="class_btn einst_btn_save{if !$avaible} inactive{/if}">{:l('bonus_activate')}</span></a>
            <a href='javascript:void(0)' onclick="buyBonusItem({$bid});"><span class="class_btn einst_btn_save {if $buyable}green{else}red inactive{/if}">{:l('bonus_buy',nicenum($liCost))}</span></a>
            <a href='javascript:void(0)' onclick="tb_remove();"><span class="class_btn einst_btn_save">{:l('close')}</span></a>
        </div>
        <br>
    </div>
    
</div>
*}