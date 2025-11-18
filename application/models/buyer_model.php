<?php
class Buyer_model extends Model
{
	var $aContainer = array();

	function Buyer_model()
	{
		// Call the Model constructor
		parent::Model();

		$this->aContainer = array(
			's_code' 		=> array('caption' => 'Buyer Code', 'rules' => 'trim|required', 'view' => 1, 'edit' => 1),
			's_name' 		=> array('caption' => 'Name', 'rules' => 'trim|required', 'view' => 1, 'edit' => 1),
			's_serial_parse' => array('caption' => 'Serial Parse', 'rules' => 'trim|required', 'view' => 1, 'edit' => 1),
			's_notes'		=> array('caption' => 'Notes', 'rules' => '', 'view' => 1, 'edit' => 1),
			's_division'	=> array('caption' => 'Division', 'rules' => '', 'view' => 1, 'edit' => 1),

			's_fullname_1'	=> array('caption' => 'Full Name 1', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_fullname_2'	=> array('caption' => 'Full Name 2', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_manager_name' => array('caption' => 'Manager Name', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_assistant_name' => array('caption' => 'Assistant Name', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_address_1'	=> array('caption' => 'Address 1', 'rules' => '', 'view' => 1, 'edit' => 1),
			's_address_2'	=> array('caption' => 'Address 2', 'rules' => '', 'view' => 1, 'edit' => 1),
			's_country'		=> array('caption' => 'Country', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_phone_1'		=> array('caption' => 'Phone 1', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_phone_2'		=> array('caption' => 'Phone 2', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_fax_1'		=> array('caption' => 'Fax 1', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_fax_2'		=> array('caption' => 'Fax 2', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_email_1'		=> array('caption' => 'Email 1', 'rules' => 'trim|valid_email', 'view' => 1, 'edit' => 1),
			's_email_2'		=> array('caption' => 'Email 2', 'rules' => 'trim|valid_email', 'view' => 1, 'edit' => 1),

			'n_serial_digit' => array('caption' => 'Serial Digit', 'rules' => 'trim|required|integer', 'view' => 1, 'edit' => 1),
			's_serial_reset' => array('caption' => 'Serial Reset', 'rules' => 'trim|required', 'view' => 1, 'edit' => 1),
		);
	}

	function getList($sCriteria = '', $nLimit = 0, $nOffset = 0, $aOrderby = array())
	{
		$this->load->model('Util_model');

		$sCriteria = ($sCriteria != '' ? " WHERE " . $sCriteria : '');
		$sOrderBy = Util_model::getOrderBy($aOrderby);
		$sLimit = ($nLimit == 0) ? "" : "LIMIT $nLimit";
		$sOffset = ($nOffset == 0) ? "" : "OFFSET $nOffset";
		$sLimitRows = $sLimit . " " . $sOffset;

		// $oQuery = $this->db->query("
		// 	SELECT 	*, REPLACE(s_code, '/', '--') AS s_code_2 
		// 	FROM 	tm_customer	
		// 	$sCriteria $sOrderBy $sLimitRows ");

		$oQuery = $this->db->query("
			SELECT 	*, REPLACE(s_code, '/', '--') AS s_code_2,
			REPLACE(s_status, ' ', '--') AS s_status_2
			FROM 	tm_customer	
			$sCriteria $sOrderBy $sLimitRows ");

		return $oQuery->result_array();
	}

	function insert($aData)
	{
		foreach ($aData as $sField => $sValue) {
			if ($sValue == '') { // NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} else {
				$this->db->set($sField, $sValue);
			}
		}
		$this->db->set('d_createtime', 'NOW()', FALSE);
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->insert('tm_customer');

		return $aData['s_code'];
	}

	function update($sCode, $aData)
	{
		foreach ($aData as $sField => $sValue) {
			if ($sValue == '') { // NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} elseif ($sValue != '') {
				$this->db->set($sField, $sValue);
			}
		}
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->where('s_code', $sCode);
		$this->db->update('tm_customer');

		return $sCode;
	}

	function delete($sCode)
	{
		$this->db->where('s_code', $sCode);
		return $this->db->delete('tm_customer');
	}
}

