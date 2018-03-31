function showItemInfo(t,id)
{
   $('#bau_info_part_' + t).html(ID("hidden"+t+id).innerHTML);
}

function reloadAll()
{
	document.location.href = document.location.href;
}
function divInfo(text)
{
	ID("divInfo").innerHTML = text + '<a href="javascript:void(0);" onclick="$(\'#divInfo\').fadeOut(\'slow\')">Schlie&szlig;en</a>';
	$('#divInfo').fadeIn('slow');
}
function showInfo(t,id)
{

	divInfo(ID("hidden"+t+id).innerHTML);
}
function buildIt(t,id)
{
	var ajax = new Ajax();
	var type = "B";
    var action = "v3_konstruktion.php";
    var target_div = "bauliste";
	if(t == "B")
	{
		action = "v3_konstruktion.php";
		target_div = "bauliste";
	}
	else if(t == "F")
	{
		action = "v3_konstruktion.php";
		target_div = "forschliste";
        type = "F";
	}
	ajax.action = action;
	ajax.method = "get";
	
	ajax.createFormArray({"ajax" : "true", "B" : id,"type" : type});
	
	ajax.onerror = function() {
		divInfo("<font color=red>There is an error by using Ajax for building. Here Comes the HTTP-Status: " + ajax.status);
	};
	
	ajax.onready = function () {
		ID(target_div).innerHTML = ajax.response;
		if(t == "B")tbreset();
		if(t == "F")tfreset();
		//showInfo(t,id);
        showItemInfo(t,id);
		eval(ID("eval"+t).innerHTML);
	};
	
	ajax.run();
}
function production(t,formName)
{
	var ajax = new Ajax();
	
	if(t == "S")
		ajax.action = "v3_konstruktion.php?type=S";
	if(t == "V")
		ajax.action = "v3_konstruktion.php?type=V";
	ajax.method = "post";
	
	ajax.selectForm(formName);
	
	ajax.onerror = function() {
		divInfo("<font color=red>There is an error by using Ajax for building. Here Comes the HTTP-Status: " + ajax.status);
	};
	
	ajax.onready = function () {
        idField = t == "S" ? "schiffliste" : "verteidigungsliste";
		ID(idField).innerHTML = ajax.response;
		if(t == "S")tsreset();
		if(t == "V")tvreset();
		eval(ID("eval"+t).innerHTML);
	};
	
	ajax.run();
}
function stopIt(t,id)
{
	var ajax = new Ajax();
	
    var type = "B";
	var action = "v3_konstruktion.php";
	var target_div = "bauliste";    
	if(t == "B")
	{
		action = "v3_konstruktion.php";
		target_div = "bauliste";
	}
	else if(t == "F")
	{
		action = "v3_konstruktion.php";
		target_div = "forschliste";
        
        type = "F";
	}
	ajax.action = action;
	ajax.method = "get";
	
	ajax.createFormArray({"ajax" : "true", "s" : id,"type" : type});
	
	ajax.onerror = function() {
		divInfo("<font color=red>There is an error by using Ajax for stopping. Here Comes the HTTP-Status: " + ajax.status);
	};
	
	ajax.onready = function () {
		ID(target_div).innerHTML = ajax.response;
		if(t == "B")tbreset();
		if(t == "F")tfreset();
	    showItemInfo(t,id);
		eval(ID("eval"+t).innerHTML);
	};
	
	ajax.run();
}
reloadPause = false;
function reloadIt(t)
{
	if(!reloadPause)
	{
		var ajax = new Ajax();
		var action = "v3_konstruktion.php";
		var type = "B";
		var target_div = "bauliste";
		if(t == "B")
		{
			action = "v3_konstruktion.php";
			type = "B";
			target_div = "bauliste";
		}
		else if(t == "F")
		{
			action = "v3_konstruktion.php";
			type = "F";
			target_div = "forschliste";
		}
		else if(t == "S")
		{
			action = "v3_konstruktion.php";
			type = "S";
			target_div = "schiffliste";
		}
		else if(t == "V")
		{
			action = "v3_konstruktion.php";
			type = "V";
			target_div = "verteidigungsliste";
		}
		ajax.action = action;
		ajax.method = "get";
		
		ajax.createFormArray({"ajax" : "true", "type": type});
		
		ajax.onerror = function() {
			divInfo("<font color=red>There is an error by using Ajax for reloading. Here Comes the HTTP-Status: " + ajax.status);
		};
		
		ajax.onready = function () {
			ID(target_div).innerHTML = ajax.response;
			if(t == "B")tbreset();
			if(t == "F")tfreset();
			if(t == "S")tsreset();
			if(t == "V")tvreset();
			eval(ID("eval"+t).innerHTML);
		};
		
		ajax.run();
		setTimeout(function () {reloadPause = false;} ,3000);
		reloadPause = true;
	}
}
bxs = new Array();
ids = new Array();
starttimes = new Array();
endtimes = new Array();
anz = 0;
ids[0] = 0;
bxs[0] = 0;
starttimes[0] = 0;
endtimes[0] = 0;
var tbid = false;
function tbreset()
{
	clearTimeout(tbid);
	delete bxs, ids, starttimes, endtimes;
	bxs = new Array();
	ids = new Array();
	starttimes = new Array();
	endtimes = new Array();
	anz = 0;
	ids[0] = 0;
	bxs[0] = 0;
	starttimes[0] = 0;
	endtimes[0] = 0;
}
cubes = new Array();
cubes_i = new Array();

