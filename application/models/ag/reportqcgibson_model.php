<?php
class reportQcGibson_model extends Model {

    function reportQcGibson_model() {
        parent::Model();
    }

    /**
     * Get list grouped by PO
     */
    function getListPo($sCriteria = '', $nLimit = 0, $nOffset = 0, $aOrderby = array()) {
        $sCriteria = ($sCriteria != '' ? " WHERE " . $sCriteria : '');
        $sOrderBy = Util_model::getOrderBy($aOrderby);
        $sLimit = ($nLimit == 0) ? "" : "LIMIT " . $nLimit;
        $sOffset = ($nOffset == 0) ? "" : "OFFSET " . $nOffset;
        $sLimitRows = $sLimit . " " . $sOffset;

        $sQuery = "
            SELECT  
                ttp.s_po_no, 
                ttp.s_po, 
                EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date, 
                ttp.d_plan_date, 
                ttp.d_delivery_date,
                ttp.d_target_date, 
                ttp.s_buyer, 
                ttp.s_buyer_name, 
                ttp.s_location,
                COUNT(ttp.d_process_1) AS n_process_1,
                COUNT(ttp.d_process_2) AS n_process_2,
                COUNT(ttp.d_process_3) AS n_process_3,
                COUNT(ttp.d_process_4) AS n_process_4,
                COUNT(ttp.d_process_5) AS n_process_5,
                COUNT(ttp.d_process_6) AS n_process_6,
                COUNT(ttp.d_process_7) AS n_process_7,
                COUNT(ttp.d_process_8) AS n_process_8,
                COUNT(ttp.d_process_9) AS n_process_9,
                COUNT(ttp.d_process_10) AS n_process_10,
                COUNT(ttp.d_process_14) AS n_process_14,
                (COUNT(ttp.d_process_1) + COUNT(ttp.d_process_2) + COUNT(ttp.d_process_3) + COUNT(ttp.d_process_4) + 
                 COUNT(ttp.d_process_5) + COUNT(ttp.d_process_6) + COUNT(ttp.d_process_7) + COUNT(ttp.d_process_8) + 
                 COUNT(ttp.d_process_9) + COUNT(ttp.d_process_10) + COUNT(ttp.d_process_14)) AS n_qty
            FROM tv_production_date_ag_spesific_gibson AS ttp 
            $sCriteria 
            GROUP BY 
                ttp.s_po_no, 
                ttp.s_po, 
                EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date), 
                ttp.d_plan_date, 
                ttp.d_delivery_date, 
                ttp.d_target_date, 
                ttp.s_buyer, 
                ttp.s_buyer_name, 
                ttp.s_location
            $sOrderBy $sLimitRows
        ";

