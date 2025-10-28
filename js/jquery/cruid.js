function _doPost(sElementId, sValue, sSortAction, sFormId, sFormAction, bMustSelect, sConfirm) {
	if (bMustSelect==true && _getFirstCheckedRow()=="") {
		alert("Please select the item for execution!");
		//return false;
		return;
	}
	if (sConfirm!=null) {
		if (confirm(sConfirm)==false ) {
			//return false;
			return;
		}
	}
	if (sElementId!=null || sValue!=null) $("#" + sElementId).val(sValue);
	if (sSortAction!=null) $("#" + sSortAction).val(1);
	if (sFormId==null) sFormId="frmSearch";
	if (sFormAction!=null) $("#" + sFormId).attr('action', sFormAction);
	$("#" + sFormId).submit();
	//return true;
}

function _doRedirect(sUrl) {
	if (_getFirstCheckedRow()==false) {
		alert("Please select one item!");
		//return true;
	}else{
		document.location.href=sUrl;
		//return true;
	}
	
}

function _getSingleCheckedRow(sCheckBoxName) {	
	if(sCheckBoxName==null) sCheckBoxName='uIdRow';
	var sReturnValue=false;
	var aValue= new Array("");
	var nCounter=0;
	$("input[name='"+sCheckBoxName+"[]']:checked").each(function () {
		aValue[nCounter]=$(this).val();
		nCounter++;
	});
	if(nCounter==1){
		sReturnValue=aValue[0];
	}else{		
		sReturnValue=false;
	}
	return sReturnValue;
}

function _getCheckedRowValue(sCheckBoxName, sValue, sElIdForReturn) {
	if(!sCheckBoxName) sCheckBoxName='uIdRow';
	var nCounter=0;
	var nIndex=0;
	var sReturnValue=false;
	$("input[name='"+sCheckBoxName+"[]']").each(function () {
		if($(this).val()==sValue){
			nIndex=nCounter;
			sReturnValue=$("#"+ sElIdForReturn + nIndex).val();
		}
		nCounter++;
	});
	return sReturnValue;		
}


function _getFirstCheckedRow() {
	var aValue= new Array("");
	var nCounter=0;
	$("input[name='uIdRow[]']:checked").each(function () {
		//aValue[nCounter]=$(this).attr('id');
		aValue[nCounter]=$(this).val();
		nCounter++;
	});
	return aValue[0];
}

function _getLastCheckedRow(sCheckBoxName) {
	var aValue2= new Array("");
	var nCounter2=0;
	$("input[name='"+sCheckBoxName+"[]']:checked").each(function () {
		aValue2[nCounter2]=$(this).val();
		nCounter2++;
	});
	return aValue2[nCounter2];
}

function _setAllChecked(sParam, sCheckBoxName){
	if(!sCheckBoxName) sCheckBoxName='uIdRow';
	if(sParam.checked==true)
		$("input[name='"+sCheckBoxName+"[]']").attr('checked', true);
	else
		$("input[name='"+sCheckBoxName+"[]']").attr('checked', false);
}

function _setAllUnchecked(sCheckBoxName){
	if(!sCheckBoxName) sCheckBoxName='uIdRow';
	$("input[name='"+sCheckBoxName+"[]']").attr('checked', false)
}