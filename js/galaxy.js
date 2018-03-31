/**
* jQuery solar system by will jessup
* will -at- willjessup.com or AIM "xdionysisx"
* This is a demo of jQuery and a bit of fun javaScript - released under creative commons
* thanks john for choosing awesome planet images
* IE fixes by Krzysztof Finowicki
* Update - pluto is no longer a planet , oops. 
*/

//kff: see my comments

//kff: declare global variables first
var de;
var w;
var h;

//kff: declare and define constants and initial values for other variables
var xm=0;                       
var ym=0;
var ay=20;
var sin=Math.sin(ay*Math.PI/180);
var cos=Math.cos(ay*Math.PI/180); 
var angle = 45;
var k=20;

var elem = [];

//default distance of "camera" from coordate grid
var camDist = 350;

//speed of rotation
var scale = .3;

//vertical angle
var vpsi = -59;

var fontScale = .0005;

//kff: declare and define default value of psi
var psi = 1.0;

//kff: essential calculations should be done on document ready, not earlier !!! (this is what this function is for!)

$(document).ready(function(){ 

  de = document.documentElement;
  w = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
  h = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
//disk angle (between 0 and 90). 0 is flat w/ no Y movement and 90 will move in a perfect circle.
  psi = Math.abs(Math.sin((ym-h*.5)/h*Math.PI));
});

// not using $() here to recude # of query calls since onmousemove gets called so often that it needs optimization

document.onmousemove = function(e) { return;
	if (window.event) e = window.event;
	xm = (e.x || e.clientX);
        ym = (e.y || e.clientY);

//kff: I'm not sure if it is necessary to repeat these calculations on each mousemove
// maybe in onresize (if you want to optimize)? but I will not change it
	var de = document.documentElement;
        var w = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
        var h = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;


        /**
	*set the amplitude of the function equal to height of the browser, shifted by 180 degrees (half browser height).
	*returns value between 90 and 0 degrees for any mouse movement on the screen
	*/
        psi=Math.sin((ym-h*.5)/h*Math.PI);
        vpsi= Math.sin((ym-h*.5)/h*Math.PI)*90;
	sin=Math.sin((-xm+w*.5)/w*Math.PI);
	cos=Math.cos((-xm+w*.5)/w*Math.PI);
};
$.fn.cartesianToCircular = function() {

    //with each link element, convert it and store it in elements
    for(var i=0; i<this.length; i++) {
                      if ( !elem[i] ) elem[i] = {};
                      var curLink = this[i].href;
                      var queryString = curLink.replace(/^[^\?]+\??/,'');
                      params = parseQuery( queryString );

                      x = params['x'];
                      y = params['y'];
                      r = parseInt(Math.abs(Math.sqrt(x*x+y*y)));
                      
                      //angle from 0 between -pi/pi  , need to add 180 degrees for anything in 2,3 quadrant
                      if(x<0)
                         theta= (Math.atan(y/x)*180/Math.PI)+180;
                      else
                         theta= (Math.atan(y/x)*180/Math.PI);

                      //circular coords
                      elem[i].theta = theta;
                      elem[i].r = r;
    }
};
$.fn.rotate = function () {
        for(var i=0; i<this.length; i++) {
                      if (!elem[i]) elem[i] = {};

                      //angle of the system
                      angle += sin*scale;

                      //the angle of each elem + their theta offset
                      elem[i].angle = (angle + elem[i].theta)*(400/(elem[i].r^2));


                      X = elem[i].r*Math.cos(elem[i].angle*Math.PI/180);
                      Y = elem[i].r*Math.sin(elem[i].angle*Math.PI/180)*psi;

                      elem[i].dist = parseInt(elem[i].r*Math.cos(vpsi*Math.PI/180)*Math.sin(elem[i].angle*Math.PI/180)+camDist);

                      //scale factor for sizing
                      size = (elem[i].dist - 20)/200;
                    
                      //was using jquery to set the CSS like this, but the following is faster
		      //$(this[i]).css({left:  X + "px", top:  -Y + "px", fontSize: size + "em", opacity: (size*10)/30 });

                          this[i].style.top = -Y + "px";
                          this[i].style.left = X + "px";
                          this[i].style.fontSize = size + "em";
                          this[i].style.opacity  = (size*10)/5;
                          this[i].style.zIndex  = elem[i].dist;
                      document.body.style.backgroundPosition = angle*10 + "px " + vpsi*2 + "px";

	}
};
var items;
function run(){
        items.rotate();
        //$("#angle").html("r angle =" + parseInt(angle));
        //$("#vangle").html("v angle =" + parseInt(vpsi));
        //$("#elm1d").html("elm2 dist =" + elem[1].dist);
        //$("#camDist").html("camera Distance =" + camDist);
	setTimeout("run()", 16);
}

$(document).ready(function(){
        items = $("#links a");
        items.cartesianToCircular();
	run();

});

  function parseQuery ( query ) {
     var Params = new Object ();
     if ( ! query ) return Params; // return empty object
     var Pairs = query.split(/[;&]/);
     for ( var i = 0; i < Pairs.length; i++ ) {
        var KeyVal = Pairs[i].split('=');
        if ( ! KeyVal || KeyVal.length != 2 ) continue;
        var key = unescape( KeyVal[0] );
        var val = unescape( KeyVal[1] );
        val = val.replace(/\+/g, ' ');
        Params[key] = val;
     }
     return Params;
  }
