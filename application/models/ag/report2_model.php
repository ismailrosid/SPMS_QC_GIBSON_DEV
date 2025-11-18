<?php
class Report2_model extends Model {
	
	function Report2_model(){
		// Call the Model constructor
		parent::Model();
  
	}
    
    function getListPo( $sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		$oQuery=$this->db->query("
			SELECT 	ttp.s_po_no, ttp.s_po, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date, 
					ttp.d_plan_date, ttp.d_delivery_date,
					ttp.d_target_date, ttp.s_buyer, ttp.s_buyer_name, ttp.s_location,
					SUM(ttp.n_process_1) AS n_process_1,
					SUM(ttp.n_process_2) AS n_process_2,
					SUM(ttp.n_process_3) AS n_process_3,
					SUM(ttp.n_process_4) AS n_process_4,
					SUM(ttp.n_process_5) AS n_process_5,
					SUM(ttp.n_process_6) AS n_process_6,
					SUM(ttp.n_process_7) AS n_process_7,
					SUM(ttp.n_process_8) AS n_process_8,
					SUM(ttp.n_process_9) AS n_process_9,
					SUM(ttp.n_process_10) AS n_process_10,
					SUM(ttp.n_process_14) AS n_process_14,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_process_9) + SUM(ttp.n_process_10) + SUM(ttp.n_process_14)) AS n_qty
			FROM 	tv_production_qty_ag AS ttp 
			$sCriteria 
			GROUP BY ttp.s_po_no, ttp.s_po, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date), 
					ttp.d_plan_date, 
					ttp.d_delivery_date, ttp.d_target_date, ttp.s_buyer, ttp.s_buyer_name, ttp.s_location
			$sOrderBy $sLimitRows ");
		
		return $oQuery->result_array();
	}
	
	function getListLot( $sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		$oQuery=$this->db->query("
			SELECT 	ttp.s_po_no, ttp.s_po, ttp.s_lot_no, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date, 
					ttp.d_plan_date, ttp.d_delivery_date,
					ttp.d_target_date, ttp.s_buyer, ttp.s_buyer_name, 
					SUM(ttp.n_process_1) AS n_process_1,
					SUM(ttp.n_process_2) AS n_process_2,
					SUM(ttp.n_process_3) AS n_process_3,
					SUM(ttp.n_process_4) AS n_process_4,
					SUM(ttp.n_process_5) AS n_process_5,
					SUM(ttp.n_process_6) AS n_process_6,
					SUM(ttp.n_process_7) AS n_process_7,
					SUM(ttp.n_process_8) AS n_process_8,
					SUM(ttp.n_process_9) AS n_process_9,
					SUM(ttp.n_process_10) AS n_process_10,
					SUM(ttp.n_process_14) AS n_process_14,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_process_9) + SUM(ttp.n_process_10) + SUM(ttp.n_process_14)) AS n_qty
			FROM 	tv_production_qty_ag AS ttp $sCriteria 
			GROUP BY ttp.s_lot_no, ttp.s_po_no, ttp.s_po, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date),
					ttp.d_plan_date, 
					ttp.d_delivery_date, ttp.d_target_date, ttp.s_buyer, ttp.s_buyer_name
			$sOrderBy $sLimitRows ");
		
		return $oQuery->result_array();
	}
	
	function getListBuyer( $sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		$oQuery=$this->db->query("
			SELECT 	EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date, 
					ttp.d_plan_date, ttp.d_delivery_date,
					ttp.d_target_date, ttp.s_buyer, ttp.s_buyer_name, 
					SUM(ttp.n_process_1) AS n_process_1,
					SUM(ttp.n_process_2) AS n_process_2,
					SUM(ttp.n_process_3) AS n_process_3,
					SUM(ttp.n_process_4) AS n_process_4,
					SUM(ttp.n_process_5) AS n_process_5,
					SUM(ttp.n_process_6) AS n_process_6,
					SUM(ttp.n_process_7) AS n_process_7,
					SUM(ttp.n_process_8) AS n_process_8,
					SUM(ttp.n_process_9) AS n_process_9,
					SUM(ttp.n_process_10) AS n_process_10,
					SUM(ttp.n_process_14) AS n_process_14,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_process_9) + SUM(ttp.n_process_10) + SUM(ttp.n_process_14)) AS n_qty
			FROM 	tv_production_qty_ag AS ttp $sCriteria 
			GROUP BY ttp.s_buyer, ttp.s_buyer_name, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date), 
					ttp.d_plan_date, 
					ttp.d_delivery_date, ttp.d_target_date
			$sOrderBy $sLimitRows ");
		
		return $oQuery->result_array();
	}
	
	function getListModel( $sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		$oQuery=$this->db->query("
			SELECT 	ttp.s_po_no, ttp.s_po, ttp.s_model, ttp.s_model_name, ttp.s_color_name, ttp.s_smodel, s_quality,
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date, 
					ttp.d_plan_date, ttp.d_delivery_date,
					ttp.d_target_date, ttp.s_buyer, ttp.s_buyer_name, ttp.s_location,
					SUM(ttp.n_process_1) AS n_process_1,
					SUM(ttp.n_process_2) AS n_process_2,
					SUM(ttp.n_process_3) AS n_process_3,
					SUM(ttp.n_process_4) AS n_process_4,
					SUM(ttp.n_process_5) AS n_process_5,
					SUM(ttp.n_process_6) AS n_process_6,
					SUM(ttp.n_process_7) AS n_process_7,
					SUM(ttp.n_process_8) AS n_process_8,
					SUM(ttp.n_process_9) AS n_process_9,
					SUM(ttp.n_process_10) AS n_process_10,
					SUM(ttp.n_process_14) AS n_process_14,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_process_9) + SUM(ttp.n_process_10) + SUM(ttp.n_process_14)) AS n_qty
			FROM 	tv_production_qty_ag AS ttp $sCriteria 
			GROUP BY ttp.s_model, ttp.s_model_name, ttp.s_color_name, ttp.s_smodel, ttp.s_po_no, ttp.s_po, ttp.s_buyer, ttp.s_buyer_name, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date),
					ttp.d_plan_date, ttp.d_delivery_date, ttp.d_target_date, ttp.s_location, s_quality
			$sOrderBy $sLimitRows ");
		
		return $oQuery->result_array();
	}
	
	function getListColor( $sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		$oQuery=$this->db->query("
			SELECT  ttp.s_color, ttp.s_color_name,
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date, 
					ttp.d_plan_date, ttp.d_delivery_date, ttp.s_smodel, ttp.s_location, s_quality,
					ttp.s_po_no, ttp.s_po, ttp.d_target_date, ttp.s_buyer, ttp.s_buyer_name, ttp.s_model, ttp.s_model_name,
					SUM(ttp.n_process_1) AS n_process_1,
					SUM(ttp.n_process_2) AS n_process_2,
					SUM(ttp.n_process_3) AS n_process_3,
					SUM(ttp.n_process_4) AS n_process_4,
					SUM(ttp.n_process_5) AS n_process_5,
					SUM(ttp.n_process_6) AS n_process_6,
					SUM(ttp.n_process_7) AS n_process_7,
					SUM(ttp.n_process_8) AS n_process_8,
					SUM(ttp.n_process_9) AS n_process_9,
					SUM(ttp.n_process_10) AS n_process_10,
					SUM(ttp.n_process_14) AS n_process_14,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_process_9) + SUM(ttp.n_process_10) + SUM(ttp.n_process_14)) AS n_qty
			FROM 	tv_production_qty_ag AS ttp $sCriteria 
			GROUP BY ttp.s_color, ttp.s_color_name, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date), 
					ttp.d_plan_date, ttp.d_delivery_date, 
					ttp.s_po_no, ttp.s_po, ttp.d_target_date, ttp.s_buyer, ttp.s_buyer_name, ttp.s_model, ttp.s_model_name,
					ttp.s_smodel, ttp.s_location, s_quality
			$sOrderBy $sLimitRows ");
		
		return $oQuery->result_array();
	}
	
	function getListSerialDatePhase( $sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$this->load->model('Util_model');
		
		$sCriteria=($sCriteria!='' ? " AND ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		
        $oQuery = $this->db->query("
			SELECT 	ttpo.s_po_no, ttpo.s_po, ttp.*,
					tmc.s_name AS s_buyer_name,
					tmm.s_smodel AS s_smodel,
					tmm.s_description AS s_model_name,
					tmcl.s_description AS s_color_name
			FROM 	tt_production ttp 	INNER JOIN tt_prod_order ttpo 	ON (ttp.u_id_po_no = ttpo.u_id)
										LEFT JOIN tm_customer AS tmc	ON (ttpo.s_buyer = tmc.s_code)
										LEFT JOIN tm_model AS tmm		ON (ttp.s_model = tmm.s_code)
										LEFT JOIN tm_color AS tmcl		ON (ttp.s_color = tmcl.s_code)
			WHERE	ttp.s_division='AG' $sCriteria 
			$sOrderBy 
			$sLimitRows ");
		
		return $oQuery->result_array();
    }
	
	function getListSerialDate( $sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$this->load->model('Util_model');
		
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		
        $oQuery = $this->db->query("
			SELECT 	ttp.*
			FROM 	tv_production_date_ag ttp
			$sCriteria 
			$sOrderBy 
			$sLimitRows ");
		
		return $oQuery->result_array();
    }
}
?>
