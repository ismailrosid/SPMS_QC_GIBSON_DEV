<?php

class Buyer extends Controller
{
	var $sUsername;
	var $sLevel;

	var $nRowsPerPage = 20;

	var $_bModal = false;
	var $_sModalTarget = '';

	var $rules = array();
	var $fields = array();

	var $sErrorMessage = '';
	var $aDefaultForm = array();

	function Buyer()
	{
		parent::Controller();
		$this->load->library('session');

		if (!$this->session->userdata('b_master_read')) show_error('Access Denied');

		$this->sUsername = $this->session->userdata('s_username');
		$this->sLevel = $this->session->userdata('s_level');

		$this->load->model('Buyer_model');
		$this->load->model('Util_model');

		$this->load->helper('url');

		$this->load->library('parser');
		$this->load->library('form');
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="message_error">', '</div>');
		$this->load->library('validatejs');

		foreach ($this->Buyer_model->aContainer as $sKey => $aProperties) {
			if ($aProperties['edit'] == 1) {
				$this->rules[$sKey] = $aProperties['rules'];
				$this->fields[$sKey] = $aProperties['caption'];
			}
		}
	}

	function index($sCode = '', $sMessage = '')
	{
		$sCode = str_replace('--', '/', $sCode);
		$sCode = str_replace('&#40;', '(', $sCode);
		$sCode = str_replace('&#41;', ')', $sCode);
		$sCriteria = '';
		/* -- Searching -- */
		$aCriteria = array();
		$aSessionSearch = $this->session->userdata('buyer_search');
		$aSearchForm = array(
			's_code_filter'		=> '',
			's_name_filter'		=> '',
			's_division_filter'	=> '',
			's_status_filter'	=> '',
			'sSort'				=> 'd_createtime', 'sSortMethod'	=> 'DESC'
		);
		$aSessionSearchForm = $this->session->userdata('buyer_search_form');

		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort'] => $aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('buyer_sort');

		/* -- Pagination -- */
		$nOffset = 0;
		$nLimit = $this->nRowsPerPage;
		$aPagination = array();
		$aSessionPagination = $this->session->userdata('buyer_pagination');

		if (isset($_POST['nOffset'])) {
			/* -- Searching -- */
			if (!empty($aSessionSearch)) {
				$this->session->unset_userdata('buyer_search');
				$this->session->unset_userdata('buyer_search_form');
			}
			if ($this->input->post('s_code_filter')) {
				$s_code_filter = $this->input->post('s_code_filter');
				$aCriteria[] = "s_code ILIKE '%$s_code_filter%'";
			}
			if ($this->input->post('s_name_filter')) {
				$s_name_filter = $this->input->post('s_name_filter');
				$aCriteria[] = "s_name ILIKE '%$s_name_filter%'";
			}
			if ($this->input->post('s_division_filter')) {
				$s_division_filter = $this->input->post('s_division_filter');
				$aCriteria[] = "s_division = '$s_division_filter'";
			}
			if ($this->input->post('s_status_filter')) {
				$s_status_filter = $this->input->post('s_status_filter');
				$aCriteria[] = "s_status = '$s_status_filter'";
			}
			foreach ($aSearchForm as $sField => $sValue) $aSearchForm[$sField] = $this->input->post($sField);
			if (!empty($aCriteria)) {
				$this->session->set_userdata(array('buyer_search' => $aCriteria));
				$this->session->set_userdata(array('buyer_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if (!empty($aSessionSort)) $this->session->unset_userdata('buyer_sort');
			if ($this->input->post('sSort') && $this->input->post('sSortMethod')) {
				$sSort = $this->input->post('sSort');
				$sSortMethod = $this->input->post('sSortMethod');
				if ($this->input->post('bSortAction')) {
					$sSortMethod = ($this->input->post('sSortMethod') == 'ASC' ? 'DESC' : 'ASC');
					$aSearchForm['sSortMethod'] = $sSortMethod;
				}
				$aSort = array($sSort => $sSortMethod);
			}
			if (!empty($aSort)) $this->session->set_userdata(array('buyer_sort' => $aSort));

			/* -- Pagination -- */
			if (!empty($aSessionPagination)) $this->session->unset_userdata('buyer_pagination');
			$aPagination['nOffset'] = ($this->input->post('nOffset') ? $this->input->post('nOffset') : 0);
			$aPagination['nLimit'] = ($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage);
			if (!empty($aPagination)) $this->session->set_userdata(array('buyer_pagination' => $aPagination));
		} else {
			/* -- Searching -- */
			if (!empty($aSessionSearch)) {
				if ($sMessage != '') {
					$aCriteria = $aSessionSearch;
					$aSearchForm = $aSessionSearchForm;
				} else {
					$this->session->unset_userdata('buyer_search');
					$this->session->unset_userdata('buyer_search_form');
				}
			}
			/* -- Sorting -- */
			if (!empty($aSessionSort)) {
				if ($sMessage != '')
					$aSort = $aSessionSort;
				else
					$this->session->unset_userdata('buyer_sort');
			}

			/* -- Pagination -- */
			if (!empty($aSessionPagination)) {
				if ($sMessage != '')
					$aPagination = $aSessionPagination;
				else
					$this->session->unset_userdata('buyer_pagination');
			}
		}

		if (!empty($aPagination)) {
			$nOffset = $aPagination['nOffset'];
			$nLimit = $aPagination['nLimit'];
		}
		$sCriteria = implode(' AND ', $aCriteria);

		$aBuyer = $this->Buyer_model->getList($sCriteria, $nLimit, $nOffset, $aSort);
		$nTotalRows = count($this->Buyer_model->getList($sCriteria));
		$sMessages = '';

		$aDisplay = array(
			'baseurl'			=> base_url(),
			'basesiteurl'		=> site_url(),
			'siteurl'			=> site_url() . '/master/buyer/',

			'MESSAGES'			=> $this->sErrorMessage,
			'PAGE_TITLE'		=> 'SPMS-G. Master/Buyer',
			'toolCaption'		=> 'Buyer Tool',
			'filterCaption'		=> 'Buyer Filter/Search',

			'sGlobalUserName'	=> $this->sUsername,
			'sGlobalUserLevel' 	=> $this->sLevel,
			'nRowsPerPage'		=> $this->nRowsPerPage,
			'nTotalRows'		=> $nTotalRows,
			'nCurrOffset'		=> $nOffset,
			'tm_customer'		=> $aBuyer
		);
		$aDisplay = array_merge($aDisplay, $aSearchForm);
		$aDisplay['s_division_filter'] = $this->form->selectboxarray($this->config->item('division'), $aSearchForm['s_division_filter']);
		$aDisplay['s_status_filter'] = $this->form->selectboxarray($this->config->item('status'), $aSearchForm['s_status_filter']);
		$aDisplay['viewFilter'] = $this->load->view('master/buyer_filter', $aDisplay, TRUE);
		$aDisplay['viewToolbar'] = $this->load->view('master/buyer_toolbar', $aDisplay, TRUE);

		$sCode = str_replace('/', '--', $sCode);
		$aEditable = $this->viewedit($sCode);
		$aDisplay = array_merge($aDisplay, $aEditable);
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse('master/buyer', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}

	function viewedit($sCode = '')
	{
		$sCode = str_replace('--', '/', $sCode);
		$sCode = str_replace('&#40;', '(', $sCode);
		$sCode = str_replace('&#41;', ')', $sCode);
		$aUserDefault = array();
		// set default data user
		$aEditable = array();
		foreach ($this->Buyer_model->aContainer as $sField => $aProperties) {
			$aUserDefault[$sField] = (isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : '');
		}
		if (trim($sCode != '') && $sCode != '0') {
			// edit mode
			$aUser = $this->Buyer_model->getList("s_code='$sCode'");
			if (count($aUser) > 0) {
				foreach ($this->Buyer_model->aContainer as $sField => $aProperties) {
					$aUserDefault[$sField] = (isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : $aUser[0][$sField]);
				}
			}
		}
		$aDataEditable = array();
		foreach ($this->Buyer_model->aContainer as $sKey => $aProperties) {
			if ($aProperties['edit'] == 1) {
				if ($sKey == 's_division') {
					$aDataEditable[$sKey] = $this->form->selectboxarray($this->config->item('division'), $aUserDefault[$sKey]);
				} elseif ($sKey == 'n_serial_digit') {
					$aDataEditable[$sKey] = $this->form->selectboxarray($this->config->item('serial_digit'), $aUserDefault[$sKey], 1);
				} elseif ($sKey == 's_serial_reset') {
					$aDataEditable[$sKey] = $this->form->selectboxarray($this->config->item('serial_reset'), $aUserDefault[$sKey]);
				} else {
					$aDataEditable[$sKey] = $aUserDefault[$sKey];
				}
			}
		}
		$aEditable[] = $aDataEditable;

		$error = $this->validatejs->setValidate($this->Buyer_model->aContainer);
		$validate_js = $this->validatejs->execValidateJS('frmEdit');

		$sCode = str_replace('/', '--', $sCode);
		$aDisplay = array(
			'formaction'	=> site_url() . '/master/buyer/' . (trim($sCode) == '' || $sCode == '0' ? 'add' : 'edit/' . $sCode),
			'editable'		=> $aEditable,
			'VALIDATE_JS'	=> $validate_js
		);
		return $aDisplay;
	}

	function add()
	{
		if (!$this->session->userdata('b_master_write')) show_error('Access Denied');

		$sMessages = 0;

		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE) {
			if (isset($_POST['s_code'])) {
				foreach ($this->Buyer_model->aContainer as $sKey => $aProperties) {
					if ($aProperties['edit'] == 1) {
						$this->aDefaultForm[$sKey] = $this->input->post($sKey);
					}
				}
				$this->sErrorMessage = $this->validation->error_string;
				$this->index(0, 2);
			} else {
				redirect("master/buyer/index/0/2");
			}
		} else {
			foreach ($this->Buyer_model->aContainer as $sKey => $aProperties) {
				if ($aProperties['edit'] == 1) {
					$aData[$sKey] = $this->validation->$sKey;
				}
			}
			$aData['s_status'] = "Active";
			$aData['s_update_by'] = $this->sUsername;
			$sCode = $this->Buyer_model->insert($aData);
			if ($sCode === FALSE) {
				$sMessages = 0;
			} else {
				$sMessages = 1;
			}

			redirect("master/buyer/index/$sCode/$sMessages");
		}
	}

	function edit($sCode)
	{
		if (!$this->session->userdata('b_master_write')) show_error('Access Denied');

		$sCode = str_replace('--', '/', $sCode);
		$sCode = str_replace('&#40;', '(', $sCode);
		$sCode = str_replace('&#41;', ')', $sCode);
		$sMessages = 0;
		$aData = array();

		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE) {
			if (isset($_POST['s_code'])) {
				foreach ($this->Buyer_model->aContainer as $sKey => $aProperties) {
					if ($aProperties['edit'] == 1) {
						$this->aDefaultForm[$sKey] = $this->input->post($sKey);
					}
				}

				$this->sErrorMessage = $this->validation->error_string;
				$this->index($sCode, 2);
			} else {
				$sCode = str_replace('/', '--', $sCode);
				redirect("master/buyer/index/$sCode/2");
			}
		} else {
			foreach ($this->Buyer_model->aContainer as $sKey => $aProperties) {
				if ($aProperties['edit'] == 1) {
					$aData[$sKey] = $this->validation->$sKey;
				}
			}
			$aData['s_update_by'] = $this->sUsername;
			$sCode = $this->Buyer_model->update($sCode, $aData);
			if ($sCode === FALSE) {
				$sMessages = 0;
			} else {
				$sMessages = 1;
			}

			$sCode = str_replace('/', '--', $aData['s_code']);
			redirect("master/buyer/index/$sCode/$sMessages");
		}
	}

	function delete($sCode = '')
	{
		if (!$this->session->userdata('b_master_write')) show_error('Access Denied');
		$rDelete = TRUE;
		if (isset($_POST['uIdRow'])) {
			foreach ($_POST['uIdRow'] as $uIdRow) {
				$rDelete = $this->Buyer_model->delete($uIdRow);
				if ($rDelete === FALSE) break;
			}
		} else {
			$sCode = str_replace('--', '/', $sCode);
			$sCode = str_replace('&#40;', '(', $sCode);
			$sCode = str_replace('&#41;', ')', $sCode);
			if ($sCode != '') $rDelete = $this->Buyer_model->delete($sCode);
		}

		if ($rDelete === FALSE) {
			$sMessages = 0;
		} else {
			$sMessages = 1;
		}

		$this->load->helper('url');
		redirect("master/buyer/index/0/$sMessages");
	}

	function excel()
	{
		$this->load->helper('excel');
		$aSessionSearch = $this->session->userdata('buyer_search');
		$aSessionSort = $this->session->userdata('buyer_sort');

		$sSearch = (!empty($aSessionSearch) ? implode(' AND ', $aSessionSearch) : '');

		$aBuyer = $this->Buyer_model->getList($sSearch, 0, 0, $aSessionSort);
		to_excel_array($aBuyer, 'buyer_master');
	}


	function editStatus($sCode = "", $sStatus = "")
	{
		if (isset($_POST['uIdRow'])) {
			foreach ($_POST['uIdRow'] as $uIdRow) {
				$lastSegment = $this->uri->segment($this->uri->total_segments());
				$aData['s_status'] = $lastSegment == '1' ? 'Active' : 'Non Active';
				$aData['s_update_by'] = $this->sUsername;
				$result = $this->Buyer_model->update($uIdRow, $aData);
				if ($result === FALSE) break;
			}
		} else {
			$sCode = str_replace('--', '/', $sCode);
			$sCode = str_replace('&#40;', '(', $sCode);
			$sCode = str_replace('&#41;', ')', $sCode);
			$sStatus = str_replace('--', ' ', $sStatus);
			$aData['s_status'] = $sStatus == 'Active' ? 'Non Active' : 'Active';
			$aData['s_update_by'] = $this->sUsername;
			$result = $this->Buyer_model->update($sCode, $aData);
		}
		if ($result === FALSE) {
			$sMessages = 0;
		} else {
			$sMessages = 1;
		}

		redirect("master/buyer/index/0/$sMessages");
	}
}
