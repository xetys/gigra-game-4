<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">
    <div class="class_content_wrapper">
            <div class="info_first_head">{:l('hof_last_kb')}</div>
            <div>{:l('hof_notice')}</div>
    <table class="highrscore_table">
        <tr>
            <th>{:l('hof_date')}</th>
            <th>{:l('kb_title')}</th>
            <th>{:l('ksim_alost')}</th>
            <th>{:l('ksim_vlost')}</th>
            <th>{:l('hof_units')}</th>
            <th>&nbsp;</th>
        </tr>
        {foreach $last as $k => $KB}
            <tr>
                {if $KB['winner'] == 'n'}
                    {$acolor = 'white'}
                    {$vcolor = 'white'}
                {elseif $KB['winner'] == 'a'}
                    {$acolor = 'lime'}
                    {$vcolor = 'red'}
                {else}
                    {$acolor = 'red'}
                    {$vcolor = 'lime'}
                {/if}
                {$units = $KB['a_lost'] + $KB['v_lost']}
                <td>{:date('d.m.Y',$KB['time'])}</td>
                <td>{$KB['title']|strip_tags}
                <td><font color="{$acolor}">{$KB['a_lost']|nicenum}</font></td>
                <td><font color="{$vcolor}">{$KB['v_lost']|nicenum}</font></td>
                <td><u>{$units|nicenum}</u></td>
                <td><a href="kb.php?id={$KB['id']}" target="_blank">{:l('hof_kb')}</a></td>
            </tr>
        {/foreach}
        {if $pages > 1}
        <tr>
        <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td colspan="2">
            {for $p=1;$p<=$pages;$p++}
                {if $p!=$page}<a href='topkbs.php?page={$p}#bottom'><span class="class_btn einst_btn_save">{$p}</span></a>
                {else}{$p}
                {/if}
            {/for}
            </td>
        </tr>
        {/if}
        </table>
    
    
            <div class="info_first_head">{:l('hof_title')}</div>
            
        <table class="highrscore_table">
        <tr>
            <th>*</th>
            <th>{:l('hof_date')}</th>
            <th>{:l('kb_title')}</th>
            <th>{:l('ksim_alost')}</th>
            <th>{:l('ksim_vlost')}</th>
            <th>{:l('hof_units')}</th>
            <th>&nbsp;</th>
        </tr>
        {foreach $top10 as $k => $KB}
            <tr>
                {if $KB['winner'] == 'n'}
                    {$acolor = 'white'}
                    {$vcolor = 'white'}
                {elseif $KB['winner'] == 'a'}
                    {$acolor = 'lime'}
                    {$vcolor = 'red'}
                {else}
                    {$acolor = 'red'}
                    {$vcolor = 'lime'}
                {/if}
                {$units = $KB['a_lost'] + $KB['v_lost']}
                <td>{$k+1}</td>
                <td>{:date('d.m.Y',$KB['time'])}</td>
                <td>{$KB['title']|strip_tags}
                <td><font color="{$acolor}">{$KB['a_lost']|nicenum}</font></td>
                <td><font color="{$vcolor}">{$KB['v_lost']|nicenum}</font></td>
                <td><u>{$units|nicenum}</u></td>
                <td><a href="kb.php?id={$KB['id']}" target="_blank">{:l('hof_kb')}</a></td>
            </tr>
        {/foreach}
        </table>
    </div>
</div>