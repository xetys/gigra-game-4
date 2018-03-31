{if $canEdit}

<table id="kampfbericht_table_main">
<tr><td>
<form action="kb.php?id={$_GET['id']}" method="post" id="main_form">
<input type="hidden" name="edit" value="1">


{:l('kb_title')}<br>
<input type="text" name="title" value="{$title}"><br>

{:l('kb_main_comment')}<br>
<textarea cols="100" rows="20" name="hauptkommentar">{$hauptkommentar}</textarea><br>
{if $is_public}
<a href='javascript:void(0)' onclick="ID('main_form').submit();"><span class="class_btn einst_btn_save">{:l('einst_save')}</span></a>
{else}
<a href='javascript:void(0)' onclick="ID('main_form').submit();"><span class="class_btn einst_btn_save">{:l('kb_publish')}</span></a>
{/if}
</form>
</td></tr>
</table>


{/if}
{if strlen($hauptkommentar) > 0}

<table id="kampfbericht_table_main" width="800">
<tr><td><h1><u>{:l('kb_main_comment')}</u></h1><br>{$hauptkommentar|bb_decode2html}</td></tr>
</table>
{/if}


<table id="kampfbericht_table_main" width="800">
{foreach $comments as $comment}
    <tr>
        <td width="30%">
        {$comment['kom_name']}<br>
        <i>{:date('d.m.Y H:i:s',$comment['kom_time'])}</i>
        </td>
        <td>
        {$comment['kom_text']|strip_tags}
        </d>
    </tr>
{/foreach}
{if $pages > 1}
<tr>
<td colspan="2"><hr></td>
</tr>
<tr>
    <td colspan="2">
    {for $p=1;$p<=$pages;$p++}
        {if $p!=$page}<a href='kb.php?id={$_GET['id']}&page={$p}#bottom'><span class="class_btn einst_btn_save">{$p}</span></a>
        {else}{$p}
        {/if}
    {/for}
    </td>
{/if}
</table>

{if $loggedIn}
<table id="kampfbericht_table_main">
<tr><td>
<form action="kb.php?id={$_GET['id']}#bottom" method="post" id="comment_form">
<input type="hidden" name="new_comment" value="1">

{:l('kb_write_comment')}<br>
<textarea cols="100" rows="20" name="comment"></textarea><br>
<a href='javascript:void(0)' onclick="ID('comment_form').submit();"><div class="class_btn einst_btn_save">{:l('einst_save')}</div></a>
</form>
</td></tr>
</table>
{/if}
<a name="bottom"></a>