function cubePercent(cn,percent)
{
    //document.getElementById("bau"+ids[cn]).style.backgroundPosition = (100 - percent) * 145 + "px 0";
    percent_cube(cn,Number(percent));
}

function animateCube(id,rate)
{
    perc = cubes[id];
    perc += rate;
    cubes[id] = perc;
    
    percent = Math.round(Math.max(0,perc - 1));
    
    if(percent > 100 || ID(id) == null)
    {
        delete cubes[id];
        clearInterval(cubes_i[id]);
        
    }
    else
        percent_cube(id,percent);
}
function tb(){
  v = new Date();
  n = new Date();
  o = new Date();
  for (var cn = 1; cn <= anz; cn++) {
    ss = bxs[cn];
    //Prozente Anzeigen
    /*
    var alltime = endtimes[cn] - starttimes[cn];
    var percent = 100 - Math.floor(ss / alltime * 100);
    //document.getElementById("bau"+ids[cn]).style.backgroundPosition = (100 - percent) * 145 + "px 0";
    percent_cube(cn,Number(percent));*/
    var lsID = "bau"+ids[cn];
    if(cubes[lsID] == undefined)
    {
        
        var alltime = endtimes[cn] - starttimes[cn];
        var percent = 100 - Math.floor(ss / alltime * 100);
        cubes[lsID] = percent;
        var rate = 1 / (alltime / 100) / 10;
        
        cubes_i[lsID] = setInterval("animateCube('"+lsID+"',"+rate+")",100);
    }
    
    bx = ID("bau" + ids[cn] + "time");
    s = ss - Math.round((n.getTime() - v.getTime()) / 1000.);
    m = 0;
    h = 0;
    if (s <= 0) {
      if(s < 0)
      {	
      	reloadIt("B");
      	reloadIt("S");
      	reloadIt("V");
      }
      bx.innerHTML = "Abgeschlossen";
      ID("bau"+ids[cn]).style.background = "transparent";
      $('#bau'+ids[cn]+'time').fadeOut('slow');
    } else {
      if (s > 59) {
	m = Math.floor(s/60);
	s = s - m * 60;
      }
      if (m > 59) {
	h = Math.floor(m / 60);
	m = m - h * 60;
      }
      if (s < 10) {
	s = "0" + s;
      }
      if (m < 10) {
	m = "0" + m;
      }
      bx.innerHTML = rt(bxs[cn])//": " + h + ":" + m + ":" + s;
    }
    	bxs[cn]--;
  }
  tbid = window.setTimeout("tb();", 999);
}
Fbxs = new Array();
Fids = new Array();
Fstarttimes = new Array();
Fendtimes = new Array();
Fanz = 0;
Fids[0] = 0;
Fbxs[0] = 0;
Fstarttimes[0] = 0;
Fendtimes[0] = 0;
var FinCount = 0;
var tfid = false;
function tfreset()
{
	clearTimeout(tfid);
	delete Fbxs, Fids, Fstarttimes, Fendtimes;
	Fbxs = new Array();
	Fids = new Array();
	Fstarttimes = new Array();
	Fendtimes = new Array();
	Fanz = 0;
	Fids[0] = 0;
	Fbxs[0] = 0;
	Fstarttimes[0] = 0;
	Fendtimes[0] = 0;
}
function tf(){
  v = new Date();
  n = new Date();
  o = new Date();
  for (var Fcn = 1; Fcn <= Fanz; Fcn++) {
    ss = Fbxs[Fcn];
    //Prozente Anzeigen
    /*
    var alltime = Fendtimes[Fcn] - Fstarttimes[Fcn];
    var percent = 100 - Math.round(ss / alltime * 100);
    document.getElementById("bauF"+Fids[Fcn]).style.backgroundPosition = (100 - percent) * 145 + "px 0";
    */
    var lsID = "bauF"+Fids[Fcn];
    if(cubes[lsID] == undefined)
    {
        
        var alltime = Fendtimes[Fcn] - Fstarttimes[Fcn];
        var percent = 100 - Math.floor(ss / alltime * 100);
        cubes[lsID] = percent;
        var rate = 1 / (alltime / 100) / 10;
        
        cubes_i[lsID] = setInterval("animateCube('"+lsID+"',"+rate+")",100);
    }
    
    
    bx = ID("bauF" + Fids[Fcn] + "time");
    s = ss - Math.round((n.getTime() - v.getTime()) / 1000.);
    m = 0;
    h = 0;
    if (s <= 0) {
      if(s < 0)
      {	
      	reloadIt("F");
      	reloadIt("S");
      	reloadIt("V");
      }
      bx.innerHTML = "Abgeschlossen";
      ID("bauF"+Fids[Fcn]).style.background = "transparent";
      $('#bauF'+Fids[Fcn]+'time').fadeOut('slow');
    } else {
      if (s > 59) {
	m = Math.floor(s/60);
	s = s - m * 60;
      }
      if (m > 59) {
	h = Math.floor(m / 60);
	m = m - h * 60;
      }
      if (s < 10) {
	s = "0" + s;
      }
      if (m < 10) {
	m = "0" + m;
      }
      bx.innerHTML = rt(Fbxs[Fcn]);//": " + h + ":" + m + ":" + s;
    }
    	Fbxs[Fcn]--;
  }
  tfid = window.setTimeout("tf();", 999);
}
var activePage = "#bauliste";
function switchTo(t)
{
	if(t == "F")
	{
		$(activePage).hide();
		activePage = "#forschliste";
		$(activePage).show('fast');
	}
	else if(t == "B")
	{
		$(activePage).hide();
		activePage = "#bauliste";
		$(activePage).show('fast');		
	}
	else if(t == "S")
	{
		$(activePage).hide();
		activePage = "#schiffliste";
		$(activePage).show('fast');		
	}
	else if(t == "V")
	{
		$(activePage).hide();
		activePage = "#verteidigungsliste";
		$(activePage).show('fast');		
	}
	else if(t == "R")
	{
		$(activePage).hide();
		activePage = "#raketenliste";
		$(activePage).show('fast');
	}
}

