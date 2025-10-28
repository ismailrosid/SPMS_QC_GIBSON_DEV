<?php

class Transaction extends Controller
{
	var $sUsername;
	var $sLevel;

	var $nRowsPerPage = 50;

	var $_bModal = false;
	var $_sModalTarget = '';

	var $rules = array();
	var $fields = array();

	var $sErrorMessage = '';
	var $aDefaultForm = array();

	var $aContainer = array();
	var $aDivision = array();

	function Transaction()
	{
		parent::Controller();
		$this->load->library('session');

		$this->sUsername = $this->session->userdata('s_username');
		$this->sLevel = $this->session->userdata('s_level');

		$this->load->model('Transaction_model');
		$this->load->model('Product_model');
		$this->load->model('Util_model');

		$this->load->helper('url');
		$this->load->library('form');

		$this->load->library('parser');

		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="message_error">', '</div>');
		$this->load->library('validatejs');

		foreach ($this->Transaction_model->aContainer as $sKey => $aProperties) {
			if ($aProperties['edit'] == 1) {
				$this->rules[$sKey] = $aProperties['rules'];
				$this->fields[$sKey] = $aProperties['caption'];
			}
		}

		$this->aDivision = $this->config->item('division');
	}

	function index($sDivision, $sMessage = '')
	{
		if (!$this->session->userdata('b_ag_transaction_read') && strtoupper($sDivision) == 'AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_transaction_read') && strtoupper($sDivision) == 'EG') show_error('Access Denied');

		$sCriteria = '';
		$sPhaseName = 'ttp.d_process_1';
		/* -- Searching -- */
		$aCriteria = array();
		$aSessionSearch = $this->session->userdata('transaction_search');
		// Changed by ismailrosid
		$aSearchForm = array(
			's_serial_no_filter'			=> '',
			's_serial_no2_filter'			=> '',
			's_po_no_filter'			=> '',
			's_po_filter'				=> '',
			's_sku'				=> '',
			's_phase_filter'			=> '',
			'ago_d_transaction_date_filter'		=> '',
			'now_d_transaction_date_filter'		=> '',
			's_buyer_filter'			=> '',
			's_model_filter'			=> '',
			's_color_filter'			=> '',
			's_smodel_filter'			=> '',
			'n_line_filter'				=> '',
			'd_production_date_month_filter'	=> '',
			'd_production_date_year_filter'		=> '',
			's_location_filter'			=> '',
			'sSort'					=> 'd_createtime', 'sSortMethod'	=> 'DESC'
		);
		// End
		$aSessionSearchForm = $this->session->userdata('transaction_search_form');

		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort'] => $aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('transaction_sort');

		/* -- Pagination -- */
		$nOffset = 0;
		$nLimit = $this->nRowsPerPage;
		$aPagination = array();
		$aSessionPagination = $this->session->userdata('transaction_pagination');

