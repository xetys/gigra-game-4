<div id="V3_Content">
    <div class="class_content_wrapper">
        <div class="info_first_head">Pranger</div>

{if $gesperrt['stufe'] > 0}
<table class="tabelle_v3">
	<tr><td class="f">{:l("verwarnung_headtext_".$gesperrt['stufe'])}</th></tr>
	<tr>
		<th>
			{:l("verwarnung_info1",$gesperrt['verwarndat'],$gesperrt['admin'])}<br>
            <strong>{$gesperrt['verwarntext']}</strong><br>
            {:l("verwarnung_info2")}<br>
            {:l("verwarnung_konseq_".$gesperrt['stufe'],$gesperrt['bis'])}<br>
		</th>
	</tr>
</table>
{/if}


<table class="tabelle_v3" id="einstellungen">
<tr>
    <th>Spieler</th>
    <th>Verwarnungen</th>
	<th>Datum</th>
	<th>Begr&uuml;ndung</th>
	<th>Admin</th>
	<th>Bis</th>
</tr>
{foreach $verwarnungen as $verwarnung}
<tr>
    <td>{$verwarnung['name']}</td>
    <td>{$verwarnung['wertigkeit']}</td>
    <td>{$verwarnung['verwarndat']}</td>
    <td>{$verwarnung['verwarntext']}</td>
    <td>{$verwarnung['admin']}</td>
    <td>{$verwarnung['bisD']}</td>
</tr>
{/foreach}

</table>
        
        
    </div>
</div>