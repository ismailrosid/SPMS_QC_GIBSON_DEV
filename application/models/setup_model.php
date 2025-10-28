<?php
class Setup_model extends Model {
	var $aContainer = array();
	
	function Setup_model(){
		// Call the Model constructor
		parent::Model();
  
		$this->aContainer=array(
			's_field_process'=> array('caption'=>'Field','rules'=>'trim|required','view'=>1,'edit'=>1),
			's_phase' 		=> array('caption'=>'Code','rules'=>'trim|required','view'=>1,'edit'=>1),
			's_type' 		=> array('caption'=>'Type','rules'=>'trim|required','view'=>1,'edit'=>1),
			's_description'	=> array('caption'=>'Process Name','rules'=>'trim|required','view'=>1,'edit'=>1),
			'n_order'		=> array('caption'=>'Line','rules'=>'trim|required|numeric','view'=>1,'edit'=>1),
			'n_line'		=> array('caption'=>'Order','rules'=>'trim|required|numeric','view'=>1,'edit'=>1)
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
			SELECT 	* FROM 	tm_prod_setup	$sCriteria $sOrderBy $sLimitRows ");
		
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
		$this->db->insert('tm_prod_setup');
		
		return $aData['s_phase'];
	}
	
	function update($sPhase, $aData){
		foreach ($aData as $sField=>$sValue) {
			if ($sValue=='') { // NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} elseif ($sValue!='') {
				$this->db->set($sField, $sValue);
			}
		}
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->where('s_phase', $sPhase);
		$this->db->update('tm_prod_setup');
		
		return $sPhase;
	}
	
	function delete($sPhase){
		$this->db->where('s_phase', $sPhase);
		return $this->db->delete('tm_prod_setup');
	}
}
?>