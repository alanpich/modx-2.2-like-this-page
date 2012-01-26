oL = window.onload;
window.onload = function(){
	onLTP();
	if(typeof(oL) == 'function'){
		oL();
	}
};


function addCookie(c_name,value,exdays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : ";expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
};



