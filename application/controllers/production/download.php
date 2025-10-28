<?php

class Download extends Controller {
	var $sUsername;
	var $sLevel;
	
	var $nRowsPerPage=100;
	
	var $_bModal=false;
	var $_sModalTarget='';
	
	var $rules=array();
	var $fields=array();
	
	var $sErrorMessage='';
	var $aDefaultForm=array();
	
	var $aContainer=array();
	var $aDivision = array();
	
	function Download(){
		parent::Controller();
		$this->load->library('session');	
		
		$this->sUsername=$this->session->userdata('s_username');
		$this->sLevel=$this->session->userdata('s_level');
		
		$this->load->model('Product_model');
		$this->load->model('Util_model');
		
		$this->load->helper('url');
		$this->load->library('form');
		
		$this->load->library('parser');
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="message_error">', '</div>');
		$this->load->library('validatejs');
		
		$this->aDivision = $this->config->item('division');
	}
	
	function index($sDivision, $sMessage=''){
		if ( (!$this->session->userdata('b_ag_transaction_read') || !$this->session->userdata('b_ag_transaction_write')) && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if ( (!$this->session->userdata('b_eg_transaction_read') || !$this->session->userdata('b_eg_transaction_write')) && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$sCriteria='';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata('download_search');
		$aSearchForm=array(
			's_serial_no_filter'			=> '', 
			's_lot_no_filter'				=> '', 
			's_color_filter'				=> '', 
			'd_order_date_month_filter'		=> '', 'd_order_date_year_filter'		=> '',
			's_po_no_filter'				=> '',
			's_po_filter'					=> '',
			's_buyer_filter'				=> '',
			's_model_filter'				=> '',
			'd_production_date_month_filter'=> '', 'd_production_date_year_filter'	=> '',
			'sSort'							=> 'd_createtime', 'sSortMethod'	=> 'DESC');
		$aSessionSearchForm = $this->session->userdata('download_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('download_sort');
		
		/* -- Pagination -- */
		$nOffset=0;
		$nLimit=$this->nRowsPerPage;
		$aPagination=array();
		$aSessionPagination = $this->session->userdata('download_pagination');
		
		if( isset($_POST['nOffset']) ){
			/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata('download_search');
				$this->session->unset_userdata('download_search_form');
			}
			
			if ( $this->input->post('s_serial_no_filter') ) {
				$s_serial_no_filter=$this->input->post('s_serial_no_filter');
				$aCriteria[]="ttp.s_serial_no ILIKE '%$s_serial_no_filter%'";
			}
			if ( $this->input->post('s_lot_no_filter') ) {
				$s_lot_no_filter=$this->input->post('s_lot_no_filter');
				$aCriteria[]="ttp.s_lot_no ILIKE '%$s_lot_no_filter%'";
			}
			if ( $this->input->post('s_color_filter') ) {
				$s_color_filter=$this->input->post('s_color_filter');
				$aCriteria[]="tmcl.s_description ILIKE '%$s_color_filter%'";
			}
			if ( $this->input->post('d_order_date_month_filter') ) {
				$d_order_date_month_filter=$this->input->post('d_order_date_month_filter');
				$aCriteria[]="EXTRACT(MONTH FROM ttp.d_order_date)='$d_order_date_month_filter'";
			}
			if ( $this->input->post('d_order_date_year_filter') ) {
				$d_order_date_year_filter=$this->input->post('d_order_date_year_filter');
				$aCriteria[]="EXTRACT(YEAR FROM ttp.d_order_date)='$d_order_date_year_filter'";
			}
			if ( $this->input->post('s_po_no_filter') ) {
				$s_po_no_filter=$this->input->post('s_po_no_filter');
				$aCriteria[]="ttpo.s_po_no ILIKE '%$s_po_no_filter%'";
			}
			if ( $this->input->post('s_po_filter') ) {
				$s_po_filter=$this->input->post('s_po_filter');
				$aCriteria[]="ttpo.s_po ILIKE '%$s_po_filter%'";
			}
			if ( $this->input->post('s_buyer_filter') ) {
				$s_buyer_filter=$this->input->post('s_buyer_filter');
				$aCriteria[]="ttp.s_buyer = '$s_buyer_filter'";
			}
			if ( $this->input->post('s_model_filter') ) {
				$s_model_filter=$this->input->post('s_model_filter');
				$aCriteria[]="tmm.s_description ILIKE '%$s_model_filter%'";
			}
			if ( $this->input->post('d_production_date_month_filter') ) {
				$d_production_date_month_filter=$this->input->post('d_production_date_month_filter');
				$aCriteria[]="EXTRACT(MONTH FROM ttp.d_production_date)='$d_production_date_month_filter'";
			}
			if ( $this->input->post('d_production_date_year_filter') ) {
				$d_production_date_year_filter=$this->input->post('d_production_date_year_filter');
				$aCriteria[]="EXTRACT(YEAR FROM ttp.d_production_date)='$d_production_date_year_filter'";
			}
			foreach ($aSearchForm as $sField=>$sValue) $aSearchForm[$sField]=$this->input->post($sField);
			if ( !empty($aCriteria) ) {
				$this->session->set_userdata(array('download_search' => $aCriteria));
				$this->session->set_userdata(array('download_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if ( !empty($aSessionSort) ) $this->session->unset_userdata('download_sort');
			if ( $this->input->post('sSort') && $this->input->post('sSortMethod') ) {
				$sSort=$this->input->post('sSort');
				$sSortMethod=$this->input->post('sSortMethod');
				if ( $this->input->post('bSortAction') ) {
					$sSortMethod=($this->input->post('sSortMethod')=='ASC' ? 'DESC' :'ASC');
					$aSearchForm['sSortMethod']=$sSortMethod;
				}
				$aSort=array($sSort => $sSortMethod);
			}
			if ( !empty($aSort) ) $this->session->set_userdata(array('download_sort' => $aSort));
			
			/* -- Pagination -- */
			if ( !empty($aSessionPagination) ) $this->session->unset_userdata('download_pagination');
			$aPagination['nOffset']=($this->input->post('nOffset') ? $this->input->post('nOffset') : 0 );
			$aPagination['nLimit']=($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage );
			if ( !empty($aPagination) ) $this->session->set_userdata(array('download_pagination' => $aPagination));
		} else {
			/* -- Default View -- */
			if (empty($aSessionSearch['d_production_date_month_filter'])) {
				$d_production_date_month_filter=date('m');
				$sPartCriteria="EXTRACT(MONTH FROM ttp.d_production_date)='$d_production_date_month_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['d_production_date_month_filter']=$sPartCriteria;
				$aSearchForm['d_production_date_month_filter']=$d_production_date_month_filter;
			}
			if (empty($aSessionSearch['d_production_date_year_filter'])) {
				$d_production_date_year_filter=date('Y');
				$sPartCriteria="EXTRACT(YEAR FROM ttp.d_production_date)='$d_production_date_year_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['d_production_date_year_filter']=$sPartCriteria;
				$aSearchForm['d_production_date_year_filter']=$d_production_date_year_filter;
			}
			/* -- Searching -- */
			if( !empty($aSessionSearch) ) {
				if ($sMessage!='') {
					$aCriteria=$aSessionSearch;
					$aSearchForm=$aSessionSearchForm;
				} else {
					$this->session->unset_userdata('download_search');
					$this->session->unset_userdata('download_search_form');
				}
			}
			/* -- Sorting -- */
			if( !empty($aSessionSort) ) {
				if ($sMessage!='') 
					$aSort=$aSessionSort;
				else 
					$this->session->unset_userdata('download_sort');
			}
			
			/* -- Pagination -- */
			if( !empty($aSessionPagination) ) {
				if ($sMessage!='') 
					$aPagination=$aSessionPagination;
				else 
					$this->session->unset_userdata('download_pagination');
			}
		}
		
		if ( !empty($aPagination) ) {
			$nOffset=$aPagination['nOffset'];
			$nLimit=$aPagination['nLimit'];
		}
		$aCriteria[]="ttp.s_division='".strtoupper($sDivision)."'";
		$aCriteria[]="ttp.d_process_14 IS NULL AND ttp.d_process_15 IS NULL";
		$sCriteria=implode(' AND ', $aCriteria);
		
		$aDataProduct=$this->Product_model->getList($sCriteria, $nLimit, $nOffset, $aSort);
		$nTotalRows=count($this->Product_model->getList($sCriteria));
		$sMessages='';
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/production/download/',
						'sDivision'			=> $sDivision,
						
						'MESSAGES'			=> '',
						'PAGE_TITLE'		=> 'SPMS-G. '.$this->aDivision[strtoupper($sDivision)].'/Download to PDT',
						'toolCaption'		=> 'Download Tool',
						'filterCaption'		=> 'Download Filter/Search',
						
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nRowsPerPage'		=> $this->nRowsPerPage,
						'nTotalRows'		=> $nTotalRows,
						'nCurrOffset'		=> $nOffset,
						
						'tt_prod_product'		=> $aDataProduct );
		$aDisplay = array_merge($aDisplay, $aSearchForm);
		$aDisplay['d_order_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_order_date_month_filter']);
		$aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter']);
		$aDisplay['s_buyer_filter'] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='".strtoupper($sDivision)."' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aDisplay['s_buyer_filter']);
		
		$aDisplay['viewFilter'] = $this->load->view($sDivision.'/download_filter', $aDisplay, TRUE);
		$aDisplay['viewToolbar'] = $this->load->view($sDivision.'/download_toolbar', $aDisplay, TRUE);
		
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse($sDivision.'/download', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function download_csv($sDivision) {
		if ( (!$this->session->userdata('b_ag_transaction_read') || !$this->session->userdata('b_ag_transaction_write')) && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if ( (!$this->session->userdata('b_eg_transaction_read') || !$this->session->userdata('b_eg_transaction_write')) && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$sCriteria = '';
		$aCriteria[]="ttp.s_division='".strtoupper($sDivision)."'";
		$aCriteria[]="ttp.d_process_14 IS NULL AND ttp.d_process_15 IS NULL";
		$sCriteria=implode(' AND ', $aCriteria);
		
		$aDataProduct=$this->Product_model->getList($sCriteria);
		$sCSV='';
		foreach ($aDataProduct as $nRow=>$aData) {
			$sCSV .= $aData['s_serial_no'].';';
			$sCSV .= $aData['s_po_no'].';';
			$sCSV .= $aData['s_po'].';';
			$sCSV .= $aData['d_order_date'].';';
			$sCSV .= $aData['d_production_date'].';';
			$sCSV .= $aData['d_plan_date'].';';
			$sCSV .= $aData['d_delivery_date'].';';
			$sCSV .= $aData['d_target_date'].';';
			$sCSV .= $aData['s_lot_no'].';';
			$sCSV .= $aData['s_buyer_name'].';';
			$sCSV .= $aData['s_brand'].';';
			$sCSV .= $aData['s_model_name'].';';
			$sCSV .= $aData['s_color_name'].';';
			$sCSV .= chr(13);
		}
		$this->load->helper('download');
		force_download('pdt_source.txt', $sCSV);
	}
}
?>