/*
function rt(sek)
{
  h=Math.floor(sek/3600);
  m=Math.floor((sek-h*3600)/60);
  s=Math.floor((sek-h*3600-m*60));
  if(s<10) s='0'+s;
  if(m<10) m='0'+m;
  if(h<10) h='0'+h;
  return h+':'+m+':'+s;
}

*/



Sa = 0;
Sids = new Array();
Snames = new Array();
Sids[0] = 0;
Snames[0] = 0;
Sstop = 0;
function ts()
{
  for(var pos=0;pos<Sa;pos++)
  {
    time = document.getElementById('Stime'+pos);
    x=time.title;
    
    atime = document.getElementById('Sline'+pos);
    alltime = atime.title;
    //meine geliebte Canvas
    var lsID = "c_s"+Sids[pos];
    if(cubes[lsID] == undefined)
    {
        
        var percent = 100 - Math.floor(Number(x) / Number(alltime) * 100);
        cubes[lsID] = percent;
        var rate = 1 / (alltime / 100) / 10;
        
        cubes_i[lsID] = setInterval("animateCube('"+lsID+"',"+rate+")",100);
    }
    
    count = document.getElementById('Scount'+pos);
    c=count.title;
    stime=document.getElementById('Ssum_time');
    u=stime.title;
    if(document.getElementById('Sline'+pos).style.display!='none' && x>0)
    {
      x--;
      time.title=x;
      time.innerHTML=rt(x);
      u--;
      stime.title = u;
      stime.innerHTML = rt(u);
    }
    else if(c>=1)
    {
      c--;
      delete cubes[lsID];
      clearInterval(lsID);
      count.title = c;
      count.innerHTML = c+' '+Snames[pos];
      time.title=document.getElementById('Sline'+pos).title-1;
      time.innerHTML=rt(time.title);
      scount = document.getElementById('Ssum_count');
      d=scount.title;
      d--;
      scount.title=d;
      scount.innerHTML = d+' St&uuml;ck gesamt';
      n = document.getElementById('n'+Sids[pos]);
      anz = n.title;
      anz++;
      n.title = anz;
      n.innerHTML = '('+anz+' vorhanden)';
      if(c!=0)
      {
        u--;
        stime.title = u;
        stime.innerHTML = rt(u);
      }
    }
    if(c==0)
    {
      document.getElementById('Sline'+pos).style.display = 'none';
    }
    if(x>=0 && c>0) break;
    if(pos==(Sa-1)) Sstop=1;
  }
  if(Sstop!=1)
  tsid = window.setTimeout("ts();",999);
  else
  {
    document.getElementById('Sheadline').style.display = 'none';
    document.getElementById('Ssum_line1').style.display = 'none';
    document.getElementById('Ssum_line2').style.display = 'none';
  }
}

