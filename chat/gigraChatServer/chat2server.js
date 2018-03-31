var sys = require("sys");
var fs = require("fs");
var path = require("path");


var io = require('socket.io').listen(8080);
io.set('log level', 1); 

Array.prototype.contains = function(searchValue)
{
for (var i = 0, len = this.length;i < len && this[i] !== searchValue;i++) ;
    return i < len;
};



db = {

  data : {
            channels : new Array(),
            users : new Array(),
  },
  save : function () {
      return null;
      fs.writeFile("chat2.db",JSON.stringify(db.data),function (err) {
      if (err) throw err;
	  //console.log(db.data);
	});
  },
  
  load : function () {
      fs.readFile('chat2.db','utf8',function (err, data) {
          if (err) throw err;
          if(data.length > 0)
          {
              var js = JSON.parse(data);
              db.data = js;
              
          }
        });
  }
};

//Testen


//db.data.users.push({ name : "admin"});
//db.save();  


//db.load();


function User(name)
{
    if(name == undefined) return;
    

    this.name = name;
    
    this.showName = name;
    
    this.admin = false;
    
    this.adminSince;
    
 
    
    this.unread = 0;
    
    this.channels = [];

    this.socket;
    /**
    * Checks if user is in db
    *
    * @returns bool
    */
    this.isRegistered = function () {
        return !(db.data.users[this.name] == undefined);
    };
    
    this.save = function () {
      var saveDB =   new SaveDB("user_" + name);
      
      var Obj = {
            name : this.name,
            showName : this.showName,
            admin : this.admin,
            adminSince : this.adminSince,
            unread : this.unread,
            channels : this.channels
      };
      
      saveDB.save(Obj);
    };
    
    this.register = function () {
        var saveDB = new SaveDB("user_" + name);
    
        if(saveDB.load())
        {
            this.name = saveDB.loaded.name;
            
            this.showName = saveDB.loaded.showName;
            
            this.admin = saveDB.loaded.admin;
            
            this.adminSince = saveDB.loaded.adminSince;
            
            this.unread = saveDB.loaded.unread;
            
            this.channels = saveDB.loaded.channels;
        }
        else
        {
            this.channels = ['main'];
        }
           
        db.data.users[this.name] = this;
        
        //speichern
        db.save();
        console.log('user ' + this.name + ' registered');
    };
    this.login = function () {
        return db.data.users[this.name];
    };
    
    this.getChannels = function () {
       
      return this.channels;
    };
    
    this.setChannels = function (socket) {
         var laChannels = this.getChannels();
        
        if(laChannels == undefined) return;
        for(i = 0; i < laChannels.length; i++)
        {
            socket.join(laChannels[i]);
        } 
    };
    
    this.newChannel = function (chanName) {
          this.channels.push(chanName);
          this.setChannels(this.socket);
          this.getMsgs();
          this.save();
    };
    
    this.leaveChannel = function (chanName) {
         this.socket.leave(chanName);
         for(i = 0; i < this.channels.length;i++)
            if(this.channels[i] == chanName)
                delete this.channels[i];
        
        this.socket.emit('leave',chanName);
        this.getMsgs();
          this.save();
    };
    
    this.setSocket = function (socket) {
        this.socket = socket;
        this.socket.join('user_'+this.name);
        this.save();
    };
    
    this.kick = function (chanName) {
        this.leaveChannel(chanName);
    };
    
    this.formatText = function (asText,abSystem) {
        var ldDate = new Date();
        
        var lsDateString = (ldDate.getHours() < 10 ? '0' + ldDate.getHours() : ldDate.getHours())  + ':' + (ldDate.getMinutes() < 10 ? '0' + ldDate.getMinutes() : ldDate.getMinutes())  + ':' + (ldDate.getSeconds() < 10 ? '0' + ldDate.getSeconds() : ldDate.getSeconds());
        
        var lsNameStr = "<i>" + (this.isAdmin() ? "<font color='yellow' style='font-weight:bold'>" : "") + this.showName + (this.admin ? "</font>" : "") + "</i>";
        
        
        if(!(abSystem == undefined))
        {
            asText = asText.replace('%user',this.showName);
            
            return "["+lsDateString+"]<font color=gray>System: " + asText + "</font>";
        }
        return "["+lsDateString+"]" + lsNameStr + ":" + asText;
    };
    
    
    this.pushMsgs = function (fromChannel) {
        var laChannels = this.getChannels();
        var loChannel;
        
        for(i = 0; i < laChannels.length; i++)
        {
            if(laChannels[i] == undefined) continue;
            loChannel = new Channel(laChannels[i]);
            loChannel = loChannel.init();
            //console.log(db.data.channels,'in user');
            
            var lbUnread = false;
            if(fromChannel != undefined && fromChannel == laChannels[i])
                lbUnread = true;
            
            io.sockets.in(laChannels[i]).emit('push',{ channel : laChannels[i], chat : loChannel.getChat(), unread : lbUnread});       
        }
        
    };
    
    this.getMsgs = function () {
        var laChannels = this.getChannels();
        var loChannel;
        
        for(i = 0; i < laChannels.length; i++)
        {
            if(laChannels[i] == undefined) continue;
            loChannel = new Channel(laChannels[i]);
            loChannel = loChannel.init();
            
         
            this.socket.emit("push",{ channel : laChannels[i], chat : loChannel.getChat() });
        }
    };
    
    this.setAdmin = function () {
        this.admin = true;  
        this.adminSince = new Date().getTime();
        
        this.save();
    };
    
    
    this.isAdmin = function () {
        if(this.admin && (((this.adminSince / 1000) + (3600 * 6)) < (new Date().getTime()/1000)))  
            this.admin = false;
        
        
        
        return this.admin;
    };
}

