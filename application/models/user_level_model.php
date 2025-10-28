<?php
class User_level_model extends Model {
	var $aContainer = array();
	
	function User_level_model(){
		// Call the Model constructor
		parent::Model();
  
		$this->aContainer=array(
			's_level' 				=> array('caption'=>'Level ID','rules'=>'trim|required','view'=>1,'edit'=>1),
			's_level_desc' 			=> array('caption'=>'Description','rules'=>'trim','view'=>1,'edit'=>1),
			'b_setup_read' 			=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_setup_write'			=> array('caption'=>'', 'rules'=>'trim','view'=>1,'edit'=>1),
			'b_master_read'			=> array('caption'=>'','rules'=>'','view'=>1,'edit'=>1),
			'b_master_write'		=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_ag_setup_read'		=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_ag_setup_write'		=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_ag_order_read'		=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_ag_order_write'		=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_ag_transaction_read'	=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_ag_transaction_write'=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_ag_report_read'		=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_eg_setup_read'		=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_eg_setup_write'		=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_eg_order_read'		=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_eg_order_write'		=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_eg_transaction_read'	=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_eg_transaction_write'=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_eg_report_read'		=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_ag_sales_batch'		=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_eg_sales_batch'		=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1),
			'b_replace_production'	=> array('caption'=>'','rules'=>'trim','view'=>1,'edit'=>1)
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
			SELECT 	* FROM 	tm_user_level	$sCriteria $sOrderBy $sLimitRows ");
		
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
		$this->db->insert('tm_user_level');
		
		return $aData['s_level'];
	}
	
	function update($sLevel, $aData){
		foreach ($aData as $sField=>$sValue) {
			if ($sValue=='') { // NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} elseif ($sValue!='') {
				$this->db->set($sField, $sValue);
			}
		}
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->where('s_level', $sLevel);
		$this->db->update('tm_user_level');
		
		return $sLevel;
	}
	
	function delete($sLevel){
		$this->db->where('s_level', $sLevel);
		return $this->db->delete('tm_user_level');
	}
}
?>