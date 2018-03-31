<div id="V3_Content">{:l("ally_gruenden_")}
    <div class="class_content_wrapper">
        <div class="info_first_head">{:l("ally_gruenden_title")}</div>
        {if $gegruendet == false}
        <form action="allianzen.php?g" method="post">
            {if isset($fehler[0])}
                {foreach $fehler as $fehler1}
                    <div class="" style="background-color:#f00;text-align:center;">{:l('ally_gruenden_'.$fehler1)}</div>
                {/foreach}
            {/if}
            {:l("ally_gruenden_tag")}<br/>
            [ <input type="text" name="tag" size="8" maxlength="8" value="{$_POST['tag']|htmlspecialchars}" class="div_konstruktion_input"> ]<br/>
            <br/>
            {:l("ally_gruenden_name")}<br/>
            <input type="text" name="name" size="20" maxlength="35" value="{$_POST['name']|htmlspecialchars}" class="div_konstruktion_input"><br/>
            <br/>
            <input type="submit" value="{:l("ally_gruenden_submit")}" class="div_konstruktion_input" />
        </form>
        {else}
            {:l("ally_gruenden_bestaetigung")}<br>
            <a href="allianzen.php">{:l("ally_gruenden_allyseite")}</a>
        {/if}
    </div>
</div>