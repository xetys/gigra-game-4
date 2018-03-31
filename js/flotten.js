function switchTargetViews(mode)
{
    if(mode == 1)
    {
        $('#manual').hide();
        $('#fleetTargetSelector').show();
    }
    else
    {
        $('#manual').show();
        $('#fleetTargetSelector').hide();
    }
    
}

function selectCoordinates(asCoords)
{
    var laCoords = asCoords.split(':');
    $("#toG").val(laCoords[0]);
    $("#toS").val(laCoords[1]);
    $("#toP").val(laCoords[2]);
    $("#toT").val(laCoords[3]);
    
}
function saveTarget()
{
    var lsCoords = $('#targetG').val() + ':' + $('#targetS').val() + ':' + $('#targetP').val() + ':' + $('#targetT').val();
    var lsComment = $('#targetComment').val();
    
    var ajax = new Ajax();
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({ type : "saveTarget", coords : lsCoords, comment : lsComment});
    
    ajax.onready = function () {
        $("#targetConfig > #einstellungen").html(this.response);
    };
    
    ajax.run();
}
function deleteTarget(asCoords)
{

    
    var ajax = new Ajax();
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({ type : "deleteTarget", coords : asCoords});
    
    ajax.onready = function () {
        $("#targetConfig > #einstellungen").html(this.response);
    };
    
    ajax.run();
}
function changeFleetPlan()
{
    var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.selectForm("chPlan");
    
    ajax.onready = function() {
          $('#fleet-panel').html(this.response);
          $('#coda-nav-1.coda-nav ul li.tab4 a').click();
          tb_init('a.thickbox, area.thickbox, input.thickbox');//pass where to apply thickbox
    };
    
    ajax.run();
}

function allShip()
{
    $('.maxlink').click();
}
function noShip()
{
    $('.shipSelect').val('0');   
}

toG = toS = toP = toT = 0;

//Epic Animation
function toFleetCommand(g,s,p,t)
{
    $("#fleet_options ul li").addClass("inactive");
    toG = g;
    toS = s;
    toP = p;
    toT = t;
    if(getSpeed() == -1)
    {
        fleetError(-30);
        return;   
    }
    getMissions(g,s,p,t,true);
}

//klassiche lösung
function fleetNext()
{
    toG = $("#toG").val();
    toS = $("#toS").val();
    toP = $("#toP").val();
    toT = $("#toT").val();
    
    
    fleetNextWithParm(toG,toS,toP,toT);
}
function fleetNextWithParm(g,s,p,t)
{
    if(getSpeed() == -1)
    {
        fleetError(-30);
        return;   
    }
    
    toG = g;
    toS = s;
    toP = p;
    toT = t;
    
    getMissions(toG,toS,toP,toT,true);
}
function cancelFleet()
{
    toG = toS = toP = toT = 0;
    
    $('#fleet-command').hide();
    //$("#fleet_command_to").html("");
    $('#fleet-select').show('fast',function() {$('#coda-nav-1.coda-nav ul li.tab4 a').click();});
    
    $("#fleet_options ul li").addClass("inactive");
    $(".fleetNonDefault").hide();
    $("#fleetSendRessource").show();
}

function prepareFormArray()
{
    var arr = new Array();
    
    //schiffe
    var obj;
    for(var i = 1;i<=120;i++)
    {
        obj = $('#s'+i);
        if(obj.length == 1 && Number(obj.val()) > 0)
            arr["ship["+i+"]"] = obj.val();
    }
    
    
    //koords
    arr["fromc"] = $('#fromG').val()+":"+$('#fromS').val()+":"+$('#fromP').val()+":"+$('#fromT').val();
    arr["toc"]  = toG+":"+toS+":"+toP+":"+toT;
    
    return arr;
}

function fleetError(errCode)
{
    errId = "";
    switch(errCode)
    {
        case -10:
            errId = "error-no-missions";
            break;
        case -15:
            errId = "error-me-in-umod";
            break;
        case -16:
            errId = "error-target-in-umod";
            break;
        case -20:
            errId = "error-no-planet";
            break;
        case -30:
            errId = "error-noships";
            break;
        case -40:
            errId = "error-invalidships";
            break;
        case -45:
            errId = "error-invalid-mission";
            break;
        case -50:
            errId = "error-invalid-res";
            break;
        case -60:
            errId = "error-not-enough-res";
            break;
        case -70:
            errId = "error-not-enough-capa";
            break;
        case -80:
            errId = "error-not-enough-fuel";
            break;
        case -90:
            errId = "error-aks-not-in-time";
            break;
        case -100:
            errId = "fleet-success";
            break;
    }
    
    $("#"+errId).popError();
}

