LTP = {};


LTP.winStart = window.onload;
window.onload = function(){
	LTP.startup();
	if(typeof(this.winStart)=="function"){ LTP.winStart();};
};

function addCookie(c_name,value,exdays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : ";expires="+exdate.toUTCString()+";path=/");
	document.cookie=c_name + "=" + c_value;
};


LTP.likeThisPage = function( ){	
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		  	ltpXHR=new XMLHttpRequest();
		} else {// code for IE6, IE5
		  	ltpXHR=new ActiveXObject("Microsoft.XMLHTTP");
		};
		
		var url = LTP.ajax_url+"?resID="+LTP.resID;
		ltpXHR.open("GET",url,true);
		ltpXHR.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		
		ltpXHR.onreadystatechange = function(){
			if(this.readyState == 4){
				LTP.receive();
			};
		};
		
		ltpXHR.send();
		
	return false;
};

LTP.receive = function(){
	console.log(ltpXHR.responseText);
	if(ltpXHR.responseText == "DONE"){
		alert("LIKED");
	};
};

	

