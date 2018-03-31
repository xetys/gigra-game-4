<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">
    <div class="class_content_wrapper tcenter">
        <!-- Bauen und Forschen -->
        <div class="info_first_head">{:l('nav_msg')}</div>
        
        <div class="left">
            <ul class="msg_tabs">
                <li class="active" id="tab_all"><a href="javascript:void(0);" onclick="switchMsgTab('all');">{:l('msg_allmsg')} {if $unread["all"] > 0}({$unread["all"]|nicenum}){/if}</a></li>
                <li id="tab_player"><a href="javascript:void(0);" onclick="switchMsgTab('player');">{:l('msg_player')} {if $unread["player"] > 0}({$unread["player"]|nicenum}){/if}</a></li>
                <li id="tab_combat"><a href="javascript:void(0);" onclick="switchMsgTab('combat');">{:l('msg_combat')} {if $unread["combat"] > 0}({$unread["combat"]|nicenum}){/if}</a></li>
                <li id="tab_spy"><a href="javascript:void(0);" onclick="switchMsgTab('spy');">{:l('msg_spy')} {if $unread["spy"] > 0}({$unread["spy"]|nicenum}){/if}</a></li>
                <li id="tab_fleet"><a href="javascript:void(0);" onclick="switchMsgTab('fleet');">{:l('msg_fleet')} {if $unread["fleet"] > 0}({$unread["fleet"]|nicenum}){/if}</a></li>
                <li id="tab_build"><a href="javascript:void(0);" onclick="switchMsgTab('build');">{:l('msg_build')} {if $unread["build"] > 0}({$unread["build"]|nicenum}){/if}</a></li>
                <li id="tab_other"><a href="javascript:void(0);" onclick="switchMsgTab('other');">{:l('msg_other')} {if $unread["other"] > 0}({$unread["other"]|nicenum}){/if}</a></li>
                <li id="tab_archive"><a href="javascript:void(0);" onclick="switchMsgTab('archive');">{:l('msg_archive')} {if $unread["archive"] > 0}({$unread["archive"]|nicenum}){/if}</a></li>
            </ul>
        </div>
        <div class="">
        <form action="msg.php" method="post">
            <select name="selection" onchange="if(this.value == 'all') $('#archive_option').hide(); else $('#archive_option').show();">
                <option value="sel">{:l('msg_drop1_selected')}</option>
                <option value="all">{:l('msg_drop1_all')}</option>
            </select>
            <select name="func">
                <option value="mar">{:l('msg_drop2_read')}</option>
                <option value="del">{:l('mgs_drop2_remove')}</option>
                <option value="arc" id="archive_option">{:l('msg_move_to_archive')}</option>
            </select>
            <input type="submit" value="OK" name="selfunc">

            {* alle nachrichten *}
            <table id="msg_all" class="msg_item_table">
                <tr>
                    <td colspan="100%">
                    {if $msgItem['ally'] != 'all'}
                        {if $start-10 >= 0}<a href="msg.php?start={$start-10}&page=all">&lt;&lt;</a>{/if}
                        {if $start == 0 or $page != "all"}1{else}{$start}{/if} {:l('v3_of')} {$all_count}
                        {if $start+10 < $all_count}<a href="msg.php?start={$start+10}&page=all">&gt;&gt;</a>{/if}
                    {/if}
                    </td>
                </tr>
                <tr>
                    <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]" onclick="MsgToggle('all')"></td>
                    <td colspan="3">{:l('msg_drop1_all')}</td>
                </tr>
                {foreach $all as $msgItem}
                    {if $msgItem["mode"] == "cmd"}
                    <tr>
                        <td style="width:5px">{if $msgItem['ally'] != 'all'}<input type="checkbox" name="msgs[{$msgItem['msg_id']}]">{else}&bull;{/if}</td>
                        <td>{$msgItem["coords"]|coordFormat}</td>
                        <td colspan="2">{:date("d.m.Y H:i:s",$msgItem['time'])}</td>
                    </tr>
                    <tr>
                        <td colspan="100%" class="tleft">
                            {:decode_cmd_msg(ikf2array($msgItem['text']))}
                        </td>
                    </tr>
                    {else}
                        <tr>
                            <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]"></td>
                            <td><a href="playercard.php?u={$msgItem['fromuid']}">{$msgItem['uname']}</a></td>
                            <td>{:date("d.m.Y H:i:s",$msgItem['time'])}</td>
                            <td><a href="msg.php?id={$msgItem['msg_id']}&modal=true&width=600&height=400" class="thickbox" {if $msgItem["red"] == "yes"} style="font-weight:normal"{/if} onclick="$(this).css('font-weight','normal');">{if empty($msgItem['subj'])}({:l('msg_nosubj')}){else}{$msgItem['subj']}{/if}</a></td>
                        </td>
                    {/if}
                {/foreach}
            </table>
            {* spieler nachrichten *}
            <table id="msg_player" class="msg_item_table hidden">
                <tr>
                    <td colspan="100%">
                        {if $start-10 >= 0}<a href="msg.php?start={$start-10}&page=player">&lt;&lt;</a>{/if}
                        {if $start == 0 or $page != "player"}1{else}{$start}{/if} {:l('v3_of')} {$player_count}
                        {if $start+10 < $player_count}<a href="msg.php?start={$start+10}&page=player">&gt;&gt;</a>{/if}
                    </td>
                </tr>
                <tr>
                    <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]" onclick="MsgToggle('player')"></td>
                    <td colspan="3">{:l('msg_drop1_all')}</td>
                </tr>
                {foreach $sorted['player_msg'] as $msgItem}
                    {if $msgItem["mode"] == "cmd"}

                    {else}
                        <tr>
                            <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]"></td>
                            <td><a href="playercard.php?u={$msgItem['fromuid']}">{$msgItem['uname']}</a></td>
                            <td>{:date("d.m.Y H:i:s",$msgItem['time'])}</td>
                            <!--<td><a href="msg.php?id={$msgItem['msg_id']}&start={$start}&page={$page}&modal=true&width=600&height=400" class="thickbox" {if $msgItem["red"] == "yes"} style="font-weight:normal"{/if} onclick="$(this).css('font-weight','normal');">{if empty($msgItem['subj'])}({:l('msg_nosubj')}){else}{$msgItem['subj']}{/if}</a></td>-->
                            <td><a href="javascript:MsgBox('msg.php?id={$msgItem['msg_id']}');" {if $msgItem["red"] == "yes"} style="font-weight:normal"{/if} onclick="$(this).css('font-weight','normal');">{if empty($msgItem['subj'])}({:l('msg_nosubj')}){else}{$msgItem['subj']}{/if}</a></td>
                        </td>
                    {/if}
                {/foreach}
            </table>
            
            {* Kampf nachrichten *}
            <table id="msg_combat" class="msg_item_table hidden">
                <tr>
                    <td colspan="100%">
                    {if $start-10 >= 0}<a href="msg.php?start={$start-10}&page=combat">&lt;&lt;</a>{/if}
                    {if $start == 0 or $page != "combat"}1{else}{$start}{/if} {:l('v3_of')} {$combat_count}
                    {if $start+10 < $combat_count}<a href="msg.php?start={$start+10}&page=combat">&gt;&gt;</a>{/if}
                    </td>
                </tr>
                <tr>
                    <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]" onclick="MsgToggle('combat')"></td>
                    <td colspan="3">{:l('msg_drop1_all')}</td>
                </tr>
                {foreach $sorted['combat_msg'] as $msgItem}
                    {if $msgItem["mode"] == "cmd"}
                    <tr>
                        <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]"></td>
                        <td>{$msgItem["coords"]|coordFormat}</td>
                        <td>{:date("d.m.Y H:i:s",$msgItem['time'])}</td>
                    </tr>
                    <tr>
                        <td colspan="100%" class="tleft">
                            {:decode_cmd_msg(ikf2array($msgItem['text']))}
                        </td>
                    </tr>
                    {/if}
                {/foreach}
            </table>
            
            {* spio nachrichten *}
            <table id="msg_spy" class="msg_item_table hidden">
                <tr>
                    <td colspan="100%">
                    {if $start-10 >= 0}<a href="msg.php?start={$start-10}&page=spy">&lt;&lt;</a>{/if}
                    {if $start == 0 or $page != "spy"}1{else}{$start}{/if} {:l('v3_of')} {$spy_count}
                    {if $start+10 < $spy_count}<a href="msg.php?start={$start+10}&page=spy">&gt;&gt;</a>{/if}
                    </td>
                </tr>
                <tr>
                    <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]" onclick="MsgToggle('spy')"></td>
                    <td colspan="3">{:l('msg_drop1_all')}</td>
                </tr>
                {foreach $sorted['spy_msg'] as $msgItem}
                    {if $msgItem["mode"] == "cmd"}
                    <tr>
                        <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]"></td>
                        <td>{$msgItem["coords"]|coordFormat}</td>
                        <td>{:date("d.m.Y H:i:s",$msgItem['time'])}</td>
                    </tr>
                    <tr>
                        <td colspan="100%" class="tleft">
                            {:decode_cmd_msg(ikf2array($msgItem['text']))}
                        </td>
                    </tr>
                    {/if}
                {/foreach}
            </table>
            
            {* flotten nachrichten *}
            <table id="msg_fleet" class="msg_item_table hidden">
                <tr>
                    <td colspan="100%">
                    {if $start-10 >= 0}<a href="msg.php?start={$start-10}&page=fleet">&lt;&lt;</a>{/if}
                    {if $start == 0 or $page != "fleet"}1{else}{$start}{/if} {:l('v3_of')} {$fleet_count}
                    {if $start+10 < $fleet_count}<a href="msg.php?start={$start+10}&page=fleet">&gt;&gt;</a>{/if}
                    </td>
                </tr>
                <tr>
                    <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]" onclick="MsgToggle('fleet')"></td>
                    <td colspan="3">{:l('msg_drop1_all')}</td>
                </tr>
                {foreach $sorted['fleet_msg'] as $msgItem}
                    {if $msgItem["mode"] == "cmd"}
                    <tr>
                        <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]"></td>
                        <td>{$msgItem["coords"]|coordFormat}</td>
                        <td>{:date("d.m.Y H:i:s",$msgItem['time'])}</td>
                    </tr>
                    <tr>
                        <td colspan="100%" class="tleft">
                            {:decode_cmd_msg(ikf2array($msgItem['text']))}
                        </td>
                    </tr>
                    {/if}
                {/foreach}
            </table>
            
            {* build nachrichten *}
            <table id="msg_build" class="msg_item_table hidden">
                <tr>
                    <td colspan="100%">
                    {if $start-10 >= 0}<a href="msg.php?start={$start-10}&page=build">&lt;&lt;</a>{/if}
                    {if $start == 0 or $page != "build"}1{else}{$start}{/if} {:l('v3_of')} {$build_count}
                    {if $start+10 < $build_count}<a href="msg.php?start={$start+10}&page=build">&gt;&gt;</a>{/if}
                    </td>
                </tr>
                <tr>
                    <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]" onclick="MsgToggle('build')"></td>
                    <td colspan="3">{:l('msg_drop1_all')}</td>
                </tr>
                {foreach $sorted['build_msg'] as $msgItem}
                    {if $msgItem["mode"] == "cmd"}
                    <tr>
                        <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]"></td>
                        <td>{$msgItem["coords"]|coordFormat}</td>
                        <td>{:date("d.m.Y H:i:s",$msgItem['time'])}</td>
                    </tr>
                    <tr>
                        <td colspan="100%" class="tleft">
                            {:decode_cmd_msg(ikf2array($msgItem['text']))}
                        </td>
                    </tr>
                    {/if}
                {/foreach}
            </table>
            
            {* other nachrichten *}
            <table id="msg_other" class="msg_item_table hidden">
                <tr>
                    <td colspan="100%">
                    {if $start-10 >= 0}<a href="msg.php?start={$start-10}&page=other">&lt;&lt;</a>{/if}
                    {if $start == 0 or $page != "other"}1{else}{$start}{/if} {:l('v3_of')} {$other_count}
                    {if $start+10 < $other_count}<a href="msg.php?start={$start+10}&page=other">&gt;&gt;</a>{/if}
                    </td>
                </tr>
                <tr>
                    <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]" onclick="MsgToggle('other')"></td>
                    <td colspan="3">{:l('msg_drop1_all')}</td>
                </tr>
                {foreach $sorted['other_msg'] as $msgItem}
                    {if $msgItem["mode"] == "cmd"}
                    <tr>
                        <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]"></td>
                        <td>{$msgItem["coords"]|coordFormat}</td>
                        <td>{:date("d.m.Y H:i:s",$msgItem['time'])}</td>
                    </tr>
                    <tr>
                        <td colspan="100%" class="tleft">
                            {:decode_cmd_msg(ikf2array($msgItem['text']))}
                        </td>
                    </tr>
                    {/if}
                {/foreach}
            </table>
            
            {* archiv nachrichten *}
            <table id="msg_archive" class="msg_item_table hidden">
                <tr>
                    <td colspan="100%">
                    {if $start-10 >= 0}<a href="msg.php?start={$start-10}&page=archive">&lt;&lt;</a>{/if}
                    {if $start == 0 or $page != "archive"}1{else}{$start}{/if} {:l('v3_of')} {$archive_count}
                    {if $start+10 < $archive_count}<a href="msg.php?start={$start+10}&page=archive">&gt;&gt;</a>{/if}
                    </td>
                </tr>
                <tr>
                    <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]" onclick="MsgToggle('archive')"></td>
                    <td colspan="3">{:l('msg_drop1_all')}</td>
                </tr>
                {foreach $sorted['archive'] as $msgItem}
                    {if $msgItem["mode"] == "cmd"}
                    <tr>
                        <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]"></td>
                        <td>{$msgItem["coords"]|coordFormat}</td>
                        <td>{:date("d.m.Y H:i:s",$msgItem['time'])}</td>
                    </tr>
                    <tr>
                        <td colspan="100%" class="tleft">
                            {:decode_cmd_msg(ikf2array($msgItem['text']))}
                        </td>
                    </tr>
                    {else}
                        <tr>
                            <td style="width:5px"><input type="checkbox" name="msgs[{$msgItem['msg_id']}]"></td>
                            <td><a href="playercard.php?u={$msgItem['fromuid']}">{$msgItem['uname']}</a></td>
                            <td>{:date("d.m.Y H:i:s",$msgItem['time'])}</td>
                            <td><a href="msg.php?id={$msgItem['msg_id']}&modal=true&width=600&height=400" class="thickbox" {if $msgItem["red"] == "yes"} style="font-weight:normal"{/if} onclick="$(this).css('font-weight','normal');">{if empty($msgItem['subj'])}({:l('msg_nosubj')}){else}{$msgItem['subj']}{/if}</a></td>
                        </td>
                    {/if}
                {/foreach}
            </table>
        </div>



    </div>
</div>
<script type="text/javascript" charset="utf-8">
switchMsgTab('{$activeTab}');
</script>
