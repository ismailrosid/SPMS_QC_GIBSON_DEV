<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Form
{
	var $CI;

	function Form()
	{
		$this->CI = &get_instance();
	}

	function selectSearch($table, $selectfield = '', $criteria = '', $field = '', $show = '', $match = '')
	{
		if ($selectfield) $this->CI->db->select($selectfield, '');
		if ($criteria) $this->CI->db->where($criteria, NULL, FALSE);
		$query = $this->CI->db->get($table);
		$option = '';
		foreach ($query->result() as $row) {
			$selected = $row->$field  == $match ? 'selected' : '';
			$option .= '<li data-value="' . htmlspecialchars($row->$field, ENT_QUOTES) . '" class="' . $selected . '">' . htmlspecialchars($row->$show, ENT_QUOTES) . '</li>';
		}

		return $option;
	}

	function selectbox($table, $selectfield = '', $criteria = '', $field = '', $show = '', $match = '')
	{
		if ($selectfield) $this->CI->db->select($selectfield, '');		//$selectfield = "one,two,three";
		if ($criteria) $this->CI->db->where($criteria, NULL, FALSE);		//$criteria = array('one'=>1);
		//$criteria = array('name !=' => $name, 'id <' => $id, 'date >' => $date);
		$query = $this->CI->db->get($table);
		$option = '';
		foreach ($query->result() as $row) {
			$option .= '<option value="' . $row->$field . '"';
			if ($row->$field == $match) $option .= ' selected';
			$option .= '>' . $row->$show . '</option>';
		}

		return $option;
	}
	function selectboxquery($sql, $field = '0', $show = '1', $match = '', $type = 'option')
	{
		$query = $this->CI->db->query($sql);
		$option = '';
		$aJsData = array();
		foreach ($query->result_array() as $row) {
			$option .= '<option value="' . $row[$field] . '"';
			if ($row[$field] == $match) $option .= ' selected';
			$option .= '>' . $row[$show] . '</option>\n';
			if ($type != 'option')
				$aJsData[$row[$field]] = $row[$show];
		}
		if ($type == 'option')
			return $option;
		else
			return $this->jsarray($aJsData);
	}
	function selectboxarray($rowset, $match, $typechosen = '')
	{
		$print = '';
		$bSelect = false;
		foreach ($rowset as $field => $value) {
			if ($typechosen == 1 || $typechosen == 2)
				$field = $value;
			$print .= "<option value='" . $field . "'";
			if (strtolower($field) == strtolower($match)) {
				$print .= " selected";
				$bSelect = true;
			};
			$print .= ">" . $value . "</option>";
		}
		if ($bSelect == false && $typechosen == 2 && $match != '') {
			$print .= "<option value='" . $match . "' selected>" . $match . "</option>";
		}
		return $print;
	}
	function jsarray($rowset, $match = '', $typechosen = '')
	{
		$aJsValue = array();
		foreach ($rowset as $value => $show) {
			if ($typechosen == 1) $value = $show;
			$aJsValue[] = '["' . $value . '","' . $show . '"]';
		}
		return implode(',', $aJsValue);
	}

	function selectboxoption($sKeyName = '', $sMatch = '', $type = 'option')
	{
		$aKeyValue = array();
		$sqlSetting = $this->CI->db->query("SELECT skeyvalue, sdefaultvalue, stypedata FROM tmsetting WHERE skey='$sKeyName' ORDER BY nsequence ASC");
		if ($sqlSetting->num_rows() > 0) {
			$oRow = $sqlSetting->first_row();
			if ($oRow->stypedata == 'boolean' && $sMatch != '') {
				$sMatch = (substr(strtolower($sMatch), 0, 1) == 'f' || substr(strtolower($sMatch), 0, 1) == 'n' || $sMatch == '0' ? 'No' : 'Yes');
			}
			$sMatch = ($sMatch == '' ? $oRow->sdefaultvalue : $sMatch);
			$aKeyValue = explode('|', $oRow->skeyvalue);
		}
		if ($type == 'option')
			return $this->selectboxarray($aKeyValue, $sMatch, 1);
		else
			return $this->jsarray($aKeyValue, $sMatch, 1);
	}

	function renderCategory($category, $sTableName = 'tmsetting', $sCriteria = '')
	{
		$aWithParent = array();
		$aWithoutParent = array();
		$sResult = '';

		$sTableName = ($sTableName == '' ? 'tmsetting' : $sTableName);
		$sCriteria = ($sCriteria != '' ? " AND " . $sCriteria : '');

		/* -- getting parent -- */
		$sqlParent = $this->CI->db->query("
				SELECT  ts.skey, ts.stitle, 
						" . ($sTableName != 'tmsetting' ? " ts.svalue " : " ts.sdefaultvalue ") . " AS sdefaultvalue, 
						ts.stypedata, ts.sinputtype,
                        ts.scategory, ts.squerydropdown, ts.nminvalue, ts.nmaxvalue,
                        ts.nnextvalue, ts.ssymboldefault, ts.sminmark, ts.smaxmark,
						ts.skeyvalue
				  FROM 	$sTableName AS ts 
				 WHERE 	ts.scategory='$category' AND (ts.sparentkey='' OR ts.sparentkey IS NULL) $sCriteria
				 ORDER 	BY ts.nsequence ASC");
		$aRowsParent = $sqlParent->result_array();

		/* -- getting child -- */
		$sqlChild = $this->CI->db->query("
					SELECT  ts.skey, ts.stitle, 
							" . ($sTableName != 'tmsetting' ? " ts.svalue " : " ts.sdefaultvalue ") . " AS sdefaultvalue, 
							ts.stypedata, ts.sinputtype, ts.sparentkey,
                            ts.scategory, ts.squerydropdown, ts.nminvalue, ts.nmaxvalue,
                            ts.nnextvalue, ts.ssymboldefault, ts.sminmark, ts.smaxmark,
							ts.skeyvalue
					  FROM 	$sTableName AS ts 
					 WHERE 	ts.scategory='$category' AND ts.sparentkey<>'' AND ts.sparentkey IS NOT NULL $sCriteria
					 ORDER 	BY ts.nsequence ASC");
		foreach ($sqlChild->result_array() as $sRowChild) {
			$sParentKey = $sRowChild['sparentkey'];
			$aWithParent[$sParentKey][] = $sRowChild;
		}

		foreach ($aRowsParent as $sRowParent) {
			$label = '';
			$sComponent = '';
			$sKey = $sRowParent['skey'];
			if (isset($aWithParent[$sKey])) {
				// generate parent
				$label .= $sRowParent['stitle'];
				$sComponent .= $this->generateInputOnly($sRowParent);
				foreach ($aWithParent[$sKey] as $rowChild) {
					// generate child
					$sComponent .= $this->generateInputOnly($rowChild);
				}
			} else {
				// generate parent only
				$label = $sRowParent['stitle'];
				$sComponent = $this->generateInputOnly($sRowParent);
			}
			$sResult .= "<tr><td>$label</td><td>:</td><td>$sComponent</td></tr>";
		}
		return $sResult;
	}

	function generateInputOnly($aProperties)
	{
		if ($aProperties['sinputtype'] == 'text') {
			$sComponent = "<input type='text' name='$aProperties[skey]' id='$aProperties[skey]' value='$aProperties[sdefaultvalue]'>";
		} elseif ($aProperties['sinputtype'] == 'currency') {
			$sComponent = "<input type='text' name='$aProperties[skey]' id='$aProperties[skey]' value='$aProperties[sdefaultvalue]'> "
				. "<select  name='$aProperties[skey]curr' id='$aProperties[skey]curr'>"
				. $this->selectboxquery('SELECT ssymbol, scurrency FROM tmcurrency', 'ssymbol', 'ssymbol', $aProperties['ssymboldefault'])
				. "</select>";
		} elseif ($aProperties['sinputtype'] == 'currencyreadonly') {
			$sComponent = "<input type='text' name='$aProperties[skey]' id='$aProperties[skey]' value='$aProperties[sdefaultvalue]' readOnly='true'> "
				. "<select  name='$aProperties[skey]curr' id='$aProperties[skey]curr'>"
				. $this->selectboxquery('SELECT ssymbol, scurrency FROM tmcurrency', 'ssymbol', 'ssymbol', $aProperties['ssymboldefault'])
				. "</select>";
		} elseif ($aProperties['sinputtype'] == 'dropdown') {
			if ($aProperties['squerydropdown'] == '') {
				$aDataDropDown = explode('|', $aProperties['skeyvalue']);
				$sComponent = $this->selectboxarray($aDataDropDown, $vDefault, 1);
			} else {
				$sComponent = "<select  name='$aProperties[skey]' id='$aProperties[skey]'>"
					. $this->selectboxquery($aProperties['squerydropdown'])
					. "</select>";
			}
		} elseif ($aProperties['sinputtype'] == 'readonly') {
			$sComponent = "<input type='text' name='$aProperties[skey]' id='$aProperties[skey]' value='$aProperties[sdefaultvalue]' readOnly='true'>";
		} elseif ($aProperties['sinputtype'] == 'radio') {
			$aRadioData = explode('|', $aProperties['skeyvalue']);
			$sComponent = $this->radioarray($aProperties['skey'], $aRadioData, $aProperties['sdefaultvalue'], 1);
		} elseif ($aProperties['sinputtype'] == 'checkbox') {
			$sComponent = "<input type='checkbox' name='" . $aProperties['skey'] . "' id='" . $aProperties['skey'] . "' value='" . $aProperties['skey'] . "' " . ($aProperties['sdefaultvalue'] == '1' ? "checked" : "") . " >";
		} else {
			$sComponent = "<input type='text' name='$aProperties[skey]' id='$aProperties[skey]' value='$aProperties[sdefaultvalue]'>";
		}
		return $sComponent;
	}

	function checkboxoption($keyname)
	{
		$query = $this->CI->db->query("
			SELECT ts.skey, ts.stitle, ts.sdefaultvalue 
				FROM tmsetting AS ts 
			WHERE ts.scategory='$keyname' ORDER BY nsquance ASC");
		$keyvalue = array();
		$print = '';
		foreach ($query->result_array() as $field => $value) {
			$print .= "<input type='checkbox' name='" . $value['skey'] . "' id='" . $value['skey'] . "' value='" . $value['skey'] . "' " . ($value['stitle'] == $value['sdefaultvalue'] ? "checked" : "") . " > <label for='" . $value['skey'] . "'>" . $value['stitle'] . "</label> <br>";
		}
		return $print;
	}

	function radiooption($sKeyName = '', $sMatch = '')
	{
		$aData = array();
		$oQuery = $this->CI->db->query("SELECT skey, skeyvalue, sdefaultvalue FROM tmsetting WHERE skey='$sKeyName'");
		if ($oQuery->num_rows() > 0) {
			$oRow = $oQuery->first_row();
			$sMatch = ($sMatch == '' ? $oRow->sdefaultvalue : $sMatch);
			$aData = explode('|', $oRow->skeyvalue);
		}
		return $this->radioarray($sKeyName, $aData, $sMatch, 1);
	}
	function radioarray($sName, $aRowSet, $sMatch, $bTypeChosen = 0)
	{
		$sPrint = '';
		$iCount = 0;
		foreach ($aRowSet as $sValue => $sCaption) {
			if ($bTypeChosen == 1) $sValue = $sCaption;
			$sPrint .= "<input type='radio' name='$sName' id='" . $sName . "_" . $iCount . "' value='$sValue' " . (strtolower($sValue) == strtolower($sMatch) ? "checked" : "") . " > <label for='" . $sName . "_" . $iCount . "'>" . $sCaption . "</label> <br>";
			$iCount++;
		}
		return $sPrint;
	}

	function get_key($key_value)
	{
		return explode('::', substr($key_value, 1, -1));
	}
}

