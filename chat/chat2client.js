 chatActiveChannel = 'main';
    channelUnread = new Array();
    chatActive = false;
    allUnread = 0;
    
    function setUnread(asChannel)
    {
        channelUnread[asChannel]++;
        allUnread++
        if(channelUnread[asChannel] > 0)
            $('#chattab_' + asChannel).html(asChannel + '(' + channelUnread[asChannel] + ')');
            

        $('.tab2 > a').html(l('v3_chat') + '(' + allUnread + ')');
    }
    function addChannel(name)
    {
        if($('#chattab_'+name).length > 0) return;
        $('#tabList').html($('#tabList').html() + '<li id="chattab_'+name+'" class="chatTab" title="'+name+'">'+name+'</li>');
        $('#chatContainer').html($('#chatContainer').html() +'<div class="chatContent" id="chatcontent_'+name+'" style="overflow:auto;">loading ' + name + '</div>');
        channelUnread[name] = 0;
        
        setTimeout(function () {
            switchTab(name);
            addEvents();
        }, 99);
    }
    function switchTab(name)
    {
          $('.chatContent').addClass('hidden');
          $('.chatContent').hide();
          $('.chatTab').removeClass('active');
          
          $('#chattab_'+name).addClass('active');
          $('#chatcontent_'+name).show();
          chatActiveChannel = name;
          channelUnread[name] = 0;
          $('#chattab_'+name).html(name);
          $('#chatcontent_' + name).attr({ scrollTop: $('#chatcontent_' + name).attr("scrollHeight") });
          channelBottom(name);
    }
    
    function addEvents()
    {
        $('.chatTab').unbind('click');
        $('.chatTab').click(function () { 
          switchTab($(this).attr('title'));  
        });
    }
    

    
    //function connectToServer() {
        
        //connectivity
        socket_2 = io.connect('http://stytex.de:8080');
        
        socket_2.on('test',function(data) {
           console.log('test'); 
        });
        
        socket_2.on('authplease', function () {
            var userID = $('#chatUserID').val();
            socket_2.emit("auth", userID);
            
        });
        
        socket_2.on('push', function (data) {
            addChannel(data.channel);
            if((data.channel != chatActiveChannel && data.unread) || (!chatActive && data.unread))
                setUnread(data.channel);
            
            var lsText = "";
            //fixme !!!!!!!!!!!!!!!!!
            for(i = 0; i<data.chat.length;i++)
                lsText += data.chat[i] + "<br>";
            $('#chatcontent_' + data.channel).html(lsText);
            $('#chatcontent_' + data.channel).attr({ scrollTop: $('#chatcontent_' + data.channel).attr("scrollHeight") });
            channelBottom(data.channel);
        });
        
        socket_2.on('leave', function (chanName) {
            switchTab('main') ;
            $('#chattab_'+chanName).remove();
            $('#chatcontent_'+chanName).remove();
        });
    //}
    
    function sendMSG()
    {
        if($('#chatTextLine').val().length > 0)
        {
            socket_2.emit("msg",{channel: chatActiveChannel, text : $('#chatTextLine').val()});
            $('#chatTextLine').val("");
        }
    }
    
    function channelBottom(id)
    {
        $("#chatcontent_" + id).scrollTop($("#chatcontent_" + id).height() * 3);   
    }

