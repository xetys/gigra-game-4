<div id="gigraChat">
    <div id="chatContainer">
    </div>
    <div id="chatTabs">
        <ul id="tabList">
        
        </ul>
        <div class="clear"></div>
    </div>
    <div id="chatControl">
        <form action="" onsubmit="sendMSG();return false;" autocomplete="off">
        <input type="hidden" id="chatUserID" value="{$lsUserName}">
        <input type="text" id="chatTextLine">
        <input type="submit" id="chatTextSubmit" value="senden">
        </form>
    </div>
</div>