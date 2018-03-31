<div id="login_index">

<form action="login.php" method="post">
				<input class="index_login_input" type="hidden" name="i" value="y">
		
		<table class="login_table">
			<tr class="login_table_tr">
				<td class="login_table_td" colspan="2">{$title} Login</td>
			</tr>
			
			<tr class="login_table_tr">
				<td colspan="2">
					<img src="{$gameURL}/design/logo.gif" />
				</td>
			</tr>
			
			{if isset($fehler)}
			<tr class="login_table_tr">
				<td class="f" colspan="2">
					{$fehler}
				</td>
			</tr>
			{/if}
			
			<tr class="login_table_tr">
				<td class="login_table_td">
					{:l('username')}
				</td>
				<td class="login_table_td">
					<input class="index_login_input" type="text" name="name" value="" maxlength="15">
				</td>
			</tr>
			
			<tr class="login_table_tr">
				<td class="login_table_td">
					{:l('password')}
				</td>
				<td  class="login_table_td">
					<input class="index_login_input" type="password" name="pw" maxlength="40">
				</td>
			</tr>
			{*
			<tr class="login_table_tr">
				<td class="login_table_td">
					{:l('this_code')}
				</td>
				<td class="login_table_td" valign="bottom">
					<img src="captcha.php" alt="Code">
				</td>
			</tr>
			*}
 			<tr class="login_table_tr">
				<td class="login_table_td">
					{:l('in_this_box')}
				</td>
				<td class="login_table_td" valign="bottom">
					<input class="index_login_input" type="text" name="code" id="code" autocomplete="off">
				</td>
			</tr>
			
			<tr class="login_table_tr">
				<td class="login_table_td">
					{:l('round')}
				</td>
				<td class="login_table_td" id="sl_runde">
					<select class="index_login_select" name="runde" onchange="document.getElementById('pwvergessen').href = 'pwvergessen.php?runde=' + this.value;document.getElementById('status').href = 'status.php?runde=' + this.value;">
							{foreach $laRunden as $liRunde => $lsName}
						<option value='{$liRunde}'>
							{$lsName}
						</option>  
							{/foreach}
					</select>
			</tr>
			
			<tr class="login_table_tr">
				<td class="login_table_td" colspan="2">
					<input class="index_loginsubmit" type="submit" value="{:l('login')}">
				</td>
			</tr>
			<tr class="login_table_tr">
				<td class="login_table_td" colspan="2">
					<a href="register.php">{:l('register')}</a>
				</td>
			</tr>
			
			<tr class="login_table_tr">
				<td class="login_table_td" colspan="2">
					<a href="resetpw.php?runde=1" id="pwvergessen" onmouseover="document.getElementById('sl_runde');" onmouseout="document.getElementById('sl_runde');">{:l('forgotpw')}</a>
				</td>
			</tr>
			
			<tr class="login_table_tr">
				<td class="login_table_td" colspan="2">
					<a href="status.php" target="_new" id="status" onmouseover="document.getElementById('sl_runde');" onmouseout="document.getElementById('sl_runde');">{:l('serverstatus')}</a>
				</td>
			</tr>
		</table>
</form>

	
</div>