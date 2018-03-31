<div id="V3_Content">
    <div class="class_content_wrapper">
        <div class="info_first_head">{:l("ally_suche_title")}</div>
<div style="text-align:center;">
    
    <form action="allianzen.php" method="post" id="ally_suchen">
        
        <input type="text" name="q" value="{$_POST['q']|htmlspecialchars}" class="div_konstruktion_input"/>    
        <input type="submit" value="{:l("ally_suche_suchen")}" class="div_konstruktion_input"/>
    </form> 
        {if !isset($_POST['q'])}
        <br>
        <form action="allianzen.php?g" method="post" id="ally_suchen">
        
        <input type="submit" value="{:l("ally_suche_gruenden")}" class="div_konstruktion_input"/>
        </form>
        {/if}
        
    
</div>
{if isset($_POST['q'])}
    {if $Allyliste != false}
    
        <table class="highrscore_table">
            <tr>
                 <th class="info_head_th">{:l("ally_suche_tag")}</th>
                 <th class="info_head_th">{:l("ally_suche_name")}</th>
                 <th class="info_head_th">{:l("ally_suche_func")}</th>
            </tr>
        {foreach $Allyliste as $allyitem}
            <tr class="highrscore_table_tr_a">
                <td>[<a href="allianzen.php?ally={$allyitem['id']}">{$allyitem['tag']}</a>]</td>
                <td>{$allyitem['name']}</td>
                <td><a href="?b={$allyitem['id']}">{:l("ally_suche_bewerben")}</a></td>
            </tr>
        {/foreach}
        </table>
    {else}
        {:l("ally_suche_keine_allys")}
    {/if}
{/if}
</div>
</div>