{if !isset($ajax)}
<div id="V3_Content">
    <div class="class_content_wrapper">
{/if}
    <table width="100%" class="">
    <tr>
    {if !isset($ajax)}
        <td valign="top">
            <img src="{$gameURL}/{$img}">
        </td>
    {/if}
        <td valign="top">
            {* desc *}
            <table class="show_info_table">
                <tr>
                    <th colspan="2">{$name}</th>
                </tr>
                <tr>
                    <td colspan="2">{$text}

                    {if isset($sensorrange) and is_numeric($sensorrange)}
                    <br>{:l('info_sensor',$sensorrange)}
                    {/if}
                    </td>
                </tr>
                {if isset($prodlist)}
                <tr><td colspan="2">
                
                {* Auflistung *}
                {if isset($ajax)}
                <div style="overflow-y:scroll;overflow-x:hidden;height:300px;width:300px;">
                {/if}
                <table class="show_info_table">
                    {$i=1}
                    {foreach $prodlist as $lvl => $data}
                        {if $i == 1}
                            <tr>
                                <th>{:l('info_lvl')}</th>
                                <th>{:l($data['r'])}</th>
                                {foreach $conslist[$lvl] as $key => $dieseDummeVariableBraucheIchNicht_NurDenKackSchluessel}
                                <th>{:l($key)}</th>
                                {/foreach}
                            </tr>
                        {/if}
                        <tr{if $i==3} style="background:#666;"{/if}>
                            <td>{$lvl}</td>
                            <td><font color="lime">{$data['prod']|nicenum}</font></td>
                            {foreach $conslist[$lvl] as $Verbr}
                                <td><font color="red">{$Verbr|nicenum}</font></td>
                            {/foreach}
                        </tr>
                        {$i=$i+1}
                    {/foreach}
                    
                    </table>
                {if isset($ajax)}
                    </div>
                {else}
                    <a href="info.php?obj={$obj_typ}{$obj_id}&mineStart={$start+10}">-&gt;</a>
                {/if}
                    
                    </td>
                    </tr>
                {/if}
                {if isset($rescapa)}
                <tr>
                    <td colspan="2">
                        <table>
                            <tr>
                                <th>{:l('info_lvl')}</th>
                                <th>{:l('info_capa')}</th>
                            </tr>
                            {foreach $rescapa as $lvl => $cp}
                            <tr>
                                <td>{$lvl}</td>
                                <td>{$cp|nicenum}</td>
                            </tr>
                            {/foreach}
                        </table>
                    </td>
                </tr>
                {/if}
                {if isset($ang)}
                <tr>
                    <td>{:l('info_ang')}</td>
                    <td>{:nicenum($ang+$ang_plus)}<font color="gray">({$ang|nicenum} + {$ang_plus|nicenum})</font></td>
                </td>
                {/if}
                
                {if isset($deff)}
                <tr>
                    <td>{:l('info_deff')}</td>
                    <td>{:nicenum($deff+$deff_plus)}<font color="gray">({$deff|nicenum} + {$deff_plus|nicenum})</font></td>
                </td>
                {/if}
                {if isset($struc)}
                <tr>
                    <td>{:l('info_struc')}</td>
                    <td>{:nicenum($struc+$struc_plus)}<font color="gray">({$struc|nicenum} + {$struc_plus|nicenum})</font></td>
                </td>
                {/if}
                
                {if isset($speed)}
                <tr>
                    <td>{:l('info_speed')}</td>
                    <td>{$speed|nicenum}</td>
                </td>
                {/if}          
                {if isset($capa)}
                <tr>
                    <td>{:l('info_capa')}</td>
                    <td>{$capa|nicenum}</td>
                </td>
                {/if}      
                {if isset($consum)}
                <tr>
                    <td>{:l('info_consum')}</td>
                    <td>{$consum|nicenum}</td>
                </td>
                {/if}      
                {if isset($engine)}
                <tr>
                    <td>{:l('info_engine')}</td>
                    <td>{$engine}</td>
                </td>
                {/if}      
                {if isset($energie)}
                <tr>
                    <td>{:l('energy')}</td>
                    <td>{$energie|nicenum}</td>
                </td>
                {/if}      
                
                {if isset($rf_against)}
                <tr>
                    <td colspan="2">
                    {foreach $rf_against as $against => $amount}
                        <font color="green">{:l('info_rapidfire_against',$amount,l('item_s'.$against))}</font> <br>
                    {/foreach}
                    </td>
                </tr>
                {/if}
                {if isset($rfv_against)}
                <tr>
                    <td colspan="2">
                    {foreach $rfv_against as $against => $amount}
                        <font color="green">{:l('info_rapidfire_against',$amount,l('item_v'.$against))}</font> <br>
                    {/foreach}
                    </td>
                </tr>
                {/if}
                {if isset($rf_from)}
                <tr>
                    <td colspan="2">
                    {foreach $rf_from as $from => $amount}
                        <font color="red">{:l('info_rapidfire_from',$amount,l('item_s'.$from))}</font> <br>
                    {/foreach}
                    </td>
                </tr>
                {/if}
                {if isset($rfv_from)}
                <tr>
                    <td colspan="2">
                    {foreach $rfv_from as $from => $amount}
                        <font color="red">{:l('info_rapidfire_from',$amount,l('item_v'.$from))}</font> <br>
                    {/foreach}
                    </td>
                </tr>
                {/if}
            </table>
        </td>
    </tr>    
    
    </table>
{if !isset($ajax)}
</div>
</div>
{/if}