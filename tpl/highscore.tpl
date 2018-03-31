<div id="V3_Content">
    <div class="class_content_wrapper">
         <div class="info_first_head">

            <form action="highscore.php" method=post>
                    <select class="highrscore_select" name="s" size="1">
                        <option value="u" {if $lsHighscoretyp == 'u'}selected{/if}>{:l("high_player")}</option>
                        <option value="l" {if $lsHighscoretyp == 'l'}selected{/if}>{:l("high_level")}</option>
                        <option value="p" {if $lsHighscoretyp == 'p'}selected{/if}>{:l("high_planeten")}</option>
                        <option value="pd" {if $lsHighscoretyp == 'pd'}selected{/if}>{:l("high_planetend")}</option>
                        <option value="s" {if $lsHighscoretyp == 's'}selected{/if}>{:l("high_flotten")}</option>
                        <option value="d" {if $lsHighscoretyp == 'd'}selected{/if}>{:l("high_vert")}</option>
                        <option value="f" {if $lsHighscoretyp == 'f'}selected{/if}>{:l("high_forsch")}</option>
                        <option value="a" {if $lsHighscoretyp == 'a'}selected{/if}>{:l("high_ally")}</option>
                        <option value="g" {if $lsHighscoretyp == 'g'}selected{/if}>{:l("high_herrsch")}</option>
                        <option value="y" {if $lsHighscoretyp == 'y'}selected{/if}>{:l("high_sonnensys")}</option>
                    </select>

                    <select class="highrscore_select" name="a" size=1>
                    {for $i=1; $i<=$liAnzahl; $i=$i+100}
                        <option value="{$i}" {if $i==$lidboffset}selected{/if}>{$i} - {if $i+99 >= $liAnzahl}{$liAnzahl}{else}{$i+99}{/if}</option>
                    {/for}
                    </select>

                    <input class="highrscore_submit" type=submit value="{:l("high_submit")}">

            </form>
        </div>
        <div class="tcenter">
            <img src="design/3/gg_hs_punkte.png" width="100">
            <img src="design/3/gg_hs_ally.png" width="100">
            <img src="design/3/gg_hs_infra.png" width="100">
            <img src="design/3/gg_hs_flotten.png" width="100">
            <img src="design/3/gg_hs_deff.png" width="100">
            <img src="design/3/gg_hs_level.png" width="100">
        </div>
        
         <!--Rang {$lidboffset}-{$lidboffset+99}-->
        <!--highscore-->
        <table class="highrscore_table">
        <tr>
             <th class="info_head_th">{:l("high_rang")}</th>
            
           {if $lsHighscoretyp == 'u' 
            or $lsHighscoretyp == 's'
            or $lsHighscoretyp == 'f'
            or $lsHighscoretyp == 'd'}
                <th class="info_head_th">{:l("high_username")}</th>
                <th class="info_head_th">{:l("galaxy_ally")}</th>
                <th class="info_head_th">{:l("high_points")}</th>
                <th class="info_head_th">{:l("high_funktion")}</th>
            {elseif $lsHighscoretyp == 'a'}
                <th class="info_head_th">{:l("high_allyname")}</th>
                <th class="info_head_th">{:l("high_allymember")}</th>
                <th class="info_head_th">{:l("high_points")}</th>
                <th class="info_head_th">{:l("high_allydp")}</th>
            {elseif $lsHighscoretyp == 'l'}
                <th class="info_head_th">{:l("high_username")}</th>
                <th class="info_head_th">{:l("high_level")}</th>
                <th class="info_head_th">{:l("high_infrast")}</th>
                <th class="info_head_th">{:l("high_kriegsf")}</th>
                <th class="info_head_th">{:l("high_forsch")}</th>
                <th class="info_head_th">{:l("high_funktion")}</th>
            {elseif $lsHighscoretyp == 'p'
            or $lsHighscoretyp == 'pd'}
                <th class="info_head_th">{:l("high_username")}</th>
                <th class="info_head_th">{:l("high_points")}</th>
                <th class="info_head_th">{:l("high_funktion")}</th>
            {elseif $lsHighscoretyp == 'g'}
                <th class="info_head_th">{:l("high_username")}</th>
                <th class="info_head_th">{:l("high_besitz")}</th>
                <th class="info_head_th">{:l("high_funktion")}</th>
            {elseif $lsHighscoretyp == 'y'}
                <th class="info_head_th">{:l("high_coords")}</th>
                <th class="info_head_th">{:l("high_besiedelte")}</th>
                <th class="info_head_th">{:l("high_points")}</th>
                <th class="info_head_th">{:l("high_punkte_planet")}</th>
            {/if}
        
        </tr>
        
        {foreach $laHighscore as $lskey => $loValue}
        <tr class="highrscore_table_tr_a">
            <td>{$lskey+$lidboffset}
             {if $lsHighscoretyp == "u"}{$diff = $loValue['recentRank']-($lskey+$lidboffset)}
             {if $diff > 0}
                <span class="green">+{$diff}</span>
             {elseif $diff < 0}
               <span class="red">{$diff}</span>
             {else}
                &bull;
             {/if}
             {/if}
            </td>
            {if $lsHighscoretyp == 'u' 
            or $lsHighscoretyp == 's'
            or $lsHighscoretyp == 'f'
            or $lsHighscoretyp == 'd'  
            }
                <td>
                <a href="playercard.php?u={$loValue['uid']}">{$loValue['name']|htmlentities}</a></td>
                <td>{if !empty($loValue['aname'])}[<a href="allianzen.php?ally={$loValue["aid"]}">{$loValue["aname"]}</a>]{else}-{/if}</td>
                <td>{$loValue['punkte']|nicenum}</td>
                <td><a class="hightscore_mail_icon" href="nachrichten.php?to={$loValue['uid']}"></a> 
                {if isAdmin()}
                    <div style="width:50px;display:inline-block;">
                        {if $loValue['lastlogin']<= (time()-60*60*24*7*2)}{:l("high_in2")}
                        {elseif $loValue['lastlogin']<= (time()-60*60*24*7)}{:l("high_in1")}
                        {else}{:l("high_akt")}{/if}
                    </div>
                    {/if}
                </td>
            {elseif $lsHighscoretyp == 'a'}
                <td><a href="allianzen.php?ally={$loValue['id']}">[{$loValue['tag']}]</a></td>
                <td>{$loValue['c']|nicenum}</td>
                <td>{$loValue['p']|nicenum}</td>
                <td>{$loValue['ds']|nicenum}</td>
            {elseif $lsHighscoretyp == 'l'}
                <td><a href="playercard.php?u={$loValue['uid']}">{$loValue['name']|htmlentities}</a></td>
                <td>{:getELevel($loValue['allp'])}</td>
                <td>{:getELevel($loValue['infra'])}</td>
                <td>{:getELevel($loValue['krieg'])}</td>
                <td>{:getELevel($loValue['forsch'])}</td>
                <td><a class="hightscore_mail_icon" href="nachrichten.php?to={$loValue['uid']}"></a></td>
            {elseif $lsHighscoretyp == 'p'
            or $lsHighscoretyp == 'pd'}
                <td><a href="playercard.php?x&amp;u={$loValue['id']}">{$loValue['name']|htmlentities}</a></td>
                <td>{$loValue['planeten']|nicenum}</td>
                <td><a class="hightscore_mail_icon" href="nachrichten.php?to={$loValue['id']}"></a>
                {if isAdmin()}
                    <div style="width:50px;display:inline-block;">
                        {if $loValue['lastlogin']<= (time()-60*60*24*7*2)}{:l("high_in2")}
                        {elseif $loValue['lastlogin']<= (time()-60*60*24*7)}{:l("high_in1")}
                        {else}{:l("high_akt")}{/if}
                    </div>
                    {/if}
                </td>
            {elseif $lsHighscoretyp == 'g'}
                <td><a href="playercard.php?x&amp;u={$loValue['id']}">{$loValue['name']|htmlentities}</a></td>
                <td>{:number_format($loValue['galaxie_besitz'],2,',','.')} %</td>
                <td><a class="hightscore_mail_icon" href="nachrichten.php?to={$loValue['id']}"></a>
                    {if isAdmin()}
                    <div style="width:50px;display:inline-block;">
                       {if $loValue['lastlogin']<= (time()-60*60*24*7*2)}{:l("high_in2")}
                        {elseif $loValue['lastlogin']<= (time()-60*60*24*7)}{:l("high_in1")}
                        {else}{:l("high_akt")}{/if}
                    </div>
                    {/if}
                </td>
            {elseif $lsHighscoretyp == 'y'}
                <td>{$loValue['c']}</td>
                <td>{$loValue['cp']|nicenum} von {$loValue['maxp']|nicenum}</td>
                <td>{$loValue['p']|nicenum}</td>
                <td>{$loValue['ds']|nicenum}</td>
            {/if}
        
        </tr>
        {/foreach}
        </table>
    </div>
</div>