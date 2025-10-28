<?php
class Reportcontrol_model extends Model {
	
	function Reportcontrol_model(){
		// Call the Model constructor
		parent::Model();
  
	}
    
    function getListColor( $sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array() ){
		$this->load->model('Util_model');
		
		$sCriteria=($sCriteria!='' ? " AND ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		
        $oQuery = $this->db->query("
			SELECT 	ttpo.s_po_no, ttpo.s_po, ttp.*,
					tmc.s_name AS s_buyer_name,
					tmm.s_code AS s_model,
					tmm.s_smodel AS s_smodel,
					tmm.s_description AS s_model_name,
					tmm.s_type AS s_type_difficult,
					tmcl.s_description AS s_color_name
			FROM 	rpt_production_timecontrol_color ttp 	
										INNER JOIN tt_prod_order ttpo 	ON (ttp.u_id_po_no = ttpo.u_id)
										LEFT JOIN tm_customer AS tmc	ON (ttpo.s_buyer = tmc.s_code)
										LEFT JOIN tm_model AS tmm		ON (ttp.s_model = tmm.s_code)
										LEFT JOIN tm_color AS tmcl		ON (ttp.s_color = tmcl.s_code)
			WHERE	ttp.s_division='AG' $sCriteria 
			$sOrderBy 
			$sLimitRows ");
		
		return $oQuery->result_array();
    }
}
?>
