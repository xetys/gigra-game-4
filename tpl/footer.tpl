</div> <!-- Ende Wrapper -->
{*if isAdmin()}
<div style="width:50px;border:1px red solid; overflow:hidden;" onclick="this.toggle();">
{listProfiles()}
</div>
{/if*}
<br><br><br>
<div id="footer">
Gigra Game &copy; 2006 - 2012 | {@GG_VERSION} | <a href="http://forum.gigra-game.de">{:l('nav_forum')}</a> | <a href="verwarnung.php">Pranger</a>
</div>
<script type='text/javascript' src='js/bauinfo_js.js'></script>

<script src="http://{$host}:8080/socket.io/socket.io.js" type="text/javascript"></script>
<script src="chat/chat2client.js?chace=3" type="text/javascript"></script>

<script type="text/javascript" src="{$gameURL}/js/tutorial.js"></script>
{:showTutorialList()}

<div id='modal-box' class="hidden"></div>
</body>
</html>