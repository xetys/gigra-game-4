
<div id="V3_Content">
    <div class="class_content_wrapper" style="border:0;">
    		<form action="nachrichten.php" method="post">
        	{if $sendefehler == true} {:l('msg_sendefehler')}{/if}
			{if $lbMsgsend == true}
			<table width="100%">
				<tr><td class="c">{:l('msg_info')}</td></tr>
				<tr><th>{:l('msg_send')}</th></tr>
			</table>
			{/if}





				<table style="width:100%;">
			<table width="100%">
				<tr><th>
					<select size=1 name="q">
					<option value="1">{:l('msg_drop1_selected')}</option>
					<option value="2">{:l('msg_drop1_unselected')}</option>
					<option value="3">{:l('msg_drop1_all')}</option>
					<option value="4">{:l('msg_drop1_system')}</option>
					<option value="5">{:l('msg_drop1_user')}</option>
					</select>
					
					<select name="a">
					<option value="r">{:l('msg_drop2_read')}</option>
					<option value="d">{:l('mgs_drop2_remove')}</option>
					</select>
					
					<input type="submit" value="OK"/>
					</th>
					<th>
					<div id="mgsview" style="display:block; width:150px;">{:l('msg_allmsg')}</div>
				</th></tr>
                
			</table>
            <!--
<table style="width:100%;">
            <tr>
                <th style="width:40px;"><input type="checkbox" /></th>
                <th>Absender</th>
                <th>Betreff</th>
                <th>Datum</th>
                <th style="width:40px;">Gelesen</th>
            </tr>
</table> -->
				
			{if $lbNomsgs}
			{:l('mgs_nomgs')}<br/>
			{else}
			{foreach $laMsgs as $liKey => $msgitem}


			   
				<div class="msgs">
					<tr>
						<table style="width:100%;">
							<tr>
							
								<td>					
										<div id="msg_{echo($msgitem['id'])}" class="mgs{echo($msgitem['mode'])}">
												<table style="width:100%;">
													<!-- Nachrichtenkopf -->
													<tr style="text-align:left;" onclick="msg_usertxt('{$msgitem['id']}');">
														

															
																<th style="width:40px;">
																	<input type="checkbox" value="mark" name="{echo($msgitem['id'])}"/>
																</th>
                                                                
                                                                
    															{if $msgitem['fromuid'] == '0'} 
                                                                <th colspan="2">
                                                                    {$msgitem['time']} <b>{:l('mgs_systemname')}</b> {$msgitem['coords']|coordFormat}
                                                                </th>
                                                                {else} 
                                                                <th>
                                                                    <a href="nachrichten.php?to={echo($msgitem['fromuid'])}">{echo($msgitem['fromname'])}</a> 	
                                                                </th>
                                                                {/if}
                                                                
														
                                                                 
																{if $msgitem['mode'] == 'text'} 
                                                               <th>
                                                                {echo($msgitem['coords'])} |
																{:l('mgs_subj')}: {echo($msgitem['subj'])} | {echo($msgitem['time'])}
                                                                </th>
																{/if}
																
																

                                                                
                                                                
                                                             {*  <th style="width:40px;" id="msg_red{$msgitem['id']}">
                                                                {if $msgitem['red'] == 'no'} 
                                                                    Nein
                                                                {else}
                                                                    Ja
                                                                {/if}
                                                               </th> *}


														
													</tr>
													<!-- Nachrichten Inhalt -->
													<tr>
														<td colspan="4">
															<!-- Nach Klick, wird die Nachricht angezeigt -->
															<div class="msg_content">
																	{if $msgitem['mode'] == 'text'}
																	<div id="msg_usertext{$msgitem['id']}" style="display:none">
																	<div class="p_mgs_content_text">{echo($msgitem['text'])}
																	<div class="msg_replay class_btn"><a href="nachrichten.php?ans={echo($msgitem['id'])}">{:l('mgs_ans')}</a></div>
                                                                    </div>
																	</div>  
																{else}
																	 <div class="p_mgs_content">{echo($msgitem['text'])}</div>
																{/if}
															</div>  
														</td>
													</tr>
												</table>
												
     
										</div>
								</td>

							</tr>
						</table>

					</tr>

					
				</div>
			{/foreach}
			</table>	  
			{/if}

			<script>
				<!--
				var mgsview = 0;
				$('#mgsview').click( function () {
					if (mgsview == 0)
					{
						$('.mgscmd').css('display','none');
						$('.mgstext').css('display','block');
						$('#mgsview').html('{:l('msg_usermsg')}');
						mgsview = 1;
					}
					else if (mgsview == 1)
					{
						$('.mgscmd').css('display','block');
						$('.mgstext').css('display','none');
						$('#mgsview').html('{:l('msg_sysmsg')}');
						mgsview = 2;
					}
					else
					{
						$('.mgscmd').css('display','block');
						$('.mgstext').css('display','block');
						$('#mgsview').html('{:l('msg_allmsg')}');
						mgsview = 0;
					}
				});
				//-->
			</script>

			</form>
	</div>
</div>