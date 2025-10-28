<?php
class User_model extends Model {
	var $aContainer = array();
	
	function User_model(){
		// Call the Model constructor
		parent::Model();

		$this->aContainer=array(
			's_username' 	=> array('caption'=>'User ID','rules'=>'trim|required','view'=>1,'edit'=>1),
			's_name' 		=> array('caption'=>'Name','rules'=>'trim|required','view'=>1,'edit'=>1),
			's_level' 		=> array('caption'=>'Level','rules'=>'trim|required','view'=>1,'edit'=>1),
			's_password'	=> array('caption'=>'Password', 'rules'=>'trim|matches[s_password_confirm]','view'=>1,'edit'=>1),
			's_notes'		=> array('caption'=>'Notes','rules'=>'','view'=>1,'edit'=>1),
			'b_active'		=> array('caption'=>'Active','rules'=>'trim','view'=>1,'edit'=>1),
			's_nip'			=> array('caption'=>'NIP','rules'=>'trim','view'=>1,'edit'=>1)
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
			SELECT 	* FROM 	tm_user	$sCriteria $sOrderBy $sLimitRows ");
		
		return $oQuery->result_array();
    }
	
	function insert($aData){
		// User Data
		foreach ($aData as $sField=>$sValue) {
			if ( !is_array($sValue) ) {
				if ($sValue=='' && $sField!='s_password') {// NULL value
					$this->db->set($sField, 'NULL', FALSE);
				} else {
					if ($sField=='s_password') $sValue=md5($sValue);
					$this->db->set($sField, $sValue);
				}
			}
		}
		$this->db->set('d_createtime', 'NOW()', FALSE);
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->insert('tm_user');
		
		return $aData['s_username'];
	}
	
	function update($aData){
		// User Data
		foreach ($aData as $sField=>$sValue) {
			if ($sValue=='' && $sField!='s_password') { // NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} elseif ($sValue!='') {
				if ($sField=='s_password') $sValue=md5($sValue);
				$this->db->set($sField, $sValue);
			}
		}
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->where('s_username', $aData['s_username']);
		$this->db->update('tm_user');
		
		return $aData['s_username'];
	}
	
	function delete($s_UserName){
		$this->db->where('s_username', $s_UserName);
		return $this->db->delete('tm_user');
	}
}
?>