var tsid = false;
function tsreset()
{
    
    for(var i = 0;i<Sids.length;i++)
    {
        delete cubes["c_s"+Sids[i]];
        clearInterval(cubes_i["c_s"+Sids[i]]);
    }
	clearTimeout(tsid);
	delete Sids, Snames, Sstop, Sa;
	Sa = 0;
	Sids = new Array();
	Snames = new Array();
	Sids[0] = 0;
	Snames[0] = 0;
	Sstop = 0;
}
Va = 0;
Vids = new Array();
Vnames = new Array();
Vids[0] = 0;
Vnames[0] = 0;
Vstop = 0;
function tv()
{
  for(var pos=0;pos<Va;pos++)
  {
    time = document.getElementById('Vtime'+pos);
    x=time.title;
    
    atime = document.getElementById('Vline'+pos);
    alltime = atime.title;
    //meine geliebte Canvas
    var lsID = "c_v"+Vids[pos];
    if(cubes[lsID] == undefined)
    {
        
        var percent = 100 - Math.floor(Number(x) / Number(alltime) * 100);
        cubes[lsID] = percent;
        var rate = 1 / (alltime / 100) / 10;
        
        cubes_i[lsID] = setInterval("animateCube('"+lsID+"',"+rate+")",100);
    }
    
    count = document.getElementById('Vcount'+pos);
    c=count.title;
    stime=document.getElementById('Vsum_time');
    u=stime.title;
    if(document.getElementById('Vline'+pos).style.display!='none' && x>0)
    {
      x--;
      time.title=x;
      time.innerHTML=rt(x);
      u--;
      stime.title = u;
      stime.innerHTML = rt(u);
    }
    else if(c>=1)
    {
      c--;
      count.title = c;
      count.innerHTML = c+' '+Vnames[pos];
      time.title=document.getElementById('Sline'+pos).title-1;
      time.innerHTML=rt(time.title);
      scount = document.getElementById('Vsum_count');
      d=scount.title;
      d--;
      scount.title=d;
      scount.innerHTML = d+' St&uuml;ck gesamt';
      n = document.getElementById('n'+Vids[pos]);
      anz = n.title;
      anz++;
      n.title = anz;
      n.innerHTML = '('+anz+' vorhanden)';
      if(c!=0)
      {
        u--;
        stime.title = u;
        stime.innerHTML = rt(u);
      }
    }
    if(c==0)
    {
      document.getElementById('Vline'+pos).style.display = 'none';
    }
    if(x>=0 && c>0) break;
    if(pos==(Va-1)) Vstop=1;
  }
  if(Vstop!=1)
  tvid = window.setTimeout("tv();",999);
  else
  {
    document.getElementById('Vheadline').style.display = 'none';
    document.getElementById('Vsum_line1').style.display = 'none';
    document.getElementById('Vsum_line2').style.display = 'none';
  }
}

