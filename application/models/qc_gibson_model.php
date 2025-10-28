<?php
class Qc_gibson_model extends Model {

    function Qc_gibson_model() {
        parent::Model();
    }

    /**
     * Cek apakah serial number sudah ada di tabel tt_checker_gibson
     */
    function is_exists($sSerialNo) {
        $this->db->where('serial_no', strtoupper(trim($sSerialNo)));
        $query = $this->db->get('tt_checker_gibson');
        if ($query->num_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Insert data baru
     */
    function insert_data($aData) {
        foreach ($aData as $sField => $sValue) {
            if ($sValue == '') {
                $this->db->set($sField, 'NULL', FALSE);
            } else {
                $this->db->set($sField, $sValue);
            }
        }

        // uploaded_by dari session/user input
        if (isset($aData['uploaded_by'])) {
            $this->db->set('uploaded_by', $aData['uploaded_by']);
        }

        $rInsert = $this->db->insert('tt_checker_gibson');
        if ($rInsert === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Update data existing
     */
    function update_data($sSerialNo, $aData) {
        foreach ($aData as $sField => $sValue) {
            if ($sValue == '') {
                $this->db->set($sField, 'NULL', FALSE);
            } else {
                $this->db->set($sField, $sValue);
            }
        }

        $this->db->set('uploaded_at', 'NOW()', FALSE);
        if (isset($aData['uploaded_by'])) {
            $this->db->set('uploaded_by', $aData['uploaded_by']);
        }

        $this->db->where('serial_no', strtoupper(trim($sSerialNo)));
        $rUpdate = $this->db->update('tt_checker_gibson');

        if ($rUpdate === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

/**
 * Ambil semua serial Gibson sekaligus
 */
function get_serials_gibson($serials) {
    if (empty($serials)) return array();

    // Ambil semua kode buyer Gibson dulu
    $buyerQuery = $this->db->select('s_code')
                           ->from('tv_customer_gibson')
                           ->get();
    $buyers = array();
    foreach ($buyerQuery->result_array() as $row) {
        $buyers[] = $row['s_code'];
    }

    if (empty($buyers)) return array(); // Tidak ada buyer Gibson

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
 * Ambil serial yang masih pending assembly II
 */
function get_pending_assembly_ii($serials) {
    if (empty($serials)) return array();

    $this->db->select('s_serial_no')
             ->from('tv_production_pending_process_9')
             ->where_in('s_serial_no', $serials);

    $query = $this->db->get();
    $result = array();
    foreach ($query->result_array() as $row) {
        $result[] = $row['s_serial_no'];
    }
    return $result;
}

}
?>
