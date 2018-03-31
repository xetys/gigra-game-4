<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">

    <div class="class_content_wrapper">
        <div class="info_first_head">{:l('status')}</div>
        <table class="highrscore_table">
        {foreach $list as $key => $value}
            <tr>
                <td>
                    {:l($key)}
                </td>
                <td>
                {if is_numeric($value)}
                    {$value|nicenum}
                {else}
                    {$value}
                {/if}
        {/foreach}
        </table>
    </div>
</div>