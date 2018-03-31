<center>
{if $lbSendForm}
	{if strlen($lsFehler) > 0}
	<center>
	<table width=700>
	<tr><td class="f">{$lsFehler}</td></tr>
		<a href="javascript:history.back()">{:l('reg_back')}</a>
	</table>
	{if $lbRegPW}
		<form action="register.php" method="POST">
		<table width="519">
		<tr><td class="c" colspan="2">{:l('reg_closed_reg')}</td></tr>
		
		{foreach $laSaveForm as $key => $value}
			<input type=hidden name='{$key}' value='{$value}'>
		{/foreach}
		
		<tr><th><input type="password" name="regpw"></th><th><input type="submit" value="{:l('reg_submit')}"></th></tr>
		</table>
		</form>
	{/if}
	</center> 
	{else}
		<center>
		<table width="700">
			<tr><td class="c">{:l('reg_welcome_gigra')}</td></tr>
			<tr><th>
			{:l('reg_welcome_text')}<br>
			<a href="{$gameURL}/login.php">{:l('pw_tologin')}</a>
			</th>
			</tr>
		</table>
		</center>
	{/if}
{else}
<form action="register.php" method=POST>
<input type=hidden name="i" value="y">
<table width=519>
	<tr><td class=c colspan=2>Anmelden zu Runde <select name="runde">
	{foreach $laRunden as $liRunde => $lsName}
		<option value="{$liRunde}">{$lsName}</option>
	{/foreach}
	</select></th></tr>
	<tr><th>Loginname (Wird im Spiel angezeigt)</td><td class=b><input type=text name="ln" maxlength=15 size=30></td></tr>
	<tr><th colspan=2><font color="#ffff00">Achtung: Nur &quot;jugendfreie&quot; Namen</font></th></tr>
	<tr><th>Wie soll Ihr erster Planet hei&szlig;en ?</td><td class=b><input type=text name="pname" maxlength=20 size=20></td></tr>
	<tr><th>E-mail Addresse</td><td class=b><input type=text name="m1" maxlength=100 size=30></td></tr>
	<tr><th>E-mail (zur Kontrolle)</td><td class=b><input type=text name="m2" maxlength=100 size=30></td></tr>
	<tr><th>Ich akzeptiere die <a href="agb.htm" target="agb">Nutzungsbedingungen</a></td><td class=b><input type=checkbox name="agb"></td></tr>
	<tr><th colspan=2><font color="#ffff00">Achtung: Pro Person nur <u>1</u> Account!</font></th></tr>
	<tr><th class=c colspan=2><input type=submit value=Anmelden></th></tr>
</table>
</form>
{/if}
</center>