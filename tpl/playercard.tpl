<!-- Main Wrapper für den V3 Inhalt -->
<div id="V3_Content">

    <div class="class_content_wrapper">
        <!-- Bauen und Forschen -->
		<div class="info_first_head">{$name}</div>
        <table id="einstellungen">
            <tr>
                <th width="30%">{:l('v3_accountname')}</th>
                <td>{$name} <a class="hightscore_mail_icon" href="nachrichten.php?to={$uid}">&nbsp;</a></td>
            </tr>
            <tr>
                <th width="30%">{:l('planet_count')}</th>
                <td>{$planetCount}</td>
            </tr>
            <tr>
                <th width="30%">{:l('ally_verwalten_rang')}</th>
                <td>{$rang}</td>
            </tr>
            <tr>
                <th width="30%">{:l('v3_score')}</th>
                {$us = userCount()}
                <td>{$pgesamt|nicenum} ({:l('v3_rank')} {$rank|nicenum} {:l('v3_of')} {$us|nicenum})</td>
            </tr>
            <tr>
                <th width="30%">{:l('planset_makeHP')}</th>
                <td>{$mainplanet|coordFormat}</td>
            </tr>
            <tr>
                <th width="30%">{:l('nav_ally')}</th>
                <td>{$tag}</td>
            </tr>
        </table>
    </div>

</div>