		if (isset($_POST['nOffset'])) {
			/* -- Searching -- */
			if (!empty($aSessionSearch)) {
				$this->session->unset_userdata('transaction_search');
				$this->session->unset_userdata('transaction_search_form');
			}

			if ($this->input->post('s_phase_filter')) {
				$sPhaseName = $this->input->post('s_phase_filter');
				$aCriteria[] = "ttp.$sPhaseName IS NOT NULL";
			}
			if ($this->input->post('s_serial_no_filter') && !$this->input->post('s_serial_no2_filter')) {
				$s_serial_no_filter = $this->input->post('s_serial_no_filter');
				$aCriteria[] = "ttp.s_serial_no ILIKE '%$s_serial_no_filter%'";
			}
			if ($this->input->post('s_serial_no_filter') && $this->input->post('s_serial_no2_filter')) {
				$s_serial_no_filter = $this->input->post('s_serial_no_filter');
				$s_serial_no2_filter = $this->input->post('s_serial_no2_filter');
				$aCriteria[] = "(UPPER(ttp.s_serial_no) BETWEEN UPPER('$s_serial_no_filter') AND UPPER('$s_serial_no2_filter'))";
			}
			if ($this->input->post('s_color_filter')) {
				$s_color_filter = $this->input->post('s_color_filter');
				$aCriteria[] = "tmcl.s_description ILIKE '%$s_color_filter%'";
			}
			if ($this->input->post('s_po_no_filter')) {
				$s_po_no_filter = $this->input->post('s_po_no_filter');
				$aCriteria[] = "ttpo.s_po_no ILIKE '%$s_po_no_filter%'";
			}
			if ($this->input->post('s_po_filter')) {
				$s_po_filter = $this->input->post('s_po_filter');
				$aCriteria[] = "ttpo.s_po ILIKE '%$s_po_filter%'";
			}
			if ($this->input->post('s_buyer_filter')) {
				$s_buyer_filter = $this->input->post('s_buyer_filter');
				$aCriteria[] = "ttp.s_buyer = '$s_buyer_filter'";
			}
			if ($this->input->post('s_model_filter')) {
				$s_model_filter = $this->input->post('s_model_filter');
				$aCriteria[] = "tmm.s_description ILIKE '%$s_model_filter%'";
			}
			if ($this->input->post('s_smodel_filter')) {
				$s_smodel_filter = $this->input->post('s_smodel_filter');
				$aCriteria[] = "tmm.s_smodel ILIKE '%$s_smodel_filter%'";
			}
			if ($this->input->post('d_production_date_month_filter')) {
				$d_production_date_month_filter = $this->input->post('d_production_date_month_filter');
				$aCriteria[] = "EXTRACT(MONTH FROM ttp.d_production_date)='$d_production_date_month_filter'";
			}
			if ($this->input->post('d_production_date_year_filter')) {
				$d_production_date_year_filter = $this->input->post('d_production_date_year_filter');
				$aCriteria[] = "EXTRACT(YEAR FROM ttp.d_production_date)='$d_production_date_year_filter'";
			}
			// Changed By ismairosid
			if ($this->input->post('ago_d_transaction_date_filter') && $this->input->post('now_d_transaction_date_filter')) {
				// To capture ago data
				$ago_d_transaction_date_filter = $this->input->post('ago_d_transaction_date_filter');
				// To capture now data
				$now_d_transaction_date_filter = $this->input->post('now_d_transaction_date_filter');
				$aCriteria[] = "TO_CHAR(ttp." . $this->input->post('s_phase_filter') . ", 'YYYY-MM-DD') BETWEEN '$ago_d_transaction_date_filter' AND '$now_d_transaction_date_filter'";
			}
			// End
			if ($this->input->post('n_line_filter')) {
				$n_line_filter = $this->input->post('n_line_filter');
				$aCriteria[] = "ttp.n_line = $n_line_filter";
			}
			if ($this->input->post('s_location_filter')) {
				$s_location_filter = $this->input->post('s_location_filter');
				$aCriteria[] = "ttp.s_location = '$s_location_filter'";
			}
			if ($this->input->post('s_sku')) {
				$s_sku = $this->input->post('s_sku');
				$aCriteria[] = "ttp.s_model ILIKE '%$s_sku%'";
			}



			foreach ($aSearchForm as $sField => $sValue) $aSearchForm[$sField] = $this->input->post($sField);
			if (!empty($aCriteria)) {
				$this->session->set_userdata(array('transaction_search' => $aCriteria));
				$this->session->set_userdata(array('transaction_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if (!empty($aSessionSort)) $this->session->unset_userdata('transaction_sort');
			if ($this->input->post('sSort') && $this->input->post('sSortMethod')) {
				$sSort = $this->input->post('sSort');
				$sSortMethod = $this->input->post('sSortMethod');
				if ($this->input->post('bSortAction')) {
					$sSortMethod = ($this->input->post('sSortMethod') == 'ASC' ? 'DESC' : 'ASC');
					$aSearchForm['sSortMethod'] = $sSortMethod;
				}
				$aSort = array($sSort => $sSortMethod);
			}
			if (!empty($aSort)) $this->session->set_userdata(array('transaction_sort' => $aSort));

			/* -- Pagination -- */
			if (!empty($aSessionPagination)) $this->session->unset_userdata('transaction_pagination');
			$aPagination['nOffset'] = ($this->input->post('nOffset') ? $this->input->post('nOffset') : 0);
			$aPagination['nLimit'] = ($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage);
			if (!empty($aPagination)) $this->session->set_userdata(array('transaction_pagination' => $aPagination));
		} else {
			/* -- Default View -- */
			$sPhaseName = (empty($aSearchForm['s_phase_filter']) ? 'd_process_1' : $aSearchForm['s_phase_filter']);
			// Changed by ismailrosid
			if (empty($aSessionSearch['ago_d_transaction_date_filter']) && empty($aSessionSearch['now_d_transaction_date_filter'])) {
				// Create now data
				$now_d_transaction_date_filter = date('Y-m-d');
				// Create ago data
				$ago_d_transaction_date_filter = date('Y-m-d', strtotime('-5 days', strtotime($now_d_transaction_date_filter)));
				$sPartCriteria = "TO_CHAR(ttp.$sPhaseName, 'YYYY-MM-DD') BETWEEN '$ago_d_transaction_date_filter' AND '$now_d_transaction_date_filter'";
				$aCriteria[] = $sPartCriteria;
				$aSearch['now_d_transaction_date_filter'] = $sPartCriteria;
				$aSearchForm['now_d_transaction_date_filter'] = $now_d_transaction_date_filter;
				$aSearch['ago_d_transaction_date_filter'] = $sPartCriteria;
				$aSearchForm['ago_d_transaction_date_filter'] = $ago_d_transaction_date_filter;
			}
			// End
			if (empty($aSessionSearch['d_production_date_month_filter'])) {
				$d_production_date_month_filter = date('m');
				$sPartCriteria = "EXTRACT(MONTH FROM ttp.d_production_date)='$d_production_date_month_filter'";
				$aCriteria[] = $sPartCriteria;
				$aSearch['d_production_date_month_filter'] = $sPartCriteria;
				$aSearchForm['d_production_date_month_filter'] = $d_production_date_month_filter;
			}
			if (empty($aSessionSearch['d_production_date_year_filter'])) {
				$d_production_date_year_filter = date('Y');
				$sPartCriteria = "EXTRACT(YEAR FROM ttp.d_production_date)='$d_production_date_year_filter'";
				$aCriteria[] = $sPartCriteria;
				$aSearch['d_production_date_year_filter'] = $sPartCriteria;
				$aSearchForm['d_production_date_year_filter'] = $d_production_date_year_filter;
			}
			/* -- Searching -- */
			if (!empty($aSessionSearch)) {
				if ($sMessage != '') {
					$aCriteria = $aSessionSearch;
					$aSearchForm = $aSessionSearchForm;
				} else {
					$this->session->unset_userdata('transaction_search');
					$this->session->unset_userdata('transaction_search_form');
				}
			}

			/* -- Sorting -- */
			if (!empty($aSessionSort)) {
				if ($sMessage != '')
					$aSort = $aSessionSort;
				else
					$this->session->unset_userdata('transaction_sort');
			}

			/* -- Pagination -- */
			if (!empty($aSessionPagination)) {
				if ($sMessage != '')
					$aPagination = $aSessionPagination;
				else
					$this->session->unset_userdata('transaction_pagination');
			}
		}

		if (!empty($aPagination)) {
			$nOffset = $aPagination['nOffset'];
			$nLimit = $aPagination['nLimit'];
		}
		$aCriteria[] = "ttp.s_division='" . strtoupper($sDivision) . "'";
		$sCriteria = implode(' AND ', $aCriteria);

		$aDataProduct = $this->Transaction_model->getList($sCriteria, $sPhaseName, 0, 0, $aSort);
		$nTotalRows = count($aDataProduct); //$this->Transaction_model->getListCount($sCriteria);
		$sMessages = '';

		$aDisplay = array(
			'baseurl'			=> base_url(),
			'basesiteurl'		=> site_url(),
			'siteurl'		=> site_url() . '/production/transaction/',
			'sDivision'		=> $sDivision,

			'MESSAGES'		=> '',
			'PAGE_TITLE'		=> 'SPMS-G. ' . $this->aDivision[strtoupper($sDivision)] . '/Transaction',
			'toolCaption'		=> 'Transaction Tool',
			'filterCaption'		=> 'Transaction Filter/Search',

			'sGlobalUserName'	=> $this->sUsername,
			'sGlobalUserLevel' 	=> $this->sLevel,
			'nRowsPerPage'		=> $this->nRowsPerPage,
			'nTotalRows'		=> $nTotalRows,
			'nCurrOffset'		=> $nOffset,
			'sPhaseName'		=> $sPhaseName,

			'tt_prod_product'	=> $aDataProduct
		);
		$aDisplay = array_merge($aDisplay, $aSearchForm);

		$sales_batch_query = '';
		if ($this->session->userdata('b_' . strtolower($sDivision) . '_sales_batch') == FALSE) {
			$sales_batch_query = " AND s_field_process <> 'd_process_14' ";
		}
		$aDisplay['s_phase_filter'] = $this->form->selectbox('tm_prod_setup', 's_field_process, s_phase || \' - \' || s_description AS s_description', "s_division='" . strtoupper($sDivision) . "' AND n_line=1 AND s_field_process<>'d_warehouse' " . (!empty($sales_batch_query) ? $sales_batch_query : '') . " GROUP BY s_division, s_field_process, s_phase, s_description, n_line, n_order ORDER BY n_order ASC, s_phase ASC", 's_field_process', 's_description', $sPhaseName);

		$aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter']);
		$aDisplay['s_buyer_filter'] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='" . strtoupper($sDivision) . "' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aDisplay['s_buyer_filter']);
		$aDisplay['n_line_filter'] = $this->form->selectboxarray($this->config->item('product_line'), $aSearchForm['n_line_filter']);
		$this->load->model('Setup_model');
		$aPhase = $this->Setup_model->getList("s_division='" . strtoupper($sDivision) . "' AND s_field_process='$sPhaseName'");
		$aDisplay['s_location_filter'] = $this->form->selectboxarray(
			$this->config->item('product_location'),
			$aDisplay['s_location_filter']
		);

		if (count($aPhase)) {
			$aDisplay['s_phase'] = $aPhase[0]['s_phase'];
		}

		$aDisplay['viewFilter'] = $this->load->view($sDivision . '/transaction/list_filter', $aDisplay, TRUE);
		$aDisplay['viewToolbar'] = $this->load->view($sDivision . '/transaction/list_toolbar', $aDisplay, TRUE);

		$this->parser->parse('header', $aDisplay);
		$this->parser->parse($sDivision . '/transaction/list', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}

	function add($sDivision)
	{
		if (!$this->session->userdata('b_ag_transaction_write') && strtoupper($sDivision) == 'AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_transaction_write') && strtoupper($sDivision) == 'EG') show_error('Access Denied');

		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		if ($this->validation->run() == FALSE) {
			foreach ($this->Transaction_model->aContainer as $sKey => $aProperties) {
				if ($aProperties['edit'] == 1) {
					$this->aDefaultForm[$sKey] = $this->input->post($sKey);
					if ($sKey == 's_serial_no[]') {
						for ($nCounter = 1; $nCounter <= $this->nRowsPerPage; $nCounter++) {
							$this->aDefaultForm['s_serial_no:' . $nCounter] = '';
							$this->aDefaultForm['s_serial_no_2:' . $nCounter] = '';
							$this->aDefaultForm['s_location:' . $nCounter] = $this->form->selectboxarray($this->config->item('product_location'), '');
						}

						if (isset($_POST['uIdRow'])) {
							$nCounter = 1;
							$sPhase = $this->input->post('s_phase_filter');
							foreach ($_POST['uIdRow'] as $sSerialNo) {
								$aProduct = $this->Product_model->getList("s_serial_no='$sSerialNo' AND ttp.s_division='" . strtoupper($sDivision) . "'");
								if (count($aProduct) > 0) {
									$this->aDefaultForm['s_serial_no_2:' . $nCounter] = $aProduct[0]['s_serial_no_2'];
									$this->aDefaultForm['s_location:' . $nCounter] = $this->form->selectboxarray($this->config->item('product_location'), $aProduct[0]['s_' . (substr($sPhase, 2, 9)) . '_location']);
								}
								$this->aDefaultForm['s_serial_no:' . $nCounter] = $sSerialNo;
								$nCounter++;
							}
						}
						if (isset($_POST['s_serial_no'])) {
							$nCounter = 1;
							foreach ($_POST['s_serial_no'] as $sSerialNo) {
								$this->aDefaultForm['s_serial_no:' . $nCounter] = $sSerialNo;
								$this->aDefaultForm['s_serial_no_2:' . $nCounter] = $this->input->post('s_serial_no_2:' . $nCounter);
								$this->aDefaultForm['s_location:' . $nCounter] = $this->form->selectboxarray($this->config->item('product_location'), $this->input->post('s_location:' . $nCounter));
								$nCounter++;
							}
						}
					}
				}
			}
			if (isset($_POST['s_serial_no'])) $this->sErrorMessage = $this->validation->error_string;
		} else {
			$prod_phases = array();
			$this->db->select('s_field_process, s_phase, s_description, n_order');
			$this->db->where('s_division', strtoupper($sDivision));
			$this->db->where('n_line', 1);
			$this->db->where('s_field_process <>', 'd_warehouse');
			if ($this->session->userdata('b_' . strtolower($sDivision) . '_sales_batch') == FALSE) {
				$this->db->where('s_field_process <>', 'd_process_14');
			}
			$this->db->order_by('n_order', 'ASC');
			$this->db->order_by('s_phase', 'ASC');
			$this->db->group_by(array('s_field_process', 's_phase', 's_description', 'n_order'));
			$tm_prod_setup = $this->db->get('tm_prod_setup');
			$prod_setups = $tm_prod_setup->result();
			foreach ($prod_setups as $prod_setup) {
				$prod_phases[$prod_setup->s_field_process] = $prod_setup->s_phase;
			}

			$phase_filter = $this->validation->s_phase;
			if (!isset($prod_phases[$phase_filter]))
				show_error('Access Denied');

			$aData = array();
			$aSerialError = array();
			$bValid = ($this->input->post('bValidation') ? TRUE : FALSE);
			if ($this->input->post('bRework') && $this->session->userdata('b_replace_production')) {
				$from_phase_num = substr($this->validation->s_phase, 10);
				for ($phase_sum = $from_phase_num; $phase_sum <= 14; $phase_sum++) {
					$aData['d_process_' . $phase_sum] = '';
					if ($phase_sum == 2 && strtoupper($sDivision) == 'EG') {
						$aData['s_serial_no_2'] = '';
					}
				}
			} else {
				$aData[$this->validation->s_phase] = $this->validation->d_transaction_date;
			}
			if (strlen($this->validation->s_phase) >= 12)
				$process_length = 10;
			else
				$process_length = 9;
			$aData['s_' . (substr($this->validation->s_phase, 2, $process_length)) . '_update_by'] = $this->sUsername;
			$aData['s_update_by'] = $this->sUsername;
			if (isset($_POST['s_serial_no'])) {
				$nCounter = 1;
				foreach ($_POST['s_serial_no'] as $sSerialNo) {
					if (!empty($sSerialNo)) {
						if (strtoupper($sDivision) == 'EG' && $this->validation->s_phase == 'd_process_2') {
							$aData['s_serial_no_2'] = $this->input->post('s_serial_no_2:' . $nCounter);
						}
						$aData['s_' . (substr($this->validation->s_phase, 2, $process_length)) . '_location'] = $this->input->post('s_location:' . $nCounter);

						/* -- Check for accepted user to replacing production date -- */
						$is_found_error = FALSE;

						$tt_production_data = NULL;
						$is_production_exists = FALSE;
						$this->db->where('s_serial_no', strtoupper($sSerialNo));
						$tt_production = $this->db->get('tt_production');
						if ($tt_production->num_rows() > 0) {
							$tt_production_data = $tt_production->first_row();
							$product_phase_update = $this->validation->s_phase;
							if ($tt_production_data->$product_phase_update != $aData[$product_phase_update])
								$is_production_exists = TRUE;

							if ($this->session->userdata('b_replace_production') == FALSE && $is_production_exists == TRUE) {
								$is_found_error = TRUE;
								$this->sErrorMessage .= $sSerialNo . " production date has already exist, you can't update. ";
							} else {
								$rSerialNo = $this->Product_model->update($sSerialNo, $aData, strtoupper($sDivision), '', $bValid);
								if ($rSerialNo === FALSE) {
									$this->sErrorMessage .= $sSerialNo . ' update failed. ';
									$is_found_error = TRUE;
								}
							}
						} else {
							$this->sErrorMessage .= $sSerialNo . ' is not found. ';
							$is_found_error = TRUE;
						}

						if ($is_found_error == TRUE) {
							$aSerialError[] = array(
								's_serial_no'	=> $sSerialNo,
								's_serial_no_2'	=> $this->input->post('s_serial_no_2:' . $nCounter),
								's_location'	=> $this->input->post('s_location:' . $nCounter)
							);
						}
					}
					$nCounter++;
				}
			}

			if (count($aSerialError) > 0) {
				//$this->sErrorMessage .= 'Data Error! May Serial No is not found or production date is failed.';
				for ($nCounter = 1; $nCounter <= $this->nRowsPerPage; $nCounter++) {
					$this->aDefaultForm['s_serial_no:' . $nCounter] = (isset($aSerialError[$nCounter - 1]['s_serial_no']) ? $aSerialError[$nCounter - 1]['s_serial_no'] : '');
					$this->aDefaultForm['s_serial_no_2:' . $nCounter] = (isset($aSerialError[$nCounter - 1]['s_serial_no_2']) ? $aSerialError[$nCounter - 1]['s_serial_no_2'] : '');
					$this->aDefaultForm['s_location:' . $nCounter] = $this->form->selectboxarray($this->config->item('product_location'), (isset($aSerialError[$nCounter - 1]['s_location']) ? $aSerialError[$nCounter - 1]['s_location'] : ''));
					if (strtoupper($sDivision) == 'EG' && $this->input->post('s_phase') == 'd_process_2') {
						$this->aDefaultForm['s_serial_no_2:' . $nCounter] = $this->input->post('s_serial_no_2:' . $nCounter);
					}
				}
			} else {
				redirect("production/transaction/index/$sDivision/1");
			}
		}

		$aDisplay = array(
			'baseurl'			=> base_url(),
			'basesiteurl'		=> site_url(),
			'siteurl'			=> site_url() . '/production/transaction/',
			'sDivision'			=> $sDivision,

			'MESSAGES'			=> $this->sErrorMessage,
			'PAGE_TITLE'		=> 'SPMS-G. ' . $this->aDivision[strtoupper($sDivision)] . '/Transaction Update',
			'toolCaption'		=> 'Transaction Tool',

			'sGlobalUserName'	=> $this->sUsername,
			'sGlobalUserLevel' 	=> $this->sLevel
		);
		$aDisplay = array_merge($aDisplay, $this->aDefaultForm);

		$sales_batch_query = '';
		if ($this->session->userdata('b_' . strtolower($sDivision) . '_sales_batch') == FALSE) {
			$sales_batch_query = " AND s_field_process <> 'd_process_14' AND s_field_process <> 'd_process_15' ";
		}
		$aDisplay['s_phase_filter'] = $this->form->selectbox('tm_prod_setup', 's_field_process, s_phase || \' - \' || s_description AS s_description', "s_division='" . strtoupper($sDivision) . "' AND n_line=1 AND s_field_process<>'d_warehouse' " . (!empty($sales_batch_query) ? $sales_batch_query : '') . " GROUP BY s_field_process, s_phase, s_description, n_order ORDER BY n_order ASC, s_phase ASC", 's_field_process', 's_description', $this->input->post('s_phase'));

		$aDisplay['d_transaction_date'] = ($this->input->post('d_transaction_date') ? $this->input->post('d_transaction_date') : date('Y-m-d'));

		$aDisplay['isRework'] = ($this->input->post('bRework') ? 'checked' : '');

		$aDisplay['viewToolbar'] = $this->load->view($sDivision . '/transaction/add_toolbar', $aDisplay, TRUE);

		$this->parser->parse('header', $aDisplay);
		$this->parser->parse($sDivision . '/transaction/add', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}

	function delete($sDivision, $sSerialNo = '')
	{
		if (!$this->session->userdata('b_ag_transaction_write') && strtoupper($sDivision) == 'AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_transaction_write') && strtoupper($sDivision) == 'EG') show_error('Access Denied');
		if (!$this->session->userdata('b_replace_production')) show_error('Access Denied');

		$rSerialNo = TRUE;
		$aData = array();

		$prod_phases = array();
		$this->db->select('s_field_process, s_phase, s_description, n_order');
		$this->db->where('s_division', strtoupper($sDivision));
		$this->db->where('n_line', 1);
		$this->db->where('s_field_process <>', 'd_warehouse');
		if ($this->session->userdata('b_' . strtolower($sDivision) . '_sales_batch') == FALSE) {
			$this->db->where('s_field_process <>', 'd_process_14');
		}
		$this->db->order_by('n_order', 'ASC');
		$this->db->order_by('s_phase', 'ASC');
		$this->db->group_by(array('s_field_process', 's_phase', 's_description', 'n_order'));
		$tm_prod_setup = $this->db->get('tm_prod_setup');
		$prod_setups = $tm_prod_setup->result();
		foreach ($prod_setups as $prod_setup) {
			$prod_phases[$prod_setup->s_field_process] = $prod_setup->s_phase;
		}

		$phase_filter = $this->input->post('s_phase_filter');
		if (!isset($prod_phases[$phase_filter]))
			show_error('Access Denied');

		$aData[$this->input->post('s_phase_filter')] = '';
		if (strlen($this->input->post('s_phase_filter')) >= 12)
			$process_length = 10;
		else
			$process_length = 9;
		$aData['s_' . (substr($this->input->post('s_phase_filter'), 2, $process_length)) . '_update_by'] = $this->sUsername;
		$aData['s_' . (substr($this->input->post('s_phase_filter'), 2, $process_length)) . '_location'] = '';
		$aData['s_update_by'] = $this->sUsername;
		if (isset($_POST['uIdRow'])) {
			foreach ($_POST['uIdRow'] as $uIdRow) {
				if ($this->input->post('s_phase_filter') == 'd_process_2') {
					$aProduct = $this->Product_model->getList("ttp.s_serial_no='$uIdRow' AND ttp.s_division='" . strtoupper($sDivision) . "' AND ttp.n_line=1");
					if (count($aProduct) > 0) $aData['s_serial_no_2'] = '';
				}
				$rSerialNo = $this->Product_model->update($uIdRow, $aData, strtoupper($sDivision));
				if ($rSerialNo === FALSE) break;
			}
		} else {
			if ($sSerialNo != '') {
				if ($this->input->post('s_phase_filter') == 'd_process_2') {
					$aProduct = $this->Product_model->getList("ttp.s_serial_no='$sSerialNo' AND ttp.s_division='" . strtoupper($sDivision) . "' AND ttp.n_line=1");
					if (count($aProduct) > 0) $aData['s_serial_no_2'] = '';
				}
				$rSerialNo = $this->Product_model->update($sSerialNo, $aData, strtoupper($sDivision));
			}
		}

		if ($rSerialNo === FALSE) {
			$sMessages = 0;
		} else {
			$sMessages = 1;
		}

		$this->load->helper('url');
		redirect("production/transaction/index/$sDivision/$sMessages");
	}

	function excel($sDivision, $sPhaseName = 'd_process_1')
	{
		if (!$this->session->userdata('b_ag_transaction_read') && strtoupper($sDivision) == 'AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_transaction_read') && strtoupper($sDivision) == 'EG') show_error('Access Denied');

		$this->load->helper('excel');
		$aSessionSearch = $this->session->userdata('transaction_search');
		$aSessionSort = $this->session->userdata('transaction_sort');

		/* -- Default View -- */
		if (empty($aSessionSearch)) {
			$d_transaction_date_filter = date('Y-m-d');
			$sPartCriteria = "TO_CHAR(ttp.$sPhaseName, 'YYYY-MM-DD') = '$d_transaction_date_filter'";
			$aSessionSearch[] = $sPartCriteria;

			$d_production_date_month_filter = date('m');
			$sPartCriteria = "EXTRACT(MONTH FROM ttp.d_production_date)='$d_production_date_month_filter'";
			$aSessionSearch[] = $sPartCriteria;

			$d_production_date_year_filter = date('Y');
			$sPartCriteria = "EXTRACT(YEAR FROM ttp.d_production_date)='$d_production_date_year_filter'";
			$aSessionSearch[] = $sPartCriteria;
		}

		$sSearch = (!empty($aSessionSearch) ? implode(' AND ', $aSessionSearch) . ' AND ' : '') . " ttp.s_division='" . strtoupper($sDivision) . "' ";

		$aDataTransaction = $this->Transaction_model->getList($sSearch, $sPhaseName, 0, 0, $aSessionSort);
		$aHeader = array(
			's_serial_no' => 'Serial No',
			's_po_no' => 'PI Number',
			's_po' => 'PO',
			'd_order_date' => 'Receive Order',
			// 's_phase' => 'Phase',
			'd_transaction_date' => 'Date',
			'd_transaction_plan_date' => 'Plan Date',
			's_transaction_location' => 'Location',
			's_transaction_by' => 'Scaned By',
			'd_production_date' => 'Production Date',
			// 'd_plan_date' => 'Production Plan Date (Input)',
			// 'd_delivery_date' => 'Production Plan Date (Output)',
			'd_target_date' => 'Export Plan Date',
			's_lot_no' => 'Lot Number',
			's_buyer_name' => 'Buyer',
			's_model' => 'Model',
			's_model_name' => 'Model Name',
			's_color_name' => 'Color',
			's_smodel' => 'Item Code'
		);
		$aDatas = array();
		foreach ($aDataTransaction as $nRow => $aRecordTransaction) {
			$aData = array();
			foreach ($aRecordTransaction as $sField => $sValue) {
				if (isset($aHeader[$sField])) {
					$aData[$aHeader[$sField]] = $sValue;
					/*} else {
					$aData[$sField]=$sValue;*/
				}
			}
			$aDatas[] = $aData;
		}
		to_excel_array($aDatas, 'transactionlist');
	}

