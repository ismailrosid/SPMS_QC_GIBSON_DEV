<?php
class Buyer_model_model extends Model {
	var $aContainer = array();
	
	function Buyer_model_model(){
		// Call the Model constructor
		parent::Model();
  
		$this->aContainer=array(
			's_code_customer'=> array('caption'=>'Customer','rules'=>'trim|required','view'=>1,'edit'=>1),
			's_code_model'	=> array('caption'=>'Model','rules'=>'trim|required','view'=>1,'edit'=>1),
			'n_price_1'		=> array('caption'=>'Price 1','rules'=>'trim|numeric','view'=>1,'edit'=>1),
			'n_price_2'		=> array('caption'=>'Price 2','rules'=>'trim|numeric','view'=>1,'edit'=>1),
			's_notes'		=> array('caption'=>'Notes','rules'=>'','view'=>1,'edit'=>1)
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
			SELECT 	tmbm.*, 
					tmc.s_name,
					tmm.s_description
			FROM 	tm_buyer_model AS tmbm	INNER JOIN tm_customer AS tmc	ON (tmbm.s_code_customer=tmc.s_code)
											INNER JOIN tm_model AS tmm		ON (tmbm.s_code_model=tmm.s_code)
			$sCriteria $sOrderBy $sLimitRows ");
		
		return $oQuery->result_array();
    }
	
	function insert($aData){
		$this->load->model('Util_model');
		$uId=Util_model::getUuid();
		
		$aData['u_id']=$uId;
		foreach ($aData as $sField=>$sValue) {
			if ($sValue=='') {// NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} else {
				$this->db->set($sField, $sValue);
			}
		}
		$this->db->set('d_createtime', 'NOW()', FALSE);
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->insert('tm_buyer_model');
		
		return $uId;
	}
	
	function update($uId, $aData){
		foreach ($aData as $sField=>$sValue) {
			if ($sValue=='') { // NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} elseif ($sValue!='') {
				$this->db->set($sField, $sValue);
			}
		}
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->where('u_id', $uId);
		$this->db->update('tm_buyer_model');
		
		return $uId;
	}
	
	function delete($uId){
		$this->db->where('u_id', $uId);
		return $this->db->delete('tm_buyer_model');
	}
}
?>