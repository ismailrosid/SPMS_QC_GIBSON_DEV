<?php
class Transaction_model extends Model {
	var $aContainer = array();
	
	function Transaction_model(){
		// Call the Model constructor
		parent::Model();
  		
		$this->aContainer=array(
			's_serial_no[]'		=> array('caption'=>'Serial No','rules'=>'trim','view'=>1,'edit'=>1),
			's_phase'		=> array('caption'=>'Phase','rules'=>'trim|required','view'=>1,'edit'=>1),
			'd_transaction_date'	=> array('caption'=>'Date','rules'=>'trim|required','view'=>1,'edit'=>1),
			's_location'		=> array('caption'=>'Location','rules'=>'trim','view'=>1,'edit'=>1)
		);
		
	}
    
    function getList( $sCriteria='', $sFieldProcess='d_process_1', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$this->load->model('Util_model');
		
		if (strlen($sFieldProcess) >= 12)
			$process_length = 10;
		else
			$process_length = 9;
			
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		
        $oQuery = $this->db->query("
			SELECT 	ttpo.s_po_no, ttpo.s_po, 
					" . $sFieldProcess . " AS d_transaction_date, 
					".$sFieldProcess."_plan AS d_transaction_plan_date, 
					s_".substr($sFieldProcess, 2, $process_length)."_location AS s_transaction_location, 
					s_".substr($sFieldProcess, 2, $process_length)."_update_by AS s_transaction_by,
				    ttp.*,
					tmc.s_name AS s_buyer_name,
					tmcl.s_description AS s_color_name,
					-- tmm.s_code AS s_model,
					tmm.s_smodel AS s_smodel,
					tmm.s_description AS s_model_name
					
			FROM 	tt_production ttp 	INNER JOIN tt_prod_order ttpo 	ON (ttp.u_id_po_no = ttpo.u_id)
				LEFT JOIN tm_customer AS tmc	ON (ttpo.s_buyer = tmc.s_code)
				LEFT JOIN tm_model AS tmm	ON (ttpo.s_model = tmm.s_code)
				LEFT JOIN tm_color AS tmcl	ON (ttpo.s_color = tmcl.s_code)
			$sCriteria 
			$sOrderBy 
			$sLimitRows ");
		
		return $oQuery->result_array();
    }
	
	function getListCount( $sCriteria='' ){
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
        $oQuery = $this->db->query("
			SELECT 	COUNT(ttp.s_serial_no) AS n_count
			FROM 	tt_production ttp 	INNER JOIN tt_prod_order ttpo 	ON (ttp.u_id_po_no = ttpo.u_id)
				LEFT JOIN tm_customer AS tmc	ON (ttpo.s_buyer = tmc.s_code)
				LEFT JOIN tm_model AS tmm	ON (ttpo.s_model = tmm.s_code)
				LEFT JOIN tm_color AS tmcl	ON (ttpo.s_color = tmcl.s_code)
			$sCriteria ");
		$nCount=0;
		if ($oQuery->num_rows()>0) {
			$oData=$oQuery->first_row();
			$nCount=$oData->n_count;
		}
		return $nCount;
    }
}
?>
