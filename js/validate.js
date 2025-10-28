function ValidateString(data,type,error,fill,error2){
	var errortemp='';
	if(fill)errortemp=ValidateNull(data,error2);
	if(errortemp==''){
		if(type == "alphanumericspace"){
			var valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
			if(error=='')error=data.name+" should be in alphanumeric format [a-b 0-9 and space]";
		}else if(type == "alphanumeric"){
			var valid = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
			if(error=='')error=data.name+" should be in alphanumeric format [a-b 0-9]";
		}else if(type == "alphadash"){
			var valid = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-";
			if(error=='')error=data.name+" should be in alphanumeric format [a-b,0-9,-,_]";
		}else if(type == "alphanumericspacepunctuation"){
			var valid = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789., &%";
			if(error=='')error=data.name+" should be in alphanumeric format [a-b 0-9]";
		}else if(type == "alphabet"){
			var valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
			if(error=='')error=data.name+" should be in alphabet format [a-b]";
		}else if(type == "number"){
			var valid = "0123456789-";
			if(error=='')error=data.name+" should be in alphabet format [a-b]";
		}else if(type == "numbercomma"){
			var valid = "0123456789-,";
			if(error=='')error=data.name+" should be in number format [0-9] and decimal";
		}else if(type == "numbercommaperiod"){
			var valid = "0123456789-,.";
			if(error=='')error=data.name+" should be in number format [0-9] and decimal";
		}else if(type == "decimal"){
			var valid = "0123456789-.";
			if(error=='')error=data.name+" should be in decimal format";
		}else if(type == "numberdashparentspace"){
			var valid = "0123456789-)(+ ";
			if(error=='')error=data.name+" should be in number format and allowed dashes, parenthesis [0-9 - () and space]";
		}else if(type == "alphanumericdashspace"){
			var valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-";
			if(error=='')error=data.name+" should be in alphanumeric format [a-b 0-9  dashes and space]";
		}else if(type == "alphabetdashspace"){
			var valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-";
			if(error=='')error=data.name+" should be in alphanumeric format [a-b 0-9  dashes and space]";
		}else if(type == "alphabetdashspaceamp"){
			var valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz&-";
			if(error=='')error=data.name+" should be in alphanumeric format [a-b 0-9  dashes and space]";
		}else if(type == "all"){
			var valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-,.?):;\'(!&\"";
			if(error=='')error=data.name+" should be in format [a-b 0-9  dashes and space]";
		}else if(type == "allspecial"){
			var valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-,.?)@#$%^*+=/:;\'(!&\"";
			if(error=='')error=data.name+" should be in format [a-b 0-9  dashes and space]";
		}else if(type == "dateformatwithslash"){
			var valid = "0123456789/";
			if(error=='')error=data.name+" should be in format [numbers and slash]";
		}/**/

		for (var i=0; i<data.value.length; i++) {
//			var dofor = true;
			temp = "" + data.value.substring(i, i+1);
			if ((valid.indexOf(temp) == "-1") || (valid.indexOf(temp) == -1)) {
				errortemp+=' - '+error+'\n';
				//alert("typemasuk"+type+"  ERROR1 "+error+"  ERROR2 "+errortemp);
			}
		}
//		
//		if (dofor!=true) {
//			errortemp = error;
//		}
	}
	return errortemp;
}

function ValidateLength(data,minimum,maximum,error,fill,error2){
	var errortemp='';
	if(fill)errortemp=ValidateNull(data,error2);
	
	if(errortemp==''){
		if(error=='')error=data.name+" number of character is invalid";
		if(minimum==0 && maximum!=0) if(data.value.length>maximum) errortemp=' - '+error+'\n';
		if(minimum!=0 && maximum==0) if(data.value.length<minimum) errortemp=' - '+error+'\n';
		if(minimum!=0 && maximum!=0) if(data.value.length>maximum || data.value.length<minimum) errortemp=' - '+error+'\n';
		
		if(minimum!=0 && maximum!=0) {
			var count_char = data.value.length;
			var char_remaining = maximum - data.value.length;
			errortemp = errortemp.replace(/COUNT_CHAR/gi, count_char);
			errortemp = errortemp.replace(/CHAR_REMAINING/gi, char_remaining);
		}
	}
	return errortemp;
}
function ValidateWordLength(data,minimum,maximum,error,fill,error2){
	var errortemp='';
	if(fill)errortemp=ValidateNull(data,error2);
	
	if(errortemp==''){
		if(error=='')error=data.name+" number of character is invalid";
		var space = ' ';
		var countspace=1;
		for (var i=0; i<data.value.length; i++) {
			temp = "" + data.value.substring(i, i+1);
			var temps=space.indexOf(temp);
			if (temps > -1) {countspace++;}
		}
		if(minimum==0 && maximum!=0) if(countspace>maximum) errortemp=' - '+error+'\n';
		if(minimum!=0 && maximum==0) if(countspace<minimum) errortemp=' - '+error+'\n';
		if(minimum!=0 && maximum!=0) if(countspace>maximum || countspace<minimum) errortemp=' - '+error+'\n';
		
		if(minimum!=0 && maximum!=0) {
			var count_word = countspace;
			var word_remaining = maximum - countspace;
			errortemp = errortemp.replace(/COUNT_WORD/gi, count_word);
			errortemp = errortemp.replace(/WORD_REMAINING/gi, word_remaining);
		}
	}
	return errortemp;
}
function ValidateNullIfNull(data,data2,type,error){
	var errortemp='';
	if(type=='denynull'){
		if((data2.value=="") || (data2.value==0)){
			errortemp=ValidateNull(data,error);
			if(error=='')error=data.name+" has invalid format";
			if((data.value=="") || (data.value==0))
				errortemp =' - '+error+'\n';
		}
	}
	if(type=='allownull'){
		if((data.value!="") || (data.value!=0)){
			errortemp=ValidateNull(data2,error);
			if(error=='')error=data.name+" has invalid format";
			if((data2.value=="") || (data2.value==0))
				errortemp =' - '+error+'\n';
		}
	}
	return errortemp;
}

