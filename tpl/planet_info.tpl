{$coAr = explode(":",$coords)}
<div class="planbox">
                <table>
                <tr>
                <td><a href='javascript:fleetNextWithParm("{$coAr[0]}","{$coAr[1]}","{$coAr[2]}","{$coAr[3]}")'><img src="{$gameURL}/design/2-0/Gigra-Arrow_Galaxie.png"></a></td>
                <td><img src="{$gameURL}/design/Planeten/{$pbild}" width="40"></td>
                <td>
                    <b>{$pname}[{$coords|coordFormat}]</b><br>
                    <div>
                    <a href="#">
                        {$name}
                        <div class="tooltip">
                        <a href="#"></a> <!-- Sowas wie ein Reset für den Tooltip Inhalt ;)  -->
                        <span class="tooltip_h1_span">{$name}</span><br />
                                                {:l('galaxy_rank')}: {$rank}<br />
                                                {:l('galaxy_ally')}: [<a href="allianz.php?id={$allyid}">{$tag}</a>]
                     
                        </div>
                    </a>
                    </div>
                </td>
                </tr>
                </table>
</div>