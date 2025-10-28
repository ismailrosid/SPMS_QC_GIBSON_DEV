<?php
class Application_model extends Model {
	var $aContainer = array();
	
	function Application_model(){
		// Call the Model constructor
		parent::Model();
		
		$this->aContainer=array(
			's_company_name'=> array('caption'=>'Company Name','rules'=>'trim|required','view'=>1,'edit'=>1),
			's_address'		=> array('caption'=>'Address','rules'=>'','view'=>1,'edit'=>1),
			's_city'		=> array('caption'=>'City','rules'=>'trim','view'=>1,'edit'=>1),
			's_province'	=> array('caption'=>'Province','rules'=>'trim','view'=>1,'edit'=>1),
			's_country'		=> array('caption'=>'Country','rules'=>'trim','view'=>1,'edit'=>1),
			's_npwp'		=> array('caption'=>'NPWP','rules'=>'trim','view'=>1,'edit'=>1),
			's_pobox'		=> array('caption'=>'Po Box','rules'=>'trim','view'=>1,'edit'=>1),
			's_phone1'		=> array('caption'=>'Phone 1','rules'=>'trim','view'=>1,'edit'=>1),
			's_phone2'		=> array('caption'=>'Phone 2','rules'=>'trim','view'=>1,'edit'=>1),
			's_fax'			=> array('caption'=>'Fax','rules'=>'trim','view'=>1,'edit'=>1),
			's_email1'		=> array('caption'=>'Email','rules'=>'trim|valid_email','view'=>1,'edit'=>1),
			's_email2'		=> array('caption'=>'Email CS','rules'=>'trim|valid_email','view'=>1,'edit'=>1),
			's_website'		=> array('caption'=>'Home Page','rules'=>'trim','view'=>1,'edit'=>1),
			'n_stock_ag'	=> array('caption'=>'AG First Stock','rules'=>'integer','view'=>1,'edit'=>1),
			'n_stock_eg'	=> array('caption'=>'EG First Stock','rules'=>'integer','view'=>1,'edit'=>1)
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
			SELECT 	* 
			FROM 	tm_application
			$sCriteria
			$sOrderBy 
			$sLimitRows ");
		
		return $oQuery->result_array();
    }
	
	function insert($aData){
		$this->load->model('Util_model');
		$uId=Util_model::getUuid();
		
		$aData['u_id']=$uId;
		foreach ($aData as $sField=>$sValue) {
			if ($sValue=='' && $sField!='u_id') // NULL value
				$this->db->set($sField, 'NULL', FALSE);
			else
				$this->db->set($sField, $sValue);
		}
		$this->db->set('d_createtime', 'NOW()', FALSE);
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->insert('tm_application');
		
		return substr($uId, 0, 32);
	}
	
	function update($aData){
		$uId=$aData['u_id'];
		foreach ($aData as $sField=>$sValue) {
			if ($sValue=='' && $sField!='u_id') // NULL value
				$this->db->set($sField, 'NULL', FALSE);
			else
				$this->db->set($sField, $sValue);
		}
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->where('u_id', $uId);
		return $this->db->update('tm_application');
	}
	
	function delete($uId){
		$this->db->where('u_id', $uId);
		return $this->db->delete('tm_application');
	}
}
?>