stop=0;
function rt_f(sek)
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
global_counter = 0;
function t()
{
    global_counter++;
    for(var pos=1;pos<=anz;pos++)
    {
        time = document.getElementById('bxx'+pos);
        x   =  time.title;
        if(document.getElementById('bxxx'+pos).style.display!='none' && x>0)
        {
            x--;
            time.title=x;
            time.innerHTML=rt_f(x);
        }
        else if(document.getElementById('bxxx'+pos).style.display!='none')
        {
            document.getElementById('bxxx'+pos).style.display = 'none';
        }
        if(pos==anz && x<0) stop=1;
    }
    if(stop!=1)
        window.setTimeout("t();",999);
    else
    {
        document.getElementById('ev_head').style.display = 'none';
    }
}

function fF_expand()
{
	$("#flottenbar").animate( { height: "100%", zIndex: 500000, opacity:1},1000);
	$("#navigation-right").hide(500);
}
function fF_inpand()
{
	$("#flottenbar").animate( { height: 170, zIndex:0, opacity:0.7} , 1000);
	$("#navigation-right").show(500);
}