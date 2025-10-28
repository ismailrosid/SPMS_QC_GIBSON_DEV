
function ConfirmDelete(url,name){
	var where_to= confirm("Are you sure to delete "+name+"?");
	if (where_to== true){
		window.location=url;
	}
}

function ConfirmAction(url,sMessage){
	var where_to= confirm(sMessage);
	if (where_to== true){
		window.location=url;
	}
}

function ConfirmEdit(url,name){
	//var where_to= confirm("Are You Sure Want to Edit "+name+"?");
	//if (where_to== true){
		window.location=url;
	//}
}
function OpenWindow(theURL,winName,features) {
  window.open(theURL,winName,features);
}

function HideContent(d) {
if(d.length < 1) { return; }
document.getElementById(d).style.display = "none";
}
function ShowContent(d) {
if(d.length < 1) { return; }
document.getElementById(d).style.display = "block";
}
function ReverseContentDisplay(d) {
if(d.length < 1) { return; }
if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
else { document.getElementById(d).style.display = "none"; }
}
function SwitchLayer(id) {
  var lyr = getElemRefs(id);
  if (lyr && lyr.css.display=="none") {
		lyr.css.display = "";	
	}else{
		lyr.css.display = "none";	
	}
}
function SwitchLayerSearch(id,imgurl) {
  var lyr = getElemRefs(id);
  if (lyr && lyr.css.display=="none") {
		lyr.css.display = "";	
	}else{
		lyr.css.display = "none";	
	}
	if (document.getElementById(id).style.display=="none") {
		var plusminus ="<img src=\""+imgurl+"images/search_max.jpg\" border=\"0\">";
	}else{
		var plusminus ="<img src=\""+imgurl+"images/switch_search.jpg\" border=\"0\">";
	}
	var divhref = document.getElementById('switch_search');
	var txt = "<a href=\"javascript:void(0);\" >"+plusminus+"</a>";
	divhref.innerHTML=txt;
}
function getElemRefs(id) {
	var el = (document.getElementById)? document.getElementById(id): (document.all)? document.all[id]: (document.layers)? document.layers[id]: null;
	if (el) el.css = (el.style)? el.style: el;
	return el;
}
function ValidateSendToFriend(f,data,error){
	//var flag=false ; 
	//alert(data);
	//alert("test "+document.theForm.email.value);
	//for (var i = 0; i < data.length; i++) {
		//if(data[i].value == ''){
			//flag=true ; 
		//}
	//}
	//if(flag)
		//alert(error);
	//else
		//f.submit();
}

function changeTab(sElementId){
	var mnuArray = new Array('mnu_profile','mnu_pkpa','mnu_pupa','mnu_magang','mnu_pendidikan','mnu_file');
	var btnArray = new Array('btn_profile','btn_pkpa','btn_pupa','btn_magang','btn_pendidikan','btn_file');
	for(var iCount=1; iCount<mnuArray.length+1; iCount++){
		if(mnuArray[iCount-1]==sElementId){
			document.getElementById(mnuArray[iCount-1]).style.visibility = "visible";
			document.getElementById(btnArray[iCount-1]).style.fontWeight = "bold";
		}else{
			document.getElementById(mnuArray[iCount-1]).style.visibility = "hidden";
			document.getElementById(btnArray[iCount-1]).style.fontWeight = "normal";
		}
	}
}