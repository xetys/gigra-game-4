<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">
<div class="info_first_head">Abstimmung?</div>
<p>
Diese Ansicht ist nur für Testphasen eingerichtet um effektiv eine Benutzerweite Abstimmung zu erhalten.<br>
Deine Antwort ist <span class="red">bindend</span> und <span class="red">nicht zur&uuml;ckziehbar</span>!<br>
Warte ggf. mit der Antwort, um sicherzugehen, eine Richtige Entscheidung zu treffen.<br><br>
MfG<br>
Gigra Team
</p>

{if !$topic}
Kein Thema verfügbar
{else}
<dic class="tcenter">
    <div class="green" style="font-weight:bold">{$question}</div>
    {if $canVote}
        {foreach $options as $k => $value}
            <a href='abstimmen.php?topic={$topic}&vote={$k}'><div class="class_btn" style="width:150px;">{$value}</div></a><br>
        {/foreach}
    {else}
    <table width="100%">
    
        {foreach $options as $k => $value}
            <tr><td>{$value}</td><td>{$votes[$k]}</td><td><span class="gray">({:round($votes[$k]/$voteCount*100,2)} &percnt;)</span></td></tr>
        {/foreach}
        </table>
        Deine Auswahl: {$myVote}<br>
    {/if}
{/if}
</div>
</div>