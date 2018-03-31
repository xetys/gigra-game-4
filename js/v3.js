$(document).ready(function () {
	$(".btn_small").mouseenter(function () {
		$(this).attr("class","btn_small btn_small_active");
	});
	$(".btn_small").mouseleave(function () {
		$(this).attr("class","btn_small");
	});
});

function raiseError(text)
{
    $('#general-error').html(text).popError();
}
function raiseSuccess(text)
{
    $('#general-success').html(text).popError();
}
function reloadRes()
{
	diff = Math.round((new Date().getTime() / 1000 ) - initrestime);
	
	pres1 = Math.min(kapa1,res1 + ((prod1/3600) * diff));
	pres2 = Math.min(kapa2,res2 + ((prod2/3600) * diff));
	pres3 = Math.min(kapa3,res3 + ((prod3/3600) * diff));
	pres4 = Math.min(kapa4,res4 + ((prod4/3600) * diff));
	
	$("#resbar1").html(number_format(pres1,0,'.','.'));
	$("#resbar2").html(number_format(pres2,0,'.','.'));
	$("#resbar3").html(number_format(pres3,0,'.','.'));
	$("#resbar4").html(number_format(pres4,0,'.','.'));
	
}

function reloadKapa()
{ 
	flottenLadeKapaLeft = flottenLadeKapa; 
	for(var i=1;i<=4;i++)
	{
		l = $('#t'+ i).val();
		loaded = l == "" ? 0 : Number(l);
		flottenLadeKapaLeft -= loaded;
	}
	var color = 'lime';
	if(flottenLadeKapaLeft < 0)
		color = 'red';
	$("#fladkap").html("<font color='" + color + "'>"+ number_format(flottenLadeKapaLeft,0,'.','.')+"</font>");
	
}

function showGalaxy(as_g,as_s)
{
    as_g = Math.min(maxGal,Math.max(as_g,1));
    as_s = Math.min(maxSys,Math.max(as_s,1));
    
    
    $('#galaxy-g').val(as_g);
    $('#galaxy-s').val(as_s);
    
    var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({type:"galaxie",g:as_g,s:as_s});
    
    ajax.onready = function ()
    {
        $('#galaxy-container').html(this.response);
        tb_init('a.thickbox, area.thickbox, input.thickbox');//pass where to apply thickbox
    };
    
    ajax.run();
}
stop=0;
function format_zeit(sek)
{
  d=Math.floor(sek/86400);
  h=Math.floor((sek-d*86400)/3600);
  m=Math.floor((sek-d*86400-h*3600)/60);
  s=Math.floor((sek-d*86400-h*3600-m*60));
  if(s<10) s='0'+s;
  if(m<10) m='0'+m;
  if(h<10) h='0'+h;
  return ((d>0)?(d+'Tag'+((d>1)?'e':'')+', '):'')+h+':'+m+':'+s;
}
function reloadFleetEvents()
{
    var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({type:"reloadevents"});
    
    ajax.onready = function () {
        $('#flotten-events').html(this.response);
        clearTimeout(eventTimeout);
        setTimeout(eventTimer,500);
    };
    
    ajax.run();
}
eventTimeout = false;
function eventTimer()
{
    clearTimeout(eventTimeout);
    var fidList = $('input[name=fid_list]');
    if(fidList.length == 0)
        stop = 1;
    for(var pos=0;pos<fidList.length;pos++)
    {
        fid = fidList[pos].value;
    
        tthere = $('#'+fid+'_tthere').val();
        tback  = $('#'+fid+'_tback').val();
        oneway  = $('#'+fid+'_oneway').val();
        nowTime = $('#timeNow').val();
        nowTime = Number(nowTime);
        flyTime = Number($('#'+fid+'_flytime').val());
        startTime = tthere - flyTime;
        x = tthere < nowTime ? tback : tthere;
        x = x - nowTime;
        doneTime = flyTime - x;
        var percent = doneTime / flyTime;
    
    
    
    
        writeTo = tthere < nowTime ? '_tback' : '_tthere';
    
        if(tthere < nowTime)
        {
            $('#'+fid+"_bar").removeClass("fly_there");
            $('#'+fid+"_bar").addClass("fly_back");
        }

    
        timeObj = $('#'+fid+'_time');
        if(timeObj[0].style.display!='none' && x>0)
        {
            x--;
            $('#'+fid+"_bar").css("width",Math.ceil(150 * percent));
            //console.log(fid + ' - ' + percent);
            timeObj.html(format_zeit(x) + ' ' + (percent >= 0 ? Math.round(percent*100) + '%' : ''));
        }
        else if((timeObj[0].style.display!='none' && tback <= nowTime) || oneway == 1)
        {
       
            $('#'+fid+"_box").hide('fast');
        
        }
        if(pos==fidList.length && x<0)
            stop=1;
        else
            stop=0;
    }
    if(stop!=1)
    {
        eventTimeout = window.setTimeout(eventTimer,999);
        $('#timeNow').val(nowTime+1);
    }
}