        $oQuery = $this->db->query($sQuery);
        return $oQuery->result_array();
    }

    /**
     * Get list grouped by Lot Number
     */
    function getListLot($sCriteria = '', $nLimit = 0, $nOffset = 0, $aOrderby = array()) {
        $sCriteria = ($sCriteria != '' ? " WHERE " . $sCriteria : '');
        $sOrderBy = Util_model::getOrderBy($aOrderby);
        $sLimit = ($nLimit == 0) ? "" : "LIMIT " . $nLimit;
        $sOffset = ($nOffset == 0) ? "" : "OFFSET " . $nOffset;
        $sLimitRows = $sLimit . " " . $sOffset;

        $sQuery = "
            SELECT  
                ttp.s_po_no, 
                ttp.s_po, 
                ttp.s_lot_no, 
                EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date, 
                ttp.d_plan_date, 
                ttp.d_delivery_date,
                ttp.d_target_date, 
                ttp.s_buyer, 
                ttp.s_buyer_name, 
                COUNT(ttp.d_process_1) AS n_process_1,
                COUNT(ttp.d_process_2) AS n_process_2,
                COUNT(ttp.d_process_3) AS n_process_3,
                COUNT(ttp.d_process_4) AS n_process_4,
                COUNT(ttp.d_process_5) AS n_process_5,
                COUNT(ttp.d_process_6) AS n_process_6,
                COUNT(ttp.d_process_7) AS n_process_7,
                COUNT(ttp.d_process_8) AS n_process_8,
                COUNT(ttp.d_process_9) AS n_process_9,
                COUNT(ttp.d_process_10) AS n_process_10,
                COUNT(ttp.d_process_14) AS n_process_14,
                (COUNT(ttp.d_process_1) + COUNT(ttp.d_process_2) + COUNT(ttp.d_process_3) + COUNT(ttp.d_process_4) + 
                 COUNT(ttp.d_process_5) + COUNT(ttp.d_process_6) + COUNT(ttp.d_process_7) + COUNT(ttp.d_process_8) + 
                 COUNT(ttp.d_process_9) + COUNT(ttp.d_process_10) + COUNT(ttp.d_process_14)) AS n_qty
            FROM tv_production_date_ag_spesific_gibson AS ttp 
            $sCriteria 
            GROUP BY 
                ttp.s_lot_no, 
                ttp.s_po_no, 
                ttp.s_po, 
                EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date),
                ttp.d_plan_date, 
                ttp.d_delivery_date, 
                ttp.d_target_date, 
                ttp.s_buyer, 
                ttp.s_buyer_name
            $sOrderBy $sLimitRows
        ";

        $oQuery = $this->db->query($sQuery);
        return $oQuery->result_array();
    }

    /**
     * Get list grouped by Model
     */
    function getListModel($sCriteria = '', $nLimit = 0, $nOffset = 0, $aOrderby = array()) {
        $sCriteria = ($sCriteria != '' ? " WHERE " . $sCriteria : '');
        $sOrderBy = Util_model::getOrderBy($aOrderby);
        $sLimit = ($nLimit == 0) ? "" : "LIMIT " . $nLimit;
        $sOffset = ($nOffset == 0) ? "" : "OFFSET " . $nOffset;
        $sLimitRows = $sLimit . " " . $sOffset;

        $sQuery = "
            SELECT  
                ttp.s_po_no, 
                ttp.s_po, 
                ttp.s_model, 
                ttp.s_model_name, 
                ttp.s_color_name, 
                ttp.s_smodel, 
                ttp.s_quality,
                EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date) AS d_production_date, 
                ttp.d_plan_date, 
                ttp.d_delivery_date,
                ttp.d_target_date, 
                ttp.s_buyer, 
                ttp.s_buyer_name, 
                ttp.s_location,
                COUNT(ttp.d_process_1) AS n_process_1,
                COUNT(ttp.d_process_2) AS n_process_2,
                COUNT(ttp.d_process_3) AS n_process_3,
                COUNT(ttp.d_process_4) AS n_process_4,
                COUNT(ttp.d_process_5) AS n_process_5,
                COUNT(ttp.d_process_6) AS n_process_6,
                COUNT(ttp.d_process_7) AS n_process_7,
                COUNT(ttp.d_process_8) AS n_process_8,
                COUNT(ttp.d_process_9) AS n_process_9,
                COUNT(ttp.d_process_10) AS n_process_10,
                COUNT(ttp.d_process_14) AS n_process_14,
                (COUNT(ttp.d_process_1) + COUNT(ttp.d_process_2) + COUNT(ttp.d_process_3) + COUNT(ttp.d_process_4) + 
                 COUNT(ttp.d_process_5) + COUNT(ttp.d_process_6) + COUNT(ttp.d_process_7) + COUNT(ttp.d_process_8) + 
                 COUNT(ttp.d_process_9) + COUNT(ttp.d_process_10) + COUNT(ttp.d_process_14)) AS n_qty
            FROM tv_production_date_ag_spesific_gibson AS ttp 
            $sCriteria 
            GROUP BY 
                ttp.s_model, 
                ttp.s_model_name, 
                ttp.s_color_name, 
                ttp.s_smodel, 
                ttp.s_po_no, 
                ttp.s_po, 
                ttp.s_buyer, 
                ttp.s_buyer_name, 
                EXTRACT(YEAR FROM ttp.d_production_date) || '-' || EXTRACT(MONTH FROM ttp.d_production_date),
                ttp.d_plan_date, 
                ttp.d_delivery_date, 
                ttp.d_target_date, 
                ttp.s_location, 
                ttp.s_quality
            $sOrderBy $sLimitRows
        ";

        $oQuery = $this->db->query($sQuery);
        return $oQuery->result_array();
    }
}
?>