function ValidateNull(data,error){
	var errortemp='';
	if(error=='')error=data.name+" has invalid format";
	if((data.value=="") || (data.value==0))
		errortemp =' - '+error+'\n';
	return errortemp;
}

function ValidateNullByID(data,id,error) {
	var errortemp='';
	if(error=='')error=data.name+" has invalid format";
	var x=document.getElementById(id);
	if((x.value=="") || (x.value==0))
		errortemp =' - '+error+'\n';
	return errortemp;	
}


function ValidateNullByID2(tName,id,error) {
	var errortemp='';
	if(error=='')error=tName+" has invalid format";
	var x=document.getElementById(id);
	if((x.value=="") || (x.value==0))
		errortemp =' - '+error+'\n';
	return errortemp;	
}

function ValidateMustSelectIt(data,error){
	var errortemp='';
	if(error=='')error=data.name+" has invalid format";
	var x=document.getElementById(data);
	if(x.checked==false)
		errortemp =' - '+error+'\n';
	return errortemp;
}

function ValidateEmail(data,error,fill,error2){	
	var errortemp='';
	if(fill) errortemp=ValidateNull(data,error2);
	if(errortemp==''){
		if(error=='') error=data.name+" has invalid format";
		if (validateEmailFix(data.value,1,1) == false) {
			errortemp =' - '+error+'\n';
		}
	}			
	return errortemp;
}


function validateEmailFix(addr,man,db) {
	if (addr == '' && man) {
  	if (db) { //alert('email address is mandatory');
   		return false;
  	}
	}
	
	if (addr == '') return true;
	var invalidChars = '\/\'\\ ";:?!()[]\{\}^|';
	for (i=0; i<invalidChars.length; i++) {
   if (addr.indexOf(invalidChars.charAt(i),0) > -1) {
      if (db) {//alert('email address contains invalid characters');
      	return false;
    	}
   }
	}
	for (i=0; i<addr.length; i++) {
   if (addr.charCodeAt(i)>127) {
      if (db) {//alert("email address contains non ascii characters.");
      	return false;
    	}
   }
	}

	var atPos = addr.indexOf('@',0);
	if (atPos == -1) {
	   if (db) {//alert('email address must contain an @');
	   	return false;
	   }
	}
	if (atPos == 0) {
	   if (db) {//alert('email address must not start with @');
	   	return false;
	   }
	}
	if (addr.indexOf('@', atPos + 1) > - 1) {
	   if (db) {//alert('email address must contain only one @');
	   	return false;
	   }
	}
	if (addr.indexOf('.', atPos) == -1) {
	   if (db) { //alert('email address must contain a period in the domain name');
	    return false;
	   }
	}
	if (addr.indexOf('@.',0) != -1) {
	   if (db) {//alert('period must not immediately follow @ in email address');
	     return false;
	   }
	}
	if (addr.indexOf('.@',0) != -1){
	   if (db) {//alert('period must not immediately precede @ in email address');
	     return false;
	   }
	}
	if (addr.indexOf('..',0) != -1) {
	   if (db) {//alert('two periods must not be adjacent in email address');
	     return false;
	   }
	}
	var suffix = addr.substring(addr.lastIndexOf('.')+1);
	if (suffix.length != 2 && suffix != 'com' && suffix != 'net' && suffix != 'org' && suffix != 'edu' && suffix != 'int' && suffix != 'mil' && suffix != 'gov' & suffix != 'arpa' && suffix != 'biz' && suffix != 'aero' && suffix != 'name' && suffix != 'coop' && suffix != 'info' && suffix != 'pro' && suffix != 'museum') {
	  if (db) {//alert('invalid primary domain in email address');
	    return false;
	  }
	}
	return true;
}