function errorNoShips()
{
    $('#error-noships').popError();
}
function errorInvalidShips()
{
    $('#error-invalidships').popError();
}

function getMissions(g,s,p,t,noAnimation)
{
    var arr = prepareFormArray();
    arr["type"] = "getmission";
    
    var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray(arr);
    ajax.onready = function ()
    {
         var json = eval(this.response);
         if(json[0].error == undefined)
         {
            if(noAnimation == undefined)
            {
                id = "planet_"+g+"_"+s+"_"+p+"_"+t;   
                $('#fleet-select').hide("fast", function () {
                     x = $('#fleet_command_to').offset().left;
                     y = $('#fleet_command_to').offset().top;
                    $('#'+id + " div").clone()
                            .prependTo($("#"+id))
                            .css({position:"absolute"})
                            .animate({top:y,left:x},600, function()
                            {
                                $("#fleet_command_to").html($('#'+id + "").html());
                                $(this).remove();
                                $('#fleet-command').show('fast', function () {showFlyData();$('#coda-nav-1.coda-nav ul li.tab4 a').click();});
                            }
                            );
                });
            }
            else
            {
                $('#fleet-select').hide("fast", function () { 
                    $('#fleet-command').show('fast', function () {showFlyData();$('#coda-nav-1.coda-nav ul li.tab4 a').click();});
                });
            }
            for(var i = 0;i<json.length;i++)
                if(json[i] != "aks_lead")
                    $("#"+json[i]).removeClass("inactive");
            var ajax2 = new Ajax();
            ajax2.action = "ajax.php";
            ajax2.method = "post";
            
            ajax2.createFormArray({type : 'planetInfo', coords : g + ':' + s + ':' + p + ':' + t});
            ajax2.onready = function () {
                var lsCancelButton = "<a href='javascript:void(0);' onclick='changeFleetPlan()'><img src='design/2-0/global_cancel.png'></a>";
                $('#fleet_command_to').html("<table><tr><td>" + lsCancelButton + "</td><td>" + this.response + "</td></tr></table>");   
            };
            
            ajax2.run();
         }
         else
         {
                cancelFleet();
                fleetError(json[0].error);
         }
    };
    
    ajax.run();
}
function getSpeed()
{
    var speed = -1;
    var obj;
    for(var i = 1;i<=120;i++)
    {
        obj = $('#speed_s'+i);
        if(obj.length==1 && Number($('#s'+i).val()) > 0)
        {
            speed = speed != -1 ? Math.min(obj.val(),speed) : obj.val();   
        }
    }
    
    return speed;
}
function getVerbrauch()
{
    var verbr = 0;
    var obj;
    for(var i = 1;i<=120;i++)
    {
        obj = $('#consum_s'+i);
        if(obj.length==1 && Number($('#s'+i).val()) > 0)
        {
            verbr += Number(obj.val()) * Number($('#s'+i).val());
        }
    }
    
    return verbr;
}
function getCapa()
{
    var capa = 0;
    var obj;
    for(var i = 1;i<=120;i++)
    {
        obj = $('#capa_s'+i);
        if(obj.length==1 && Number($('#s'+i).val()) > 0)
        {
            capa += Number(obj.val()) * Number($('#s'+i).val());
        }
    }
    
    return Math.floor(capa);
}
function tp(ac)
{
  ei=ac+"";
  au="";
  while(ei.length>3)
  {
    au="."+ei.substring(ei.length-3,ei.length)+au;
    ei=ei.substring(0,ei.length-3);
  }au=ei+au;
  return au;
}
actVerbrauch = 0;
function formatDate(liTime)
{
    var loDate = new Date(getServerTime() + (liTime * 1000));

    var lsRet = '';
    lsRet = (loDate.getDate()+1) + '.' 
            + (loDate.getMonth()+1) + '.' 
            + loDate.getFullYear() + ' ' 
            + loDate.getHours() + ':' 
            + (loDate.getMinutes() < 10 ? '0'+loDate.getMinutes() : loDate.getMinutes())  + ':' 
            + (loDate.getSeconds() < 10 ? '0'+loDate.getSeconds() : loDate.getSeconds());
    
    lsRet = loDate.format("dd.mm.yyyy h:MM:ss");

    return lsRet;
}
timerTO = false;

