<div id="resbar">
    
<table class="ressbar_table_img_bg" id="ressbartable" cellspacing="0" cellpadding="0">
<tr>
<td class="ressbar_table_img_bg_a"></td>
<td class="ressbar_table_img_bg_b">

    <table>
 <tr class="rowa">
		<td class="col1 cell">{:l('res1')}</td>
		<td class="col2 cell">{:l('res2')}</td>
		<td class="col3 cell">{:l('res3')}</td>
		<td class="col4 cell">{:l('res4')}</td>
		<td class="col5 cell">{:l('energy_all')}</td>
        <td class="col6 cell"><img src="{$gameURL}/design/2-0/gigron.png" width="20" onclick="startTutorial()"></td>
	</tr>
	<tr class="rowb">
		<td class="col1 cell" id="resbar1">{$laRes[0]|nicenum}</td>
		<td class="col2 cell" id="resbar2">{$laRes[1]|nicenum}</td>
		<td class="col3 cell" id="resbar3">{$laRes[2]|nicenum}</td>
		<td class="col4 cell" id="resbar4">{$laRes[3]|nicenum}</td>
        {$eColor = 'green'}
        {if $laRes[5] < 0}
        {$eColor = 'red'}
        {/if}
		<td class="col5 cell">{$laRes[4]|nicenum} / <span class="{$eColor}">{$laRes[5]|nicenum}</span></td>
        <td class="col6 cell">{:nicenum(getGigronen(Uid()))}</td>
	</tr>
	</table>
	
</td>
<td class="ressbar_table_img_bg_c">
<a href="msg.php" class="mail_icon"></a>
{if $iNewMSG > 0}
<p id="sub_msg" class="sub_msg_fl">{$iNewMSG}</p>
{/if}
{if $iNewMSG <= 0}
<p id="sub_msg" class="sub_msg">{$iNewMSG}</p>
{/if}

        <div id="light" class="white_content">
            <div id="mgs_content"></div>
        </div>
        <!-- <div id="fade" class="black_overlay"></div> Für CSS3 Mail Pop Up -->
</td>
</tr>
{*
<tr>
<td colspan="100%">
    <img src="{$gameURL}/design/2-0/gigron.png" width="20">{:nicenum(getGigronen(Uid()))}
</td>
</tr>
*}
</table>
<script type="text/javascript">
var iopenmsg = 0;
function openmsg()
{
    if (iopenmsg == 0)
    {
        document.getElementById('light').style.display='block';
        /*document.getElementById('fade').style.display='block';*/
        $.ajax({
            url: "nachrichten.php?resbar=1",
            success: function( data ) {
                $('#mgs_content').html(data);
            }
        });
        iopenmsg=1;
    }
    else
    {
        document.getElementById('light').style.display='none';
        iopenmsg=0;
    }
}

var gametitle = $('title').text();
function msgrefresh()
{
    $.ajax({
            url: "nachrichten.php?ajax=1",
            dataType: 'json',
            success: function( data ) {
                if( data.msg == 0 )
                {
                    $('#sub_msg').html(data.msg);
                    $('#sub_msg').removeClass('sub_msg_fl');
                    $('#sub_msg').addClass('sub_msg');
                    $('title').text(gametitle);
                }
                else
                {
                    $('#sub_msg').html(data.msg);
                    $('#sub_msg').removeClass('sub_msg');
                    $('#sub_msg').addClass('sub_msg_fl');
                    $('title').text('(' + data.msg + ') ' + gametitle);
                } 
                
                res1 = data.res1 -0;
                res2 = data.res2 -0;
                res3 = data.res3 -0;
                res4 = data.res4 -0;
                initrestime = data.time -0;
                window.setTimeout("msgrefresh();",7000);
            }
        });/*1sek=1000*/
}
msgrefresh();

</script>

<script type="text/javascript">
//Rohstoffe
initrestime = {:time()};
res1 = {$laRes[0]};
res2 = {$laRes[1]};
res3 = {$laRes[2]};
res4 = {$laRes[3]};

//produktion
prod1 = {$laRes["prod"][0]};
prod2 = {$laRes["prod"][1]};
prod3 = {$laRes["prod"][2]};
prod4 = {$laRes["prod"][3]};

//kapa
kapa1 = {$laRes["prod"][6]};
kapa2 = {$laRes["prod"][7]};
kapa3 = {$laRes["prod"][8]};
kapa4 = {$laRes["prod"][9]};

setInterval(reloadRes,1000);

</script>
</div>