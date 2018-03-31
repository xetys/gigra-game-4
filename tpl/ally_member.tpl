<table style="width: 600px;margin: -12px auto;background: #0F0F0F;border: 1px solid black;margin-bottom: 10px;">
<tr>
    <th>{:l("ally_member_name")}</th>
    <th></th>
    <th>{:l("ally_member_rang")}</th>
    <th>{:l("ally_member_punkte")}</th>
    <th>{:l("ally_member_status")}</th>
</tr>
{foreach $allymember as $member}
<tr class="highrscore_table_tr_a">
    <td><a href="p.php?x&u={$member['id']}">{$member['name']}</a></td>
    <td><a href="nachrichten.php?to={$member['id']}" class="hightscore_mail_icon"></a></td>
    <td>{$member['rang']|htmlentities}</td>
    <td>{$member['pgesamt']|nicenum}</td>
    <td>
    {if $member['last'] == false}
        {:l("ally_member_off")}
    {else}
    {$member['last']}
    {/if}
    {if $member['umod'] != 0}
        <br/>{:l("ally_member_umod")}
    {/if}    
    </td>
</tr>
{/foreach}
</table>