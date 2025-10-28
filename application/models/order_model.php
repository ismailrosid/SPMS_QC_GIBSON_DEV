<?php
class Order_model extends Model
{
	var $aContainer = array();

	function Order_model()
	{
		// Call the Model constructor
		parent::Model();

		$this->aContainer = array(
			's_po_no' 			=> array('caption' => 'PI Number', 'rules' => 'trim|required', 'view' => 1, 'edit' => 1),
			's_po' 				=> array('caption' => 'PO', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_division'		=> array('caption' => 'Division', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_type'			=> array('caption' => 'Type Process', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_location'		=> array('caption' => 'Location', 'rules' => 'trim|required', 'view' => 1, 'edit' => 1),
			'd_order_date' 		=> array('caption' => 'Receive Order', 'rules' => 'trim|required', 'view' => 1, 'edit' => 1),
			'd_production_date'	=> array('caption' => 'Production Date', 'rules' => 'trim|required', 'view' => 1, 'edit' => 1),
			'd_plan_date'		=> array('caption' => 'Production Plan Date (Input)', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			'd_delivery_date' 	=> array('caption' => 'Production Plan Date (Output)', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			'd_target_date'		=> array('caption' => 'Export Plan Date', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_buyer'			=> array('caption' => 'Buyer', 'rules' => 'trim|required|callback_buyer_check', 'view' => 1, 'edit' => 1),
			's_brand' 			=> array('caption' => 'Brand', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_bench'			=> array('caption' => 'Bench', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_model' 			=> array('caption' => 'Model', 'rules' => 'trim|callback_model_check', 'view' => 1, 'edit' => 1),
			// 's_smodel'			=> array('caption'=>'Item Code','rules'=>'trim','view'=>1,'edit'=>1), 
			's_color'			=> array('caption' => 'Color', 'rules' => 'trim|callback_color_check', 'view' => 1, 'edit' => 1),
			'n_qty' 			=> array('caption' => 'Qty', 'rules' => 'trim|required|integer', 'view' => 1, 'edit' => 1),
			'n_price'			=> array('caption' => 'Price', 'rules' => 'trim|numeric', 'view' => 1, 'edit' => 1),
			'n_amount'			=> array('caption' => 'Amount', 'rules' => 'trim|numeric', 'view' => 1, 'edit' => 1),
			's_upc_code'		=> array('caption' => 'UPC Code', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_ship1'			=> array('caption' => 'Ship 1', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_ship2'			=> array('caption' => 'Ship 2', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			'n_rank1'			=> array('caption' => 'Rank 1', 'rules' => 'trim|integer', 'view' => 1, 'edit' => 1),
			'n_rank2'			=> array('caption' => 'Rank 2', 'rules' => 'trim|integer', 'view' => 1, 'edit' => 1),
			's_proforma'		=> array('caption' => 'Proforma', 'rules' => 'trim', 'view' => 1, 'edit' => 1),
			's_notes1'			=> array('caption' => 'Notes 1', 'rules' => '', 'view' => 1, 'edit' => 1),
			's_notes2'			=> array('caption' => 'Notes 2', 'rules' => '', 'view' => 1, 'edit' => 1),
			'n_begin_number'	=> array('caption' => 'Serial Begin', 'rules' => 'trim|integer', 'view' => 1, 'edit' => 1)
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

		$oQuery = $this->db->query("
			SELECT 	tmc.s_name AS s_buyer_name,
				tmm.s_description AS s_model_name,
				tmcl.s_description AS s_color_name,
				tmm.s_smodel AS s_smodel,
				ttpo.*
			FROM 	tt_prod_order AS ttpo	LEFT JOIN tm_customer AS tmc	ON (ttpo.s_buyer = tmc.s_code)
			LEFT JOIN tm_model AS tmm		ON (ttpo.s_model = tmm.s_code)
			LEFT JOIN tm_color AS tmcl		ON (ttpo.s_color = tmcl.s_code)
			$sCriteria $sOrderBy $sLimitRows ");

		return $oQuery->result_array();
	}

	function getListCount($sCriteria = '')
	{
		$sCriteria = ($sCriteria != '' ? " WHERE " . $sCriteria : '');
		$oQuery = $this->db->query("
			SELECT 	COUNT(ttpo.u_id) AS n_count, SUM(ttpo.n_qty) AS n_qty
			FROM 	tt_prod_order AS ttpo	LEFT JOIN tm_customer AS tmc	ON (ttpo.s_buyer = tmc.s_code)
			LEFT JOIN tm_model AS tmm		ON (ttpo.s_model = tmm.s_code)
			LEFT JOIN tm_color AS tmcl		ON (ttpo.s_color = tmcl.s_code)
			$sCriteria ");
		$aTotal = array();
		$aTotal['n_count'] = 0;
		$aTotal['n_qty'] = 0;
		if ($oQuery->num_rows() > 0) {
			$oData = $oQuery->first_row();
			$aTotal['n_count'] = $oData->n_count;
			$aTotal['n_qty'] = $oData->n_qty;
		}
		return $aTotal;
	}

	function insert($aData, $sDivision, $nSerialNo1 = '')
	{
		$CI = &get_instance();
		$CI->load->model('Util_model');

		$uId = $CI->Util_model->getUuid();
		$this->db->trans_begin();

		foreach ($aData as $sField => $sValue) {
			if ($sValue == '') { // NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} else {
				$this->db->set($sField, $sValue);
			}
		}

		if (empty($aData['s_type'])) {
			$this->db->set('s_type', 'set');
		}

		$this->db->set('u_id', $uId);
		$this->db->set('s_division', $sDivision);
		$this->db->set('d_createtime', 'NOW()', FALSE);
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		$this->db->insert('tt_prod_order');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return $uId;
		}
	}

	function update($aKey, $aValue, $aData, $sDivision)
	{
		$this->db->trans_begin();

		foreach ($aData as $sField => $sValue) {
			if ($sValue == '') { // NULL value
				$this->db->set($sField, 'NULL', FALSE);
			} elseif ($sValue != '') {
				$this->db->set($sField, $sValue);
			}
		}
		($aKey == 'u_id') ? $this->db->set('s_division', $sDivision) : null;
		// $this->db->set('s_division', $sDivision);
		$this->db->set('d_lastupdate', 'NOW()', FALSE);
		// $this->db->where('u_id', $uId);
		$this->db->where($aKey, $aValue);
		($aKey == 'u_id') ? $this->db->where('s_division', $sDivision) : null;
		// $this->db->where('s_division', $sDivision);
		$this->db->update('tt_prod_order');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return $aValue;
		}
	}

	function delete($uId, $sDivision)
	{
		$this->db->where('u_id', $uId);
		$this->db->where('s_division', $sDivision);
		return $this->db->delete('tt_prod_order');
	}

	function getListSimple($sCriteria)
	{
		$sCriteria = ($sCriteria != '' ? " WHERE " . $sCriteria : '');
		$oQuery = $this->db->query(" SELECT * FROM 	tt_prod_order	$sCriteria ");
		return $oQuery->result_array();
	}

	function getColor($sCode)
	{
		$sCriteria = "WHERE mc.s_model = '$sCode'";
		$oQuery = $this->db->query("
			SELECT c.*
			FROM tm_color c
			INNER JOIN tm_model_color mc ON (c.s_code = mc.s_color) $sCriteria");

		return $oQuery->result_array();
	}
}