{if $lbSend}
<center>
	<h1>{:l('pw_thankyou')}</h1>
  {:l('pw_sent')}<br>
  <a href="login.php">{:l('pw_tologin')}</a>
</center>
{else}
<center>
	<form action="resetpw.php" method=POST>
		<table width=519>
			{if $lbError}
				<tr><td class="f" colspan="2">{:l('pw_noemail')}</td></tr>
			{/if}
			<tr><td class=c colspan=2>{:l('pw_forgot')}</th></tr>
			<tr><th>{:l('pw_email')}</td><td class=b><input type=text name="mail" size=30></td></tr>
			<tr><th class=c colspan=2><input type=submit value=OK></th></tr>
		</table>
	</form>
</center>
{/if}