function GetDistance() {
    var thisGalaxy = $('#fromG').val();
	var thisSystem = $('#fromS').val();
	var thisPlanet = $('#fromP').val();
	var targetGalaxy = toG;
	var targetSystem = toS;
	var targetPlanet = toP;

	if (targetGalaxy - thisGalaxy != 0) {
		return Math.abs(targetGalaxy - thisGalaxy) * 20000;
	} else if (targetSystem - thisSystem != 0) {
		return Math.abs(targetSystem - thisSystem) * 5 * 19 + 2700;
	} else if (targetPlanet - thisPlanet != 0) {
		return Math.abs(targetPlanet - thisPlanet) * 5 + 1000;
	} else {
		return 5;
	}
}
function showFlyData()
{
  
  a=$('#fromG').val();
  b=$('#fromS').val();
  c=$('#fromP').val();
  
  p=document.getElementsByName("s")[0].value;
  
  m=0;
  h=0;
  d="-";
  en="";
  
  //d=Math.round(Math.abs((a-toG)*20000))+Math.round((2700+5*Math.abs((b-toS)*19)))+Math.round((1000+Math.abs((c-toP)*5)));
  d = GetDistance();
  if(a<1|a>maxGal|b<1|b>maxSys|c<0|c>199)
  {
    d="-";
  }
  e=Math.round(getVerbrauch()*d/35000*((p/10)+1)*((p/10)+1))+1;
  actVerbrauch = e;
  s = Math.max(Math.round((3500 / (p * 0.1) * Math.pow(d * 10 / getSpeed(), 0.5) + 10) / confSpeedFl), 5);
  //s=Math.round((35000/p*Math.sqrt(d*10/getSpeed()))/confSpeedFl);
  //s=Math.round(s/5);
  
  s*=userFlugzeitBonus;
  s=Math.round(s);
  s_save = s;
  
  if(s>59){m=Math.floor(s/60);s=s-m*60;}
  if(m>59){h=Math.floor(m/60);m=m-h*60;}
  if(s<10){s="0"+s;}
  if(m<10){m="0"+m;}
  u="00FF";
  if(e>getCapa()){u="FF00";}
  if(e>1){en="en";}
  if(d=="-")
  {
    document.getElementById("w").innerHTML=d;
    document.getElementById("x").innerHTML=d;
    document.getElementById("z").innerHTML=d;
  }
  else
  {
    clearInterval(timerTO);
    timerTO = setInterval(function() {
        ID('arrive').innerHTML = formatDate(s_save);
        ID('back').innerHTML = formatDate(s_save*2);
    }, 999);
    document.getElementById("w").innerHTML=tp(d*confSpeedFl)+".000 km";
    document.getElementById("x").innerHTML=tp(h)+":"+m+":"+s;
    document.getElementById("z").innerHTML="<font color="+u+"00>"+tp(e)+" H<sub>2</sub></font>";
    document.getElementById("mspeed").innerHTML=number_format(getSpeed(),0,".",".");
    document.getElementById("capaall").innerHTML=number_format(getCapa(),0,".",".");
  }
}
function z() {
  c=document.getElementsByName("t")[0].value.split(":");
  if(c[0]=="") return;
  document.getElementsByName("ft1")[0].value = c[0];
  document.getElementsByName("ft2")[0].value = c[1];
  document.getElementsByName("ft3")[0].value = c[2];
  showFlyData();
}


function maxRes(r)
{
    var capaLeft = 0;
    fillRes = 0;
    capaUsed = 0;
    capaAll = getCapa();
    for(var i=1;i<=4;i++)
        capaUsed += Number($('#t'+i).val());
    capaLeft = capaAll - capaUsed;
    
    if(r != undefined)
    {
        actRes = $("#res"+r+"_trans").val();
        actRes = Math.floor(Number(actRes));
        if(r == 4)//Wasserstoffverbrauch abziehen
            actRes -= actVerbrauch;
        
        fillRes = Math.min(Math.round(actRes),capaLeft);
        
        $("#t"+r).val(fillRes);
        capaLeft -= fillRes;
    }
    color = capaLeft < 0 ? "red" : "lime";
    capaStr = "<font color='"+color+"'>"+number_format(capaLeft,0,'.','.')+"</font>";
    
    $("#capaall").html(capaStr);
}

