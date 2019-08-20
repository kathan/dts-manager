//==== Framework Base====
var debugWin;
function debug(str)
{
	if (!debugWin)
	{
		debugWin = createPopup();
	}
	window.innerHTML += debugWin;
}

function get(id)
{//Shortcut for getElementById
	return document.getElementById(id);
}

function $(id)
{//Shortcut for getElementById
	return document.getElementById(id);
}

function instanceOf(object, constructor)
{ 
	//No worky?
	while (object != null) {
		if (object == constructor.prototype) 
			return true; 
		object = object.__proto__; 
	} 
	return false; 
}

function extend(descendant, parent) {
	//Working
	//Credit to Troels Knak-Nielsen
	//http://www.kyberfabrikken.dk
	
    var sConstructor = parent.toString();//Get the parent constructor as a string
    var aMatch = sConstructor.match( /\s*function (.*)\(/ );//checks to make sure it's a function
    if ( aMatch != null ) { descendant.prototype[aMatch[1]] = parent; }//adds the parent constructor to the descendent
    for (var m in parent.prototype) {
    	/*loops thru all of the parent class members
    	and adds them to the descendant class. brilliant*/
        descendant.prototype[m] = parent.prototype[m];
    }
}

function cast(descendant, parent, call_constructor) {
	/*This is a modified version of Troels Knak-Nielsen's extend.
	Instead of adding members of a parent class to an entire subclass,
	this function adds members of a parent class (parent) to a specific instance (descendent)*/
	
	if(call_constructor == null)
	{
		call_constructor = true;
	}
	if(descendant[parent])
	{
		var call_constructor = false;
	}else{
		var call_constructor = true;
	}
    var sConstructor = parent.toString();//Get the parent constructor as a string
    //debug(sConstructor);
    var aMatch = sConstructor.match( /\s*function (.*)\(/ );//checks to make sure it's a function
   
    if ( aMatch != null ) { descendant[aMatch[1]] = parent; }//adds the parent constructor to the descendent
    for (var m in parent.prototype) {
    	/*loops thru all of the parent members
    	and adds them to the descendant object. brilliant*/
    	if(!descendant[m])
    	{
        	descendant[m] = parent.prototype[m];
    	}
    }
    //alert(descendant.propertiesToString());
    if (call_constructor)
    {
		parent.call(descendant);//Call the parent constructor
	}
}

function include(url, target)
{
	//check to see if it has been loaded
	var cur_inc = $(url);

	if (cur_inc)
	{
		return false;
	}else{
			
		if (!target)
		{
			target = document.getElementsByTagName('head').item(0);
		}
			
		/*var incEle = document.createElement("SCRIPT");
		target.appendChild(incEle);
		incEle.id = url;
		incEle.language = "javascript";*/
		var script = getFromURL(url);
		try{
			//Yet another workaround for IE
			document.writeln("\n<script id='"+url+"' type='text/javascript'>\n"+script+"\n</script>\n");		
		}catch(e){
			alert("src failed");
    	}	
    	
	}
}

function add_script(script)
{
	document.writeln("\n<script type='text/javascript'>\n"+script+"\n</script>\n");
}

function createID(len)
{
	// String.createID Method v1.0
	// by Jonas Galvez (jonas@onrelease.org)
    var r, hex = new Date().getTime().toString();
    var id;
    for(var j = hex.length, id = ""; j--;)
    {
        r = Math.floor((Math.random()*36)).toString(36);
        if(Math.random() > 0.5) r = r.toUpperCase();
        id += hex.charAt(j) + r;
        
    };
   
    this.id = id;
    return id;
}

function propertiesToString (obj, delimiter)
{
	if(!delimiter)
	{
		var delimiter = ";";
	}
	var ret;
	if(obj)
	{
		ret = "Object " + (obj.id||obj.name||"undefined") + " is [\n";
		
		for (var prop in obj)
		{
			
			if(obj[prop])
			{
			
		   		//if(obj[prop].toString().match(/\[object /))
		   		//{
		   			//ret += propertiesToString(obj[prop], delimiter+"   ");
		   		//}else{
			      ret += "  " + prop + ": " + obj[prop] + delimiter +"\n";
			      
		   		//}
	   		}
	   		
	   	}
	   	//window.status = "1";
	   	return ret + "]";
	}else{
		return "No Object";
	}
}

String.prototype.random = function()
{
    var _this = this.split("");
    for(var str = "", r; _this.length;) {
        str += _this[r = Math.floor(Math.random() * _this.length)];
        _this.splice(r, 1);
    };
    
    return str;
}

function getAbsPos( oId, tl )
{
	var o = ( typeof oId == 'String' ) ? document.getElementById( oId ) : oId;
	var val = 0;
	while ( o.nodeName != "BODY" )
	
	{
		val += parseInt( ( tl == 'top' ) ? o.offsetTop : o.offsetLeft );
		if(o.style)
		{
			if(o.style.borderLeftWidth)
			{
				val += parseInt(o.style.borderLeftWidth);
			}
			if(o.style.borderWidth)
			{
				val += parseInt(o.style.borderWidth);
			}
			if(o.style.marginLeft)
			{
				val += parseInt(o.style.marginLeft);
			}
			if(o.style.paddingLeft)
			{
				val += parseInt(o.style.paddingLeft);
			}
		}
		o = o.offsetParent;
		//o = o.parentNode;
	}
	return val;
}

function getPosition(e) {
    e = e || window.event;
    var cursor = {x:0, y:0};
    if (e.pageX || e.pageY) {
        cursor.x = e.pageX;
        cursor.y = e.pageY;
    } 
    else {
        cursor.x = e.clientX + 
            (document.documentElement.scrollLeft || 
            document.body.scrollLeft) - 
            document.documentElement.clientLeft;
        cursor.y = e.clientY + 
            (document.documentElement.scrollTop || 
            document.body.scrollTop) - 
            document.documentElement.clientTop;
    }
    return cursor;
}
var isSafari = navigator.userAgent.indexOf('Safari/') != -1;

function getFromURL(url)
{
	var req = false;
	
	// branch for native XMLHttpRequest object
	if(window.XMLHttpRequest)
	{
		try
		{
			req = new XMLHttpRequest();
		} catch(e) {
			req = false;
        }
	// branch for IE/Windows ActiveX version
	} else if(window.ActiveXObject) {
		try
		{
			req = new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try
			{
				req = new ActiveXObject("Microsoft.XMLHTTP");
			} catch(e) {
				req = false;
			}
		}
	}
		
	if(req)
	{
		req.open("GET", url, false);
		req.send("");
		if(req.status == 200)
		{
			try
			{
				return req.responseText;
			}catch(e){
				alert("src failed");
    		}
    	}else{
    		return req.status;
    	}
	}
}

//==== End Framework Base ====