var tvid = false;
function tvreset()
{
    for(var i = 0;i<Vids.length;i++)
    {
        delete cubes["c_v"+Vids[i]];
        clearInterval(cubes_i["c_v"+Vids[i]]);
    }
	clearTimeout(tvid);
	delete Vids, Vnames, Vstop, Va;
	Va = 0;
	Vids = new Array();
	Vnames = new Array();
	Vids[0] = 0;
	Vnames[0] = 0;
	Vstop = 0;
}








function draw(id,points)
{
    //var c2 = document.getElementById("bau"+ids[id]).getContext('2d');
    try
    {
        var c2 = document.getElementById(id).getContext('2d');
    }
    catch(e)
    {
        return;   
    }
    c2.clearRect(0,0,145,145);
    c2.fillStyle = '#000';
    c2.beginPath();
    c2.moveTo(points[0], points[1]);
    for(var i = 0;i<points.length;i+=2)
    {
       c2.lineTo(points[i],points[i+1]);
    }
    c2.closePath();
    c2.fill();
}

function percent_cube($id,$percent)
{
    
    $size = 145;
    $hyp = Math.sqrt(2 * Math.pow($size,2)) / 2;
    $float_percent = $percent / 100;
    $h_size = $size / 2;
    $start_x = 0;
    $w_percent = 1 - $float_percent;
    $winkel = Math.PI * 2 * (0.5 + $w_percent);
    $x = Math.sin($winkel);
    $y = Math.cos($winkel);
    $x_k = ($h_size + ($x*($hyp)));
    $y_k = ($h_size + ($y*($hyp)));
    


    if($float_percent <= 0.125)
    {
        $x = $x_k;
        $y = 0;
        $points = new Array($start_x + 0,0, $start_x + $h_size,0, $start_x + $h_size,$h_size, $start_x + $x,$y, $start_x + $size,0, $start_x + $size,$size, $start_x + 0,$size);
    }
    else if($float_percent <= 0.375)
    {
        $x = $size;
        $y = $y_k;
        $points = new Array($start_x + 0,0,
                        $start_x + $h_size,0,
                        $start_x + $h_size,$h_size,
                        $start_x + $x,$y,
                        $start_x + $size,$size,
                        $start_x + 0,$size);
    }
    else if($float_percent <= 0.625)
    {
        $x = $x_k;
        $y = $size;
        $points = new Array($start_x + 0,0,
                        $start_x + $h_size,0,
                        $start_x + $h_size,$h_size,
                        $start_x + $x,$y,
                        $start_x + 0,$size);
    }
    else if($float_percent <= 0.875)
    {
        $x = 0;
        $y = $y_k;
        $points = new Array($start_x + 0,0,
                        $start_x + $h_size,0,
                        $start_x + $h_size,$h_size,
                        $start_x + $x,$y);
    }
    else
    {
        $x = $x_k;
        $y = 0;
        $points = new Array($x,0,
                        $start_x + $h_size,0,
                        $start_x + $h_size,$h_size);
    }

   
    draw($id,$points);
}



