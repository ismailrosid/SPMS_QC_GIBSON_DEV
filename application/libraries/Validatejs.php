<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Validatejs{
	var $error; 	
	var $js; 
	var $functionname;
	
	function Validatejs($functionname=''){
		$this->error='';
		$this->js='';
		$this->functionname = $functionname;
	}
		
	function ValidateString($field,$type='alphanumeric',$error='',$fill=false,$error2=''){
		$data=(isset($_POST[$field]))?$_POST[$field]:'';
		$errortemp='';
		if($fill){
			$errortemp=$this->ValidateNull($field,$error2);
			$this->js.="error+=ValidateString(f.$field,'$type','$error',$fill,'$error2'); \n";
		}else{
			$this->js.="error+=ValidateString(f.$field,'$type','$error'); \n";
		}
		if($errortemp==''){
			if($type == "alphanumericspace"){
				$valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
				if($error=='')$error=$data." should be in alphanumeric format [a-b 0-9 and space]";
			}else if($type == "alphanumericspacepunctuation"){
				$valid = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789 ;.,$%&";
				if($error=='')$error=$data." should be in alphanumeric format [a-b 0-9]";
			}else if($type == "alphanumeric"){
				$valid = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
				if($error=='')$error=$data." should be in alphanumeric format [a-b 0-9]";
			}else if($type == "alphadash"){
				$valid = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-";
				if($error=='')$error=$data." should be in alphanumeric format [a-b,0-9,-,_]";
			}else if($type == "alphabet"){
				$valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
				if($error=='')$error=$data." should be in alphabet format [a-b]";
			}else if($type == "number"){
				$valid = "0123456789-";
				if($error=='')$error=$data." should be in number format [0-9]";
			}else if($type == "numbercomma"){
				$valid = "0123456789-,";
				if($error=='')$error=$data." should be in number format [0-9] and decimal";
			}else if($type == "numbercommaperiod"){
				$valid = "0123456789-,.";
				if($error=='')$error=$data." should be in number format [0-9] and decimal";
			}else if($type == "decimal"){  //kaya nya uda ga di pake, tapi dibiarin dulu
				$valid = "0123456789-.";
				if($error=='')$error=$data." should be in decimal format";
			}else if($type == "numberdashparentspace"){
				$valid = "0123456789-)(+ ";
				if($error=='')$error=$data." should be in number format and allowed dashes, parenthesis [0-9 - () and space]";
			}else if($type == "alphanumericdashspace"){
				$valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-";
				if($error=='')$error=$data." should be in alphanumeric format [a-b 0-9 dashes and space]";
			}else if($type == "alphabetdashspace"){
				$valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-";
				if($error=='')$error=$data." should be in alphanumeric format [a-b 0-9 dashes and space]";
			}else if($type == "alphabetdashspaceamp"){
				$valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz&-";
				if($error=='')$error=$data." should be in alphanumeric format [a-b 0-9 dashes and space]";
			}else if($type == "all"){
				$valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-,.?):;\'(!&\"";
				if($error=='')$error=$data." should be in format [a-b 0-9 dashes and space]";
			}else if($type == "allspecial"){
				$valid = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-,.?)@#$%^*+=/:;\'(!&\"";
				if($error=='')$error=$data." should be in format [a-b 0-9 dashes and space]";
			}else if($type == "dateformatwithslash"){
				$valid = "0123456789/";
				if($error=='')$error=$data." should be in format [numbers and slash]";
			}/**/
			
			for ($i=0; $i<strlen($data); $i++) {
				$temp = substr($data,$i,1);
				if (strpos($valid,$temp)===false) {$errortemp=' - '.$error.'<br>';};
			}
		}
		$this->error.=$errortemp;
	}
	
	function ValidateLength($field,$minimum=0,$maximum=0,$error='',$fill=false,$error2=''){
		$data=(isset($_POST[$field]))?$_POST[$field]:'';
		$errortemp='';
		if($fill)$errortemp=$this->ValidateNull($field,$error2);
		if($errortemp=='' && strlen($data)>0){
			if($error=='')$error=$data." number of character is invalid";
			if($minimum==0 && $maximum!=0) if(strlen($data)>$maximum) $errortemp=' - '.$error.'<br>';
			if($minimum!=0 && $maximum==0) if(strlen($data)<$minimum) $errortemp=' - '.$error.'<br>';
			if($minimum!=0 && $maximum!=0) if(strlen($data)>$maximum || strlen($data)<$minimum) $errortemp=' - '.$error.'<br>';
		}
		$this->error.=$errortemp;
		$fill=($fill ? 'true' : 'false');
		$this->js.="error+=ValidateLength(f.$field,$minimum,$maximum,'$error',$fill,'$error2'); \n";
	}	
	
	function ValidateWordLength($field,$minimum=0,$maximum=0,$error='',$fill=false,$error2=''){
		$data=(isset($_POST[$field]))?$_POST[$field]:'';
		$errortemp='';
		if($fill)$errortemp=$this->ValidateNull($field,$error2);
		if($errortemp=='' && strlen($data)>0){
			if($error=='')$error=$data." number of character is invalid";
			$space = ' ';
			$countspace=1;
			for ($i=0; $i<strlen($data); $i++) {
				$temp = substr($data,$i,1);
				$temps = strpos($space,$temp);
				if ($temps > -1) {$countspace++;}
			}
			if($minimum==0 && $maximum!=0) if($countspace>$maximum) $errortemp=' - '.$error.'<br>';
			if($minimum!=0 && $maximum==0) if($countspace<$minimum) $errortemp=' - '.$error.'<br>';
			if($minimum!=0 && $maximum!=0) if($countspace>$maximum || $countspace<$minimum) $errortemp=' - '.$error.'<br>';
		}
		$this->error.=$errortemp;
		$fill=($fill ? 'true' : 'false');
		$this->js.="error+=ValidateWordLength(f.$field,$minimum,$maximum,'$error',$fill,'$error2'); \n";
	}	
	
	function ValidateMatch($password,$repassword,$error) {
		$pass1=(isset($_POST[$password]))?$_POST[$password]:'';
		$pass2=(isset($_POST[$repassword]))?$_POST[$repassword]:'';
		$errortemp='';
		if($pass1!=$pass2)
			$errortemp =' - '.$error.'<br>';
		$this->error.=$errortemp;
		$this->js.="error+=ValidateMatch(f.$password,f.$repassword,'$error'); \n";
	}
	
	function ValidateNullByID($field,$id,$error='') {
		$data=(isset($_POST[$field[$id]]))?$_POST[$field[$id]]:'';
		$errortemp='';
		if($error=='')$error=$field." is empty";
		if($data=="")
			$errortemp =' - '.$error.'<br>';
		$this->error.=$errortemp;
		$this->js.="error+=ValidateNull($field,$id,'$error'); \n";
		return $errortemp;
	}

	function ValidateNullIfNull($field,$field2,$type='allownull',$error=''){
		$data=(isset($_POST[$field]))?$_POST[$field]:'';
		$data2=(isset($_POST[$field2]))?$_POST[$field2]:'';
		$errortemp='';
		if($type=='denynull'){
			if($data2=="" || $data2==0){
				if($data=="")$errortemp =' - '.$error.'<br>';
			}
		}
		if($type=='allownull'){
			if($data!="" || $data!=0){
				if($error=='')$error=$field." is empty";
				if($data2=="" || $data2==0)
					$errortemp =' - '.$error.'<br>';
			}
		}
		$this->error.=$errortemp;
		$this->js.="error+=ValidateNullIfNull(f.$field,f.$field2,'$type','$error'); \n";
		return $errortemp;
	}
	
	function ValidateNull($field,$error=''){
		$data=(isset($_POST[$field]))?$_POST[$field]:'';
		$errortemp='';
		if($error=='')$error=$field." is empty";
		if($data=="")
			$errortemp =' - '.$error.'<br>';
		$this->error.=$errortemp;
		$this->js.="error+=ValidateNull(f.$field,'$error'); \n";
		return $errortemp;
	}

	function ValidateNullByID2($name,$id,$error=''){
		$data=(isset($_POST[$id]))?$_POST[$id]:'';
		$errortemp='';
		if($error=='')$error=$field." is empty";
		if($data=="")
			$errortemp =' - '.$error.'<br>';
		$this->error.=$errortemp;
		$this->js.="error+=ValidateNullByID2('$name','$id','$error'); \n";
		return $errortemp;
	}
	
	// for input type "checkbox", getting by id
	function ValidateMustSelectIt($field,$data,$error='') {
		$data=(isset($_POST[$field]))?$_POST[$field]:'';
		$errortemp='';
		if($error=='')$error=$field." is empty";
		if(($data=="") || ($data==0) || (empty($data))) {
			$errortemp =' - '.$error.'<br>';
		}
		$this->error.=$errortemp;
		$this->js.="error+=ValidateMustSelectIt(f.$data,'$error'); \n";
		return $errortemp;
	}
	
	function ValidateEmail($field,$error='',$fill=false,$error2=''){	
		$data=(isset($_POST[$field]))?$_POST[$field]:'';
		$errortemp='';		
		if($fill)$errortemp=$this->ValidateNull($field,$error2);
		if($errortemp=='' && strlen($data)>0){
			if($error=='')$error=$field." has invalid email format";
			$ak=intval(strpos($data,"@"));
			$tt=intval(strpos($data,"."));
			if ($ak==0||$tt==0||$ak==-1||$tt==-1||$tt==$ak+1){
				$errortemp =' - '.$error.'<br>';
			}
			$this->error.=$errortemp;
		}
		$fill=($fill ? 'true' : 'false');
		$this->js.="error+=ValidateEmail(f.$field,'$error',$fill,'$error2'); \n";
		return $errortemp;
	}
	
	function ValidateError($prefix='Error Occured<br>',$suffix=''){
		if (trim($this->error)!=''){
			$this->error=$prefix.$this->error.$suffix;
		}
	}
	
	function ValidateUnique($fields,$error) {
		$f = array();
		$unique = true;
		$x = 0;
		$this->js.="var varUniqueField = new Array(); \n";
		foreach ($fields as $field) {
			$x++;
			if (!empty($_POST[$field])) {
				if (in_array($field, $f)) {
					$unique = false;
				}
				$f[] = $_POST[$field];
			}
			$this->js.="varUniqueField[$x] = '$field'; \n";
		}
		if (!$unique) {
			$this->error.=$error;
		}
		$this->js.="error+=ValidateUnique(f, varUniqueField, \"".$error."\"); \n";
	}
	
	function execValidateJS($frmname){
		if($this->js){
			if (!empty($this->functionname)) {
				$this->js="function ". $this->functionname ."(frmname){ \n"
								."var error=''; \n"
								."var f=document.forms[frmname];\n".$this->js
								.'ValidateError(frmname,error);'
								."}";
			} else {
				$this->js="function ValidateForm(frmname){ \n"
								."var error=''; \n"
								."var f=document.forms[frmname];\n".$this->js
								."ValidateError(frmname,error);"
								."}";
			}
			
		}
		return $this->js;
	}
	
	function ValidateJSSpecial($frmname,$frmmethod,$frmaction){
		if($this->js){
			if (!empty($this->functionname)) {
				$this->js="function ". $this->functionname ."(frmname){ \n"
								."var error=''; \n"
								."var f=document.forms[frmname];\n".$this->js
								.'ValidateError(frmname,error);'
								."}";
			} else {
				$this->js="function ValidateForm(frmname){ \n"
								."var error=''; \n"
								."var f=document.forms[frmname];\n".$this->js
								."ValidateErrorSpecial(frmname,'".$frmmethod."','".$frmaction."',error);\n"
								."return error;\n"
								."}";
			}
			
		}
		return $this->js;
	}

	/*
	How to call:
	$button_name = "length_time_owner_to_stay";
	$required = 1;
	$error = "you must select one of leng time owner to stay";
	$button_id = array("ltots01","ltots02","ltots03","ltots04","ltots05");
	$field = array("","","","","length_time_owner_to_stay_other_content");
	$fungsi_other_content = array("ValidateNull|'Error layah...'", "ValidateLength|f.length_time_owner_to_stay_other_content|10|100|'You must enter 10-100 char!'|false|''");
	$function = array(array(),array(),array(),array(),$fungsi_other_content);
	ValidateMustFillChecked($button_name, $required = false, $error, $button_id, $field = array(), $function = array())
	*/
	// for checking various possible if one field was filled or checked or selected, than have to check the other one
	function ValidateMustFillChecked($button_name, $required = false, $errorx, $button_id, $field = array(), $function = array(), $maxrequired=false) {
		if (!empty($_POST[$button_name])) {
			$button = $_POST[$button_name];
			if (!empty($field[$button])) {
				if (!empty($function[$button])) {
					if (is_array($function[$button])) {
						$par = explode(',',$function[$button][0]);
					} else {
						$par = explode(',',$function[$button]);
					}
					
					$func = $par[0];
					switch(strtolower($func)) {
						case "validatestring":
							ValidateString($par[1],$par[2],$par[3],$par[4],$par[5]);
							break;
						case "validatelength":
							ValidateLength($par[1],$par[2],$par[3],$par[4],$par[5],$par[6]);
							break;
					}
				}
			}
		}
		
		$req = ($required)?'1':'0';
		$this->js.="varMFRequired = '$req'; \n";
		$this->js.="errorx = '$errorx'; \n";
		$this->js.="var varMFElementID = new Array(); \n";
		$this->js.="var varMFField = new Array(); \n";
		$this->js.="var varMFFunction = new Array(); \n";
		$maxreq = (($maxrequired) && $maxrequired>0)?$maxrequired:'';
		$this->js.="var varMFmaxrequired = '$maxreq' \n";
		$n = 0;
		foreach($button_id as $button) {
			$n++;
			$m = $n-1;
			$this->js.="var func 						= new Array(); \n";
			$this->js.="varMFElementID[$n] 	= '$button'; \n";
			if (!empty($field[$m])) {
				$this->js.="varMFField[$n] 			= '$field[$m]'; \n";
			} else {
				$this->js.="varMFField[$n] 			= ''; \n";
			}

			$x = 0;
			$func = $function[$m];
			if (is_array($func)) {
				foreach ($func as $f) {
					$x++;
					$this->js.="func[$x] 			= '$f'; \n";
				}
			}
			$this->js.="varMFFunction[$n] 	= func; \n\n\n";
		}
		$this->js.="error+=ValidateMustFillChecked(f, varMFRequired, errorx, varMFElementID, varMFField, varMFFunction, varMFmaxrequired); \n";
		if ((empty($_POST[$button_name])) && ($required)) {
			$this->error.=$errorx;
		}
	}

	function ValidateValue($field,$value1=0,$value2=0,$error='',$fill=false,$error2=''){
		$data=(isset($_POST[$field]))?$_POST[$field]:'';
		$errortemp='';
		if($fill)$errortemp=$this->ValidateString($field,'number',$error2);
		
		if($errortemp=='' && strlen($data)>0){
			if($error=='')$error=$data." number of character is invalid";
			if($value1!=0 && $value2!=0) if($data<$value1 || $data>$value2) $errortemp=' - '.$error.'<br>';
			if($value1!=0 && $value2==0) if($data<$value1) $errortemp=' - '.$error.'<br>';
			if($value1==0 && $value2!=0) if($data>$value2) $errortemp=' - '.$error.'<br>';
		}
		$this->error.=$errortemp;
		$this->js.="error+=ValidateValue(f.$field,$value1,$value2,'$error','$fill','$error2'); \n";
	}
	
	function setValidate($aContainer){
		$sMessage='';
		foreach ($aContainer as $sField=>$aProperties) {
			if (isset($aProperties['rules'])) {
				$sRules=$aProperties['rules'];
				$aRule=explode('|', $sRules);
				foreach ($aRule as $sRule) {
					if ($sRule=='required') {
						$sMessage.=$this->ValidateNull($sField, 'The '.$aProperties['caption'].' field is required.');
					}
					if (preg_match("/(.*?)\[(.*?)\]/", $sRule, $aMatch)){
						$sRuleMatch=$aMatch[1];
						$sParamMatch=$aMatch[2];
						if ($sRuleMatch=='matches') {
							$sMessage.=$this->ValidateMatch($sField, $sParamMatch, 'The '.$aProperties['caption'].' field does not match.');
						}
						if ($sRuleMatch=='min_length') {
							$sMessage.=$this->ValidateLength($sField, $sParamMatch, 0, 'The '.$aProperties['caption'].' field must be at least '.$sParamMatch.' characters in length.');
						}
						if ($sRuleMatch=='max_length') {
							$sMessage.=$this->ValidateLength($sField, 0, $sParamMatch, 'The '.$aProperties['caption'].' field can not exceed '.$sParamMatch.' characters in length.');
						}
						if ($sRuleMatch=='exact_length') {
							$sMessage.=$this->ValidateLength($sField, $sParamMatch, $sParamMatch, 'The '.$aProperties['caption'].' field must be exactly '.$sParamMatch.' characters in length.');
						}
					}
					if ($sRule=='alpha') {
						$sMessage.=$this->ValidateString($sField, 'alphabet', 'The '.$aProperties['caption'].' field may only contain alphabetical characters.');
					}
					if ($sRule=='alpha_numeric') {
						$sMessage.=$this->ValidateString($sField, 'alphanumeric', 'The '.$aProperties['caption'].' field may only contain alpha-numeric characters.');
					}
					if ($sRule=='alpha_dash') {
						$sMessage.=$this->ValidateString($sField, 'alphadash', 'The '.$aProperties['caption'].' field may only contain alpha-numeric characters, underscores, and dashes.');
					}
					if ($sRule=='numeric') {
						$sMessage.=$this->ValidateString($sField, 'numbercommaperiod', 'The '.$aProperties['caption'].' field must contain a number.');
					}
					if ($sRule=='integer' || $sRule=='is_natural' || $sRule=='is_natural_no_zero') {
						$sMessage.=$this->ValidateString($sField, 'number', 'The '.$aProperties['caption'].' field must contain a number.');
					}
					if ($sRule=='valid_email') {
						$sMessage.=$this->ValidateEmail($sField, 'The '.$aProperties['caption'].' field must contain a valid email address.');
					}
				}
			}
		}
		return $sMessage;
	}
}
?>