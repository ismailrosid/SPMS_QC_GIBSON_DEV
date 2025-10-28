<?php
class Model_model extends Model {
	var $aContainer = array();
	
	function Model_model(){
		// Call the Model constructor
		parent::Model();
  
		$this->aContainer=array(
			's_code' 		=> array('caption'=>'Model Code','rules'=>'trim|required','view'=>1,'edit'=>1),
			's_division' 	=> array('caption'=>'Division','rules'=>'trim','view'=>1,'edit'=>1),
			's_description'	=> array('caption'=>'Description','rules'=>'trim|required','view'=>1,'edit'=>1),
			's_type'		=> array('caption'=>'Difficult','rules'=>'trim','view'=>1,'edit'=>1),
			's_upc_code'	=> array('caption'=>'UPC Code','rules'=>'trim','view'=>1,'edit'=>1),
			
			's_notes'		=> array('caption'=>'Notes','rules'=>'','view'=>1,'edit'=>1),
			's_hsno'		=> array('caption'=>'HS No','rules'=>'trim','view'=>1,'edit'=>1),
			'n_price_1'		=> array('caption'=>'Price 1','rules'=>'trim|numeric','view'=>1,'edit'=>1),
			'n_price_2'		=> array('caption'=>'Price 2','rules'=>'trim|numeric','view'=>1,'edit'=>1),
			's_brand'		=> array('caption'=>'Brand','rules'=>'trim','view'=>1,'edit'=>1),
			'd_efdate'		=> array('caption'=>'Ef Date','rules'=>'trim','view'=>1,'edit'=>1),
			'd_validate'	=> array('caption'=>'Validate','rules'=>'trim','view'=>1,'edit'=>1),
			'n_cbm'			=> array('caption'=>'CBM','rules'=>'trim|numeric','view'=>1,'edit'=>1),
			'n_kg'			=> array('caption'=>'KG','rules'=>'trim|numeric','view'=>1,'edit'=>1),
			's_opt_1'		=> array('caption'=>'Option 1','rules'=>'trim','view'=>1,'edit'=>1),
			's_opt_2'		=> array('caption'=>'Option 2','rules'=>'trim','view'=>1,'edit'=>1),
			's_factory'		=> array('caption'=>'Factory','rules'=>'trim','view'=>1,'edit'=>1)
		);
	}
    
    function getList( $sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$this->load->model('Util_model');
		
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		
        $oQuery = $this->db->query("
			SELECT 	*, REPLACE(s_code, '/', '--') AS s_code_2 
			FROM 	tm_model	
			$sCriteria $sOrderBy $sLimitRows ");
		
		return $oQuery->result_array();
    }
	
	function insert($aData){
		foreach ($aData as $sField=>$sValue) {
			if ($sValue=='') {// NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} else {
				$this->db->set($sField, $sValue);
			}
		}
		$this->db->set('d_createtime', 'NOW()', FALSE);
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->insert('tm_model');
		
		return $aData['s_code'];
	}
	
	function update($sCode, $aData){
		foreach ($aData as $sField=>$sValue) {
			if ($sValue=='') { // NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} elseif ($sValue!='') {
				$this->db->set($sField, $sValue);
			}
		}
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->where('s_code', $sCode);
		$this->db->update('tm_model');
		
		return $sCode;
	}
	
	function delete($sCode){
		$this->db->where('s_code', $sCode);
		return $this->db->delete('tm_model');
	}
}
?>