function sendFleet(mission,specialCommand,fleetArray)
{
    if(specialCommand === undefined)
        specialCommand = '';
    
    var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    arr = fleetArray == undefined ? prepareFormArray() : fleetArray;
    
    arr["res[0]"] = $("#t1").val();
    arr["res[1]"] = $("#t2").val();
    arr["res[2]"] = $("#t3").val();
    arr["res[3]"] = $("#t4").val();
    
    arr["mission"] = mission;
    arr["speed_select"] = $("#speed_select").val();
    arr["sc"] = specialCommand;
    arr["type"] = "sendfleet";
    
    ajax.createFormArray(arr);
    
    ajax.onready = function () {
        var fleetCode = Number(this.response);
        if(fleetCode == -100)
        {
            //success   
            reloadFleetEvents();
            
            changeFleetPlan();
        }
        fleetError(fleetCode); 
    };
    
    clearInterval(timerTO);
    ajax.run();
}
function sendProbes(toc)
{
  
  a=$('#fromG').val();
  b=$('#fromS').val();
  c=$('#fromP').val();   
  
  var arr = new Array();
  
   //koords
  arr["fromc"] = $('#fromG').val()+":"+$('#fromS').val()+":"+$('#fromP').val()+":"+$('#fromT').val();
  arr["toc"]  = toc;
  arr["type"] = "sendprobes";
  
  var ajax = new Ajax();
  
  ajax.action = "ajax.php";
  ajax.method = "post";
  
  ajax.createFormArray(arr);
  
  ajax.onready = function () {
        var fleetCode = Number(this.response);
        if(fleetCode == -100)
        {
            //success   
            reloadFleetEvents();
            
            //changeFleetPlan();
        }
        fleetError(fleetCode); 
    };
    
    clearInterval(timerTO);
    ajax.run();
}
function fleetBack(id)
{
    var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({type : "fleetback", fid : id});
    ajax.onready = function () {
        var r = this.response;
        
        if(r == "ok")
            reloadFleetEvents();
    };
    
    ajax.run();
}

function showAKS()
{
    var ajax = new Ajax();
    
        ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({type : "getAksFleets", toc : toG + ':' + toS + ':' + toP + ':' + toT});
    ajax.onready = function ()
    {
        $('#fleetSendRessource').hide();
        $('#sc-aks').show('fast');
        if(this.response != -1)
        {
            var aksList = JSON.parse(this.response);
        
            $.each(aksList, function(val, text) {
                $('#aks_fleetlist').append(
                    $('<option></option>').val(val).html(text)
                );
            });
            $('.aksjoin').show();
        }
    };
    
    ajax.run();
}

function joinAKS()
{
   sendFleet("aks","join="+$('#aks_fleetlist').val());
}
pcResPrio = "";
pcCountRes = 0;
pcShips = "";
pcCountShip = 0;

function pcSelectRes(n)
{
    if(pcCountRes == 4)
        return;
    if(pcResPrio.indexOf(""+n) > -1)
        return;
    pcResPrio += ","+n;
    if(pcResPrio.charAt(0) == ",")
        pcResPrio = pcResPrio.substring(1);
    pcCountRes++;
    $('#rb' + n + ' > span').html(' - '+ (pcCountRes));
    $('#rb'+n).removeClass("transparent");
    pcCheck();
    
}
function pcResetRes()
{
    $(".resbn").addClass("transparent");
    $(".resbn > span").html("");
    pcCountRes = 0;
    pcResPrio = "";
    pcCheck();
}

function pcResetShip()
{
    $(".shipbn").addClass("transparent");
    $(".shipbn > span").html("");
    pcCountShip = 0;
    pcShips = "";
    pcCheck();
}

