<div id="V3_Content">
        <div class="class_content_wrapper">
    <div id="nachrichten_schreiben" class="tcenter">
    
    {if $send == false}
        <form action="?rundmail{$mod}&ally={$allyid}" method="post">
        <div class="mgs_replay_head">
            {:l('msgr_msgto')} {:l("ally_rundmail_title")} [{$allyinfo['tag']}] {$allyinfo['name']}
        </div>
        {if isAdmin()}
            <input class="nachrichten_schreiben_textarea_input" type="text" id="msgSubj" name="subj" value="{$subj}" maxlength="40" size="50"/>
        {/if}
        <textarea class="nachrichten_schreiben_textarea_input" name="text" id="msgText" rows="18" cols="60">{$txtr}</textarea>
        <input class="nachrichten_schreiben_textarea_input" type="submit" value="{:l('msgr_send')}">
        </form>
    {else}
        {:l("ally_rundmail_gesendet")}<br>
        <a href="allianzen.php?ally={$allyid}">{:l("ally_rundmail_zur_ally")}</a>
    {/if}
    </div>
    </div>
</div>