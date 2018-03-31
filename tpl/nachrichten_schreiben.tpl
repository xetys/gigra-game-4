<div id="V3_Content">
    <div class="class_content_wrapper" style="border:0;">
    				<div class="mgs_replay_head">
						{if $subj != ""}
						{:l('msgr_from')} <b><a href="u.php?i={$to}">{$empf}</a></b> | {:l('msgr_to')} <b><a href="u.php?i={$to}">{$empf}</a></b> | {:l('msgr_subj')} <b>{$subj}</b>
						{else}
						{:l('msgr_msgto')} <a href="u.php?i={$to}">{$empf}</a>
						{/if}
					</div>
<div id="nachricht_replay">
		<form action="nachrichten.php" method="post" style="border:0;">
			<input type="hidden" name="a" value="n">
			<input type="hidden" name="to" value="{$to}">

			<input class="nachrichten_schreiben_textarea_input" type="text" id="msgSubj" name="subj" value="{$subj}" maxlength="40" size="50"/>
			<textarea class="nachrichten_schreiben_textarea_input" name="text" id="msgText" rows="18" cols="60">{$txtr}</textarea>
			<input class="nachrichten_schreiben_textarea_input" type="submit" value="{:l('msgr_send')}">
			
		</form>
</div>
	</div>

</div>