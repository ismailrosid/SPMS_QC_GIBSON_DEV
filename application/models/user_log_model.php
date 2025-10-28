<?php
class User_log_model extends Model {
	
	function User_log_model(){
		// Call the Model constructor
		parent::Model();
	}
    
    function getList( $sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$this->load->model('Util_model');
		
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		
        $oQuery = $this->db->query("
			SELECT 	tlul.*, tmu.s_level
			FROM 	tl_user_log AS tlul	LEFT JOIN tm_user AS tmu ON (tlul.s_username=tmu.s_username)
			$sCriteria $sOrderBy $sLimitRows ");
		
		return $oQuery->result_array();
    }
	
	
	function delete($sCriteria=''){
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$oQuery = $this->db->query("DELETE FROM tl_user_log AS tlul	$sCriteria");
		return $oQuery;
	}
}
?>