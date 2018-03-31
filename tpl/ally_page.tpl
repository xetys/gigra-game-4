<div id="V3_Content">
    <div class="class_content_wrapper">
        <div class="info_first_head">{:l("ally_page_allianz")} - [{$allyinfo['tag']}] {$allyinfo['name']}</div>
        
        
        <div id="ally_content" class="tcenter">
        
            <div class="tcenter">
                {if $allyinfo['logo'] != ''}<img src="{$allyinfo['logo']}" alt="Allianz Logo" style="max-width:550px;"/>{/if}
            </div>
        <!-- für diesen Inhalt ist die Allianz verantwortlich-->
            {:bb_decode2html($allyinfo['text'])}
        <!-- ende für diesen Inhalt ist die Allianz verantwortlich-->
        
        
        
            
            
        
        </div>
    
    {if $myally==true}
            <div class="tcenter" style="width:655px">
                <div class="ally_page_btn">{:l('ally_member_rang')}: {$allyrechte['name']}</div>
                {if $allyrechte['admin']}
                    <a href="./allianzen.php?verwalten&ally={$allyid}"><div class="ally_page_btn" style="cursor:pointer;font-weight:bold;">{:l("ally_page_adminmenu")}</div></a>
                    {if $bewerbungen > 0}{$bewerbungen} {:l("ally_page_bewerbungen")}{/if}
                {/if}
                
                {if $allyrechte['memberlist']}
                    <div class="ally_page_btn allylist" style="cursor:pointer;font-weight:bold;">{:l("ally_page_allyliste")}</div>
                    <div id="allylistcontent"><!--hier wird die ally liste angezeigt--></div>
                {/if}
                {*
                    <div class="ally_page_btn allyforum" style="cursor:pointer;font-weight:bold;">{:l("ally_page_forum")}</div>
                    <div id="ally_page_btn allyforumcontent" style="text-align:center; margin:auto; width:500px;"><!--hier wird die ally liste angezeigt--></div>
                *}
                
                {if $allyrechte['rundmail']}
                    <a href="./allianzen.php?rundmail&ally={$allyid}"><div class="ally_page_btn" style="cursor:pointer;font-weight:bold;">{:l("ally_page_allymsg")}</div></a>
                {/if}
                {if $admin == 1}
                    <a href="./allianzen.php?rundmailall"> <div class="ally_page_btn">{:l("ally_page_adminrundmail")}</div></a>
                {/if}
                
                {if $allyrechte['founder'] and count($member) > 0}
                    <div class="ally_page_btn">
                    <form action="allianzen.php?abtreten" method="post">
                    {:l('ally_verwalten_abtreten')}
                        <select name="newFounder">
                        {foreach $member as $mem}    
                            <option value="{$mem['id']}">{$mem['name']}</option>
                        {/foreach}
                        </select>
                    <input type="submit" value="{:l('einst_save')}">
                    </form>
                    
                    </div>
                {else}
                    <div class="ally_page_btn">{:question(2,l('ally_page_verlassen'))}
                    </div>
                {/if}
                
               
            </div>
            {/if}
    </div>
</div>


 <script>
            <!--
            var allylist = 0;
            $('.allylist').click( function () {
                if (allylist == 0)
                {
                    document.getElementById('allylistcontent').style.display='block';
                    $.ajax({
                        url: "allianzen.php?member&ally={$allyid}",
                        success: function( data ) {
                            $('#allylistcontent').html(data);
                        }
                    });
                    allylist=1;
                }
                else
                {
                    document.getElementById('allylistcontent').style.display='none';
                    allylist=0;
                }
            });
            var allyforum = 0;
            $('.allyforum').click( function () {
                if (allylist == 0)
                {
                    document.getElementById('allyforumcontent').style.display='block';
                    $.ajax({
                        url: "allianzen.php?forum&ally={$allyid}",
                        success: function( data ) {
                            $('#allyforumcontent').html(data);
                        }
                    });
                    allylist=1;
                }
                else
                {
                    document.getElementById('allyforumcontent').style.display='none';
                    allylist=0;
                }
            });
            //-->
            </script>