function ValidateError(frmname,error){
	if (error){
		var	errortemp='';
		var brokenstring=error.split(' - ');		
		for(i=0;i<brokenstring.length;i++){
			for(j=0;j<brokenstring.length;j++){
					if(i!=j && brokenstring[i]==brokenstring[j])brokenstring[i]='';
			}				
			if(brokenstring[i]!='')errortemp+=' - '+brokenstring[i];
		}
		alert('The following error(s) occurred:\n'+errortemp);
	}else{
		var f=eval('document.'+frmname);
		f.submit();
	}
}

function ValidateMatch(password,repassword,error) {
	var errortemp='';
	if(password.value != repassword.value)
		errortemp =' - '+error+'\n';
	return errortemp;
}

function ValidateUnique(f,varUniquerField,error) {
	var newFields = new Array();
	var	unique = true;
	if (error=='') {
		error = 'Must unique!';
	}
	var errortemp='';
	for (x=1;x<=varUniquerField.length-1;x++) {
		fieldx = varUniquerField[x];
		if ((fieldx!='') && (fieldx!=0) && (fieldx!=null) && (fieldx!='each')) {
			valfield = f[fieldx].selectedIndex;
			if (valfield!=0) {
				for (y in newFields) {
					if (newFields[y] == valfield) {
						unique = false;
					}
				}
				newFields[x] = valfield;
			}
		}
	}
	if (unique == false) {
		errortemp = " - " + error + "\n";
	}
	return errortemp;
}

function ValidateMustFillChecked(f, varMFRequired, error, varMFElementID, varMFField, varMFFunction, varMFmaxrequired) {
	var count = 0;
	var checked = false;
	errortemp = "";
	for(n=1;n<=varMFElementID.length-1;n++) {
		var x=document.getElementById(varMFElementID[n]);
		if (x.checked == true) {
			count++;
			checked = true;
			field = varMFField[n];
			if (field!='') {
				par = varMFFunction[n];
				if (par!="") {
					for(w=1;w<=par.length-1;w++) {
						var arrPar=par[w].split("|");
						switch(arrPar[0]) {
							case "ValidateNull":
								errortemp = ValidateNull(f[field],arrPar[2]);
								break;
							case "ValidateNullByID":
								errortemp = ValidateNullByID(arrPar[1],arrPar[2],arrPar[3]);
								break;
							case "ValidateLength":
								break;
							case "ValidateString":
								errortemp = ValidateString(f[field],arrPar[2],arrPar[3],arrPar[4],arrPar[5]);
								break;
							case "ValidateMustSelectIt":
								errortemp = ValidateMustSelectIt(arrPar[1],arrPar[2]);
								break;
							case "ValidateMustSelectItMax":
								if((varMFmaxrequired) && count > varMFmaxrequired ){
									errortemp = ' - '+arrPar[1]+'\n';
								}
								break;
							default:
								errortemp = "";
						  	break;
						}
					}
				}
			}
		}
	}
	if ((checked == false) && (varMFRequired)) {
		if (error!="") {
			errortemp =' - '+error+'\n';
		}
	}
	return errortemp;
}

function ValidateValue(data,value1,value2,error,fill,error2){
	var errortemp='';
	if(fill)errortemp=ValidateString(data,'number',error2);
	
	if(errortemp=='' && data.value.length>0){
		if(error=='')error=data.name+" number of character is invalid";
		if(value1!=0 && value2!=0) if(data.value<value1 || data.value>value2) errortemp=' - '+error+'\n';
		if(value1!=0 && value2==0) if(data.value<value1) errortemp=' - '+error+'\n';
		if(value1==0 && value2!=0) if(data.value>value2) errortemp=' - '+error+'\n';
		if (errortemp!="") {
			errortemp =' - '+error+'\n';
		}
	}
	return errortemp;
}


function ValidateErrorSpecial(frmname,frmmethod,frmaction,error){
	if (error){
		var	errortemp='';
		var brokenstring=error.split(' - ');		
		for(i=0;i<brokenstring.length;i++){
			for(j=0;j<brokenstring.length;j++){
					if(i!=j && brokenstring[i]==brokenstring[j])brokenstring[i]='';
			}				
			if(brokenstring[i]!='')errortemp+=' - '+brokenstring[i];
		}
		alert('The following error(s) occurred:\n'+errortemp);
	}else{
		flag=ValidateCheckBox();
		if(flag==true){
			//alert(frmname+"="+frmmethod+"="+frmaction);
			var where_to= confirm("Are you sure want to update this order?");
			if (where_to== true){
				var f=eval('document.'+frmname);
				//f.method="'"+frmmethod+"'";
				f.method='post';
				f.action=frmaction;
				f.submit();
			}
		}
	}
}