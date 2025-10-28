<?php
class Report_model extends Model {
	
	function Report_model(){
		// Call the Model constructor
		parent::Model();
  
	}
    
    function getListStock( $sCriteria='', $nMonth='01', $nYear=2009, $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$this->load->model('Util_model');
		
		$sCriteria=($sCriteria!='' ? " AND ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		
        /*$oQuery = $this->db->query("
			SELECT 	ttpo.s_po_no, ttpo.s_po, ttp.*,
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_month,
					tmc.s_name AS s_buyer_name,
					tmm.s_description AS s_model_name,
					tmcl.s_description AS s_color_name,
					(CASE 	WHEN (ttp.d_process_8 IS NULL OR CAST( TO_CHAR(ttp.d_process_8, 'YYYYMM') AS NUMERIC(7)) > ".$nYear.$nMonth.") THEN 1
					 		ELSE 0 END) AS n_on_progress,
					(CASE 	WHEN ttp.d_process_8 IS NOT NULL AND CAST( TO_CHAR(ttp.d_process_8, 'YYYYMM') AS NUMERIC(7)) = ".$nYear.$nMonth." THEN 1
					 		ELSE 0 END) AS n_in, 
					(CASE 	WHEN 	(	CAST( TO_CHAR(ttp.d_process_14, 'YYYYMM') AS NUMERIC(7)) = ".$nYear.$nMonth." 
									 OR CAST( TO_CHAR(ttp.d_process_15, 'YYYYMM') AS NUMERIC(7)) = ".$nYear.$nMonth." ) THEN 1
					 		ELSE 0 END) AS n_out
			FROM 	tt_production ttp 	INNER JOIN tt_prod_order ttpo 	ON (ttp.u_id_po_no = ttpo.u_id)
										LEFT JOIN tm_customer AS tmc	ON (ttpo.s_buyer = tmc.s_code)
										LEFT JOIN tm_model AS tmm		ON (ttp.s_model = tmm.s_code)
										LEFT JOIN tm_color AS tmcl		ON (ttp.s_color = tmcl.s_code)
			WHERE	(CAST( TO_CHAR(ttp.d_process_8, 'YYYYMM') AS NUMERIC(7)) >= ".$nYear.$nMonth." 
					 AND CAST( TO_CHAR(ttp.d_production_date, 'YYYYMM') AS NUMERIC(7)) <= ".$nYear.$nMonth." 
					 AND (CAST( TO_CHAR(ttp.d_process_14, 'YYYYMM') AS NUMERIC(7)) >= ".$nYear.$nMonth." OR ttp.d_process_14 IS NULL 
					  	OR CAST( TO_CHAR(ttp.d_process_15, 'YYYYMM') AS NUMERIC(7)) >= ".$nYear.$nMonth."  OR ttp.d_process_15 IS NULL)
					 AND ttp.s_division='AG' $sCriteria)
				OR 	(CAST( TO_CHAR(ttp.d_process_8, 'YYYYMM') AS NUMERIC(7)) IS NULL 
					 AND CAST( TO_CHAR(ttp.d_production_date, 'YYYYMM') AS NUMERIC(7)) <= ".$nYear.$nMonth." 
					 AND (CAST( TO_CHAR(ttp.d_process_14, 'YYYYMM') AS NUMERIC(7)) >= ".$nYear.$nMonth." OR ttp.d_process_14 IS NULL
					  	OR CAST( TO_CHAR(ttp.d_process_15, 'YYYYMM') AS NUMERIC(7)) >= ".$nYear.$nMonth." OR ttp.d_process_15 IS NULL)
					 AND ttp.s_division='AG' $sCriteria)
				OR 	((CAST( TO_CHAR(ttp.d_process_14, 'YYYYMM') AS NUMERIC(7)) >= ".$nYear.$nMonth." 
					  	OR CAST( TO_CHAR(ttp.d_process_15, 'YYYYMM') AS NUMERIC(7)) >= ".$nYear.$nMonth.")
					 AND CAST( TO_CHAR(ttp.d_production_date, 'YYYYMM') AS NUMERIC(7)) <= ".$nYear.$nMonth." 
					 AND ttp.s_division='AG' $sCriteria)
			$sOrderBy $sLimitRows ");*/
		
		$oQuery = $this->db->query("
			SELECT 	SUM(CASE WHEN (ttp.d_process_9 IS NULL OR CAST( TO_CHAR(ttp.d_process_9, 'YYYYMM') AS NUMERIC(7)) > ".$nYear.$nMonth.") THEN 1
					 		ELSE 0 END) AS n_on_progress,
					SUM(CASE WHEN CAST( TO_CHAR(ttp.d_process_9, 'YYYYMM') AS NUMERIC(7)) = ".$nYear.$nMonth." AND CAST( TO_CHAR(ttp.d_production_date, 'YYYYMM') AS NUMERIC(6)) = ".$nYear.$nMonth." THEN 1
					 		ELSE 0 END) AS n_in, 
					SUM(CASE WHEN (	CAST( TO_CHAR(ttp.d_process_14, 'YYYYMM') AS NUMERIC(6)) = ".$nYear.$nMonth." AND CAST( TO_CHAR(ttp.d_production_date, 'YYYYMM') AS NUMERIC(6)) = ".$nYear.$nMonth.") THEN 1
					 		ELSE 0 END) AS n_out
			FROM 	tt_production ttp 	INNER JOIN tt_prod_order ttpo 	ON (ttp.u_id_po_no = ttpo.u_id)
										LEFT JOIN tm_customer AS tmc	ON (ttpo.s_buyer = tmc.s_code)
										LEFT JOIN tm_model AS tmm		ON (ttp.s_model = tmm.s_code)
										LEFT JOIN tm_color AS tmcl		ON (ttp.s_color = tmcl.s_code)
			WHERE	CAST( TO_CHAR(ttp.d_production_date, 'YYYY') AS NUMERIC(4)) >= 2011 
					AND CAST( TO_CHAR(ttp.d_production_date, 'YYYYMM') AS NUMERIC(6)) <= ".$nYear.$nMonth." 
					AND ttp.s_division='AG' $sCriteria");
			
		return $oQuery->result_array();
    }
	
	function getLastStock( $sCriteria='', $nMonth='01', $nYear=2009 ) {
		$sCriteria=($sCriteria!='' ? " AND ".$sCriteria : '');
		
		$nFirstStock=0;
		$oQuery = $this->db->query(" SELECT * FROM tm_application ");
		if ($oQuery->num_rows()>0) {
			$oQueryData=$oQuery->first_row();
			$nFirstStock=$oQueryData->n_stock_ag;
		}
		
		$nLastStock = 0;
		$oQuery = $this->db->query("
			SELECT 	COUNT(ttp.s_serial_no) AS n_last_stock
			FROM 	tt_production ttp 	INNER JOIN tt_prod_order ttpo 	ON (ttp.u_id_po_no = ttpo.u_id)
										LEFT JOIN tm_customer AS tmc	ON (ttpo.s_buyer = tmc.s_code)
										LEFT JOIN tm_model AS tmm		ON (ttp.s_model = tmm.s_code)
										LEFT JOIN tm_color AS tmcl		ON (ttp.s_color = tmcl.s_code)
			WHERE	ttp.s_division='AG'
				AND CAST( TO_CHAR(ttp.d_process_9, 'YYYYMM') AS NUMERIC(7)) < ".$nYear.$nMonth."
				AND (CAST( TO_CHAR(ttp.d_process_14, 'YYYYMM') AS NUMERIC(6)) >= ".$nYear.$nMonth." OR ttp.d_process_14 IS NULL)
				AND	CAST( TO_CHAR(ttp.d_production_date, 'YYYY') AS NUMERIC(4)) >= 2011
				AND	CAST( TO_CHAR(ttp.d_production_date, 'YYYYMM') AS NUMERIC(6)) < ".$nYear.$nMonth."
				$sCriteria");
		if ($oQuery->num_rows() > 0) {
			$oQueryData = $oQuery->first_row();
			$nLastStock = $oQueryData->n_last_stock;
		}
		
		return ($nLastStock + $nFirstStock);
	}
	
	function getListDaily( $nMonth=10, $nYear=2009 ){
		$oQuery = $this->db->query("SELECT * FROM tm_prod_setup WHERE s_division='AG' AND s_field_process!='d_warehouse' ORDER BY n_line ASC, n_order ASC ");
		$aTm_prod_setup = $oQuery->result_array();
		$aDatas = array();
		foreach ($aTm_prod_setup as $nRow=>$aPhase) {
			$aData = array();
			$aData['s_code'] = $aPhase['s_phase'];
			$aData['s_name'] = $aPhase['s_description'];
			
			$nTotal=0;
			$sDate = $nYear.'-'.sprintf('%02d',$nMonth);
			$sFieldProcess=$aPhase['s_field_process'];
			$oQuery = $this->db->query("
				SELECT 	EXTRACT(DAY FROM $sFieldProcess) AS hari, COUNT(s_serial_no) AS jml FROM tt_production 
				WHERE 	EXTRACT(MONTH FROM $sFieldProcess) = $nMonth AND
						EXTRACT(YEAR FROM $sFieldProcess) = $nYear AND
						s_division = 'AG'
				GROUP BY EXTRACT(DAY FROM $sFieldProcess)");
			$aCalcData=array();
			if ($oQuery->num_rows()>0) {
				foreach ($oQuery->result_array() as $nRowProcess=>$aDataProcess) {
					$aCalcData['n_date_'.$aDataProcess['hari']] = $aDataProcess['jml'];
					$nTotal+=$aCalcData['n_date_'.$aDataProcess['hari']];
					/*$aData['n_date_'.$aDataProcess['hari']] = $aDataProcess['jml'];
					$nTotal+=$aData['n_date_'.$aDataProcess['hari']];*/
				}
			}
			
			for ($nCount=1; $nCount<=31; $nCount++) {
				if ( !isset($aCalcData['n_date_'.$nCount]) ) {
					$aData['n_date_'.$nCount] = 0;
				} else {
					$aData['n_date_'.$nCount] = $aCalcData['n_date_'.$nCount];
				}
			}
			$aData['n_total'] = $nTotal;
			$aDatas[] = $aData;
		}
		
		return $aDatas;
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
					SUM(ttp.n_warehouse) AS n_warehouse,
					SUM(ttp.n_process_14) AS n_process_14,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_process_9) + SUM(ttp.n_process_10) + SUM(ttp.n_warehouse) + SUM(ttp.n_process_14)) AS n_qty
			FROM 	tv_production_stock_qty_ag AS ttp 
			$sCriteria 
			GROUP BY ttp.s_po_no, ttp.s_po, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date), 
					ttp.d_plan_date, 
					ttp.d_delivery_date, ttp.d_target_date, ttp.s_buyer, ttp.s_buyer_name
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
					SUM(ttp.n_warehouse) AS n_warehouse,
					SUM(ttp.n_process_14) AS n_process_14,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_process_9) + SUM(ttp.n_process_10) + SUM(ttp.n_warehouse) + SUM(ttp.n_process_14)) AS n_qty
			FROM 	tv_production_stock_qty_ag AS ttp $sCriteria 
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
					SUM(ttp.n_warehouse) AS n_warehouse,
					SUM(ttp.n_process_14) AS n_process_14,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_process_9) + SUM(ttp.n_process_10) + SUM(ttp.n_warehouse) + SUM(ttp.n_process_14)) AS n_qty
			FROM 	tv_production_stock_qty_ag AS ttp $sCriteria 
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
			SELECT 	ttp.s_po_no, ttp.s_po, ttp.s_model, ttp.s_model_name, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date, 
					ttp.d_plan_date, ttp.d_delivery_date,
					ttp.d_target_date, ttp.s_buyer, ttp.s_buyer_name,
					ttp.s_smodel, ttp.s_color_name, ttp.s_location, ttp.s_quality,
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
					SUM(ttp.n_warehouse) AS n_warehouse,
					SUM(ttp.n_process_14) AS n_process_14,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_process_9) + SUM(ttp.n_process_10) + SUM(ttp.n_warehouse) + SUM(ttp.n_process_14)) AS n_qty
			FROM 	tv_production_stock_qty_ag AS ttp $sCriteria 
			GROUP BY ttp.s_model, ttp.s_model_name, ttp.s_po_no, ttp.s_po, ttp.s_buyer, ttp.s_buyer_name, ttp.s_smodel, ttp.s_color_name,
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date),
					ttp.d_plan_date, ttp.d_delivery_date, ttp.d_target_date, ttp.s_location, ttp.s_quality
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
					ttp.d_plan_date, ttp.d_delivery_date, 
					ttp.s_po_no, ttp.s_po, ttp.d_target_date, ttp.s_buyer, ttp.s_buyer_name, ttp.s_model, ttp.s_model_name,
					ttp.s_smodel, ttp.s_location, ttp.s_quality,
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
					SUM(ttp.n_warehouse) AS n_warehouse,
					SUM(ttp.n_process_14) AS n_process_14,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_process_9) + SUM(ttp.n_process_10) + SUM(ttp.n_warehouse) + SUM(ttp.n_process_14)) AS n_qty
			FROM 	tv_production_stock_qty_ag AS ttp $sCriteria 
			GROUP BY ttp.s_color, ttp.s_color_name,
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date), 
					ttp.d_plan_date, ttp.d_delivery_date, 
					ttp.s_po_no, ttp.s_po, ttp.d_target_date, ttp.s_buyer, ttp.s_buyer_name, ttp.s_model, ttp.s_model_name,
					ttp.s_smodel, ttp.s_location, ttp.s_quality
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
				tmc.s_name 		AS s_buyer_name,
				tmm.s_smodel 		AS s_smodel,
				tmm.s_description 	AS s_model_name,
				tmcl.s_description 	AS s_color_name
			FROM 	tt_production ttp 	INNER JOIN tt_prod_order ttpo 	ON (ttp.u_id_po_no = ttpo.u_id)
										LEFT JOIN tm_customer AS tmc	ON (ttpo.s_buyer = tmc.s_code)
										LEFT JOIN tm_model AS tmm		ON (ttp.s_model = tmm.s_code)
										LEFT JOIN tm_color AS tmcl		ON (ttp.s_color = tmcl.s_code)
			WHERE	ttp.s_division='AG' 
			$sCriteria 
			$sOrderBy 
			$sLimitRows ");
		
		return $oQuery->result_array();
    }
	
	function getListSerialPhase( $sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$this->load->model('Util_model');
		
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		
        $oQuery = $this->db->query("
			SELECT 	ttp.*
			FROM 	tv_production_stock_date_ag ttp
			$sCriteria 
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
			FROM 	tv_production_stock_date_ag ttp
			$sCriteria 
			$sOrderBy 
			$sLimitRows ");
		
		return $oQuery->result_array();
    }
	
	function getListSerial( $sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$this->load->model('Util_model');
		
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		
        $oQuery = $this->db->query("
			SELECT 	ttp.*
			FROM 	tv_production_stock_qty_ag ttp
			$sCriteria 
			$sOrderBy 
			$sLimitRows ");
		
		return $oQuery->result_array();
    }
}
?>