function pcSelectShip(n)
{
    if(pcCountShip == 4)
        return;
    if(pcShips.indexOf(""+n) > -1)
        return;
    pcShips += ","+n;
    if(pcShips.charAt(0) == ",")
        pcShips = pcShips.substring(1);
    pcCountShip++;
    $('#sb' + n + ' > span').html(' - '+ (pcCountShip));
    $('#sb'+n).removeClass("transparent");
    
    pcCheck();
    
}
function pcStart(abTest)
{
    powerCollect($('#pc_coords').val(),pcResPrio,pcShips,abTest);
}
function pcCheck()
{
    if(pcCountRes == 4 && pcCountShip > 0)
    {
        $("#pc_start").bind("click",function() { pcStart(0); });
        $("#pc_start > div").removeClass("transparent");
        
        $("#pc_list").bind("click",function() { pcStart(1); });
        $("#pc_list > div").removeClass("transparent");
    }
    else
    {
        $("#pc_start").unbind("click");
        $("#pc_start > div").addClass("transparent");
        
        $("#pc_list").unbind("click");
        $("#pc_list > div").addClass("transparent");
    }
}
function powerCollect(asCoords,asPrio,asShips,abTest)
{
    var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({ type: "powerCollect", coords : asCoords, prio : asPrio, ships : asShips, test: abTest});
    
    ajax.onready = function () {
        $('#successlist').html(this.response);
    };
    
    
    ajax.run();
}
function allFleetBack()
{
    var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({ type: "allFleetBack"});
    
    ajax.onready = function () {
        reloadFleetEvents();
    };
    
    
    ajax.run();
}

var dateFormat = function () {
    var	token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
		timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
		timezoneClip = /[^-+\dA-Z]/g,
		pad = function (val, len) {
			val = String(val);
			len = len || 2;
			while (val.length < len) val = "0" + val;
			return val;
		};

	// Regexes and supporting functions are cached through closure
	return function (date, mask, utc) {
		var dF = dateFormat;

		// You can't provide utc if you skip other args (use the "UTC:" mask prefix)
		if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
			mask = date;
			date = undefined;
		}

		// Passing date through Date applies Date.parse, if necessary
		date = date ? new Date(date) : new Date;
		if (isNaN(date)) throw SyntaxError("invalid date");

		mask = String(dF.masks[mask] || mask || dF.masks["default"]);

		// Allow setting the utc argument via the mask
		if (mask.slice(0, 4) == "UTC:") {
			mask = mask.slice(4);
			utc = true;
		}

		var	_ = utc ? "getUTC" : "get",
			d = date[_ + "Date"](),
			D = date[_ + "Day"](),
			m = date[_ + "Month"](),
			y = date[_ + "FullYear"](),
			H = date[_ + "Hours"](),
			M = date[_ + "Minutes"](),
			s = date[_ + "Seconds"](),
			L = date[_ + "Milliseconds"](),
			o = utc ? 0 : date.getTimezoneOffset(),
			flags = {
				d:    d,
				dd:   pad(d),
				ddd:  dF.i18n.dayNames[D],
				dddd: dF.i18n.dayNames[D + 7],
				m:    m + 1,
				mm:   pad(m + 1),
				mmm:  dF.i18n.monthNames[m],
				mmmm: dF.i18n.monthNames[m + 12],
				yy:   String(y).slice(2),
				yyyy: y,
				h:    H % 12 || 12,
				hh:   pad(H % 12 || 12),
				H:    H,
				HH:   pad(H),
				M:    M,
				MM:   pad(M),
				s:    s,
				ss:   pad(s),
				l:    pad(L, 3),
				L:    pad(L > 99 ? Math.round(L / 10) : L),
				t:    H < 12 ? "a"  : "p",
				tt:   H < 12 ? "am" : "pm",
				T:    H < 12 ? "A"  : "P",
				TT:   H < 12 ? "AM" : "PM",
				Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
				o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
				S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
			};

		return mask.replace(token, function ($0) {
			return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
		});
	};
}();

// Some common format strings
dateFormat.masks = {
	"default":      "ddd mmm dd yyyy HH:MM:ss",
	shortDate:      "m/d/yy",
	mediumDate:     "mmm d, yyyy",
	longDate:       "mmmm d, yyyy",
	fullDate:       "dddd, mmmm d, yyyy",
	shortTime:      "h:MM TT",
	mediumTime:     "h:MM:ss TT",
	longTime:       "h:MM:ss TT Z",
	isoDate:        "yyyy-mm-dd",
	isoTime:        "HH:MM:ss",
	isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
	isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
	dayNames: [
		"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
		"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
	],
	monthNames: [
		"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
		"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
	]
};

// For convenience...
Date.prototype.format = function (mask, utc) {
	return dateFormat(this, mask, utc);
};