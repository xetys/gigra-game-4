
//scrippi AJAX objekt (c) 2008

function ID(id) {
	return document.getElementById(id);
}
function Ajax()
{
	this.action = "";
	this.method = "get";
	this.async = true;
	this.response = "";
	this.status = 0;
	this.fromForm = false;
	
	this.onrun = function ()
	{
		return;
	};
	this.onready = function ()
	{
		alert(this.response);
	};
	this.onerror = function ()
	{
		alert("Status" + this.status + "\nError");
	};
	this.run = AjaxRun;
	this.form = false;
	this.selectForm = selectForm;
	this.createFormArray = createFormArray;
	this.rbody = false;
	this.help = help;
}
function help() {
	alert("Willkommen zur Hilfe zum Scrippi AJAX Modul\n\nDiese Erweiterung stellt folgende Funktionen zur verfuegung:\n(F = Funktion, E =  Eigenschaft)\n    *action(E): Ziel der Anfrage\n    *method(E): post oder get(kleingeschrieben)\n    *async: true oder false\n    *onready(F): wird bei Erfolg ausgefuehrt\n    *onerror(F): wird bei Fehlern ausgefuehrt\n    *run(): Fuehrt den AjaxRequest aus\n    *createFormArray(F): Wandelt ein ArrayObjekt in den RequestBody um\n    *selectForm(F): Liest einen Form ein\n    *help(): Dieser Alert\n\n\n\n\nscrippi � 2008");
}
function selectForm(form)
{
	this.form = document.forms[form];
	this.fromForm = form;
}
function createFormArray(ar)
{
	var oArray = new Array();
	k = 0;
	for (i in ar)
	{
		oArray[k] = new Array();
		oArray[k]["name"] = i; 
		oArray[k]["value"] = ar[i];
		k++; 
	}
	
	this.rbody = encodeRequestBody(oArray);
}
function getRequestBody(oForm)
{
	var aParams = new Array();
	
	for (var i = 0; i < oForm.elements.length; i++)
	{
		var sParam = encodeURIComponent(oForm.elements[i].name);
		sParam += "=";
		sParam += encodeURIComponent(oForm.elements[i].value);
		
		aParams.push(sParam);
	}
	
	return aParams.join("&");
}
function encodeRequestBody(oArray)
{
	var aParams = new Array();
	
	for (var i = 0; i < oArray.length; i++)
	{
		var sParam = encodeURIComponent(oArray[i].name);
		sParam += "=";
		sParam += encodeURIComponent(oArray[i].value);
		
		aParams.push(sParam);
	}
	
	return aParams.join("&");	
}
function AjaxRun()
{
	this.onrun();
	var req;

	if(window.XMLHttpRequest) {
    try {
      req = new XMLHttpRequest();
    } catch(e) {
      req = false;
    }
  } else if(window.ActiveXObject) {
    try {
      req = new ActiveXObject("Microsoft.XMLHTTP");
    } catch(e) {
      req = false;
    }
  }
  
		
	if(this.method == "get" && (this.rbody || this.form))
	{
		if(this.rbody)
		{
			req.open(this.method, this.action + "?" + this.rbody, this.async);
		}
		else if(this.form)
		{
			this.rbody = getRequestBody(this.form);
			req.open(this.method, this.action + "?" + this.rbody, this.async);
		}
	}
	else
	{
		req.open(this.method, this.action, this.async);
	}
	var parent = this;
	req.onreadystatechange = function ()
	{
		if(req.readyState == 4) {
			parent.response = req.responseText;
			parent.status = req.status;
			if(req.status == 200)
			{
				parent.onready();
			}
			else
			{
				parent.onerror();
			}
		}
	}
	;
	if(this.method == "post" && (this.rbody || this.form))
	{
		if(this.form)
		{
			//alert(1);
			this.rbody = getRequestBody(this.form);
		}
		req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		req.send(this.rbody);
	}
	else
	{
		req.send(null);
	}
	delete req;
}
