<script>
var actual_slot = Number(1);
function switch_slot(to) {
    var new_slot = actual_slot + to;
    {foreach $_SHIP AS $id => $v}
			{if $id!=15}
				//document.getElementById('A_'+ actual_slot + '_{$id}').style.display = 'none';
                $('#A_'+ actual_slot + '_{$id}').hide();
                $('#A_'+ new_slot + '_{$id}').show();
				//document.getElementById('A_'+ new_slot + '_{$id}').style.display = 'block';
			{/if}
			//document.getElementById('V_'+ actual_slot + '_{$id}').style.display = 'none';
			//document.getElementById('V_'+ new_slot + '_{$id}').style.display = 'block';
            $('#V_'+ actual_slot + '_{$id}').hide();
            $('#V_'+ new_slot + '_{$id}').show();

    {/foreach}
	{foreach $_VERT AS $id => $v}

			if(new_slot == 1) 
				document.getElementById('V_1_V{$id}').style.display = 'block';
			else
			    document.getElementById('V_1_V{$id}').style.display = 'none';

	{/foreach}
	document.getElementById("A_" + actual_slot + "_F5").style.display = "none";
	document.getElementById("A_" + actual_slot + "_F6").style.display = "none";
	document.getElementById("A_" + actual_slot + "_BON").style.display = "none";
	document.getElementById("A_" + actual_slot + "_F9").style.display = "none";
	document.getElementById("A_" + actual_slot + "_KL").style.display = "none";
	document.getElementById("V_" + actual_slot + "_F5").style.display = "none";
	document.getElementById("V_" + actual_slot + "_F6").style.display = "none";
	document.getElementById("V_" + actual_slot + "_BON").style.display = "none";
	document.getElementById("V_" + actual_slot + "_F9").style.display = "none";
	document.getElementById("V_" + actual_slot + "_KL").style.display = "none";
	
	document.getElementById("A_" + new_slot + "_F5").style.display = "block";
	document.getElementById("A_" + new_slot + "_F6").style.display = "block";
	document.getElementById("A_" + new_slot + "_BON").style.display = "block";
	document.getElementById("A_" + new_slot + "_F9").style.display = "block";
	document.getElementById("A_" + new_slot + "_KL").style.display = "block";
	document.getElementById("V_" + new_slot + "_F5").style.display = "block";
	document.getElementById("V_" + new_slot + "_F6").style.display = "block";
	document.getElementById("V_" + new_slot + "_BON").style.display = "block";
	document.getElementById("V_" + new_slot + "_F9").style.display = "block";
	document.getElementById("V_" + new_slot + "_KL").style.display = "block";
	actual_slot = actual_slot + to;
	
	document.getElementById("disp").value = actual_slot;
}
function switch_desk(dnum) {
	if(dnum==2)
	{
		document.getElementById("desk_1").style.display = "none";
		document.getElementById("desk_2").style.display = "block";
	}
	if(dnum==1)
	{
		document.getElementById("desk_2").style.display = "none";
		document.getElementById("desk_1").style.display = "block";
	}
}
function submit_and_do() {
	var end = false;
	var end_fleet = <? echo FLEETS; ?>;
	while(actual_slot<end_fleet && !end)
	{
		if(document.getElementById("A_" + actual_slot + "_202").value=="" && document.getElementById("V_" + actual_slot + "_202").value=="")
		{
			end = true;
			switch_slot(-1);
		}
		else
		{
			switch_slot(1);
		}
	}
}
</script>
</head>
<body>
<center>

