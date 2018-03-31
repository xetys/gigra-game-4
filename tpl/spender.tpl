<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">

    <div class="class_content_wrapper tcenter">
        <!-- Bauen und Forschen -->
        <div class="info_first_head">Spende</div>
        Das Gigra-Game Team dankt f&uumlr; eine erfolgreiche Spende an folgende Spieler:<br>
        Enrico Falkenhain<br>
        {foreach $donators as $name}
            {$name}<br>
        {/foreach}
        
        {if isAdmin()}
        <table>
        <tr>
            <th>Spender</th>
            <th>Email</th>
            <th>OK?</th>
        </tr>
        {foreach $requestors as $req}
        <tr>
            <td>{$req['name']}</td>
            <td>{$req['email']}</td>
            <td><a href="?submit={$req['id']}">Best&auml;tigen</a></td>
        </tr>
        {/foreach}
        </table>
        
        {/if}
    </div>
</div>