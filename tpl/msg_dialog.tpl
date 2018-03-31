{if !$composeMode}
{$fromuser}<br>
<i>{$time}</i><br>
<div>
{$text}
</div>
<input type="hidden" id="msg_id" value="{$msg_id}">
<div class="hidden" id="msgAppend">
[quote={$fromuser}]
{$raw}
[/quote]
</div>
{else}
    <input type="text" id="msgSubject"><br>
    <input type="hidden" id="msg_to" value="{$msg_to}">
{/if}
<textarea name="text" style="width:590px;height:200px" id="msgText"></textarea>


<input type="button" class="v3_build_btn" onclick="{if $composeMode}MsgCompose();{else}MsgReply();{/if}closeModal();" value="{:l('msgr_send')}">
<input type="button" class="v3_cancel_btn" onclick="closeModal();" value="{:l('close')}">
<!--
<a href='javascript:void(0)' onclick="MsgReply();"><div class="class_btn einst_btn_save">{:l('msgr_send')}</div></a>
<a href='javascript:void(0)' onclick="tb_remove()"><div class="class_btn einst_btn_save">{:l('close')}</div></a>
 --->