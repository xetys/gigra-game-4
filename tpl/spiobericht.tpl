<center>
    <table cid="table_stand_alone_kb">
		<tr><td class="c" colspan="100%">{:l('spio_header',coordFormat($coords))}</td></tr>
		<tr><th>Name</th><th>Stufe/Anzahl</th></tr>
		{foreach $spyData as $sName => $iVal}
			{if isset($iVal["heading"])}
				<tr><td class'c' colspan=2>{$iVal[heading]}</td></tr>
			{else}
			    <tr><th>{$iVal[0]}</th><th>{$iVal[1]}</th></tr>
                
		    {/if}	
        {/foreach}
		<tr><td class="c" colspan="100%">Chance auf Spionageabwehr: {$chance} &percnt;</td></tr>
	</table>
    {$lsSimStr = "send=1"}
    {foreach $spyData as $sName => $iVal}
        {if isset($iVal[2])}
            {$type = substr($iVal[2],0,1)}
            {if $type == "s"}
                {$id = substr($iVal[2],1)}
            {elseif $type == "f"}
                {$id = "F".substr($iVal[2],2)}
            {else}
                {$id = strtoupper($iVal[2])}
            {/if}
            
            {if $type == "s" or $type == "v"}
                {$lsSimStr .= "&V[1][".$id."]=".$iVal[1]}
            {else}
                {$lsSimStr .= "&V_1_".$id."=".$iVal[1]}
            {/if}
        {/if}
    {/foreach}
    <br>
    <a href="kampfsim.php?{$lsSimStr}" target="_blank"><span class="class_btn einst_btn_save">{:l('nav_sim')}</span></a>
    <br>
    <br>
    </form>
	</center>