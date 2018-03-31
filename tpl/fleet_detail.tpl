<div onclick="$('#fleetInfo_{$fid}').toggle('fast');" class="fleet_detail" id="{$fid}_box">
<input type="hidden" id="{$fid}_tthere" value="{$tthere}">
<input type="hidden" id="{$fid}_tback" value="{$tback}">
<input type="hidden" id="{$fid}_flytime" value="{$flytime}">
<input type="hidden" id="{$fid}_oneway" value="{$oneway}">
<input type="hidden" name="fid_list" value="{$fid}">

                        
    <table>
    <!-- Tooltip anfang
        In der Tablle - Wichtig
    -->
    <div>
<!--<a href='javascript:void(0);'>-->

    <tr>
        <td style="width:50px">
        {$fromc|coordFormat}
        <img src="{$gameURL}/design/Planeten/{$frombild}" width="50">
        </td>
        <td style="width:150px">
            {:nicenum(array_sum($schiffe))}
            <div class="fe_pc_bg">
                <div class="fe_pc_bar fly_there" style="width:0px;background:{$color}" id="{$fid}_bar">
                &nbsp;
            </div>
            <div class="clear"></div>
            <span id="{$fid}_time">...</span>
        </td>
        <td style="width:50px">
        {$toc|coordFormat}
        <img src="{$gameURL}/design/Planeten/{$tobild}" width="50">
        </td>
    </tr>
    {*
                                <div class="tooltip" style="text-align:left">
                                <a href="#"></a> <!-- Sowas wie ein Reset für den Tooltip Inhalt ;)  -->
                                    <h1 class="tooltip_h1">{:l('fleet_event_mission')}: {:l('fleet_mission_'.$mission)}</h1> <!-- Tooltip Header -->
                                    <div class="tooltip_content"> <!-- Tooltip Content -->
        {if !$myfleet}
            <span style="color:#ff0000;">{:l('fleet_event_player')} - {$fromuname}</span>
            <hr />
         {/if}
         
         
            <!-- Liste aller Schiffe -->
            <!--{:l('fleet_event_ships')}-->

            {foreach $schiffe as $id => $count}
            <span style="background:#090D12; padding-bottom:1px, pading-top:1px; width:190px; "><span style="font-weight: bold;">{:l('item_s'.$id)}</span> <span style="text-align:right">{$count|nicenum}</span></span><br />
            {/foreach}

<hr />
            <!-- Ankunft -->
           {:l('fleet_event_arrive')} : {if $tthere > 0}{$lsTThere_formated}{else}{:l('fleet_event_arrived')}{/if}<br />

        {if $myfleet}
            {:l('fleet_event_back')}
            {$lsTBack_formated}
        {/if}
                                    </div><!-- Tooltip Content Ende -->
                                    *}
    </table>
    <div class="hidden" id="fleetInfo_{$fid}">
    <table>
        {if !$myfleet}
        <tr>
            <th>{:l('fleet_event_player')}</th>
            <td>{$fromuname}</td>
        </tr>
        {else}
        {if $tthere > 0}
        <tr>
            <td class="" colspan="2">
                <!-- <a href="javascript:void(0);" onclick="fleetBack('{$realfid}')"><div class="class_btn einst_btn_save">{:l('fleet_event_fleetback')}</div></a> -->
                <input type="button" class="v3_cancel_btn" value="{:l('fleet_event_fleetback')}" onclick="fleetBack('{$realfid}')">
            </td>
        </tr>
        {/if}
        {/if}
        <tr>
            <th>{:l('fleet_event_ships')}</th>
            <td>{foreach $schiffe as $id => $count}{:l('item_s'.$id)} {$count|nicenum}<br>{/foreach}</td>
        </tr>
        <tr>
            <th>{:l('fleet_event_mission')}</th>
            <td>{:l('fleet_mission_'.$mission)}</td>
        </tr>
        <tr>
            <th>{:l('fleet_event_arrive')}</th>
            <td>{if $tthere > 0}{$lsTThere_formated}{else}{:l('fleet_event_arrived')}{/if}</td>
        </tr>
        {if $myfleet or $sensor}
        <tr>
            <th>{:l('fleet_event_back')}</th>
            <td>{$lsTBack_formated}</td>
        </tr>
        {/if}
        {if $myfleet or $mission == "trans" or $mission == "stat"}
        <tr>
            <th>{:l('res1')}</th>
            <td>{$res1|nicenum}</td>
        </tr>
        <tr>
            <th>{:l('res2')}</th>
            <td>{$res2|nicenum}</td>
        </tr>
        <tr>
            <th>{:l('res3')}</th>
            <td>{$res3|nicenum}</td>
        </tr>
        <tr>
            <th>{:l('res4')}</th>
            <td>{$res4|nicenum}</td>
        </tr>
        {/if}
    </table>
    </div>
</div>
{*
Eine Flotte von {$fromc|coordFormat} befindet sich auf den Weg zu {$toc|coordFormat}. Der Missionstyp ist {:l('fleet_mission_'.$mission)}. <br /><br />
 

Es handelt sich hierbei um folgende schiffe:<br />
{foreach $schiffe as $id => $count}
    {:l('item_s'.$id)} : {$count|nicenum}<br />
{/foreach}
<br /><br />
Die Flotte kommt an: <br />
{if $tthere > 0}{$lsTThere_formated}{else}{:l('fleet_event_arrived')}{/if}<br /><br /><br />


        {if $myfleet}
		Du hast folgende Optionen:<br />
            {:l('fleet_event_back')}
            {$lsTBack_formated}
        {/if}
<br /><br />

{if !$myfleet}

	{:l('fleet_event_player')} {$fromuname} <br />
{else}
        {if $tthere > 0}
<a href="javascript:void(0);" onclick="fleetBack('{$realfid}')">{:l('fleet_event_fleetback')}</a> <br />
        {/if}
        {/if}


{:l('fleet_event_arrive')}
	{if $tthere > 0}
		{$lsTThere_formated}
	{else}
		{:l('fleet_event_arrived')}
	{/if}
<br />

Meine Flotte:	<br />
{if $myfleet}
	{:l('fleet_event_back')} {$lsTBack_formated}
{/if}
*}