<div id="desk_1" style="display: block;">
<form class="kampfsim" action="" method="POST" onsubmit="submit_and_do()" name="f1">
<input type="hidden" name="send" value="1">
<table style="background-color: #0D1014; border: 1px solid black; padding:20px;">
<tr>
	<th>
	<fieldset>
	<legend>{:l('ksim_ships')}</legend>
	<table>
	<input type="button" value="&lt;" onclick="switch_slot(-1)">
	<input type="text" value="1" id="disp">
	<input type="button" value="&gt;" onclick="switch_slot(1)">
	<tr><td class="c">{:l('ksim_ships')}</td><td class="c">{:l('kb_atter')}</td><td class="c">{:l('kb_deffer')}</td></tr>
	
	{foreach $_SHIP AS $id => $v}
        
		<tr><th>{$v[0]}</th>
		{if $id!=15}
			<th><input type='text' id='A_1_{$id}' name='A[1][{$id}]' value='{$_POST['A'][1][$id]}' style='display: block;'>
			{for $i=2;$i<=FLEETS;$i++}
			
				<input type='text' id='A_{$i}_{$id}' name='A[{$i}][{$id}]' value='{$_POST['A'][$i][$id]}' style='display: none;'>
			{/for}
		{else}
			<th>
        {/if}
		</th><th><input type='text' id='V_1_{$id}' name='V[1][{$id}]' value='{$_POST['V'][1][$id]}' style='display: block;'>
		{for $i=2;$i<=FLEETS;$i++}
			<input type='text' id='V_{$i}_{$id}' name='V[{$i}][{$id}]' value='{$_POST['V'][$i][$id]}' style='display: none;'>
		{/for}
		</th></tr>
	{/foreach}
	{foreach $_VERT AS $id => $v}

		<tr><th>{$v[0]}</th>

		<th></th><th><input type='text' id='V_1_V{$id}' name='V[1][V{$id}]' value='{$_POST['V'][1]['V'.$id]}' style='display: block;'>
		</th></tr>
		
	{/foreach}
	</table>
	</fieldset>
	</th>
	<th>
	<fieldset>
	<legend>Spielerwerte</legend>
	<table>
	<tr><td class="c">{:l('ksim_property')}</td><td class="c">{:l('kb_atter')}</td><td class="c">{:l('kb_deffer')}</td></tr>

		<tr><th>{:l('item_f5')}</th>
		
		<th><input type='text' id='A_1_F5' name='A_1_F5' value='{$_POST['A_1_F5']}' style='display: block;'>
		{for $i=2;$i<=FLEETS;$i++}
			<input type='test' id='A_{$i}_F5' name='A_{$i}_F5' value='{$_POST['A_'.$i.'_F5']}' style='display: none;'>
		{/for}
		</th>
		
		<th><input type='text' id='V_1_F5' name='V_1_F5' value='{$_POST['V_1_F5']}' style='display: block;'>
		{for $i=2;$i<=FLEETS;$i++}
			<input type='text' id='V_{$i}_F5' name='V_{$i}_F5' value='{$_POST['V_'.$i.'_F5']}' style='display: none;'>
		{/for}
		</th></tr>
		<tr><th>{:l('item_f6')}</th>
		
		<th><input type='text' id='A_1_F6' name='A_1_F6' value='{$_POST['A_1_F6']}' style='display: block;'>
		{for $i=2;$i<=FLEETS;$i++}
			<input type='test' id='A_{$i}_F6' name='A_{$i}_F6' value='{$_POST['A_'.$i.'_F6']}' style='display: none;'>
		{/for}
		</th>
		
		<th><input type='text' id='V_1_F6' name='V_1_F6' value='{$_POST['V_1_F6']}' style='display: block;'>
		{for $i=2;$i<=FLEETS;$i++}
			<input type='text' id='V_{$i}_F6' name='V_{$i}_F6' value='{$_POST['V_'.$i.'_F6']}' style='display: none;'>
		{/for}
		</th></tr>
		<tr><th>{:l('item_f9')}</th>
		
		<th><input type='text' id='A_1_F9' name='A_1_F9' value='{$_POST['A_1_F9']}' style='display: block;'>
		{for $i=2;$i<=FLEETS;$i++}
			<input type='test' id='A_{$i}_F9' name='A_{$i}_F9' value='{$_POST['A_'.$i.'_F9']}' style='display: none;'>
		{/for}
		</th>
		
		<th><input type='text' id='V_1_F9' name='V_1_F9' value='{$_POST['V_1_F9']}' style='display: block;'>
		{for $i=2;$i<=FLEETS;$i++}
			<input type='text' id='V_{$i}_F9' name='V_{$i}_F9' value='{$_POST['V_'.$i.'_F9']}' style='display: none;'>
		{/for}
		</th></tr>


		<tr><th>{:l('exp_krieg_treffer')}</th>
		
		<th><input type='text' id='A_1_KL' name='A_1_KL' value='{$_POST['A_1_KL']}' style='display: block;'>
		{for $i=2;$i<=FLEETS;$i++}
			<input type='test' id='A_{$i}_KL' name='A_{$i}_KL' value='{$_POST['A_'.$i.'_KL']}' style='display: none;'>
		{/for}
		</th>
		
		<th><input type='text' id='V_1_KL' name='V_1_KL' value='{$_POST['V_1_KL']}' style='display: block;'>
		{for $i=2;$i<=FLEETS;$i++}
			<input type='text' id='V_{$i}_KL' name='V_{$i}_KL' value='{$_POST['V_'.$i.'_KL']}' style='display: none;'>
		{/for}
		</th></tr>
        
        <tr><th>{:l('v3_combatbonus')}</th>
		<th>
        <select id='A_1_BON' name='A_1_BON' style='display: block;'>
            <option value="0"{if empty($_POST['A_1_BON']) or $_POST['A_1_BON'] == 0} selected{/if}>0%</option>
            <option value="5"{if $_POST['A_1_BON'] == 5} selected{/if}>5%</option>
            <option value="10"{if $_POST['A_1_BON'] == 10} selected{/if}>10%</option>
        </select>
		{for $i=2;$i<=FLEETS;$i++}
            <select id='A_{$i}_BON' name='A_{$i}_BON' style='display: none;'>
            <option value="0"{if empty($_POST['A_'.$i.'_BON']) or $_POST['A_'.$i.'_BON'] == 0} selected{/if}>0%</option>
            <option value="5"{if $_POST['A_'.$i.'_BON'] == 5} selected{/if}>5%</option>
            <option value="10"{if $_POST['A_'.$i.'_BON'] == 10} selected{/if}>10%</option>
        </select>
		{/for}
		</th>
		<th>
		<select id='V_1_BON' name='V_1_BON' style='display: block;'>
            <option value="0"{if empty($_POST['V_1_BON']) or $_POST['V_1_BON'] == 0} selected{/if}>0%</option>
            <option value="5"{if $_POST['V_1_BON'] == 5} selected{/if}>5%</option>
            <option value="10"{if $_POST['V_1_BON'] == 10} selected{/if}>10%</option>
        </select>
		{for $i=2;$i<=FLEETS;$i++}
			<select id='V_{$i}_BON' name='V_{$i}_BON' style='display: none;'>
            <option value="0"{if empty($_POST['V_'.$i.'_BON']) or $_POST['V_'.$i.'_BON'] == 0} selected{/if}>0%</option>
            <option value="5"{if $_POST['V_'.$i.'_BON'] == 5} selected{/if}>5%</option>
            <option value="10"{if $_POST['V_'.$i.'_BON'] == 10} selected{/if}>10%</option>
        </select>
		{/for}
		</th></tr>
	
		<tr><th>{:l('res1')}</th><th></th><th><input type='text' name='eisen' value='{$_POST['eisen']}'></th><th></th></tr>
		<tr><th>{:l('res2')}</th><th></th><th><input type='text' name='titan' value='{$_POST['titan']}'></th><th></th></tr>
		<tr><th>{:l('res3')}</th><th></th><th><input type='text' name='wasser' value='{$_POST['wasser']}'></th><th></th></tr>
		<tr><th>{:l('res4')}</th><th></th><th><input type='text' name='wasserstoff' value='{$_POST['wasserstoff']}'></th><th></th></tr>

	</table>
	</fieldset>
	<fieldset>
	<legend>{:l('ksim_result')}</legend>
	<table width="100%">
	<tr><th>{:l('ksim_winner')}</th><th><? echo $gewinner; ?></th></tr>
	<tr><th>{:l('ksim_alost')}</th><th><? echo $lost_a; ?></th></tr>
	<tr><th>{:l('ksim_vlost')}</th><th><? echo $lost_v; ?></th></tr>
	<tr><th>{:l('galaxy_tf')}</th><th><? echo $tf; ?></th></tr>
	<tr><th>{:l('ksim_winnings')}</th><th><? echo $beute_a; ?></th></tr>
	<tr><th>{:l('ksim_mondchance')}</th><th><? echo $mondchance; ?>%</th></tr>
	</table>
	</fieldset>
	<fieldset>
	<legend>{:l('ksim_actions')}</legend>
	<table width="100%">
	<tr><th><input type="Submit" value="{:l('ksim_calculate_battle')}"></th></tr>
	</table>
	</fieldset>
	</th>
</tr>
</table>
</form>
</div>


<div class="hidden">
{$out}
</div>


{*

<table>
    <tr>
		<td valign="top">
			<table border="1" width="500">
			  <tr>
				<th colspan="3">Schiffe</th>
			  </tr>
			  <tr>
				<td>Schiffe</td>
				<td>Angreifer</td>
				<td>Verteidiger</td>
			  </tr>
			  
				<tr>
					<td>Schiff</td>
					<td>Spieler 1</td>
					<td>Spieler 2</td>
				</tr>
			</table>
		
		</td>
		
		<td valign="top">
			<table border="1" width="500">
			  <tr>
				<th colspan="3">Spielerwerte</th>
			  </tr>
			  <tr>
				<td>Eigenschaft</td>
				<td>Angreifer</td>
				<td>Verteidiger</td>
			  </tr>
			</table>
		</td>
	</tr>
</table>
		

*}