	function export_list($sDivision, $sMessage = '', $is_export = 0)
	{
		if (!$this->session->userdata('b_replace_production')) show_error('Access Denied');

		$division_upper = strtoupper($sDivision);
		$division_lower = strtolower($sDivision);

		$this->load->model($division_lower . '/Report_model');

		$sCriteria = '';
		/* -- Searching -- */
		$aCriteria = array();
		$aSessionSearch = $this->session->userdata('export_list_search');
		$aSearchForm = array(
			'd_transaction_date_input'		=> '',
			's_location_input'			=> '',
			's_type_filter'				=> '',
			's_serial_no_filter'			=> '',
			's_serial_no2_filter'			=> '',
			'd_transaction_date_filter'		=> '',
			's_color_filter'				=> '',
			's_lot_no_filter'				=> '',
			'd_production_date_month_filter' => '', 'd_production_date_year_filter'	=> '',
			'd_production_date_month_filter2' => '', 'd_production_date_year_filter2' => '',
			's_po_no_filter'				=> '',
			's_po_filter'					=> '',
			's_buyer_filter'				=> '',
			's_model_filter'				=> '',
			's_smodel_filter'				=> '',
			'sSort'							=> 'd_production_date', 'sSortMethod'	=> 'ASC'
		);
		$aSessionSearchForm = $this->session->userdata('export_list_search_form');

		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort'] => $aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('export_list_sort');

		/* -- Pagination -- */
		$nOffset = 0;
		$nLimit = $this->nRowsPerPage;
		$aPagination = array();
		$aSessionPagination = $this->session->userdata('export_list_pagination');

		$aCriteria[] = "ttp.d_process_10 IS NOT NULL AND ttp.d_process_14 IS NULL";
		if ($division_lower == 'eg')
			$aCriteria[] = "ttp.n_line = 1";

		if (isset($_POST['nOffset'])) {
			/* -- Searching -- */
			if (!empty($aSessionSearch)) {
				$this->session->unset_userdata('export_list_search');
				$this->session->unset_userdata('export_list_search_form');
			}

			if ($this->input->post('s_type_filter')) {
				$s_type_filter = $this->input->post('s_type_filter');
				$aCriteria[] = "ttp.s_type = '$s_type_filter'";
			}
			if ($this->input->post('s_serial_no_filter') && !$this->input->post('s_serial_no2_filter')) {
				$s_serial_no_filter = $this->input->post('s_serial_no_filter');
				$aCriteria[] = "ttp.s_serial_no ILIKE '%$s_serial_no_filter%'";
			}
			if ($this->input->post('s_serial_no_filter') && $this->input->post('s_serial_no2_filter')) {
				$s_serial_no_filter = $this->input->post('s_serial_no_filter');
				$s_serial_no2_filter = $this->input->post('s_serial_no2_filter');
				$aCriteria[] = "(UPPER(ttp.s_serial_no) BETWEEN UPPER('$s_serial_no_filter') AND UPPER('$s_serial_no2_filter'))";
			}
			if ($this->input->post('d_transaction_date_filter')) {
				$d_transaction_date_filter = $this->input->post('d_transaction_date_filter');
				$aCriteria[] = "TO_CHAR(ttp.d_process_10, 'YYYY-MM-DD') = '$d_transaction_date_filter'";
			}
			if ($this->input->post('s_color_filter')) {
				$s_color_filter = $this->input->post('s_color_filter');
				$aCriteria[] = "ttp.s_color_name ILIKE '%$s_color_filter%'";
			}
			if ($this->input->post('s_lot_no_filter')) {
				$s_lot_no_filter = $this->input->post('s_lot_no_filter');
				$aCriteria[] = "ttp.s_lot_no ILIKE '%$s_lot_no_filter%'";
			}
			if ($this->input->post('d_production_date_month_filter') && $this->input->post('d_production_date_year_filter')) {
				$d_production_date_month_filter = sprintf('%02d', $this->input->post('d_production_date_month_filter'));
				$d_production_date_year_filter = $this->input->post('d_production_date_year_filter');
				$d_production_date_year_month_filter = $d_production_date_year_filter . $d_production_date_month_filter;
				$aCriteria[] = "TO_CHAR(ttp.d_production_date, 'YYYYMM')>='$d_production_date_year_month_filter'";
			}
			if ($this->input->post('d_production_date_month_filter2') && $this->input->post('d_production_date_year_filter2')) {
				$d_production_date_month_filter = sprintf('%02d', $this->input->post('d_production_date_month_filter2'));
				$d_production_date_year_filter = $this->input->post('d_production_date_year_filter2');
				$d_production_date_year_month_filter = $d_production_date_year_filter . $d_production_date_month_filter;
				$aCriteria[] = "TO_CHAR(ttp.d_production_date, 'YYYYMM')<='$d_production_date_year_month_filter'";
			}
			if ($this->input->post('s_po_no_filter')) {
				$s_po_no_filter = $this->input->post('s_po_no_filter');
				$aCriteria[] = "ttp.s_po_no ILIKE '%$s_po_no_filter%'";
			}
			if ($this->input->post('s_po_filter')) {
				$s_po_filter = $this->input->post('s_po_filter');
				$aCriteria[] = "ttp.s_po ILIKE '%$s_po_filter%'";
			}
			if ($this->input->post('s_buyer_filter')) {
				$s_buyer_filter = $this->input->post('s_buyer_filter');
				$aCriteria[] = "ttp.s_buyer_name = '$s_buyer_filter'";
			}
			if ($this->input->post('s_model_filter')) {
				$s_model_filter = $this->input->post('s_model_filter');
				$aCriteria[] = "ttp.s_model_name ILIKE '%$s_model_filter%'";
			}
			if ($this->input->post('s_smodel_filter')) {
				$s_smodel_filter = $this->input->post('s_smodel_filter');
				$aCriteria[] = "ttp.s_smodel_name ILIKE '%$s_model_filter%'";
			}
			if ($this->input->post('s_location_filter')) {
				$s_location_filter = $this->input->post('s_location_filter');
				$aCriteria[] = "ttp.s_location ILIKE '%$s_location_filter%'";
			}

			foreach ($aSearchForm as $sField => $sValue) $aSearchForm[$sField] = $this->input->post($sField);
			if (!empty($aCriteria)) {
				$this->session->set_userdata(array('export_list_search' => $aCriteria));
				$this->session->set_userdata(array('export_list_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if (!empty($aSessionSort)) $this->session->unset_userdata('export_list_sort');
			if ($this->input->post('sSort') && $this->input->post('sSortMethod')) {
				$sSort = $this->input->post('sSort');
				$sSortMethod = $this->input->post('sSortMethod');
				if ($this->input->post('bSortAction')) {
					$sSortMethod = ($this->input->post('sSortMethod') == 'ASC' ? 'DESC' : 'ASC');
					$aSearchForm['sSortMethod'] = $sSortMethod;
				}
				$aSort = array($sSort => $sSortMethod);
			}
			if (!empty($aSort)) $this->session->set_userdata(array('export_list_sort' => $aSort));

			/* -- Pagination -- */
			if (!empty($aSessionPagination)) $this->session->unset_userdata('export_list_pagination');
			$aPagination['nOffset'] = ($this->input->post('nOffset') ? $this->input->post('nOffset') : 0);
			$aPagination['nLimit'] = ($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage);
			if (!empty($aPagination)) $this->session->set_userdata(array('export_list_pagination' => $aPagination));
		} else {
			/* -- Default View -- */
			if (empty($aSessionSearch['d_production_date_month_filter']) && empty($aSessionSearch['d_production_date_year_filter'])) {
				$d_production_date_month_filter = date('m');
				$d_production_date_year_filter = date('Y');
				$d_production_date_year_month_filter = $d_production_date_year_filter . $d_production_date_month_filter;
				$sPartCriteria = "TO_CHAR(ttp.d_production_date, 'YYYYMM')>='$d_production_date_year_month_filter'";
				//$sPartCriteria="EXTRACT(MONTH FROM ttp.d_production_date)='$d_production_date_month_filter' AND EXTRACT(YEAR FROM ttp.d_production_date)='$d_production_date_year_filter'";
				$aCriteria[] = $sPartCriteria;
				$aSearch['d_production_date_month_filter'] = $sPartCriteria;
				$aSearch['d_production_date_year_filter'] = $sPartCriteria;
				$aSearchForm['d_production_date_month_filter'] = $d_production_date_month_filter;
				$aSearchForm['d_production_date_year_filter'] = $d_production_date_year_filter;
			}
			if (empty($aSessionSearch['d_production_date_month_filter2']) && empty($aSessionSearch['d_production_date_year_filter2'])) {
				$d_production_date_month_filter = date('m');
				$d_production_date_year_filter = date('Y');
				$d_production_date_year_month_filter = $d_production_date_year_filter . $d_production_date_month_filter;
				$sPartCriteria = "TO_CHAR(ttp.d_production_date, 'YYYYMM')<='$d_production_date_year_month_filter'";
				$aCriteria[] = $sPartCriteria;
				$aSearch['d_production_date_month_filter2'] = $sPartCriteria;
				$aSearch['d_production_date_year_filter2'] = $sPartCriteria;
				$aSearchForm['d_production_date_month_filter2'] = $d_production_date_month_filter;
				$aSearchForm['d_production_date_year_filter2'] = $d_production_date_year_filter;
			}
			/* -- Searching -- */
			if (!empty($aSessionSearch)) {
				if ($sMessage != '') {
					$aCriteria = $aSessionSearch;
					$aSearchForm = $aSessionSearchForm;
				} else {
					$this->session->unset_userdata('export_list_search');
					$this->session->unset_userdata('export_list_search_form');
				}
			}
			/* -- Sorting -- */
			if (!empty($aSessionSort)) {
				if ($sMessage != '')
					$aSort = $aSessionSort;
				else
					$this->session->unset_userdata('export_list_sort');
			}
			/* -- Pagination -- */
			if (!empty($aSessionPagination)) {
				if ($sMessage != '')
					$aPagination = $aSessionPagination;
				else
					$this->session->unset_userdata('export_list_pagination');
			}
		}

		if (!empty($aPagination)) {
			$nOffset = $aPagination['nOffset'];
			$nLimit = $aPagination['nLimit'];
		}

		$sCriteria = implode(' AND ', $aCriteria);

		$aDataProduct = array();
		//set view report
		//declare variable temporary
		$aAllDataProduct = $this->Report_model->getListSerialPhase($sCriteria, 0, 0, $aSort);
		$nTotalRows = count($aAllDataProduct);

		if ($is_export == 0) {
			$aDataProduct = $this->Report_model->getListSerialPhase($sCriteria, $nLimit, $nOffset, $aSort);
			$aDisplay = array(
				'baseurl'			=> base_url(),
				'basesiteurl'		=> site_url(),
				'siteurl'		=> site_url() . '/production/transaction/',
				'sDivision'		=> $sDivision,

				'MESSAGES'		=> '',
				'PAGE_TITLE'		=> 'SPMS-G. ' . $division_upper . ' To Export/Local List',
				'filterCaption'		=> $division_upper . ' Export/Local Filter/Search',
				'toolCaption'		=> 'Transaction Tool',

				'sGlobalUserName'	=> $this->sUsername,
				'sGlobalUserLevel' 	=> $this->sLevel,

				'nTotalRows'		=> $nTotalRows,
				'nRowsPerPage'		=> $this->nRowsPerPage,
				'nCurrOffset'		=> $nOffset,

				'tt_report_stock'	=> $aDataProduct
			);

			$aDisplay = array_merge($aDisplay, $aSearchForm);
			$aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter']);
			$aDisplay['d_production_date_month_filter2'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter2']);
			$aDisplay['s_buyer_filter'] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='" . $division_upper . "' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aDisplay['s_buyer_filter']);
			$aDisplay['s_location_filter'] = $this->form->selectboxarray(
				$this->config->item('product_location'),
				$aDisplay['s_location_filter']
			);

			$aDisplay['viewFilter'] = $this->load->view($division_lower . '/transaction/export_list_filter', $aDisplay, TRUE);
			$aDisplay['viewToolbar'] = $this->load->view($division_lower . '/transaction/export_list_toolbar', $aDisplay, TRUE);

			$this->parser->parse('header', $aDisplay);
			$this->parser->parse($division_lower . '/transaction/export_list', $aDisplay);
			$this->parser->parse('footer', $aDisplay);
		} else {
			$this->load->helper('excel');
			$aHeader = array(
				'd_production_date' => 'Production Date',
				'd_plan_date' => 'Production Plan Date (Input)',
				'd_delivery_date' => 'Production Plan Date (Output)',
				'd_target_date' => 'Export Plan Date',
				's_serial_no' => 'Serial No',
				's_buyer_name' => 'Buyer',
				's_po_no' => 'PI Number',
				's_po' => 'PO',
				's_lot_no' => 'Lot No',
				's_model' => 'Model',
				's_model_name' => 'Model Name',
				's_color_name' => 'Color',
				's_smodel' => 'Item Code',
				's_location' => 'Location'
			);
			if ($division_lower == 'ag') {
				$aHeader['d_process_1'] = 'WK Center Input';
				$aHeader['d_process_2'] = 'WK Center Output';
				$aHeader['d_process_3'] = 'Wood Working';
				$aHeader['d_process_4'] = 'Coating-I - Neck';
				$aHeader['d_process_5'] = 'Sanding';
				$aHeader['d_process_6'] = 'Coating-IIA';
				$aHeader['d_process_7'] = 'Coating-IIB';
				$aHeader['d_process_8'] = 'Assembly-I_Control Center';
				$aHeader['d_process_9'] = 'Assembly-II';
				$aHeader['d_process_10'] = 'Packing';
				$aHeader['d_warehouse'] = 'Warehouse Incoming';
			} elseif ($division_lower == 'eg') {
				$aHeader['s_type'] = 'Type Process';
				$aHeader['d_process_1'] = 'WK-I Center Input';
				$aHeader['d_process_2'] = 'WK-I Center Output';
				$aHeader['d_process_3'] = 'WK-II';
				$aHeader['d_process_4'] = 'WK-II Control Center';
				$aHeader['d_process_5'] = 'Coating-I';
				$aHeader['d_process_6'] = 'Coating-IIA';
				$aHeader['d_process_7'] = 'Coating-IIB';
				$aHeader['d_process_8'] = 'Assembly-I_Control Center';
				$aHeader['d_process_9'] = 'Assembly-II';
				$aHeader['d_process_10'] = 'Packing';
				$aHeader['d_warehouse'] = 'Warehouse Incoming';
			}
			$aDatas = array();
			foreach ($aAllDataProduct as $nRow => $aEachDataProduct) {
				$aData = array();
				foreach ($aEachDataProduct as $sField => $sValue) {
					if (isset($aHeader[$sField])) {
						$aData[$aHeader[$sField]] = $sValue;
					} /*else {
						$aData[$sField]=$sValue;
					}*/
				}
				$aDatas[] = $aData;
			}
			to_excel_array($aDatas, 'report_to_export_local');
		}
	}

	function export_action($sDivision, $sSerialNo = '')
	{
		if (!$this->session->userdata('b_replace_production')) show_error('Access Denied');

		$rSerialNo = TRUE;

		$d_transaction_date = $this->input->post('d_transaction_date');
		$s_product_location = $this->input->post('s_product_location');
		$aData = array();

		$aData['d_process_14'] = $d_transaction_date;
		$aData['s_process_14_update_by'] = $this->sUsername;
		$aData['s_process_14_location'] = $s_product_location;
		$aData['s_update_by'] = $this->sUsername;
		if (isset($_POST['uIdRow'])) {
			foreach ($_POST['uIdRow'] as $uIdRow) {
				$rSerialNo = $this->Product_model->update($uIdRow, $aData, strtoupper($sDivision));
				if ($rSerialNo === FALSE) break;
			}
		} else {
			if ($sSerialNo != '') {
				$rSerialNo = $this->Product_model->update($sSerialNo, $aData, strtoupper($sDivision));
			}
		}

		if ($rSerialNo === FALSE) {
			$sMessages = 0;
		} else {
			$sMessages = 1;

			$aSessionSearchForm = $this->session->userdata('export_list_search_form');
			$aSessionSearchForm['d_transaction_date_input'] = $d_transaction_date;
			$aSessionSearchForm['s_location_filter'] = $s_location;
			$this->session->set_userdata(array('export_list_search_form' => $aSessionSearchForm));
		}

		$this->load->helper('url');
		redirect("production/transaction/export_list/$sDivision/$sMessages");
	}
}
