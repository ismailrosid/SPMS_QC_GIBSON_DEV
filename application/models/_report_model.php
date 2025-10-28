<?php
class Report_model extends Model {
		
	function Report_model(){
		// Call the Model constructor
		parent::Model();
  
	}
    
    function getListStock( $sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$this->load->model('Util_model');
		
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		
        $oQuery = $this->db->query("
				SELECT ttp.s_po_no, EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date,
					ttp.d_target_date, ttp.d_delivery_date, ttp.s_buyer, ttp.s_model, ttp.s_color, 
					ttp.s_serial_no, ttp.s_lot_no, ttp.d_plan_date,
					(SELECT COUNT(ttpc.s_serial_no) FROM tt_production AS ttpc WHERE ttpc.s_serial_no=ttp.s_serial_no AND EXTRACT(MONTH FROM ttpc.d_production_date)= (EXTRACT(MONTH FROM ttpc.d_production_date)-1) AND ttpc.d_process_14 IS NULL AND ttpc.d_process_15 IS NULL) AS n_first_stock,
					(SELECT COUNT(ttpc.s_serial_no) FROM tt_production AS ttpc WHERE ttpc.s_serial_no=ttp.s_serial_no AND ttpc.d_process_8 IS NULL) AS n_on_progress,
					(SELECT COUNT(ttpc.s_serial_no) FROM tt_production AS ttpc WHERE ttpc.s_serial_no=ttp.s_serial_no AND ttpc.d_process_8 IS NOT NULL)AS n_in, 
					(SELECT COUNT(ttpc.s_serial_no) FROM tt_production AS ttpc WHERE ttpc.s_serial_no=ttp.s_serial_no AND (ttpc.d_process_14 IS NOT NULL OR ttpc.d_process_15 IS NOT NULL))AS n_out,
					((SELECT COUNT(ttpc.s_serial_no) FROM tt_production AS ttpc WHERE ttpc.s_serial_no=ttp.s_serial_no AND EXTRACT(MONTH FROM ttpc.d_production_date)= (EXTRACT(MONTH FROM ttpc.d_production_date)-1) AND ttpc.d_process_14 IS NULL AND ttpc.d_process_15 IS NULL) + (SELECT COUNT(ttpc.s_serial_no) FROM tt_production AS ttpc WHERE ttpc.s_serial_no=ttp.s_serial_no AND ttpc.d_process_8 IS NULL) -  (SELECT COUNT(ttpc.s_serial_no) FROM tt_production AS ttpc WHERE ttpc.s_serial_no=ttp.s_serial_no AND (ttpc.d_process_14 IS NOT NULL OR ttpc.d_process_15 IS NOT NULL)))AS n_last_stock	
				FROM tt_production AS ttp 
				$sCriteria $sOrderBy $sLimitRows ");
		
		return $oQuery->result_array();
    }
	
	function getListDaily( $nMonth=10, $nYear=2009 ){
		$oQuery = $this->db->query("SELECT * FROM tm_prod_setup WHERE s_division='AG' ORDER BY n_line ASC, n_order ASC ");
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
						EXTRACT(YEAR FROM $sFieldProcess) = $nYear
				GROUP BY EXTRACT(DAY FROM $sFieldProcess)");
			if ($oQuery->num_rows()>0) {
				foreach ($oQuery->result_array() as $nRowProcess=>$aDataProcess) {
					$aData['n_date_'.$aDataProcess['hari']] = $aDataProcess['jml'];
					$nTotal+=$aData['n_date_'.$aDataProcess['hari']];
				}
			}
			
			
			for ($nCount=1; $nCount<=31; $nCount++) {
				if ( !isset($aData['n_date_'.$nCount]) ) {
					$aData['n_date_'.$nCount] = 0;
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
			SELECT 	ttp.s_po_no, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date, 
					ttp.d_plan_date, ttp.d_delivery_date,
					ttp.d_target_date, ttp.s_buyer, 
					SUM(ttp.n_process_1) AS n_process_1,
					SUM(ttp.n_process_2) AS n_process_2,
					SUM(ttp.n_process_3) AS n_process_3,
					SUM(ttp.n_process_4) AS n_process_4,
					SUM(ttp.n_process_5) AS n_process_5,
					SUM(ttp.n_process_6) AS n_process_6,
					SUM(ttp.n_process_7) AS n_process_7,
					SUM(ttp.n_process_8) AS n_process_8,
					SUM(ttp.n_warehouse) AS n_warehouse,
					SUM(ttp.n_process_14) AS n_process_14,
					SUM(ttp.n_process_15) AS n_process_15,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_warehouse) + SUM(ttp.n_process_14) + SUM(ttp.n_process_15)) AS n_qty
			FROM 	tv_production_stock_qty_ag AS ttp 
			$sCriteria 
			GROUP BY ttp.s_po_no, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date), 
					ttp.d_plan_date, 
					ttp.d_delivery_date, ttp.d_target_date, ttp.s_buyer
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
			SELECT 	ttp.s_po_no, ttp.s_lot_no, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date, 
					ttp.d_plan_date, ttp.d_delivery_date,
					ttp.d_target_date, ttp.s_buyer, 
					SUM(ttp.n_process_1) AS n_process_1,
					SUM(ttp.n_process_2) AS n_process_2,
					SUM(ttp.n_process_3) AS n_process_3,
					SUM(ttp.n_process_4) AS n_process_4,
					SUM(ttp.n_process_5) AS n_process_5,
					SUM(ttp.n_process_6) AS n_process_6,
					SUM(ttp.n_process_7) AS n_process_7,
					SUM(ttp.n_process_8) AS n_process_8,
					SUM(ttp.n_warehouse) AS n_warehouse,
					SUM(ttp.n_process_14) AS n_process_14,
					SUM(ttp.n_process_15) AS n_process_15,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_warehouse) + SUM(ttp.n_process_14) + SUM(ttp.n_process_15)) AS n_qty
			FROM 	tv_production_stock_qty_ag AS ttp $sCriteria 
			GROUP BY ttp.s_lot_no, ttp.s_po_no, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date),
					ttp.d_plan_date, 
					ttp.d_delivery_date, ttp.d_target_date, ttp.s_buyer
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
					ttp.d_target_date, ttp.s_buyer, 
					SUM(ttp.n_process_1) AS n_process_1,
					SUM(ttp.n_process_2) AS n_process_2,
					SUM(ttp.n_process_3) AS n_process_3,
					SUM(ttp.n_process_4) AS n_process_4,
					SUM(ttp.n_process_5) AS n_process_5,
					SUM(ttp.n_process_6) AS n_process_6,
					SUM(ttp.n_process_7) AS n_process_7,
					SUM(ttp.n_process_8) AS n_process_8,
					SUM(ttp.n_warehouse) AS n_warehouse,
					SUM(ttp.n_process_14) AS n_process_14,
					SUM(ttp.n_process_15) AS n_process_15,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_warehouse) + SUM(ttp.n_process_14) + SUM(ttp.n_process_15)) AS n_qty
			FROM 	tv_production_stock_qty_ag AS ttp $sCriteria 
			GROUP BY ttp.s_buyer, 
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
			SELECT 	ttp.s_po_no, ttp.s_lot_no, s_model, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date, 
					ttp.d_plan_date, ttp.d_delivery_date,
					ttp.d_target_date, ttp.s_buyer, 
					SUM(ttp.n_process_1) AS n_process_1,
					SUM(ttp.n_process_2) AS n_process_2,
					SUM(ttp.n_process_3) AS n_process_3,
					SUM(ttp.n_process_4) AS n_process_4,
					SUM(ttp.n_process_5) AS n_process_5,
					SUM(ttp.n_process_6) AS n_process_6,
					SUM(ttp.n_process_7) AS n_process_7,
					SUM(ttp.n_process_8) AS n_process_8,
					SUM(ttp.n_warehouse) AS n_warehouse,
					SUM(ttp.n_process_14) AS n_process_14,
					SUM(ttp.n_process_15) AS n_process_15,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_warehouse) + SUM(ttp.n_process_14) + SUM(ttp.n_process_15)) AS n_qty
			FROM 	tv_production_stock_qty_ag AS ttp $sCriteria 
			GROUP BY s_model, ttp.s_po_no, ttp.s_lot_no, ttp.s_buyer, 
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date),
					ttp.d_plan_date, ttp.d_delivery_date, ttp.d_target_date
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
			SELECT  ttp.s_color,
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date, 
					ttp.d_plan_date, ttp.d_delivery_date, 
					ttp.s_po_no, ttp.s_lot_no, ttp.d_target_date, ttp.s_buyer, ttp.s_model,
					SUM(ttp.n_process_1) AS n_process_1,
					SUM(ttp.n_process_2) AS n_process_2,
					SUM(ttp.n_process_3) AS n_process_3,
					SUM(ttp.n_process_4) AS n_process_4,
					SUM(ttp.n_process_5) AS n_process_5,
					SUM(ttp.n_process_6) AS n_process_6,
					SUM(ttp.n_process_7) AS n_process_7,
					SUM(ttp.n_process_8) AS n_process_8,
					SUM(ttp.n_warehouse) AS n_warehouse,
					SUM(ttp.n_process_14) AS n_process_14,
					SUM(ttp.n_process_15) AS n_process_15,
					(SUM(ttp.n_process_1) + SUM(ttp.n_process_2) + SUM(ttp.n_process_3) + SUM(ttp.n_process_4) + SUM(ttp.n_process_5) + SUM(ttp.n_process_6) + SUM(ttp.n_process_7) + SUM(ttp.n_process_8) + SUM(ttp.n_warehouse) + SUM(ttp.n_process_14) + SUM(ttp.n_process_15)) AS n_qty
			FROM 	tv_production_stock_qty_ag AS ttp $sCriteria 
			GROUP BY ttp.s_color,
					EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date), 
					ttp.d_plan_date, ttp.d_delivery_date, 
					ttp.s_po_no, ttp.s_lot_no, ttp.d_target_date, ttp.s_buyer, ttp.s_model, ttp.d_createtime
			$sOrderBy $sLimitRows ");
		
		return $oQuery->result_array();
	}
}
?>