function SaveDB(id)
{
    this.filename = "";
    
    this.loaded = {};
    
    
    this.save = function (obj) {
        fs.writeFile(this.filename,JSON.stringify(obj),function (err) {
            if (err) 
            console.log(err);
        });
    };
    
    this.load = function () {
        ObjectPointer = this;
        
        if(path.existsSync(this.filename))
        {
            var data = fs.readFileSync(this.filename,'utf8');
                
            if(data.length > 0)
            {
                var js = JSON.parse(data);
                this.loaded = js;
            }
            
            return true;
        }
        else
            return false;
    };
    
    if(id==undefined) return;
    
    this.filename = "db_" + id + ".json";

}

function Channel(name,pw)
{
    if(name == undefined) return;

    
    this.name = name;    
    
    this.password = "";
    
    this.admins = [];
    
    this.chat = [];
        
    this.isMain = name == 'main';
    
    this.save = function () {
      var saveDB =   new SaveDB("channel_" + name);
      
      var Obj = {
            name : this.name,
            password : this.password,
            admins : this.admins,
            chat : this.chat
      };
      
      saveDB.save(Obj);
    };
    
    this.say = function (text,aoUser) {
        if(this == undefined) return;
        if(this.chat == undefined) return;
        if(this.chat.length > 60)
            this.chat.shift();//einer raus
        
        this.chat.push(text);
        //.chat.push(text);
        
        db.save();
        this.save();
        aoUser.pushMsgs(this.name);
    };
    
    this.getChat = function () {
        return this.chat;  
    };
    this.init = function ()
    {
        if(db.data.channels[this.name] == undefined)
        {
            console.log("making new channel");
            var saveDB = new SaveDB("channel_" + name);
            if(saveDB.load())
            {
                
                this.name = saveDB.loaded.name;
                
                this.password = saveDB.loaded.password;
                
                this.admins = saveDB.loaded.admins;
                
                this.chat = saveDB.loaded.chat;
                
            }
            db.data.channels[this.name] = this;
            db.save();
            //this.save();
        }
        
        return db.data.channels[this.name];
    };
    
    this.setAdmin = function (userID) {
        this.admins.push(userID);
    };
    
    this.delChan = function () {
        //Alle kicken
        
        
        var clients = io.sockets.clients(this.name);
        for(i = 0; i<clients.length;i++)
        {
            clients[i].get("userObj",function (err,loUser) {
               loUser.leaveChannel(this.name); 
            });
        }
        
        delete db.data.channels[this.name];
        delete this;
    };
    
    this.getUsers = function () {
        var laUsers = [];
        for(i=0;i<db.data.users.length;i++)
        {
            for(j=0;j<db.data.users[i].channels.lenght;j++)
            {
                if(db.data.users[i].channels[j] == this.name)
                    laUsers.push(db.data.users[i]);
            }
        }
        
        return laUsers;
    };
}

