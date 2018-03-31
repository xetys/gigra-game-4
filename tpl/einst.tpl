<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">
    <div class="class_content_wrapper">
    
    <form method="post" id="settingsForm">
            <div class="info_first_head">{:l('nav_settings')}</div>
            <table id="einstellungen">
                <tr>
                    <th colspan="2">{:l('einst_acc_settings')}</th>
                </tr>
                {if isActivated()}
                <tr>
                    <td>{:l('einst_username')}</td>
                    <td><input type="text" name="uname" value="{$uname}"></td>
                </tr>
                {/if}
                <tr>
                    <td>{:l('einst_email')}</td>
                    <td><input type="text" name="email" value="{$email}"></td>
                </tr>
                <tr>
                    <td>{:l('einst_old_pw')}</td>
                    <td><input type="password" name="old_pw"></td>
                </tr>
                <tr>
                    <td>{:l('einst_new_pw')}</td>
                    <td><input type="password" name="pw1"></td>
                </tr>
                <tr>
                    <td>{:l('einst_pw_repeat')}</td>
                    <td><input type="password" name="pw2"></td>
                </tr>
                <tr>
                    <td>{:l('einst_baumsg')}</td>
                    <td><input type="checkbox" name="baumsg"{if $bauMsgOn} checked{/if}></td>
                </tr>
                <tr>
                    <td>{:l('einst_spioanz')}</td>
                    <td><input type="text" name="spioanz" value="{$spioanz}"></td>
                </tr>
                {if isActivated()}
                <tr>
                    <th colspan="2">{:l('einst_acc_modes')}</th>
                </tr>
                
                <tr>
                    <td>{:l('einst_umod')}</td>
                    <td>
                    {if $canUmod}
                        {if isset($umodUntil)}
                            {:l('einst_umod_until',date("d.m.Y",$umodUntil),date("H:i:s",$umodUntil))}
                        {else}
                            <input type="checkbox" name="umod"{if $umodOn} checked{/if}>
                        {/if}
                    {else}
                        {:l('einst_umod_cant')}
                    {/if}
                    </td>
                </tr>
                
                <tr>
                    <td>{:l('einst_acc_delete')}</td>
                    <td><input type="checkbox" name="accdel"{if $delOn} checked{/if}></td>
                </tr>
                {/if}
            </table>
        </div>
    <a href='javascript:void(0)' onclick="ID('settingsForm').submit();"><div class="class_btn einst_btn_save">{:l('einst_save')}</div></a>
    </form>
</div>
{if isset($ERROR)}
<script type="text/javascript">
raiseError('{$ERROR}');
</script>
{/if}
{if isset($SUCCESS)}
<script type="text/javascript">
raiseSuccess('{$SUCCESS}');
</script>
{/if}