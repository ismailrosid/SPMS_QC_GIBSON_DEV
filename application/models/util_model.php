<?php
class Util_model extends Model {
	function Util_model(){
		// Call the Model constructor
		parent::Model();
	}
	
	function getSerialNo($sDivision, $dProductionDate, $sBuyer) {
		$nCount=1;
		$sSerialNoParse='';
		$nSerialDigit=5;
		$oTmCustomer = $this->db->get_where('tm_customer', array('s_code' => $sBuyer));
		if ($oTmCustomer->num_rows()>0) {
			$oDataTmCustomer = $oTmCustomer->first_row();
				
			$sSerialNoParse=str_replace('{yymm}', date('ym', strtotime($dProductionDate)), $oDataTmCustomer->s_serial_parse);
			$sSerialNoParse=str_replace('{buyercode}', $sBuyer, $sSerialNoParse);
			$nSerialDigit=$oDataTmCustomer->n_serial_digit;
			
			$this->db->where('s_buyer ~~*', $sBuyer);
			$this->db->where('s_division', strtoupper($sDivision)); 
			if (trim(strtolower($oDataTmCustomer->s_serial_reset)) == 'monthly') {
				$this->db->where('EXTRACT(MONTH FROM d_production_date)=', date('m', strtotime($dProductionDate)));
			}
			$this->db->where('EXTRACT(YEAR FROM d_production_date)=', date('Y', strtotime($dProductionDate)));
			$this->db->order_by("n_serial_no", "desc"); 
			$this->db->limit(1);
			$oTtProduction = $this->db->get('tt_production');
			$nCount=1;
			if ($oTtProduction->num_rows()>0) {
				$dataTtProduction=$oTtProduction->first_row();
				$nCount=$dataTtProduction->n_serial_no + 1;
			}
			
			/*$oTtProduction = $this->db->get_where('tt_production', 
				array(	's_buyer' => $sBuyer, 
						'EXTRACT(MONTH FROM d_production_date)=' => date('m', strtotime($dProductionDate)),
						'EXTRACT(YEAR FROM d_production_date)=' => date('Y', strtotime($dProductionDate))
					)
				);
			$nCount=$oTtProduction->num_rows() + 1;*/
		}
		return array('serial_no'=>$sSerialNoParse, 'count'=>$nCount, 'digit'=>$nSerialDigit);
	}
	
	function getUuid(){
		$uId='';
		$oQuery = $this->db->query("SELECT uuid_generate_v1() AS u_uid");
		if ($oQuery->num_rows()>0) {
			$oDataQuery=$oQuery->first_row();
			$uId=$oDataQuery->u_uid;
		}
		return $uId;
	}
	
	function getOrderBy($OrderBy=''){
		$sOrderBy='';
		if(is_array($OrderBy) && count($OrderBy)>0){
			 $aOrderBy=array();
			 foreach($OrderBy as $fieldorder=>$order){
				$aOrderBy[]=$fieldorder." ".$order;
			 }
			 $sOrderBy=implode(', ', $aOrderBy);
		} elseif(!is_array($OrderBy) && $OrderBy!='') {
			$sOrderBy=$OrderBy;
		}
		if ($sOrderBy!='') $sOrderBy=" ORDER BY ".$sOrderBy;
		return $sOrderBy;
	}
}
?>