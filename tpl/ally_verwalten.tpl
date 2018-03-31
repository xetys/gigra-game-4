<div id="V3_Content">{:l("ally_verwalten_")}
    <div class="class_content_wrapper">
        <div class="info_first_head">{:l("ally_verwalten_title")} - [{$allyinfo['tag']}] {$allyinfo['name']}</div>
    <div class="ally_page_btn" style="font-weight: bold; color: #619FC8; text-align:center;">{:l('ally_page_bewerbungen')}</div>
    <div class="tcenter" style="width: 605px;margin: -12px auto 0 23px;background: #0F0F0F;border: 1px solid black;margin-bottom: 20px;">
    {if $bewerbunginfo == 1}
        {:l("ally_verwalten_bewerbung_abg")}<br>
    {elseif $bewerbunginfo ==2}
        {:l("ally_verwalten_bewerbung_ang")}<br>
    {/if}
    
    {* Allianz bewerbungen *}
    {if $bewerbungen != false}
        <table>
        {foreach $bewerbungen as $bewerbung}
            <tr>
                <td><a href="playercard.php?u={$bewerbung['uid']}">{$bewerbung['name']}</a></td>
                <td>{:date("d.m.Y",$bewerbung['time'])}</td>
            </tr>
            <tr>
                <td colspan="2">{:bb_decode2html($bewerbung['text'])}

                <a href="?verwalten&amp;ally={$allyid}&amp;mod=adduser&amp;uid={$bewerbung['uid']}&amp;add=true"><span class="class_btn green">{:l("ally_verwalten_annehmen")}</span></a> 
                <a href="?verwalten&amp;ally={$allyid}&amp;mod=adduser&amp;uid={$bewerbung['uid']}&amp;add=false"><span class="class_btn red">{:l("ally_verwalten_ablehnen")}</span></a>
                </td>
            </tr>
        {/foreach}
        </table>
        
    {else}
        {:l("ally_verwalten_keine_bew")}
    {/if}
    </div>
    {if $uploadfehler >= 1}
        {:l("ally_verwalten_upload_fehler")}
    {/if}
    
    <div class="ally_page_btn" style="font-weight: bold; color: #619FC8; text-align:center;cursor:pointer;" onclick="show_ally('ally_rechte');">{:l("ally_verwalten_rechte")}</div>
    <div id="ally_rechte" class="ally_hidden" style="width: 605px;margin: -12px auto 0 23px;background: #0F0F0F;border: 1px solid black;margin-bottom: 20px;">
    <form action="?verwalten&amp;ally={$allyid}&amp;mod=rechte2" method="post">
    <table width="100%">
    <tr class="highrscore_table_tr_a">
        <th>{:l('ally_verwalten_rang')}</th>
        <th>{:l('ally_verwalten_recht_memberlist')}</th>
        <th>{:l('ally_verwalten_recht_rundmail')}</th>
        <th>{:l('ally_verwalten_recht_verwalten')}</th>
        <th>{:l('ally_verwalten_recht_entf')}</th>
    </tr>
    {foreach $rechte as $rechtID => $recht}
    <tr class="highrscore_table_tr_a">
        <td><input type="text" name="recht[{$rechtID}][name]" value="{$recht['name']}" class="div_konstruktion_input"/></td>
        <td><input type="checkbox" name="recht[{$rechtID}][memberlist]" value="admin" {if $recht['memberlist']}checked{/if} {if $rechtID == 0 or !$allyrechte['memberlist']}disabled="disabled" style="opacity:0.4"{/if}/></td>
        <td><input type="checkbox" name="recht[{$rechtID}][rundmail]" value="admin" {if $recht['rundmail']}checked{/if} {if $rechtID == 0 or !$allyrechte['rundmail']}disabled="disabled" style="opacity:0.4"{/if}/></td>
        <td><input type="checkbox" name="recht[{$rechtID}][admin]" value="admin" {if $recht['admin']}checked{/if} {if $rechtID == 0 or !$allyrechte['admin']}disabled="disabled" style="opacity:0.4"{/if}/></td>
        <td><input type="checkbox" name="recht[{$rechtID}][delete]" value="admin" {if $recht['delete']}checked{/if} {if $rechtID == 0 or !$allyrechte['delete']}disabled="disabled" style="opacity:0.4"{/if}/></td>
    </tr>
    {/foreach}
    </table>
    {:l("ally_verwalten_neuer_rang")}: <input type="text" name="new_rang" value="" class="div_konstruktion_input"/><br>
    <input class="class_btn" style="cursor:pointer;" type="submit" value="{:l("ally_verwalten_save")}"/>
    </form>
    </div>
    
    
    <div class="ally_page_btn" style="font-weight: bold; color: #619FC8; text-align:center;cursor:pointer;" onclick="show_ally('ally_member');">{:l("ally_verwalten_member_verw")}</div>
    <div id="ally_member" class="ally_hidden">
    <form action="?verwalten&amp;ally={$allyid}&amp;mod=rechte" method="post">
        <input type="hidden" name="mod" value="rechte"/>
        <table style="width: 605px;margin: -23px auto 0 -25px;background: #0F0F0F;border: 1px solid black;margin-bottom: 20px;">
            <tr>
                <th>{:l("ally_verwalten_username")}</th>
                <th></th>
                <th>{:l("ally_verwalten_rang")}</th>
                <th>{:l("ally_verwalten_punkte")}</th>
                <th>{:l("ally_verwalten_status")}</th>
            </tr>
            {foreach $allymember as $member}
            <tr class="highrscore_table_tr_a">
                <td><a href="p.php?x&u={$member['id']}">{$member['name']}</a></td>
                <td>
                    <a href="nachrichten.php?to={$member['id']}" class="hightscore_mail_icon"></a> 
                    <a href="allianzen.php?verwalten&ally={$allyid}&amp;mod=kickuser&amp;uid={$member['id']}"><img src="{$gameURL}/design/2-0/global_cancel.png"</a>
                </td>
                <td>
                {if $member['isFounder']}
                    {$member['rang']}
                {else}
                    <select name="{$member['id']}" size="1">
                    {foreach $rechte as $rechtenum => $recht}
                        {if $rechtenum > 0}
                            <option value="{$rechtenum}"{if $member['rang'] == $recht["name"]} selected{/if}>{$recht['name']}</option>
                        {/if}
                    {/foreach}
                    </select>
                {/if}
                </td>
                <td>{$member['pgesamt']|nicenum}</td>
                <td>
                {if $member['last'] == false}
                {:l("ally_verwalten_offline")}
                {else}
                {$member['last']}
                {/if}
                {if $member['umod'] != 0}
                    <br/>{:l("ally_verwalten_umod")}
                {/if}    
                </td>
            </tr>
            {/foreach}
            <tr>
                <td colspan="5">
                    <input class="class_btn" style="width: 600px; cursor:pointer;" type="submit" value="{:l("ally_verwalten_rechte_save")}"/>
                </td>
            </tr>
        </table>
    </form>
    
    </div>

    
    
    <div class="ally_page_btn" style="font-weight: bold; color: #619FC8; text-align:center;cursor:pointer;" onclick="show_ally('ally_logo');">{:l("ally_verwalten_logo_page")}</div>
    <div id="ally_logo" class="ally_hidden" style="width: 585px;margin: -12px auto 0 23px;background: #0F0F0F;border: 1px solid black;margin-bottom: 20px;padding:10px;">
        <p>{:l("ally_verwalten_hinweistext")}</p>
        <form method="post" action="?verwalten&ally={$allyid}" enctype="multipart/form-data">
            {if $allyinfo['logo'] != ''}
                <img src="{$allyinfo['logo']}" alt="{:l("ally_verwalten_bild_alt")}" style="max-width:600px;"/><br/>
            {else}
                {:l("ally_verwalten_kein_bild")}<br/>
            {/if}
            <!--input type="hidden" value="256000" name="MAX_FILE_SIZE"/-->
            <input type="hidden" value="logo" name="mod"/>
            <!--Neues Bild (maximal Größe 250 KiB): <br/>-->
            <!--<input type="file" size="40" name="bild"/><br/>-->
            <input class="div_konstruktion_input" type="text" maxlength="255" name="bild" value="{$allyinfo['logo']}"/><br/>
            <input class="class_btn" style="width: 600px; cursor:pointer;" type="submit" value="{:l("ally_verwalten_save")}"/><br/>
        </form>

        <form method="post" action="?verwalten&ally={$allyid}">
            <input type="hidden" value="link" name="mod"/>
            {:l("ally_verwalten_homepage")} <br/>
            <input class="div_konstruktion_input" type="text" name="link" value="{$allyinfo['hp']}"/><br/>
            <input class="class_btn" style="width: 600px; cursor:pointer;" type="submit" value="{:l("ally_verwalten_save")}"/>
        </form>
    </div>

    
    {if $allyrechte['delete']}
    <div class="ally_page_btn" style="font-weight: bold; color: #619FC8; text-align:center;cursor:pointer;" onclick="show_ally('ally_beschreibung');">{:l("ally_verwalten_beschreibung")}</div>
    <div id="ally_beschreibung" class="ally_hidden">
        <form action="?verwalten&ally={$allyid}" method="post">
            <input type="hidden" name="mod" value="text"/>
            <textarea style="width: 100%;background: -moz-radial-gradient(50% 46% 90deg,circle closest-corner, #252525, #090909);background: -webkit-gradient(radial, 50% 50%, 0, 50% 50%, 150, from(#252525), to(#090909));background: #111;-moz-box-shadow: 0px 0px 4px #000 inset;-webkit-box-shadow: 0px 0px 4px #000 inset;box-shadow: 0px 0px 4px #000 inset;outline: 1px solid #333;border: 1px solid black;" name="text" rows="18" cols="60">{$allyinfo['text']|utf8_encode}</textarea><br/>
            <input class="class_btn" style="width: 100%;margin-bottom: 20px;" type="submit" value="{:l("ally_verwalten_save")}"/>
        </form>
    </div>

    

    <div class="ally_page_btn" style="font-weight: bold; color: #619FC8; text-align:center;cursor:pointer;" onclick="show_ally('ally_del');">{:l("ally_verwalten_ally_del")}</div>
    <div id="ally_del" class="ally_hidden">
        <form action="?verwalten&ally={$allyid}" method="post">
            <input type="hidden" name="mod" value="del"/>
            {:l("ally_verwalten_ally_del_text1")}<br/>
            {:l("ally_verwalten_passwort")}<br/>
            <input type="password" name="pw" /><br/>
            <input type="submit" value="{:l("ally_verwalten_ally_del_submit")}"/>
        </form>
    </div>
    {/if}
    
    <style>
    .ally_hidden {
        display: none;
        margin: 10px 50px 10px 50px;
    }
    
    </style>
    <script>
    
    function show_ally(id) {
        if ( $("#"+id).css("display") == "none")
            $("#"+id).css("display", "block");
        else
            $("#"+id).css("display", "none");
    }
    
    </script>
</div>
</div>