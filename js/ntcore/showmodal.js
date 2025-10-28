	function showModal(sUrl, sTarget, sName, nWidth, nHeight) {
		if (sUrl==null) sUrl="{basesiteurl}";
		if (sName==null) sName="wndUnknown";
		if (nWidth==null) nWidth=800;
		if (nHeight==null) nHeight=600;
		
		sUrl+=sTarget;
		
		var nTop = (screen.width-nWidth)/2;
		var nLeft = (screen.height-nHeight)/2;
		if (nTop < 0) nTop = 0;
		if (nLeft < 0) nLeft = 0;
		
		if (window.showModalDialog) {
			// IE
			var sValue = window.showModalDialog(sUrl,sName,"dialogWidth:" + nWidth + "px;dialogHeight:"+nHeight+"px;");
			addOptionModal(sTarget, sValue);
		} else {
			// Non IE
			window.open(sUrl,sName,"height=" + nHeight + ",width=" + nWidth + ",top=" + nTop + ",left=" + nLeft + ",toolbar=no,directories=no,status=no,linemenubar=no,scrollbars=yes,resizable=no,modal=yes");
		}
	}
	
	function addOptionModal(sElementId, sValue) {
		var aValue = sValue.split(';');
		var oElement = document.getElementById(sElementId);
		
		var newOption = new Option(aValue[1], aValue[0]); //show, value
		oElement.options[oElement.options.length] = newOption;
		oElement.selectedIndex = oElement.options.length - 1;
	}