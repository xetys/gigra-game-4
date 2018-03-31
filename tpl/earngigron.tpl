<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">

    <div class="class_content_wrapper">
        <div class="info_first_head">{:l('earn_gigrons')}</div>
        
        <table id="einstellungen">
            <tr>
                <th colspan="100%">{:l('earn_your_gvf',$gvf)}</th>
            </tr>
            <tr>
                <td colspan="100%">{:l('earn_gvf_info')}</th>
            </tr>
            <tr>
                <th colspan="100%">{:l('earn_1st')}</th>
            </tr>
            <tr>
                <td colspan="100%">{:l('earn_1st_desc')}</th>
            </tr>
            <tr>
                <th colspan="100%">{:l('earn_2nd')}</th>
            </tr>
            <tr>
                <td colspan="100%">{:l('earn_2nd_desc')}</th>
            </tr>
            <tr>
                <th>{:l('earn_your_url')}</th>
                <td><a href="{$myURL}">{$myURL}</a></td>
            </tr>
            <tr>
                <td colspan="100%">
                    <table width="100%">
                        <tr>
                            <th>{:l('username')}</th>
                            <th>{:l('v3_score')}</th>
                            <th>{:l('earn_active')}</th>
                            <th>{:l('galaxy_funcs')}</th>
                        </tr>
                        {if !$players}
                        <tr>
                            <td colspan="100%">{:l('earn_no_player')}</td>
                        </tr>
                        {else}
                            {foreach $players as $pdata}
                                <tr>
                                    <td>{$pdata['name']}</td>
                                    <td>{$pdata['punkte']|nicenum}</td>
                                    <td>{if $pdata['permaActive']}{:l('question_yes')}{else}{:l('question_no')}{/if}</td>
                                    <td>
                                    {if $pdata['status'] == 0}
                                        {:l('earn_status_0')}
                                    {elseif $pdata['status'] == 1}
                                        <a href="earngigron.php?do={$pdata['id']}" class="green">{:l('earn_status_1')}</a>
                                    {elseif $pdata['status'] == 2}
                                        {:l('earn_status_2')}
                                    {elseif $pdata['status'] == 3}
                                        <a href="earngigron.php?do={$pdata['id']}" class="green">{:l('earn_status_3',nicenum($pdata['punkte']))}</a>
                                    {elseif $pdata['status'] == 4}
                                        {:l('earn_status_4')}
                                    {/if}
                                    </td>
                                </td>
                            {/foreach}
                        {/if}
                    </table>
                </td>
            </tr>
            <tr>
                <th colspan="100%">{:l('earn_3rd')}</th>
            </tr>
            <tr>
                <td colspan="100%">{:l('earn_3rd_desc')}</th>
            </tr>
            <tr>
                <th colspan="100%">{:l('earn_4th')}</th>
            </tr>
            <tr>
                <td colspan="100%">{:l('earn_4th_desc')}</th>
            </tr>
        </table>
    </div>
</div>