function number_format (number, decimals, dec_point, thousands_sep) {
    // Formats a number with grouped thousands  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/number_format    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     bugfix by: Michael White (http://getsprink.com)
    // +     bugfix by: Benjamin Lupton
    // +     bugfix by: Allan Jensen (http://www.winternet.no)    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +     bugfix by: Howard Yeend
    // +    revised by: Luke Smith (http://lucassmith.name)
    // +     bugfix by: Diogo Resende
    // +     bugfix by: Rival    // +      input by: Kheang Hok Chin (http://www.distantia.ca/)
    // +   improved by: davook
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Jay Klehr
    // +   improved by: Brett Zamir (http://brett-zamir.me)    // +      input by: Amir Habibi (http://www.residence-mixte.com/)
    // +     bugfix by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Theriault
    // +      input by: Amirouche
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)    // *     example 1: number_format(1234.56);
    // *     returns 1: '1,235'
    // *     example 2: number_format(1234.56, 2, ',', ' ');
    // *     returns 2: '1 234,56'
    // *     example 3: number_format(1234.5678, 2, '.', '');    // *     returns 3: '1234.57'
    // *     example 4: number_format(67, 2, ',', '.');
    // *     returns 4: '67,00'
    // *     example 5: number_format(1000);
    // *     returns 5: '1,000'    // *     example 6: number_format(67.311, 2);
    // *     returns 6: '67.31'
    // *     example 7: number_format(1000.55, 1);
    // *     returns 7: '1,000.6'
    // *     example 8: number_format(67000, 5, ',', '.');    // *     returns 8: '67.000,00000'
    // *     example 9: number_format(0.9, 0);
    // *     returns 9: '1'
    // *    example 10: number_format('1.20', 2);
    // *    returns 10: '1.20'    // *    example 11: number_format('1.20', 4);
    // *    returns 11: '1.2000'
    // *    example 12: number_format('1.2000', 3);
    // *    returns 12: '1.200'
    // *    example 13: number_format('1 000,50', 2, '.', ' ');    // *    returns 13: '100 050.00'
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');    }
    return s.join(dec);
}

jQuery.fn.popError = function () {
    this.css("position","absolute");
    this.css("top", (($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop() + "px");
    this.css("left", (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft() + "px");
    this.css("z-index",50000);
    this.css("display","block");
    tmpObj = this;
    setTimeout(function () {tmpObj.fadeOut(4000);},1000);
    return this;
};

jQuery.fn.getPath = function () {
    if (this.length != 1) throw 'Requires one element.';

    var path, node = this;
    while (node.length) {
        var realNode = node[0], name = realNode.localName;
        if (!name) break;
        name = name.toLowerCase();

        var parent = node.parent();

        var siblings = parent.children(name);
        if (siblings.length > 1) { 
            name += ':eq(' + siblings.index(realNode) + ')';
        }

        path = name + (path ? '>' + path : '');
        node = parent;
    }

    return path;
};



function msg_usertxt(id)
{
    if ($('#msg_usertext'+id).css('display') == 'none')
    {
        $('#msg_usertext'+id).slideDown('slow', function() {
            $('#msg_usertext'+id).css('display','inline');
            $.ajax({
                type: "GET",
                url: "nachrichten.php",
                data: "ajax="+id
            });
        });
        $('#msg_red'+id).html('Ja');
    }
    else if($('#msg_usertext'+id).css('display') == 'inline')
    {
        $('#msg_usertext'+id).slideUp('slow',function (){
            $('#msg_usertext'+id).css('display','none');
        });
    }
}

function switchTech()
{
    $('#build_research').toggle();   
    $('#military').toggle();   
}


function cancelBS(id)
{
    var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({type:"cancelBauliste",sid:id});
    ajax.onready = function () 
    {
          reloadIt('S');
          reloadIt('V');
    };
    
    ajax.run();
}


function renamePlanet()
{
     var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({type:"renamePlan",name:$('#planet-rename').val()});
    ajax.onready = function () 
    {
          tb_remove();
          $('#save-success').popError();
    };
    
    ajax.run();     
}

function hpPlanet()
{
     var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({type:"hpPlan"});
    ajax.onready = function () 
    {
          tb_remove();
          $('#save-success').popError();
    };
    
    ajax.run();     
}


function leavePlanet()
{
     var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({type:"leavePlan"});
    ajax.onready = function () 
    {
          tb_remove();
          $('#save-success').popError();
    };
    
    ajax.run();     
}
function showBonusBox(id)
{
    var ajax = new Ajax();
    
    ajax.action = "bonusinfo.php?id=" + id;

    ajax.onrun = function () { 
        $('#coda-nav-1.coda-nav ul li.tab5 a').click();
    };
    
    ajax.onready = function() {$('#bonus_info').html(this.response)};
    
    ajax.run();
}

function useBonusItem(asId)
{
    var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({type:"useBonusItem",id:asId});
    ajax.onready = function () 
    {
          var json = JSON.parse(this.response);
          if(json.error == 1)
            raiseError(json.text);
          else
            raiseSuccess(json.text);
        
        
        window.location.reload();
    };
    
    ajax.run();     
}

function buyBonusItem(asId)
{
    var ajax = new Ajax();
    
    ajax.action = "ajax.php";
    ajax.method = "post";
    
    ajax.createFormArray({type:"buyBonusItem",id:asId});
    ajax.onready = function () 
    {
          var json = JSON.parse(this.response);
          if(json.error == 1)
            raiseError(json.text);
          else
          {
            raiseSuccess(json.text);
            var o = $('#bonusActivate');
            o.attr('class','v3_build_btn');
            //addeiren
            $('#bonus_have_'+asId).html(Number($('#bonus_have_'+asId).html())+1);
          }
    };
    
    ajax.run();     
}


function showModal(url,aiWidth,aiHeight,aoButtons)
{
    liWidth = aiWidth == undefined ? 500 : aiWidth;
    liHeight = aiHeight == undefined ? 500 : aiHeight;
    loButtons = aoButtons == undefined ? {} : aoButtons;
    
    $.get(url).done(function (data) { 
        $('#modal-box').html(data).dialog({
                modal:true,
                width : liWidth,
                height: liHeight,
                buttons : loButtons
            });
    });
}

function closeModal()
{
    $('#modal-box').dialog("close");   
}
