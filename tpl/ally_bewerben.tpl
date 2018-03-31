<div id="V3_Content">
    <div class="class_content_wrapper">
    <div class="info_first_head">Allianz Bewerben - [{$allyinfo['tag']}] {$allyinfo['name']}</div>
    {if $beworben == false}
        Bewerbung bei der Allianz [{$tag}]
        
        <form action="allianzen.php?b={$tag}" method="post">
        <input type="hidden" name="allyid" value="{$allyid}"/>
        
        <textarea name="text" cols="90" rows="10">
        </textarea><br/>
        <input type="submit" value="Bewerben"/>
        
        </form>
        
        
    {else}
    Bewerbung abgesendet!
    
    
    {/if}
    </div>
</div>