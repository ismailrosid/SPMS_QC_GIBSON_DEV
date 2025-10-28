<?php
class Product_model extends Model {
	var $aContainer = array();
	
	function Product_model(){
		// Call the Model constructor
		parent::Model();
  		
		$this->aContainer=array(
			'n_serial_no'		=> array('caption'=>'Number','rules'=>'trim|required|integer','view'=>1,'edit'=>0),
			's_serial_no'		=> array('caption'=>'Serial No','rules'=>'trim|callback_serial_check','view'=>1,'edit'=>1),
			's_serial_no_2'		=> array('caption'=>'Serial No 2','rules'=>'trim|callback_serial2_check','view'=>1,'edit'=>1),
			's_po_no' 			=> array('caption'=>'PI Number','rules'=>'','view'=>1,'edit'=>0),
			's_po' 				=> array('caption'=>'PO','rules'=>'','view'=>1,'edit'=>0),
			's_type'			=> array('caption'=>'Type Process','rules'=>'trim','view'=>1,'edit'=>0),
			's_location'		=> array('caption'=>'Location','rules'=>'trim','view'=>1,'edit'=>1),
			'd_order_date' 		=> array('caption'=>'Receive Order','rules'=>'trim|required','view'=>1,'edit'=>1),
			'd_production_date'	=> array('caption'=>'Production Date','rules'=>'trim|required','view'=>1,'edit'=>1),
			'd_plan_date'		=> array('caption'=>'Production Plan Date (Input)','rules'=>'trim','view'=>1,'edit'=>1),
			'd_delivery_date' 	=> array('caption'=>'Production Plan Date (Output)','rules'=>'trim','view'=>1,'edit'=>1),
			'd_target_date'		=> array('caption'=>'Export Plan Date','rules'=>'trim','view'=>1,'edit'=>1),
			's_lot_no' 			=> array('caption'=>'Lot Number','rules'=>'trim','view'=>1,'edit'=>1),
			's_buyer'			=> array('caption'=>'Buyer','rules'=>'trim|required|callback_buyer_check','view'=>1,'edit'=>1),
			's_brand' 			=> array('caption'=>'Brand','rules'=>'trim','view'=>1,'edit'=>1),
			's_bench'			=> array('caption'=>'Bench Mark','rules'=>'trim','view'=>1,'edit'=>1),
			's_model' 			=> array('caption'=>'Model','rules'=>'trim|callback_model_check','view'=>1,'edit'=>1),
			's_color'			=> array('caption'=>'Color','rules'=>'trim|callback_color_check','view'=>1,'edit'=>1),
			's_smodel'			=> array('caption'=>'S Model','rules'=>'trim','view'=>1,'edit'=>1),
			's_invoice'			=> array('caption'=>'Invoice','rules'=>'trim','view'=>1,'edit'=>1),
			'n_price'			=> array('caption'=>'Price','rules'=>'trim|numeric','view'=>1,'edit'=>1),
			's_proforma'		=> array('caption'=>'Proforma','rules'=>'trim','view'=>1,'edit'=>1),
			
			'd_process_1'		=> array('caption'=>'Process 1','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_2'		=> array('caption'=>'Process 2','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_3'		=> array('caption'=>'Process 3','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_4'		=> array('caption'=>'Process 4','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_5'		=> array('caption'=>'Process 5','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_6'		=> array('caption'=>'Process 6','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_7'		=> array('caption'=>'Process 7','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_8'		=> array('caption'=>'Process 8','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_9'		=> array('caption'=>'Process 9','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_10'		=> array('caption'=>'Process 10','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_14'		=> array('caption'=>'Process 11','rules'=>'trim','view'=>1,'edit'=>1),
			
			'd_process_1_plan'		=> array('caption'=>'Plan 1','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_2_plan'		=> array('caption'=>'Plan 2','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_3_plan'		=> array('caption'=>'Plan 3','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_4_plan'		=> array('caption'=>'Plan 4','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_5_plan'		=> array('caption'=>'Plan 5','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_6_plan'		=> array('caption'=>'Plan 6','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_7_plan'		=> array('caption'=>'Plan 7','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_8_plan'		=> array('caption'=>'Plan 8','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_9_plan'		=> array('caption'=>'Plan 9','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_10_plan'		=> array('caption'=>'Plan 10','rules'=>'trim','view'=>1,'edit'=>1),
			'd_process_14_plan'		=> array('caption'=>'Plan 11','rules'=>'trim','view'=>1,'edit'=>1),
						
			's_process_1_location'		=> array('caption'=>'Location 1','rules'=>'trim','view'=>1,'edit'=>1),
			's_process_2_location'		=> array('caption'=>'Location 2','rules'=>'trim','view'=>1,'edit'=>1),
			's_process_3_location'		=> array('caption'=>'Location 3','rules'=>'trim','view'=>1,'edit'=>1),
			's_process_4_location'		=> array('caption'=>'Location 4','rules'=>'trim','view'=>1,'edit'=>1),
			's_process_5_location'		=> array('caption'=>'Location 5','rules'=>'trim','view'=>1,'edit'=>1),
			's_process_6_location'		=> array('caption'=>'Location 6','rules'=>'trim','view'=>1,'edit'=>1),
			's_process_7_location'		=> array('caption'=>'Location 7','rules'=>'trim','view'=>1,'edit'=>1),
			's_process_8_location'		=> array('caption'=>'Location 8','rules'=>'trim','view'=>1,'edit'=>1),
			's_process_9_location'		=> array('caption'=>'Location 9','rules'=>'trim','view'=>1,'edit'=>1),
			's_process_10_location'		=> array('caption'=>'Location 10','rules'=>'trim','view'=>1,'edit'=>1),
			's_process_14_location'		=> array('caption'=>'Location 11','rules'=>'trim','view'=>1,'edit'=>1)
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
			SELECT 	ttpo.s_po_no, ttpo.s_po, 
					ttp.*,
					tmc.s_name AS s_buyer_name,
					tmm.s_description AS s_model_name,
					tmcl.s_description AS s_color_name
			FROM 	tt_production ttp 	INNER JOIN tt_prod_order ttpo 	ON (ttp.u_id_po_no = ttpo.u_id)
										LEFT JOIN tm_customer AS tmc	ON (ttpo.s_buyer = tmc.s_code)
										LEFT JOIN tm_model AS tmm		ON (ttp.s_model = tmm.s_code)
										LEFT JOIN tm_color AS tmcl		ON (ttp.s_color = tmcl.s_code)
			$sCriteria $sOrderBy $sLimitRows ");
		
		return $oQuery->result_array();
    }
	
	function getListCount( $sCriteria='' ){
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
        $oQuery = $this->db->query("
			SELECT 	COUNT(ttp.s_serial_no) AS n_count
			FROM 	tt_production ttp 	INNER JOIN tt_prod_order ttpo 	ON (ttp.u_id_po_no = ttpo.u_id)
										LEFT JOIN tm_customer AS tmc	ON (ttpo.s_buyer = tmc.s_code)
										LEFT JOIN tm_model AS tmm		ON (ttp.s_model = tmm.s_code)
										LEFT JOIN tm_color AS tmcl		ON (ttp.s_color = tmcl.s_code)
			$sCriteria ");
		$nCount=0;
		if ($oQuery->num_rows()>0) {
			$oData=$oQuery->first_row();
			$nCount=$oData->n_count;
		}
		return $nCount;
    }
	
	function insert($aData, $sDivision){
		foreach ($aData as $sField=>$sValue) {
			if ($sValue=='') {// NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} else {
				$this->db->set($sField, $sValue);
			}
		}
		
		$this->db->set('s_division', $sDivision);
		$this->db->set('d_createtime', 'NOW()', FALSE);
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$rInsert = $this->db->insert('tt_production');
		
		$this->db->set('n_qty', 'n_qty + 1', FALSE);
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->where('u_id', $aData['u_id_po_no']);
		$this->db->where('s_division', $sDivision);
		$this->db->update('tt_prod_order');
		
		if ($rInsert === FALSE) {
			return FALSE;
		} else {
			return TRUE; //$aData['s_serial_no'];
		}
	}
	
	function update2($sSerialNo='', $aData, $sDivision, $uIdPoNo='', $bValid=FALSE) {
		foreach ($aData as $sField=>$sValue) {
			if ($sValue=='') { // NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} elseif ($sValue!='') {
				$this->db->set($sField, $sValue);
			}
		}
		
		if ( isset($aData['d_process_3']) && !isset($aData['d_process_2']) ) $this->db->set('d_process_2', $aData['d_process_3']);
		
		if (isset($aData['d_process_6']) && !isset($aData['d_process_4']) && !isset($aData['d_process_5'])) {
			$this->db->set('d_process_4', $aData['d_process_6']);
			$this->db->set('d_process_5', $aData['d_process_6']);
		}
		if (trim(strtoupper($sDivision))=='AG') {
			if (isset($aData['d_process_8']) && !isset($aData['d_process_7'])) $this->db->set('d_process_7', $aData['d_process_8']);
		}
		
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		if (!empty($sSerialNo)) $this->db->where('s_serial_no', $sSerialNo);
		if (!empty($uIdPoNo)) $this->db->where('u_id_po_no', $uIdPoNo);
		$this->db->where('s_division', strtoupper($sDivision));
		$this->db->update('tt_production');
		
		return $sSerialNo;
	}
	
	function update($sSerialNo='', $aData, $sDivision, $uIdPoNo='', $bValid=FALSE) {
		foreach ($aData as $sField=>$sValue) {
			if ($sValue=='') { // NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} elseif ($sValue!='') {
				$this->db->set($sField, $sValue);
			}
		}
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		if (!empty($sSerialNo)) $this->db->where('s_serial_no', $sSerialNo);
		if (!empty($uIdPoNo)) $this->db->where('u_id_po_no', $uIdPoNo);
		$this->db->where('s_division', strtoupper($sDivision));
		$rUpdate = $this->db->update('tt_production');
		
		if ($rUpdate === FALSE){
			return FALSE;
		} else {
			return $sSerialNo;
		}
	}
	
	function delete($sSerialNo, $sDivision){
		$this->db->where('s_serial_no', $sSerialNo);
		$this->db->where('s_division', strtoupper($sDivision));
		$TtProduction = $this->db->get('tt_production');
		if ($TtProduction->num_rows() > 0){
			$oTtProduction = $TtProduction->first_row();
			
			$this->db->trans_begin();
			
			$this->db->set('b_enabled_trigger', 'false', FALSE);
			$this->db->set('n_qty', 'n_qty - 1', FALSE);
			$this->db->where('u_id', $oTtProduction->u_id_po_no);
			$this->db->update('tt_prod_order');
			
			$this->db->where('s_serial_no', $sSerialNo);
			$this->db->where('s_division', strtoupper($sDivision));
			$this->db->delete('tt_production');
			
			$this->db->set('b_enabled_trigger', 'true', FALSE);
			$this->db->where('u_id', $oTtProduction->u_id_po_no);
			$this->db->update('tt_prod_order');
			
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return false;
			} else {
				$this->db->trans_commit();
				return true;
			}
		}
	}
	
	function validateTransaction($sSerialNo, $sPhase) {
		
	}
}
?>