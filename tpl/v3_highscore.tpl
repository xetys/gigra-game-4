<div id="V3_Content">
    <div class="class_content_wrapper">
        <div class="tcenter">
            <table width="100%">
                <tr>
                    <td class="tcenter"><a href="?page=punkte"><img src="design/3/gg_hs_punkte.png" width="80"></a></td>
                    <td class="tcenter"><a href="?page=ally"><img src="design/3/gg_hs_ally.png" width="80"></td>
                    <td class="tcenter"><a href="?page=infra"><img src="design/3/gg_hs_infra.png" width="80"></td>
                    <td class="tcenter"><a href="?page=forschung"><img src="design/3/gg_hs_forschung.png" width="80"></td>
                    <td class="tcenter"><a href="?page=flotten"><img src="design/3/gg_hs_flotten.png" width="80"></td>
                    <td class="tcenter"><a href="?page=deff"><img src="design/3/gg_hs_deff.png" width="80"></td>
                    <td class="tcenter"><a href="?page=level"><img src="design/3/gg_hs_level.png" width="80"></td>
                </tr>
                <tr>
                    <td class="tcenter">{:l('high_points')}</td>
                    <td class="tcenter">{:l('high_ally')}</td>
                    <td class="tcenter">{:l('high_infrast')}</td>
                    <td class="tcenter">{:l('high_forsch')}</td>
                    <td class="tcenter">{:l('high_flotten')}</td>
                    <td class="tcenter">{:l('high_vert')}</td>
                    <td class="tcenter">{:l('high_level')}</td>                    
                </tr>
            </table>
        </div>
        <br>
        <div>
        {foreach $data as $i => $row}
            {if $i == 0}
                {$pages = $page == "ally" ? $pageData['aPages'] : $pageData['uPages']}
                {* Page Header *}
                {if $pages > 1}
                    {$pagenum=($start/100)+1}
                    <div class="tcenter">
                    {for $j=1;$j<=$pages;$j++}
                        {if $j == $pagenum}
                            {$j}
                        {else}
                            <a href="?page={$page}&start={:floor(($j*100)-1)}"><span class="class_btn einst_btn_save">{$j}</span></a>
                        {/if}
                    {/for}
                    </div>
                    <br>
                    <br>
                {/if}
                
                {* Tabellen Kopf *}
                <table class="highrscore_table">
                <tr>
                        <th class="info_head_th">{:l('high_rang')}</th>
                    {if $page == 'ally'}
                        <th class="info_head_th">{:l('galaxy_ally')}</th>
                        <th class="info_head_th">{:l('high_allymember')}</th>
                        <th class="info_head_th">{:l('high_points')}</th>
                        <th class="info_head_th">{:l('high_allydp')}</th>
                    {elseif $page == "level"}
                        <th class="info_head_th">{:l('high_player')}</th>
                        <th class="info_head_th">{:l('high_level')}</th>
                        <th class="info_head_th">{:l('high_infrast')}</th>
                        <th class="info_head_th">{:l('high_kriegsf')}</th>
                        <th class="info_head_th">{:l('high_forsch')}</th>
                        <th class="info_head_th">{:l('high_funktion')}</th>
                    {else}
                        <th class="info_head_th">{:l('high_player')}</th>
                        <th class="info_head_th">{:l('high_ally')}</th>
                        <th class="info_head_th">{:l('high_points')}</th>
                        <th class="info_head_th">{:l('high_funktion')}</th>
                    {/if}
                </tr>
            {/if}
            
            {* die eigentliche Highscore *}
            {$me=false}
            {$member=false}
            {if $page == "ally" and $row['id'] == $_SESSION['ally']}{$me=true}
            {elseif $page != 'ally' and $row['id'] == Uid()}{$me = true}
            {elseif $page != 'ally' and $_SESSION['ally'] > '' and $row['allianz'] == $_SESSION['ally']}{$member=true}
            {/if}
            <tr class="highrscore_table_tr_a"{if $me} style="background-color:blue;"{/if}{if $member} style="background-color:green;"{/if}>
                <td>{$i+1+$start}
                {if $page=="punkte"}
                    {$diff = $row['recentRank']-($i+1+$start)}
                    {if $diff > 0}
                        <span class="green">+{$diff}</span>
                    {elseif $diff < 0}
                        <span class="red">{$diff}</span>
                    {else}
                        &bull;
                    {/if}
                {/if}
                </td>
                {if $page == 'ally'}
                    <td>[<a href="allianzen.php?ally={$row['id']}">{$row['tag']}</a>] <em>{$row['name']}</em></td>
                    <td>{$row['member']}</td>
                    <td>{$row['punkte']|nicenum}</td>
                    <td>{$row['ppm']|nicenum}</td>
                {elseif $page == "level"}
                    <td><a href="playercard.php?u={$row['id']}">{$row['name']}</a>{if $row['allianz'] > ''} - [<a href="allianzen.php&ally={$row['allianz']}">{$row['tag']}</a>]{/if}</td>
                    <td>{:nicenum(getELevel($row['egesamt']))}</td>
                    <td>{:nicenum(getELevel($row['infra']))}</td>
                    <td>{:nicenum(getELevel($row['krieg']))}</td>
                    <td>{:nicenum(getELevel($row['forsch']))}</td>
                    <td><a class="hightscore_mail_icon" href="nachrichten.php?to={$row['id']}"></a>
                {else}
                    <td><a href="playercard.php?u={$row['id']}">{$row['name']}</a></td>
                    <td>{if $row['allianz'] > ''}[<a href="allianzen.php?ally={$row['allianz']}">{$row['tag']}</a>]{else}-{/if}</td>
                    <td>{$row['punkte']|nicenum}</td>
                    <td><a class="hightscore_mail_icon" href="javascript:void(0);" onclick="MsgBox('msg.php?to={$row['id']}')"></a>
                {/if}
            </tr>
        {/foreach}
        </table>
        </div>
    </div>
</div>