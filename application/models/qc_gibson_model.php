<?php
class Qc_gibson_model extends Model
{

    // Constructor
    function Qc_gibson_model()
    {
        parent::Model();
    }

    /**
     * Check if a serial number already exists in the tt_checker_gibson table
     */
    function is_exists($sSerialNo)
    {
        $this->db->where('serial_no', strtoupper(trim($sSerialNo)));
        $query = $this->db->get('tt_checker_gibson');
        return $query->num_rows() > 0;
    }

    /**
     * Insert new data into tt_checker_gibson
     */
    function insert_data($aData)
    {
        foreach ($aData as $sField => $sValue) {
            if ($sValue == '') {
                $this->db->set($sField, 'NULL', FALSE); // Set NULL if value is empty
            } else {
                $this->db->set($sField, $sValue);
            }
        }

        // Set uploaded_by if provided
        if (isset($aData['uploaded_by'])) {
            $this->db->set('uploaded_by', $aData['uploaded_by']);
        }

        $rInsert = $this->db->insert('tt_checker_gibson');
        return $rInsert !== FALSE;
    }

    /**
     * Update existing data for a specific serial number
     */
    function update_data($sSerialNo, $aData)
    {
        foreach ($aData as $sField => $sValue) {
            if ($sValue == '') {
                $this->db->set($sField, 'NULL', FALSE);
            } else {
                $this->db->set($sField, $sValue);
            }
        }

        // Update timestamp
        $this->db->set('uploaded_at', 'NOW()', FALSE);

        // Update uploaded_by if provided
        if (isset($aData['uploaded_by'])) {
            $this->db->set('uploaded_by', $aData['uploaded_by']);
        }

        $this->db->where('serial_no', strtoupper(trim($sSerialNo)));
        $rUpdate = $this->db->update('tt_checker_gibson');

        return $rUpdate !== FALSE;
    }

    /**
     * Retrieve all Gibson serial numbers from a given list
     */
    function get_serials_gibson($serials)
    {
        if (empty($serials)) return array();

        // Get all Gibson buyer codes
        $buyerQuery = $this->db->select('s_code')
            ->from('tv_customer_gibson')
            ->get();
        $buyers = array();
        foreach ($buyerQuery->result_array() as $row) {
            $buyers[] = $row['s_code'];
        }

        if (empty($buyers)) return array(); // No Gibson buyers found

        // Get matching serial numbers from tt_production
        $this->db->select('s_serial_no')
            ->from('tt_production')
            ->where_in('s_serial_no', $serials)
            ->where_in('s_buyer', $buyers);

        $query = $this->db->get();
        $result = array();
        foreach ($query->result_array() as $row) {
            $result[] = $row['s_serial_no'];
        }

        return $result;
    }

    /**
     * Retrieve all category defects from tgibson_category_defect table
     */
    function get_all_category_defect()
    {
        $query = $this->db->select('SysId, category_code, category_name')
            ->from('tgibson_category_defect')
            ->order_by('category_code', 'ASC')
            ->get();

        return $query->result_array(); // Return all rows as array
    }

    /**
     * Retrieve single category defect by category_code
     */
    function get_category_defect_by_code($code)
    {
        $query = $this->db->select('SysId, category_code, category_name')
            ->from('tgibson_category_defect')
            ->where('category_code', $code)
            ->get();

        return $query->row_array(); // Return single row
    }
}
