<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">

    <div class="class_content_wrapper tcenter">
        <!-- Bauen und Forschen -->
    	<div class="info_first_head">{:l('search')}</div>
        <form action="" method="post">
        <input type="hidden" name="send" value="1">
        <input type="text" name="data" class="div_konstruktion_input" value="{$searchContext}">
        <select name="type" class="div_konstruktion_input">
            <option value="1"{if $searchType == 1} selected{/if}>{:l('search_for_player')}</option>
            <option value="2"{if $searchType == 2} selected{/if}>{:l('search_for_allys')}</option>
        </form>
        <input type="submit" class="div_konstruktion_input" value="{:l('search')}">
        
        {if isset($searched)}
            {if count($laResults) == 0}
                <div class="red">{:l('search_no_results')}</div>
            {else}
            
            <table id="einstellungen">
            <tr>
                <th>{:l('info_name')}</th>
                <th width="100">{:l('galaxy_funcs')}</th>
            </tr>
            {foreach $laResults as $result}
            <tr>
                <td>
                {if $result['type'] == 1}
                    <a href="playercard.php?u={$result['id']}">{$result['name']}</a>
                {elseif $result['type'] == 2}
                    <a href="allianzen.php?ally={$result['id']}">{$result['name']}</a>
                {else}
                    {$result['name']}
                {/if}
                </td>
                <td>
                    {if $result['type'] == 1}
                        <a class="hightscore_mail_icon" href="nachrichten.php?to={$result['id']}"></a>
                    {elseif $result['type'] == 2 and empty($_SESSION['ally'])}
                        <a class="hightscore_mail_icon" href="allianzen.php?b={$result['id']}"></a>
                    {else}
                    -
                    {/if}
                </td>
            
            {/foreach}
            </table>
            {/if}
        {/if}
    </div>
</div>