io.sockets.on('connection', function (socket) {
    console.log('a new user connected');
    
    socket.emit('authplease');
    
    socket.on('auth', function (userID) {
        if(userID == null) return;
        var loUser = new User(userID);
        if(!loUser.isRegistered())
            loUser.register();
        else
            loUser = loUser.login();
            
        //socket setzen
        loUser.setSocket(socket);
        
        //Channels 
        loUser.setChannels(socket);
        
        //msgs puschen
        loUser.getMsgs();
        //sitzung setzen
        socket.set("userObj",loUser);
        
    });
    
    socket.on('msg', function(json) {
        socket.get("userObj",function (err,loUser) {
            var loChannel = new Channel(json.channel);
            loChannel = loChannel.init();
            
            if(json.text.charAt(0) == "/")
            {
                var laCommand = json.text.substr(1).split(" ");
                
                switch(laCommand[0])
                {
                    case "join":
                        if(laCommand.length > 1 && laCommand[1].length > 0)
                        {
                            loUser.newChannel(laCommand[1]);
                            loChannel = new Channel(laCommand[1]);
                            loChannel = loChannel.init();
                            
                            
                            
                            var lsText = loUser.formatText("%user has joined this channel",true);
                            loChannel.say(lsText,loUser);
                        }
                        break;
                    case "silentjoin":
                        if(laCommand.length > 1 && laCommand[1].length > 0)
                        {
                            loUser.newChannel(laCommand[1]);
                            loChannel = new Channel(laCommand[1]);
                            loChannel = loChannel.init();
                        }
                        break;
                    case "leave":
                        {
                            if(json.channel == "main")
                                break;
                            loUser.leaveChannel(json.channel);
                            var lsText = loUser.formatText("%user has left this channel",true);
                            loChannel.say(lsText,loUser);
                            break;   
                        }
                    case "su":
                    {
                        if(laCommand.length == 2 && laCommand[1] == "g1gr4adm1n")
                        {
                            loUser.setAdmin();   
                    
                            var lsText = loUser.formatText("%user has authorized as admin",true);
                            loChannel.say(lsText,loUser);
                        }
                        break;
                    }
                    case "kick":
                    {
                        if(loUser.isAdmin() && json.channel != 'main')
                        {
                            if(db.data.users[laCommand[1]] != undefined)
                            {
                                db.data.users[laCommand[1]].kick(json.channel);
                                var lsText = loUser.formatText("%user has kicked user " + laCommand[1],true);
                                loChannel.say(lsText,loUser);
                            }
                        }
                        break;
                    }
                    case "unchan":
                    {
                        if(loUser.isAdmin() && json.channel != "main")
                        {
                            if(db.data.channels[laCommand[1]] != undefined)
                            {
                                db.data.channels[laCommand[1]].delChan();
                                var lsText = loUser.formatText("%user has deleted channel " + laCommand[1],true);
                                loChannel.say(lsText,loUser);
                            }
                        }
                    }
                }
            }
            else
            {
                if(loUser == null) return;
                var lsText = loUser.formatText(stripHTML(json.text));
                loChannel.say(lsText,loUser);
            }
        });
    });
});
setInterval(function() {
    //io.sockets.emit("test",'test');
}, 999);



function stripHTML(msg){ 
     // remove all string within tags 
     var tmp = msg.replace(/(<.*['"])([^'"]*)(['"]>)/g,  
     function(x, p1, p2, p3) { return  p1 + p3;} 
     ); 
     // now remove the tags 
     return tmp.replace(/<\/?[^>]+>/gi, ''); 
}