<?php

class Reportlist extends Controller {
	var $sUsername;
	var $sLevel;
	
	var $nRowsPerPage=50;
	
	var $_bModal=false;
	var $_sModalTarget='';

	function Reportlist(){
		parent::Controller();
		$this->load->library('session');
		
		if (!$this->session->userdata('b_eg_report_read')) show_error('Access Denied');
		
		$this->sUsername=$this->session->userdata('s_username');
		$this->sLevel=$this->session->userdata('s_level');
		
		$this->load->model('eg/Report_model');
		$this->load->model('Util_model');
		
		$this->load->helper('url');
		$this->load->library('form');
		
		$this->load->library('parser');
		
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="message_error">', '</div>');
		$this->load->library('validatejs');
	}
	
	function index($sMessage=''){
		$nOffset=0;
		$nTotalRows=0;
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/eg/reportlist/',
						'MESSAGES'			=> '',
						'PAGE_TITLE'		=> 'SPMS-G. Electric Guitar/Report List',
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nRowsPerPage'		=> $this->nRowsPerPage,
						'nTotalRows'		=> $nTotalRows,
						'nCurrOffset'		=> $nOffset);
		
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse('eg/reportlist', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function stock($sMessage=''){
		$sCriteria='';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata('stock_search');
		$aSearchForm=array(
			's_serial_no_filter'			=> '', 's_serial_no2_filter'			=> '', 
			's_color_filter'				=> '',
			's_lot_no_filter'				=> '', 
			'd_production_date_month_filter'=> '', 'd_production_date_year_filter'	=> '',
			's_po_no_filter'				=> '',
			's_po_filter'					=> '',
			's_buyer_filter'				=> '',
			's_model_filter'				=> '',
			's_location_filter'				=> '',
			'sSort'						=> 'ttp.d_createtime', 'sSortMethod'	=> 'DESC');
		$aSessionSearchForm = $this->session->userdata('stock_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('stock_sort');
		
		/* -- Pagination -- */
		$nOffset=0;
		$nLimit=$this->nRowsPerPage;
		$aPagination=array();
		$aSessionPagination = $this->session->userdata('stock_pagination');
		
		if( isset($_POST['nOffset']) ){
			/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata('stock_search');
				$this->session->unset_userdata('stock_search_form');
			}
			
			if ( $this->input->post('s_serial_no_filter') ) {
				$s_serial_no_filter=$this->input->post('s_serial_no_filter');
				$aCriteria[]="ttp.s_serial_no ILIKE '%$s_serial_no_filter%'";
			}
			if ( $this->input->post('s_color_filter') ) {
				$s_color_filter=$this->input->post('s_color_filter');
				$aCriteria[]="ttp.s_color_name ILIKE '%$s_color_filter%'";
			}
			if ( $this->input->post('s_lot_no_filter') ) {
				$s_lot_no_filter=$this->input->post('s_lot_no_filter');
				$aCriteria[]="ttp.s_lot_no ILIKE '%$s_lot_no_filter%'";
			}
			$d_production_date_month_filter=sprintf('%02d', $this->input->post('d_production_date_month_filter'));
			$d_production_date_year_filter=$this->input->post('d_production_date_year_filter');
			/*if ( $this->input->post('d_production_date_month_filter') ) {
				$d_production_date_month_filter=$this->input->post('d_production_date_month_filter');
				$aCriteria[]="EXTRACT(MONTH FROM ttp.d_production_date)='$d_production_date_month_filter'";
			}
			if ( $this->input->post('d_production_date_year_filter') ) {
				$d_production_date_year_filter=$this->input->post('d_production_date_year_filter');
				$aCriteria[]="EXTRACT(YEAR FROM ttp.d_production_date)='$d_production_date_year_filter'";
			}*/
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
				$aCriteria[]="ttp.s_model_name ILIKE '%$s_model_filter%'";
			}
			if ( $this->input->post('s_location_filter') ) {
				$s_location_filter=$this->input->post('s_location_filter');
				$aCriteria[]="ttp.s_location ILIKE '%$s_location_filter%'";
			}
			foreach ($aSearchForm as $sField=>$sValue) $aSearchForm[$sField]=$this->input->post($sField);
			if ( !empty($aCriteria) ) {
				$this->session->set_userdata(array('stock_search' => $aCriteria));
				$this->session->set_userdata(array('stock_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if ( !empty($aSessionSort) ) $this->session->unset_userdata('stock_sort');
			if ( $this->input->post('sSort') && $this->input->post('sSortMethod') ) {
				$sSort=$this->input->post('sSort');
				$sSortMethod=$this->input->post('sSortMethod');
				if ( $this->input->post('bSortAction') ) {
					$sSortMethod=($this->input->post('sSortMethod')=='ASC' ? 'DESC' :'ASC');
					$aSearchForm['sSortMethod']=$sSortMethod;
				}
				$aSort=array($sSort => $sSortMethod);
			}
			if ( !empty($aSort) ) $this->session->set_userdata(array('stock_sort' => $aSort));
			
			/* -- Pagination -- */
			if ( !empty($aSessionPagination) ) $this->session->unset_userdata('stock_pagination');
			$aPagination['nOffset']=($this->input->post('nOffset') ? $this->input->post('nOffset') : 0 );
			$aPagination['nLimit']=($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage );
			if ( !empty($aPagination) ) $this->session->set_userdata(array('stock_pagination' => $aPagination));
		} else {
			/* -- Default View -- */
			$d_production_date_month_filter=sprintf('%02d', date('m'));
			$d_production_date_year_filter=date('Y');
			$aSearchForm['d_production_date_month_filter']=$d_production_date_month_filter;
			$aSearchForm['d_production_date_year_filter']=$d_production_date_year_filter;
			/*if (empty($aSessionSearch['d_production_date_month_filter'])) {
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
			}*/
			/* -- Searching -- */
			if( !empty($aSessionSearch) ) {
				if ($sMessage!='') {
					$aCriteria=$aSessionSearch;
					$aSearchForm=$aSessionSearchForm;
				} else {
					$this->session->unset_userdata('stock_search');
					$this->session->unset_userdata('stock_search_form');
				}
			}
			/* -- Sorting -- */
			if( !empty($aSessionSort) ) {
				if ($sMessage!='') 
					$aSort=$aSessionSort;
				else 
					$this->session->unset_userdata('stock_sort');
			}
			
			/* -- Pagination -- */
			if( !empty($aSessionPagination) ) {
				if ($sMessage!='') 
					$aPagination=$aSessionPagination;
				else 
					$this->session->unset_userdata('stock_pagination');
			}
		}
		
		if ( !empty($aPagination) ) {
			$nOffset=$aPagination['nOffset'];
			$nLimit=$aPagination['nLimit'];
		}
		
		$sCriteria=implode(' AND ', $aCriteria);
		
		$nFirstStock=$this->Report_model->getLastStock( $d_production_date_month_filter, $d_production_date_year_filter );
		
		//set view report
		$aTotalData=array();
		$aTotalDatas=array();
		
		$aDataProducts=$this->Report_model->getListStock($sCriteria, $d_production_date_month_filter, $d_production_date_year_filter);
		$nTotalRows=count($aDataProducts);
		
		$nOnProgress=0; $nIn=0; $nOut=0; 
		foreach($aDataProducts as $nRow=>$aData){
			$nOnProgress+=$aData['n_on_progress'];
			$nIn+=$aData['n_in'];
			$nOut+=$aData['n_out'];
		}
		$aTotalData['n_t_first_stock']=number_format($nFirstStock, 0, ',', '.');
		$aTotalData['n_t_on_progress']=number_format($nOnProgress, 0, ',', '.');
		$aTotalData['n_t_in']=number_format($nIn, 0, ',', '.');
		$aTotalData['n_t_out']=number_format($nOut, 0, ',', '.');
		$aTotalData['n_t_last_stock']=number_format($nFirstStock+$nIn-$nOut, 0, ',', '.');
		$aTotalDatas[]=$aTotalData;
		
		$sMessages='';
		if (empty($sMessage)) {
			$aDataProduct=array(); //$this->Report_model->getListStock($sCriteria, $d_production_date_month_filter, $d_production_date_year_filter, $nLimit, $nOffset, $aSort);
			$aDisplay=array('baseurl'			=> base_url(),
							'basesiteurl'		=> site_url(),
							'siteurl'		=> site_url().'/eg/reportlist/stock/',
							
							'MESSAGES'		=> '',
							'filterCaption'		=> 'EG Stock Filter/Search',
							
							'sGlobalUserName'	=> $this->sUsername,
							'sGlobalUserLevel' 	=> $this->sLevel,
							'nRowsPerPage'		=> $this->nRowsPerPage,
							'nTotalRows'		=> $nTotalRows,
							'nCurrOffset'		=> $nOffset,
							'tt_report_stock'	=> $aDataProduct,
							'tt_report_stock_total'	=> $aTotalDatas);
			$aDisplay['PAGE_TITLE']	= 'SPMS-G. Electric Guitar/Last Month';
			
			$aDisplay = array_merge($aDisplay, $aSearchForm);
			$aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter']);
			$aDisplay['s_buyer_filter'] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='EG' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aDisplay['s_buyer_filter']);
			
			$aDisplay['viewFilter'] = $this->load->view('eg/report/stock_filter', $aDisplay, TRUE);
			
			$this->parser->parse('header', $aDisplay);
			$this->parser->parse('eg/report/stock', $aDisplay);
			$this->parser->parse('footer', $aDisplay);
		} else {
			$this->load->helper('excel');
			$aHeader = array(
				'd_production_month' => 'Month',
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
				's_location' => 'Location',
				'n_on_progress' => 'On Progress',
				'n_in' => 'In',
				'n_out' => 'Out');
			$aDatas=array();
			foreach($aDataProducts as $nRow=>$aEachDataProduct){
				$aData=array();
				foreach($aEachDataProduct as $sField=>$sValue) {
					if (isset($aHeader[$sField])) {
						$aData[$aHeader[$sField]]=$sValue;
					} /*else {
						$aData[$sField]=$sValue;
					}*/
				}
				$aDatas[]=$aData;
			}
			$aTotalDatas=array();
			if (count($aDataProducts)>0) {
				foreach($aDataProducts[0] as $sField=>$sValue) {
					if (isset($aHeader[$sField])) {
						if ($sField=='s_color_name') {
							$aTotalDatas[$sField] = 'Past Stock : '.$aTotalData['n_t_first_stock'];
						} elseif ($sField=='n_on_progress') {
							$aTotalDatas[$sField] = $aTotalData['n_t_on_progress'];
						} elseif ($sField=='n_in') {
							$aTotalDatas[$sField] = $aTotalData['n_t_in'];
						} elseif ($sField=='n_out') {
							$aTotalDatas[$sField] = $aTotalData['n_t_out'];
						} else {
							$aTotalDatas[$sField]=' ';
						}
					}
				}
				$aDatas[]=$aTotalDatas;
				foreach($aDataProducts[0] as $sField=>$sValue) {
					if (isset($aHeader[$sField])) {
						if ($sField=='n_out') {
							$aTotalDatas[$sField] = 'Last Stock : '.$aTotalData['n_t_last_stock'];
						} else {
							$aTotalDatas[$sField]=' ';
						}
					}
				}
				$aDatas[]=$aTotalDatas;
			}
			to_excel_array($aDatas, 'report_last_month');
		}
	}
	
	function daily($bExcel=''){
		$nOffset=0;
		$nTotalRows=0;
		
		$d_production_date_month_filter = $this->input->post('d_production_date_month_filter');
		if ( !$this->input->post('d_production_date_month_filter') ) $d_production_date_month_filter=date('m');
		
		$d_production_date_year_filter=$this->input->post('d_production_date_year_filter');
		if ( !$this->input->post('d_production_date_year_filter') ) $d_production_date_year_filter=date('Y');
		
		$aDataProduct=$this->Report_model->getListDaily($d_production_date_month_filter, $d_production_date_year_filter);
		
		$aDataProductTotal=array();
		$aDataProductTotals=array();
		$n_t_total=0;
		foreach ($aDataProduct as $nRow=>$aData) {
			for ($nCount=1; $nCount<=31; $nCount++) {
				if (!isset($aDataProductTotal['n_t_date_'.$nCount])) $aDataProductTotal['n_t_date_'.$nCount]=0;
				$aDataProductTotal['n_t_date_'.$nCount]+=$aData['n_date_'.$nCount];
			}
			$n_t_total+=$aData['n_total'];
		}
		$aDataProductTotal['n_t_total']=number_format($n_t_total, 0, ',', '.');
		$aDataProductTotals[]=$aDataProductTotal;
		
		if (empty($bExcel)) {
			$aDisplay=array('baseurl'			=> base_url(),
							'basesiteurl'		=> site_url(),
							'siteurl'			=> site_url().'/eg/reportlist/daily/',
							
							'MESSAGES'			=> '',
							'PAGE_TITLE'		=> 'SPMS-G. Electric Guitar/Daily (Production)',
							'filterCaption'		=> 'EG Daily Filter/Search',
							
							'sGlobalUserName'	=> $this->sUsername,
							'sGlobalUserLevel' 	=> $this->sLevel,
							'nRowsPerPage'		=> $this->nRowsPerPage,
							'aData'				=> $aDataProduct,
							'aDataTotal'		=> $aDataProductTotals,
							'nTotalRows'		=> $nTotalRows,
							'nCurrOffset'		=> $nOffset);
			
			$aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $d_production_date_month_filter);
			$aDisplay['d_production_date_year_filter'] = $d_production_date_year_filter;
			
			$aDisplay['viewFilter'] = $this->load->view('eg/report/daily_filter', $aDisplay, TRUE);
			
			$this->parser->parse('header', $aDisplay);
			$this->parser->parse('eg/report/daily', $aDisplay);
			$this->parser->parse('footer', $aDisplay);
		} else {
			// excel exporter
			$this->load->helper('excel');
			$aDataProduct[]=$aDataProductTotal;
			to_excel_array($aDataProduct, 'daily');
		}
	}
	
	function group($sViewReport, $sMessage=''){
		$sCriteria='';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata($sViewReport.'_search');
		$aSearchForm=array(
			's_color_filter'				=> '',
			's_lot_no_filter'				=> '', 
			'd_production_date_month_filter'=> '', 'd_production_date_year_filter'	=> '',
			'd_production_date_month_filter2'=> '', 'd_production_date_year_filter2'=> '',
			's_po_no_filter'				=> '',
			's_po_filter'					=> '',
			's_buyer_filter'				=> '',
			's_model_filter'				=> '',
			's_status_filter'				=> '',
			's_location_filter'				=> '',
			'sSort'							=> 'd_production_date', 'sSortMethod'	=> 'ASC');
		$aSessionSearchForm = $this->session->userdata($sViewReport.'_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata($sViewReport.'_sort');
		
		if( isset($_POST['nOffset']) ){
			/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata($sViewReport.'_search');
				$this->session->unset_userdata($sViewReport.'_search_form');
			}
			
			if ( $this->input->post('s_color_filter') ) {
				$s_color_filter=$this->input->post('s_color_filter');
				$aCriteria[]="ttp.s_color_name ILIKE '%$s_color_filter%'";
			}
			if ( $this->input->post('s_lot_no_filter') ) {
				$s_lot_no_filter=$this->input->post('s_lot_no_filter');
				$aCriteria[]="ttp.s_lot_no ILIKE '%$s_lot_no_filter%'";
			}
			if ( $this->input->post('d_production_date_month_filter') && $this->input->post('d_production_date_year_filter') ) {
				$d_production_date_month_filter=sprintf('%02d', $this->input->post('d_production_date_month_filter'));
				$d_production_date_year_filter=$this->input->post('d_production_date_year_filter');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$aCriteria[]="TO_CHAR(ttp.d_production_date, 'YYYYMM')>='$d_production_date_year_month_filter'";
			}
			if ( $this->input->post('d_production_date_month_filter2') && $this->input->post('d_production_date_year_filter2') ) {
				$d_production_date_month_filter=sprintf('%02d', $this->input->post('d_production_date_month_filter2'));
				$d_production_date_year_filter=$this->input->post('d_production_date_year_filter2');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$aCriteria[]="TO_CHAR(ttp.d_production_date, 'YYYYMM')<='$d_production_date_year_month_filter'";
			}
			if ( $this->input->post('s_po_no_filter') ) {
				$s_po_no_filter=$this->input->post('s_po_no_filter');
				$aCriteria[]="ttp.s_po_no ILIKE '%$s_po_no_filter%'";
			}
			if ( $this->input->post('s_po_filter') ) {
				$s_po_filter=$this->input->post('s_po_filter');
				$aCriteria[]="ttp.s_po ILIKE '%$s_po_filter%'";
			}
			if ( $this->input->post('s_buyer_filter') ) {
				$s_buyer_filter=$this->input->post('s_buyer_filter');
				$aCriteria[]="ttp.s_buyer = '$s_buyer_filter'";
			}
			if ( $this->input->post('s_model_filter') ) {
				$s_model_filter=$this->input->post('s_model_filter');
				$aCriteria[]="ttp.s_model_name ILIKE '%$s_model_filter%'";
			}
			if ( $this->input->post('s_location_filter') ) {
				$s_location_filter=$this->input->post('s_location_filter');
				$aCriteria[]="ttp.s_location ILIKE '%$s_location_filter%'";
			}
			if ( $this->input->post('s_status_filter') ) {
				$s_status_filter=$this->input->post('s_status_filter');
				if ($s_status_filter == 'export') {
					$aCriteria[]="ttp.n_process_14 > 0";
				} else {
					$aCriteria[]="ttp.n_process_14 = 0";
				}
			}
			foreach ($aSearchForm as $sField=>$sValue) $aSearchForm[$sField]=$this->input->post($sField);
			if ( !empty($aCriteria) ) {
				$this->session->set_userdata(array($sViewReport.'_search' => $aCriteria));
				$this->session->set_userdata(array($sViewReport.'_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if ( !empty($aSessionSort) ) $this->session->unset_userdata($sViewReport.'_sort');
			if ( $this->input->post('sSort') && $this->input->post('sSortMethod') ) {
				$sSort=$this->input->post('sSort');
				$sSortMethod=$this->input->post('sSortMethod');
				if ( $this->input->post('bSortAction') ) {
					$sSortMethod=($this->input->post('sSortMethod')=='ASC' ? 'DESC' :'ASC');
					$aSearchForm['sSortMethod']=$sSortMethod;
				}
				$aSort=array($sSort => $sSortMethod);
			}
			if ( !empty($aSort) ) $this->session->set_userdata(array($sViewReport.'_sort' => $aSort));
		} else {
			/* -- Default View -- */
			if (empty($aSessionSearch['d_production_date_month_filter']) && empty($aSessionSearch['d_production_date_year_filter'])) {
				$d_production_date_month_filter=date('m'); $d_production_date_year_filter=date('Y');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$sPartCriteria="TO_CHAR(ttp.d_production_date, 'YYYYMM')>='$d_production_date_year_month_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['d_production_date_month_filter']=$sPartCriteria;
				$aSearch['d_production_date_year_filter']=$sPartCriteria;
				$aSearchForm['d_production_date_month_filter']=$d_production_date_month_filter;
				$aSearchForm['d_production_date_year_filter']=$d_production_date_year_filter;
			}
			if (empty($aSessionSearch['d_production_date_month_filter2']) && empty($aSessionSearch['d_production_date_year_filter2'])) {
				$d_production_date_month_filter=date('m'); $d_production_date_year_filter=date('Y');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$sPartCriteria="TO_CHAR(ttp.d_production_date, 'YYYYMM')<='$d_production_date_year_month_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['d_production_date_month_filter2']=$sPartCriteria;
				$aSearch['d_production_date_year_filter2']=$sPartCriteria;
				$aSearchForm['d_production_date_month_filter2']=$d_production_date_month_filter;
				$aSearchForm['d_production_date_year_filter2']=$d_production_date_year_filter;
			}
			$this->session->set_userdata(array($sViewReport.'_search' => $aCriteria));
			
			/* -- Searching -- */
			if( !empty($aSessionSearch) ) {
				if ($sMessage!='') {
					$aCriteria=$aSessionSearch;
					$aSearchForm=$aSessionSearchForm;
				} else {
					$this->session->unset_userdata($sViewReport.'_search');
					$this->session->unset_userdata($sViewReport.'_search_form');
				}
			}
			/* -- Sorting -- */
			if( !empty($aSessionSort) ) {
				if ($sMessage!='') 
					$aSort=$aSessionSort;
				else 
					$this->session->unset_userdata($sViewReport.'_sort');
			}
		}
		
		$sCriteria=implode(' AND ', $aCriteria);
		
		$aDataProduct = array();
		//set view report
		$aTotalData=array();
		$aTotalDatas=array();
		//declare variable temporary
		$n_t_qty=0; 
		$n_t_process_1s=0; $n_t_process_2s=0; 
		$n_t_process_1=0; $n_t_process_2=0; $n_t_process_3=0; $n_t_process_4=0; $n_t_process_5=0; $n_t_process_6=0; 
		$n_t_process_1_2=0; $n_t_process_2_2=0; 
		$n_t_process_7=0;$n_t_process_8=0; $n_t_process_9=0; $n_t_process_10=0; 
		$n_t_warehouse=0; $n_t_process_14=0;
		if (trim($sViewReport)=='po') {
			$aDataProduct=$this->Report_model->getListPo($sCriteria, 0, 0, $aSort);
		} elseif(trim($sViewReport)=='lot') {
			$aDataProduct=$this->Report_model->getListLot($sCriteria, 0, 0, $aSort);
		} elseif(trim($sViewReport)=='buyer') {
			$aDataProduct=$this->Report_model->getListBuyer($sCriteria, 0, 0, $aSort);
		} elseif(trim($sViewReport)=='model') {
			$aDataProduct=$this->Report_model->getListModel($sCriteria, 0, 0, $aSort);
		} elseif(trim($sViewReport)=='color') {
			$aDataProduct=$this->Report_model->getListColor($sCriteria, 0, 0, $aSort);
		}
		$nTotalRows=count($aDataProduct);
		
		foreach($aDataProduct as $nRow=>$aEachDataProduct){
			$n_t_qty+=$aEachDataProduct['n_qty'];
			$n_t_process_1s+=$aEachDataProduct['n_process_1s']; $n_t_process_1+=$aEachDataProduct['n_process_1']; $n_t_process_1_2+=$aEachDataProduct['n_process_1_2'];
			$n_t_process_2s+=$aEachDataProduct['n_process_2s']; $n_t_process_2+=$aEachDataProduct['n_process_2']; $n_t_process_2_2+=$aEachDataProduct['n_process_2_2'];
			$n_t_process_3+=$aEachDataProduct['n_process_3'];
			$n_t_process_4+=$aEachDataProduct['n_process_4'];
			$n_t_process_5+=$aEachDataProduct['n_process_5'];
			$n_t_process_6+=$aEachDataProduct['n_process_6'];
			$n_t_process_7+=$aEachDataProduct['n_process_7'];
			$n_t_process_8+=$aEachDataProduct['n_process_8'];
			$n_t_process_9+=$aEachDataProduct['n_process_9'];
			$n_t_process_10+=$aEachDataProduct['n_process_10'];
			$n_t_warehouse+=$aEachDataProduct['n_warehouse'];
			$n_t_process_14+=$aEachDataProduct['n_process_14'];
		}
		$aTotalData['n_t_qty']= number_format($n_t_qty , 0, ',', '.');
		$aTotalData['n_t_process_1s']=number_format($n_t_process_1s , 0, ',', '.'); 
		$aTotalData['n_t_process_1']=number_format($n_t_process_1 , 0, ',', '.'); 
		$aTotalData['n_t_process_1_2']=number_format($n_t_process_1_2 , 0, ',', '.');
		$aTotalData['n_t_process_2s']=number_format($n_t_process_2s , 0, ',', '.'); 
		$aTotalData['n_t_process_2']=number_format($n_t_process_2 , 0, ',', '.'); 
		$aTotalData['n_t_process_2_2']=number_format($n_t_process_2_2 , 0, ',', '.');
		$aTotalData['n_t_process_3']=number_format($n_t_process_3 , 0, ',', '.');
		$aTotalData['n_t_process_4']=number_format($n_t_process_4 , 0, ',', '.');
		$aTotalData['n_t_process_5']=number_format($n_t_process_5 , 0, ',', '.');
		$aTotalData['n_t_process_6']=number_format($n_t_process_6 , 0, ',', '.');
		$aTotalData['n_t_process_7']=number_format($n_t_process_7 , 0, ',', '.');
		$aTotalData['n_t_process_8']=number_format($n_t_process_8 , 0, ',', '.');
		$aTotalData['n_t_process_9']=number_format($n_t_process_9 , 0, ',', '.');
		$aTotalData['n_t_process_10']=number_format($n_t_process_10 , 0, ',', '.');
		$aTotalData['n_t_warehouse']=number_format($n_t_warehouse , 0, ',', '.');
		$aTotalData['n_t_process_14']=number_format($n_t_process_14 , 0, ',', '.');
		$aTotalDatas[]=$aTotalData;
				
		$sMessages='';
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'		=> site_url().'/eg/reportlist/group/'.$sViewReport.'/',
						
						'MESSAGES'		=> '',
						'filterCaption'		=> 'EG Group Filter/Search',
						
						'sViewReport'		=> $sViewReport,
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nTotalRows'		=> $nTotalRows,
						'tt_report_stock'	=> $aDataProduct,
						'tt_report_stock_total'	=> $aTotalDatas);
		
		if (trim($sViewReport)=='po') {
			$aDisplay['PAGE_TITLE']	= 'SPMS-G. Electric Guitar/Group By PI Number (Stock)';
		}elseif (trim($sViewReport)=='lot') {
			$aDisplay['PAGE_TITLE']	= 'SPMS-G. Electric Guitar/Group By LOT Number (Stock)';
		}elseif (trim($sViewReport)=='buyer') {
			$aDisplay['PAGE_TITLE']	= 'SPMS-G. Electric Guitar/Group By Buyer(Stock)';
		}elseif (trim($sViewReport)=='model') {
			$aDisplay['PAGE_TITLE']	= 'SPMS-G. Electric Guitar/Group By Model (Stock)';
		}elseif (trim($sViewReport)=='color') {
			$aDisplay['PAGE_TITLE']	= 'SPMS-G. Electric Guitar/Group By Color (Stock)';
		}
		
		$aDisplay = array_merge($aDisplay, $aSearchForm);
		$aDisplay['s_location_filter'] = $this->form->selectboxarray($this->config->item('product_location'), $aSearchForm['s_location_filter']);
		$aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter']);
		$aDisplay['d_production_date_month_filter2'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter2']);
		$aDisplay['s_buyer_filter'] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='EG' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aDisplay['s_buyer_filter']);
		$aDisplay['s_status_filter'] = $this->form->selectboxarray($this->config->item('status_export'), $aSearchForm['s_status_filter']);
		
		$aDisplay['viewFilter'] = $this->load->view('eg/report/'.$sViewReport.'_filter', $aDisplay, TRUE);
		
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse('eg/report/'.$sViewReport, $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function groupexcel($sViewReport) {
		$this->load->helper('excel');
		$aSessionSearch = $this->session->userdata($sViewReport.'_search');
		$aSort = $this->session->userdata($sViewReport.'_sort');
		
		$sCriteria=(!empty($aSessionSearch) ? implode(' AND ', $aSessionSearch) : '');
		
		//set view report
		$aTotalData=array();
		$aTotalDatas=array();
		//declare variable temporary
		$n_t_qty=0; 
		$n_t_process_1s=0; $n_t_process_2s=0; 
		$n_t_process_1=0; $n_t_process_2=0; $n_t_process_3=0; $n_t_process_4=0; $n_t_process_5=0; $n_t_process_6=0; 
		$n_t_process_1_2=0; $n_t_process_2_2=0; 
		$n_t_process_7=0; $n_t_process_8=0; $n_t_process_9=0; $n_t_process_10=0;
		$n_t_warehouse=0; $n_t_process_14=0; 
		if (trim($sViewReport)=='po') {
			$aDataProduct=$this->Report_model->getListPo($sCriteria, 0, 0, $aSort);
		} elseif(trim($sViewReport)=='lot') {
			$aDataProduct=$this->Report_model->getListLot($sCriteria, 0, 0, $aSort);
		} elseif(trim($sViewReport)=='buyer') {
			$aDataProduct=$this->Report_model->getListBuyer($sCriteria, 0, 0, $aSort);
		} elseif(trim($sViewReport)=='model') {
			$aDataProduct=$this->Report_model->getListModel($sCriteria, 0, 0, $aSort);
		} elseif(trim($sViewReport)=='color') {
			$aDataProduct=$this->Report_model->getListColor($sCriteria, 0, 0, $aSort);
		}
		$aHeader = array(
			'd_production_date' => 'Month',
			'd_plan_date' => 'Production Plan Date (Input)',
			'd_delivery_date' => 'Production Plan Date (Output)',
			'd_target_date' => 'Export Plan Date',
			's_buyer_name' => 'Buyer',
			's_po_no' => 'PI Number',
			's_po' => 'PO',
			's_lot_no' => 'Lot No',
			's_model' => 'Model',
			's_model_name' => 'Model Name',
			's_color_name' => 'Color',
			's_smodel' => 'Item Code',
			's_location' => 'Location',
			'n_qty' => 'Qty',

			'n_process_1' => 'WK-I Center Input',
			'n_process_1_2' => 'Body - Center Input',
			'n_process_2' => 'WK-I Center Output',
			'n_process_3' => 'WK-II',
			'n_process_4' => 'WK-II Control Center',
			'n_process_5' => 'Coating-I',
			'n_process_6' => 'Coating-IIA',
			'n_process_7' => 'Coating-IIB',
			'n_process_8' => 'Assembly-I_Control Center',
			'n_process_9' => 'Assembly-II',
			'n_process_10' => 'Packing',
			'n_warehouse' => 'Warehouse Incoming',
			'n_process_14' => 'Warehouse Outgoing');
		$aDatas=array();
		foreach($aDataProduct as $nRow=>$aEachDataProduct){
			$n_t_qty+=$aEachDataProduct['n_qty'];
			$n_t_process_1s+=$aEachDataProduct['n_process_1s']; $n_t_process_1+=$aEachDataProduct['n_process_1']; $n_t_process_1_2+=$aEachDataProduct['n_process_1_2'];
			$n_t_process_2s+=$aEachDataProduct['n_process_2s']; $n_t_process_2+=$aEachDataProduct['n_process_2']; $n_t_process_2_2+=$aEachDataProduct['n_process_2_2'];
			$n_t_process_3+=$aEachDataProduct['n_process_3'];
			$n_t_process_4+=$aEachDataProduct['n_process_4'];
			$n_t_process_5+=$aEachDataProduct['n_process_5'];
			$n_t_process_6+=$aEachDataProduct['n_process_6'];
			$n_t_process_7+=$aEachDataProduct['n_process_7'];
			$n_t_process_8+=$aEachDataProduct['n_process_8'];
			$n_t_process_9+=$aEachDataProduct['n_process_9'];
			$n_t_process_10+=$aEachDataProduct['n_process_10'];
			$n_t_warehouse+=$aEachDataProduct['n_warehouse'];
			$n_t_process_14+=$aEachDataProduct['n_process_14'];
			
			$aData=array();
			foreach($aEachDataProduct as $sField=>$sValue) {
				if (isset($aHeader[$sField])) {
					$aData[$aHeader[$sField]]=$sValue;
				} else {
					$aData[$sField]=$sValue;
				}
			}
			$aDatas[]=$aData;
		}
		if (count($aDataProduct)>0) {
			foreach($aDataProduct[0] as $sField=>$sValue) {
				if ($sField=='n_process_1s') break;
				$aTotalData[$sField]=' ';
			}
		}
		$aTotalData['n_t_process_1s']=$n_t_process_1s; $aTotalData['n_t_process_1']=$n_t_process_1; $aTotalData['n_t_process_1_2']=$n_t_process_1_2;
		$aTotalData['n_t_process_2s']=$n_t_process_2s; $aTotalData['n_t_process_2']=$n_t_process_2; $aTotalData['n_t_process_2_2']=$n_t_process_2_2;
		$aTotalData['n_t_process_3']=$n_t_process_3;
		$aTotalData['n_t_process_4']=$n_t_process_4;
		$aTotalData['n_t_process_5']=$n_t_process_5;
		$aTotalData['n_t_process_6']=$n_t_process_6;
		$aTotalData['n_t_process_7']=$n_t_process_7;
		$aTotalData['n_t_process_8']=$n_t_process_8;
		$aTotalData['n_t_process_9']=$n_t_process_9;
		$aTotalData['n_t_process_10']=$n_t_process_10;
		$aTotalData['n_t_warehouse']=$n_t_warehouse;
		$aTotalData['n_t_process_14']=$n_t_process_14;
		$aTotalData['n_t_qty']=$n_t_qty;
		
		$aDatas[]=$aTotalData;
		
		to_excel_array($aDatas, 'report_group_by_'.$sViewReport);
	}
	
	function serialdatephase($sMessage=''){
		$sCriteria='';
		$sPhaseName='ttp.d_process_1';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata('serialdatephase_search');
		$aSearchForm=array(
			'n_line_filter'					=> '', 's_type_filter'					=> '',
			's_serial_no_filter'			=> '', 's_serial_no2_filter'			=> '', 
			's_phase_filter'				=> '', 'd_transaction_date_filter'		=> '',
			's_color_filter'				=> '',
			's_lot_no_filter'				=> '', 
			'd_production_date_month_filter'=> '', 'd_production_date_year_filter'	=> '',
			'd_production_date_month_filter2'=> '', 'd_production_date_year_filter2'=> '',
			's_po_no_filter'				=> '',
			's_po_filter'					=> '',
			's_buyer_filter'				=> '',
			's_model_filter'				=> '',
			's_location_filter'				=> '',
			'sSort'							=> 'd_production_date', 'sSortMethod'	=> 'ASC');
		$aSessionSearchForm = $this->session->userdata('serialdatephase_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('serialdatephase_sort');
		
		/* -- Pagination -- */
		$nOffset=0;
		$nLimit=$this->nRowsPerPage;
		$aPagination=array();
		$aSessionPagination = $this->session->userdata('serialdatephase_pagination');
		
		if( isset($_POST['nOffset']) ){
			/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata('serialdatephase_search');
				$this->session->unset_userdata('serialdatephase_search_form');
			}
			
			if ( $this->input->post('n_line_filter') ) {
				$n_line_filter=$this->input->post('n_line_filter');
				$aCriteria[]="ttp.n_line = $n_line_filter";
			}
			if ( $this->input->post('s_type_filter') ) {
				$s_type_filter=$this->input->post('s_type_filter');
				$aCriteria[]="ttp.s_type = '$s_type_filter'";
			}
			if ( $this->input->post('s_serial_no_filter') && !$this->input->post('s_serial_no2_filter')) {
				$s_serial_no_filter=$this->input->post('s_serial_no_filter');
				$aCriteria[]="ttp.s_serial_no ILIKE '%$s_serial_no_filter%'";
			}
			if ( $this->input->post('s_serial_no_filter') && $this->input->post('s_serial_no2_filter')) {
				$s_serial_no_filter=$this->input->post('s_serial_no_filter');
				$s_serial_no2_filter=$this->input->post('s_serial_no2_filter');
				$aCriteria[]="(UPPER(ttp.s_serial_no) BETWEEN UPPER('$s_serial_no_filter') AND UPPER('$s_serial_no2_filter'))";
			}
			if ( $this->input->post('s_phase_filter') ) {
				$sPhaseName=$this->input->post('s_phase_filter');
				if ($sPhaseName=='d_process_1')
					$aCriteria[]="ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NULL AND ttp.d_process_3 IS NULL AND ttp.d_process_4 IS NULL AND 
						ttp.d_process_5 IS NULL AND ttp.d_process_6 IS NULL AND ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND 
						ttp.d_process_9 IS NULL AND ttp.d_process_10 IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_2')
					$aCriteria[]="ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL AND ttp.d_process_3 IS NULL AND ttp.d_process_4 IS NULL AND 
						ttp.d_process_5 IS NULL AND ttp.d_process_6 IS NULL AND ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND 
						ttp.d_process_9 IS NULL AND ttp.d_process_10 IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_3')
					$aCriteria[]="ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NULL AND 
						ttp.d_process_5 IS NULL AND ttp.d_process_6 IS NULL AND ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND 
						ttp.d_process_9 IS NULL AND ttp.d_process_10 IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_4')
					$aCriteria[]="ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL AND 
						ttp.d_process_5 IS NULL AND ttp.d_process_6 IS NULL AND ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND 
						ttp.d_process_9 IS NULL AND ttp.d_process_10 IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_5')
					$aCriteria[]="ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL AND 
						ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NULL AND ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND 
						ttp.d_process_9 IS NULL AND ttp.d_process_10 IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_6')
					$aCriteria[]="ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL AND 
						ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NOT NULL AND ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND 
						ttp.d_process_9 IS NULL AND ttp.d_process_10 IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_7')
					$aCriteria[]="ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL AND 
						ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NOT NULL AND ttp.d_process_7 IS NOT NULL AND ttp.d_process_8 IS NULL AND 
						ttp.d_process_9 IS NULL AND ttp.d_process_10 IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_8')
					$aCriteria[]="ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL AND 
						ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NOT NULL AND ttp.d_process_7 IS NOT NULL AND ttp.d_process_8 IS NOT NULL AND 
						ttp.d_process_9 IS NULL AND ttp.d_process_10 IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_9')
					$aCriteria[]="ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL AND 
						ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NOT NULL AND ttp.d_process_7 IS NOT NULL AND ttp.d_process_8 IS NOT NULL AND 
						ttp.d_process_9 IS NOT NULL AND ttp.d_process_10 IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_10')
					$aCriteria[]="ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL AND 
						ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NOT NULL AND ttp.d_process_7 IS NOT NULL AND ttp.d_process_8 IS NOT NULL AND 
						ttp.d_process_9 IS NOT NULL AND ttp.d_process_10 IS NOT NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_14')
					$aCriteria[]="ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL AND 
						ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NOT NULL AND ttp.d_process_7 IS NOT NULL AND ttp.d_process_8 IS NOT NULL AND 
						ttp.d_process_9 IS NOT NULL AND ttp.d_process_10 IS NOT NULL AND ttp.d_process_14 IS NOT NULL";
			}
			if ( $this->input->post('d_transaction_date_filter') ) {
				$d_transaction_date_filter=$this->input->post('d_transaction_date_filter');
				$aCriteria[]="TO_CHAR(ttp.".$this->input->post('s_phase_filter').", 'YYYY-MM-DD') = '$d_transaction_date_filter'";
			}
			if ( $this->input->post('s_phase_filter') ) {
				$sPhaseName=$this->input->post('s_phase_filter');
				$aCriteria[]="ttp.$sPhaseName IS NOT NULL";
			}
			if ( $this->input->post('s_color_filter') ) {
				$s_color_filter=$this->input->post('s_color_filter');
				$aCriteria[]="ttp.s_color_name ILIKE '%$s_color_filter%'";
			}
			if ( $this->input->post('s_lot_no_filter') ) {
				$s_lot_no_filter=$this->input->post('s_lot_no_filter');
				$aCriteria[]="ttp.s_lot_no ILIKE '%$s_lot_no_filter%'";
			}
			if ( $this->input->post('d_production_date_month_filter') && $this->input->post('d_production_date_year_filter') ) {
				$d_production_date_month_filter=sprintf('%02d', $this->input->post('d_production_date_month_filter'));
				$d_production_date_year_filter=$this->input->post('d_production_date_year_filter');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$aCriteria[]="TO_CHAR(ttp.d_production_date, 'YYYYMM')>='$d_production_date_year_month_filter'";
			}
			if ( $this->input->post('d_production_date_month_filter2') && $this->input->post('d_production_date_year_filter2') ) {
				$d_production_date_month_filter=sprintf('%02d', $this->input->post('d_production_date_month_filter2'));
				$d_production_date_year_filter=$this->input->post('d_production_date_year_filter2');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$aCriteria[]="TO_CHAR(ttp.d_production_date, 'YYYYMM')<='$d_production_date_year_month_filter'";
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
				$aCriteria[]="ttp.s_model_name LIKE '%$s_model_filter%'";
			}
			if ( $this->input->post('s_location_filter') ) {
				$s_location_filter=$this->input->post('s_location_filter');
				$aCriteria[]="ttp.s_location ILIKE '%$s_location_filter%'";
			}
			foreach ($aSearchForm as $sField=>$sValue) $aSearchForm[$sField]=$this->input->post($sField);
			if ( !empty($aCriteria) ) {
				$this->session->set_userdata(array('serialdatephase_search' => $aCriteria));
				$this->session->set_userdata(array('serialdatephase_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if ( !empty($aSessionSort) ) $this->session->unset_userdata('serialdatephase_sort');
			if ( $this->input->post('sSort') && $this->input->post('sSortMethod') ) {
				$sSort=$this->input->post('sSort');
				$sSortMethod=$this->input->post('sSortMethod');
				if ( $this->input->post('bSortAction') ) {
					$sSortMethod=($this->input->post('sSortMethod')=='ASC' ? 'DESC' :'ASC');
					$aSearchForm['sSortMethod']=$sSortMethod;
				}
				$aSort=array($sSort => $sSortMethod);
			}
			if ( !empty($aSort) ) $this->session->set_userdata(array('serialdatephase_sort' => $aSort));
			
			/* -- Pagination -- */
			if ( !empty($aSessionPagination) ) $this->session->unset_userdata('serialdatephase_pagination');
			$aPagination['nOffset']=($this->input->post('nOffset') ? $this->input->post('nOffset') : 0 );
			$aPagination['nLimit']=($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage );
			if ( !empty($aPagination) ) $this->session->set_userdata(array('serialdatephase_pagination' => $aPagination));
		} else {
			/* -- Default View -- */
			$sPhaseName=(empty($aSearchForm['s_phase_filter']) ? 'd_process_1' : $aSearchForm['s_phase_filter']);
			if (empty($aSessionSearch['s_phase_filter'])) {
				$s_phase_filter='d_process_1';
				$sPartCriteria="ttp.$s_phase_filter IS NOT NULL AND	ttp.d_process_2 IS NULL AND 
					ttp.d_process_3 IS NULL AND ttp.d_process_4 IS NULL AND 
					ttp.d_process_5 IS NULL AND ttp.d_process_6 IS NULL AND 
					ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND 
					ttp.d_process_9 IS NULL AND ttp.d_process_10 IS NULL AND ttp.d_process_14 IS NULL";
				$aCriteria[]=$sPartCriteria;
				$aSearch['s_phase_filter']=$sPartCriteria;
				$aSearchForm['s_phase_filter']=$s_phase_filter;
			}
			if (empty($aSessionSearch['n_line_filter'])) {
				$sPartCriteria="ttp.n_line = 1";
				$aCriteria[]=$sPartCriteria;
				$aSearch['n_line_filter']=$sPartCriteria;
				$aSearchForm['n_line_filter']=1;
			}
			if (empty($aSessionSearch['d_production_date_month_filter']) && empty($aSessionSearch['d_production_date_year_filter'])) {
				$d_production_date_month_filter=date('m'); $d_production_date_year_filter=date('Y');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$sPartCriteria="TO_CHAR(ttp.d_production_date, 'YYYYMM')>='$d_production_date_year_month_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['d_production_date_month_filter']=$sPartCriteria;
				$aSearch['d_production_date_year_filter']=$sPartCriteria;
				$aSearchForm['d_production_date_month_filter']=$d_production_date_month_filter;
				$aSearchForm['d_production_date_year_filter']=$d_production_date_year_filter;
			}
			if (empty($aSessionSearch['d_production_date_month_filter2']) && empty($aSessionSearch['d_production_date_year_filter2'])) {
				$d_production_date_month_filter=date('m'); $d_production_date_year_filter=date('Y');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$sPartCriteria="TO_CHAR(ttp.d_production_date, 'YYYYMM')<='$d_production_date_year_month_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['d_production_date_month_filter2']=$sPartCriteria;
				$aSearch['d_production_date_year_filter2']=$sPartCriteria;
				$aSearchForm['d_production_date_month_filter2']=$d_production_date_month_filter;
				$aSearchForm['d_production_date_year_filter2']=$d_production_date_year_filter;
			}
			/* -- Searching -- */
			if( !empty($aSessionSearch) ) {
				if ($sMessage!='') {
					$aCriteria=$aSessionSearch;
					$aSearchForm=$aSessionSearchForm;
				} else {
					$this->session->unset_userdata('serialdatephase_search');
					$this->session->unset_userdata('serialdatephase_search_form');
				}
			}
			/* -- Sorting -- */
			if( !empty($aSessionSort) ) {
				if ($sMessage!='') 
					$aSort=$aSessionSort;
				else 
					$this->session->unset_userdata('serialdatephase_sort');
			}
			/* -- Pagination -- */
			if( !empty($aSessionPagination) ) {
				if ($sMessage!='') 
					$aPagination=$aSessionPagination;
				else 
					$this->session->unset_userdata('serialdatephase_pagination');
			}
		}
		
		if ( !empty($aPagination) ) {
			$nOffset=$aPagination['nOffset'];
			$nLimit=$aPagination['nLimit'];
		}
		
		$sCriteria=implode(' AND ', $aCriteria);
		
		$aDataProduct = array();
		//set view report
		$aTotalData=array();
		$aTotalDatas=array();
		//declare variable temporary
		$n_t_process_1=0; $n_t_process_2=0; $n_t_process_3=0;
		$n_t_process_4=0; $n_t_process_5=0; $n_t_process_6=0; $n_t_process_7=0; $n_t_process_8=0;
		$n_t_process_9=0; $n_t_process_10=0; $n_t_process_14=0;
		$aAllDataProduct=$this->Report_model->getListSerialDatePhase($sCriteria, 0, 0, $aSort);
		$nTotalRows=count($aAllDataProduct);
		
		foreach($aAllDataProduct as $nRow=>$aEachDataProduct){
			$n_t_process_1+=(!empty($aEachDataProduct['d_process_1']) && empty($aEachDataProduct['d_process_2']) && empty($aEachDataProduct['d_process_3']) && 
							 empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_2+=(!empty($aEachDataProduct['d_process_2']) && empty($aEachDataProduct['d_process_3']) && 
							 empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_3+=(!empty($aEachDataProduct['d_process_3']) && 
							 empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_4+=(!empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_5+=(!empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_6+=(!empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_7+=(!empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_8+=(!empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_9+=(!empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_10+=(!empty($aEachDataProduct['d_process_10']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_14+=(!empty($aEachDataProduct['d_process_14']) ? 1 : 0);
		}
		$aTotalData['n_t_process_1']=number_format($n_t_process_1 , 0, ',', '.'); 
		$aTotalData['n_t_process_2']=number_format($n_t_process_2 , 0, ',', '.'); 
		$aTotalData['n_t_process_3']=number_format($n_t_process_3 , 0, ',', '.');
		$aTotalData['n_t_process_4']=number_format($n_t_process_4 , 0, ',', '.');
		$aTotalData['n_t_process_5']=number_format($n_t_process_5 , 0, ',', '.');
		$aTotalData['n_t_process_6']=number_format($n_t_process_6 , 0, ',', '.');
		$aTotalData['n_t_process_7']=number_format($n_t_process_7 , 0, ',', '.');
		$aTotalData['n_t_process_8']=number_format($n_t_process_8 , 0, ',', '.');
		$aTotalData['n_t_process_9']=number_format($n_t_process_9 , 0, ',', '.');
		$aTotalData['n_t_process_10']=number_format($n_t_process_10 , 0, ',', '.');
		$aTotalData['n_t_process_14']=number_format($n_t_process_14 , 0, ',', '.');
		$aTotalDatas[]=$aTotalData;
				
		if (empty($sMessage)) {
			$aDataProduct=$this->Report_model->getListSerialDatePhase($sCriteria, $nLimit, $nOffset, $aSort);
			$aDisplay=array('baseurl'			=> base_url(),
							'basesiteurl'		=> site_url(),
							'siteurl'		=> site_url().'/eg/reportlist/serialdatephase/',
							
							'MESSAGES'		=> '',
							'PAGE_TITLE'		=> 'SPMS-G. Electric Guitar/Serial No, Date & Phase (Production)',
							'filterCaption'		=> 'EG Report Filter/Search',
							
							'sGlobalUserName'	=> $this->sUsername,
							'sGlobalUserLevel' 	=> $this->sLevel,
							
							'nTotalRows'		=> $nTotalRows,
							'nRowsPerPage'		=> $this->nRowsPerPage,
							'nCurrOffset'		=> $nOffset,
							
							'tt_report_stock'	=> $aDataProduct,
							'tt_report_stock_total'	=> $aTotalDatas);
			
			$aDisplay = array_merge($aDisplay, $aSearchForm);
			$aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter']);
			$aDisplay['d_production_date_month_filter2'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter2']);
			$aDisplay['s_phase_filter'] = $this->form->selectbox('tm_prod_setup', 's_field_process, s_phase || \' - \' || s_description AS s_description', "s_division='EG' AND n_line=1 AND s_field_process<>'d_warehouse' GROUP BY s_field_process, s_phase, s_description, n_order, s_phase ORDER BY n_order ASC, s_phase ASC", 's_field_process', 's_description', $sPhaseName);
			$aDisplay['s_buyer_filter'] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='EG' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aDisplay['s_buyer_filter']);
			$this->load->model('Product_model');
			$aDisplay['n_line_filter'] = $this->form->selectboxarray($this->config->item('product_line'), $aSearchForm['n_line_filter']);
			$aDisplay['s_type_filter'] = $this->form->selectboxarray($this->config->item('production_process'), $aSearchForm['s_type_filter']);
			
			$aDisplay['s_location_filter'] = $this->form->selectboxarray($this->config->item('product_location'), 
			$aSearchForm['s_location_filter']);
			$aDisplay['viewFilter'] = $this->load->view('eg/report/serialdatephase_filter', $aDisplay, TRUE);
			
			$this->parser->parse('header', $aDisplay);
			$this->parser->parse('eg/report/serialdatephase', $aDisplay);
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
				's_type' => 'Type Process',
				's_lot_no' => 'Lot No',
				's_model' => 'Model',
				's_model_name' => 'Model Name',
				's_color_name' => 'Color',
				's_smodel' => 'Item Code',
				's_location' => 'Location',
				'd_process_1' => 'WK-I Center Input',
				'd_process_2' => 'WK-I Center Output',
				'd_process_3' => 'WK-II',
				'd_process_4' => 'WK-II Control Center',
				'd_process_5' => 'Coating-I',
				'd_process_6' => 'Coating-IIA',
				'd_process_7' => 'Coating-IIB',
				'd_process_8' => 'Assembly-I_Control Center',
				'd_process_9' => 'Assembly-II',
				'd_process_10' => 'Packing',
				'd_process_14' => 'Warehouse Outgoing');
			$aDatas=array();
			foreach($aAllDataProduct as $nRow=>$aEachDataProduct){
				$aData=array();
				foreach($aEachDataProduct as $sField=>$sValue) {
					if (isset($aHeader[$sField])) {
						$aData[$aHeader[$sField]]=$sValue;
					} /*else {
						$aData[$sField]=$sValue;
					}*/
				}
				$aDatas[]=$aData;
			}
			$aTotalDatas=array();
			if (count($aAllDataProduct)>0) {
				$nCounter=1;
				foreach($aAllDataProduct[0] as $sField=>$sValue) {
					if (isset($aHeader[$sField])) {
						if (substr($sField,0,9)=='d_process') {
							$aTotalDatas[$sField] = $aTotalData['n_t_process_'.($nCounter >= 11 ? ($nCounter + 3) : $nCounter)];
							$nCounter++;
						} else {
							$aTotalDatas[$sField]=' ';
						}
					}
				}
			}
			$aDatas[]=$aTotalDatas;
			to_excel_array($aDatas, 'report_serial_date_phase');
		}
	}
	
	function serialphase($sMessage=''){
		$sCriteria='';
		$sPhaseName='ttp.d_process_1';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata('serialphase_search');
		$aSearchForm=array(
			'n_line_filter'					=> '', 's_type_filter'					=> '', 
			's_serial_no_filter'			=> '', 's_serial_no2_filter'			=> '', 
			's_phase_filter'				=> '', 'd_transaction_date_filter'		=> '',
			's_color_filter'				=> '',
			's_lot_no_filter'				=> '', 
			'd_production_date_month_filter'=> '', 'd_production_date_year_filter'	=> '',
			'd_production_date_month_filter2'=> '', 'd_production_date_year_filter2'=> '',
			's_po_no_filter'				=> '',
			's_po_filter'					=> '',
			's_buyer_filter'				=> '',
			's_model_filter'				=> '',
			's_location_filter'				=> '',
			'sSort'							=> 'd_production_date', 'sSortMethod'	=> 'ASC');
		$aSessionSearchForm = $this->session->userdata('serialphase_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('serialphase_sort');
		
		/* -- Pagination -- */
		$nOffset=0;
		$nLimit=$this->nRowsPerPage;
		$aPagination=array();
		$aSessionPagination = $this->session->userdata('serialphase_pagination');
		
		if( isset($_POST['nOffset']) ){
			/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata('serialphase_search');
				$this->session->unset_userdata('serialphase_search_form');
			}
			
			if ( $this->input->post('n_line_filter') ) {
				$n_line_filter=$this->input->post('n_line_filter');
				$aCriteria[]="ttp.n_line = $n_line_filter";
			}
			if ( $this->input->post('s_type_filter') ) {
				$s_type_filter=$this->input->post('s_type_filter');
				$aCriteria[]="ttp.s_type = '$s_type_filter'";
			}
			if ( $this->input->post('s_serial_no_filter') && !$this->input->post('s_serial_no2_filter')) {
				$s_serial_no_filter=$this->input->post('s_serial_no_filter');
				$aCriteria[]="ttp.s_serial_no ILIKE '%$s_serial_no_filter%'";
			}
			if ( $this->input->post('s_serial_no_filter') && $this->input->post('s_serial_no2_filter')) {
				$s_serial_no_filter=$this->input->post('s_serial_no_filter');
				$s_serial_no2_filter=$this->input->post('s_serial_no2_filter');
				$aCriteria[]="(UPPER(ttp.s_serial_no) BETWEEN UPPER('$s_serial_no_filter') AND UPPER('$s_serial_no2_filter'))";
			}
			if ( $this->input->post('s_phase_filter') ) {
				$sPhaseName=$this->input->post('s_phase_filter');
				if ($sPhaseName=='d_process_1')
					$aCriteria[]="
						((ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NULL) OR
						 (ttp.d_process_1s IS NOT NULL AND ttp.d_process_2s IS NULL) OR
						 (ttp.d_process_1_2 IS NOT NULL AND ttp.d_process_2_2 IS NULL))
						AND ttp.d_process_3 IS NULL AND ttp.d_process_4 IS NULL 
						AND ttp.d_process_5 IS NULL AND ttp.d_process_6 IS NULL
						AND ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND ttp.d_process_9 IS NULL AND ttp.d_process_10 IS NULL 
						AND ttp.d_warehouse IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_2')
					$aCriteria[]="
						((ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL) OR 
						 (ttp.d_process_1s IS NOT NULL AND ttp.d_process_2s IS NOT NULL) OR 
						 (ttp.d_process_1_2 IS NOT NULL AND ttp.d_process_2_2 IS NOT NULL))
						AND ttp.d_process_3 IS NULL AND ttp.d_process_4 IS NULL 
						AND ttp.d_process_5 IS NULL AND ttp.d_process_6 IS NULL
						AND ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND ttp.d_process_9 IS NULL 
						AND ttp.d_process_10 IS NULL AND ttp.d_warehouse IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_3')
					$aCriteria[]="
						((ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL) OR 
						 (ttp.d_process_1s IS NOT NULL AND ttp.d_process_2s IS NOT NULL) OR 
						 (ttp.d_process_1_2 IS NOT NULL AND ttp.d_process_2_2 IS NOT NULL))
						AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NULL 
						AND ttp.d_process_5 IS NULL AND ttp.d_process_6 IS NULL
						AND ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND ttp.d_process_9 IS NULL 
						AND ttp.d_process_10 IS NULL AND ttp.d_warehouse IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_4')
					$aCriteria[]="
						((ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL) OR 
						 (ttp.d_process_1s IS NOT NULL AND ttp.d_process_2s IS NOT NULL) OR 
						 (ttp.d_process_1_2 IS NOT NULL AND ttp.d_process_2_2 IS NOT NULL))
						AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL 
						AND ttp.d_process_5 IS NULL AND ttp.d_process_6 IS NULL
						AND ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND ttp.d_process_9 IS NULL 
						AND ttp.d_process_10 IS NULL AND ttp.d_warehouse IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_5')
					$aCriteria[]="
						((ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL) OR 
						 (ttp.d_process_1s IS NOT NULL AND ttp.d_process_2s IS NOT NULL) OR 
						 (ttp.d_process_1_2 IS NOT NULL AND ttp.d_process_2_2 IS NOT NULL))
						AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL 
						AND ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NULL
						AND ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND ttp.d_process_9 IS NULL 
						AND ttp.d_process_10 IS NULL AND ttp.d_warehouse IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_6')
					$aCriteria[]="
						((ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL) OR 
						 (ttp.d_process_1s IS NOT NULL AND ttp.d_process_2s IS NOT NULL) OR 
						 (ttp.d_process_1_2 IS NOT NULL AND ttp.d_process_2_2 IS NOT NULL))
						AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL 
						AND ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NOT NULL
						AND ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND ttp.d_process_9 IS NULL 
						AND ttp.d_process_10 IS NULL AND ttp.d_warehouse IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_7')
					$aCriteria[]="
						((ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL) OR 
						 (ttp.d_process_1s IS NOT NULL AND ttp.d_process_2s IS NOT NULL) OR 
						 (ttp.d_process_1_2 IS NOT NULL AND ttp.d_process_2_2 IS NOT NULL))
						AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL 
						AND ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NOT NULL
						AND ttp.d_process_7 IS NOT NULL AND ttp.d_process_8 IS NULL AND ttp.d_process_9 IS NULL 
						AND ttp.d_process_10 IS NULL AND ttp.d_warehouse IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_8')
					$aCriteria[]="
						((ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL) OR 
						 (ttp.d_process_1s IS NOT NULL AND ttp.d_process_2s IS NOT NULL) OR 
						 (ttp.d_process_1_2 IS NOT NULL AND ttp.d_process_2_2 IS NOT NULL))
						AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL 
						AND ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NOT NULL
						AND ttp.d_process_7 IS NOT NULL AND ttp.d_process_8 IS NOT NULL AND ttp.d_process_9 IS NULL 
						AND ttp.d_process_10 IS NULL AND ttp.d_warehouse IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_9')
					$aCriteria[]="
						((ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL) OR 
						 (ttp.d_process_1s IS NOT NULL AND ttp.d_process_2s IS NOT NULL) OR 
						 (ttp.d_process_1_2 IS NOT NULL AND ttp.d_process_2_2 IS NOT NULL))
						AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL 
						AND ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NOT NULL
						AND ttp.d_process_7 IS NOT NULL AND ttp.d_process_8 IS NOT NULL AND ttp.d_process_9 IS NOT NULL 
						AND ttp.d_process_10 IS NULL AND ttp.d_warehouse IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_10')
					$aCriteria[]="
						((ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL) OR 
						 (ttp.d_process_1s IS NOT NULL AND ttp.d_process_2s IS NOT NULL) OR 
						 (ttp.d_process_1_2 IS NOT NULL AND ttp.d_process_2_2 IS NOT NULL))
						AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL 
						AND ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NOT NULL
						AND ttp.d_process_7 IS NOT NULL AND ttp.d_process_8 IS NOT NULL AND ttp.d_process_9 IS NOT NULL 
						AND ttp.d_process_10 IS NOT NULL AND ttp.d_warehouse IS NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_warehouse')
					$aCriteria[]="
						((ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL) OR 
						 (ttp.d_process_1s IS NOT NULL AND ttp.d_process_2s IS NOT NULL) OR 
						 (ttp.d_process_1_2 IS NOT NULL AND ttp.d_process_2_2 IS NOT NULL))
						AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL 
						AND ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NOT NULL
						AND ttp.d_process_7 IS NOT NULL AND ttp.d_process_8 IS NOT NULL AND ttp.d_process_9 IS NOT NULL 
						AND ttp.d_process_10 IS NOT NULL AND ttp.d_warehouse IS NOT NULL AND ttp.d_process_14 IS NULL";
				if ($sPhaseName=='d_process_14')
					$aCriteria[]="
						((ttp.d_process_1 IS NOT NULL AND ttp.d_process_2 IS NOT NULL) OR 
						 (ttp.d_process_1s IS NOT NULL AND ttp.d_process_2s IS NOT NULL) OR 
						 (ttp.d_process_1_2 IS NOT NULL AND ttp.d_process_2_2 IS NOT NULL))
						AND ttp.d_process_3 IS NOT NULL AND ttp.d_process_4 IS NOT NULL 
						AND ttp.d_process_5 IS NOT NULL AND ttp.d_process_6 IS NOT NULL
						AND ttp.d_process_7 IS NOT NULL AND ttp.d_process_8 IS NOT NULL AND ttp.d_process_9 IS NOT NULL 
						AND ttp.d_process_10 IS NOT NULL AND ttp.d_warehouse IS NOT NULL AND ttp.d_process_14 IS NOT NULL";
			}
			if ( $this->input->post('d_transaction_date_filter') ) {
				$d_transaction_date_filter=$this->input->post('d_transaction_date_filter');
				$aCriteria[]="TO_CHAR(ttp.".$this->input->post('s_phase_filter').", 'YYYY-MM-DD') = '$d_transaction_date_filter'";
			}
			if ( $this->input->post('s_color_filter') ) {
				$s_color_filter=$this->input->post('s_color_filter');
				$aCriteria[]="ttp.s_color_name ILIKE '%$s_color_filter%'";
			}
			if ( $this->input->post('s_lot_no_filter') ) {
				$s_lot_no_filter=$this->input->post('s_lot_no_filter');
				$aCriteria[]="ttp.s_lot_no ILIKE '%$s_lot_no_filter%'";
			}
			if ( $this->input->post('d_production_date_month_filter') && $this->input->post('d_production_date_year_filter') ) {
				$d_production_date_month_filter=sprintf('%02d', $this->input->post('d_production_date_month_filter'));
				$d_production_date_year_filter=$this->input->post('d_production_date_year_filter');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$aCriteria[]="TO_CHAR(ttp.d_production_date, 'YYYYMM')>='$d_production_date_year_month_filter'";
			}
			if ( $this->input->post('d_production_date_month_filter2') && $this->input->post('d_production_date_year_filter2') ) {
				$d_production_date_month_filter=sprintf('%02d', $this->input->post('d_production_date_month_filter2'));
				$d_production_date_year_filter=$this->input->post('d_production_date_year_filter2');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$aCriteria[]="TO_CHAR(ttp.d_production_date, 'YYYYMM')<='$d_production_date_year_month_filter'";
			}
			if ( $this->input->post('s_po_no_filter') ) {
				$s_po_no_filter=$this->input->post('s_po_no_filter');
				$aCriteria[]="ttp.s_po_no ILIKE '%$s_po_no_filter%'";
			}
			if ( $this->input->post('s_po_filter') ) {
				$s_po_filter=$this->input->post('s_po_filter');
				$aCriteria[]="ttp.s_po ILIKE '%$s_po_filter%'";
			}
			if ( $this->input->post('s_buyer_filter') ) {
				$s_buyer_filter=$this->input->post('s_buyer_filter');
				$aCriteria[]="ttp.s_buyer_name = '$s_buyer_filter'";
			}
			if ( $this->input->post('s_model_filter') ) {
				$s_model_filter=$this->input->post('s_model_filter');
				$aCriteria[]="ttp.s_model_name ILIKE '%$s_model_filter%'";
			}
			if ( $this->input->post('s_location_filter') ) {
				$s_location_filter=$this->input->post('s_location_filter');
				$aCriteria[]="ttp.s_location ILIKE '%$s_location_filter%'";
			}
			foreach ($aSearchForm as $sField=>$sValue) $aSearchForm[$sField]=$this->input->post($sField);
			if ( !empty($aCriteria) ) {
				$this->session->set_userdata(array('serialphase_search' => $aCriteria));
				$this->session->set_userdata(array('serialphase_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if ( !empty($aSessionSort) ) $this->session->unset_userdata('serialphase_sort');
			if ( $this->input->post('sSort') && $this->input->post('sSortMethod') ) {
				$sSort=$this->input->post('sSort');
				$sSortMethod=$this->input->post('sSortMethod');
				if ( $this->input->post('bSortAction') ) {
					$sSortMethod=($this->input->post('sSortMethod')=='ASC' ? 'DESC' :'ASC');
					$aSearchForm['sSortMethod']=$sSortMethod;
				}
				$aSort=array($sSort => $sSortMethod);
			}
			if ( !empty($aSort) ) $this->session->set_userdata(array('serialphase_sort' => $aSort));
			
			/* -- Pagination -- */
			if ( !empty($aSessionPagination) ) $this->session->unset_userdata('serialphase_pagination');
			$aPagination['nOffset']=($this->input->post('nOffset') ? $this->input->post('nOffset') : 0 );
			$aPagination['nLimit']=($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage );
			if ( !empty($aPagination) ) $this->session->set_userdata(array('serialphase_pagination' => $aPagination));
		} else {
			/* -- Default View -- */
			$sPhaseName=(empty($aSearchForm['s_phase_filter']) ? 'd_process_1' : $aSearchForm['s_phase_filter']);
			if (empty($aSessionSearch['s_phase_filter'])) {
				$s_phase_filter='d_process_1';
				$sPartCriteria="
					((ttp.$s_phase_filter IS NOT NULL AND ttp.d_process_2 IS NULL) OR
					(ttp.".$s_phase_filter."s IS NOT NULL AND ttp.d_process_2s IS NULL) OR
					(ttp.".$s_phase_filter."_2 IS NOT NULL AND ttp.d_process_2_2 IS NULL))
					AND ttp.d_process_3 IS NULL AND ttp.d_process_4 IS NULL 
					AND ttp.d_process_5 IS NULL AND ttp.d_process_6 IS NULL
					AND ttp.d_process_7 IS NULL AND ttp.d_process_8 IS NULL AND ttp.d_process_9 IS NULL AND ttp.d_process_10 IS NULL 
					AND ttp.d_warehouse IS NULL AND ttp.d_process_14 IS NULL";
				$aCriteria[]=$sPartCriteria;
				$aSearch['s_phase_filter']=$sPartCriteria;
				$aSearchForm['s_phase_filter']=$s_phase_filter;
			}
			if (empty($aSessionSearch['n_line_filter'])) {
				$sPartCriteria="ttp.n_line = 1";
				$aCriteria[]=$sPartCriteria;
				$aSearch['n_line_filter']=$sPartCriteria;
				$aSearchForm['n_line_filter']=1;
			}
			if (empty($aSessionSearch['d_production_date_month_filter']) && empty($aSessionSearch['d_production_date_year_filter'])) {
				$d_production_date_month_filter=date('m'); $d_production_date_year_filter=date('Y');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$sPartCriteria="TO_CHAR(ttp.d_production_date, 'YYYYMM')>='$d_production_date_year_month_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['d_production_date_month_filter']=$sPartCriteria;
				$aSearch['d_production_date_year_filter']=$sPartCriteria;
				$aSearchForm['d_production_date_month_filter']=$d_production_date_month_filter;
				$aSearchForm['d_production_date_year_filter']=$d_production_date_year_filter;
			}
			if (empty($aSessionSearch['d_production_date_month_filter2']) && empty($aSessionSearch['d_production_date_year_filter2'])) {
				$d_production_date_month_filter=date('m'); $d_production_date_year_filter=date('Y');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$sPartCriteria="TO_CHAR(ttp.d_production_date, 'YYYYMM')<='$d_production_date_year_month_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['d_production_date_month_filter2']=$sPartCriteria;
				$aSearch['d_production_date_year_filter2']=$sPartCriteria;
				$aSearchForm['d_production_date_month_filter2']=$d_production_date_month_filter;
				$aSearchForm['d_production_date_year_filter2']=$d_production_date_year_filter;
			}
			/* -- Searching -- */
			if( !empty($aSessionSearch) ) {
				if ($sMessage!='') {
					$aCriteria=$aSessionSearch;
					$aSearchForm=$aSessionSearchForm;
				} else {
					$this->session->unset_userdata('serialphase_search');
					$this->session->unset_userdata('serialphase_search_form');
				}
			}
			/* -- Sorting -- */
			if( !empty($aSessionSort) ) {
				if ($sMessage!='') 
					$aSort=$aSessionSort;
				else 
					$this->session->unset_userdata('serialphase_sort');
			}
			/* -- Pagination -- */
			if( !empty($aSessionPagination) ) {
				if ($sMessage!='') 
					$aPagination=$aSessionPagination;
				else 
					$this->session->unset_userdata('serialphase_pagination');
			}
		}
		
		if ( !empty($aPagination) ) {
			$nOffset=$aPagination['nOffset'];
			$nLimit=$aPagination['nLimit'];
		}
		
		$sCriteria=implode(' AND ', $aCriteria);
		
		$aDataProduct = array();
		//set view report
		$aTotalData=array();
		$aTotalDatas=array();
		//declare variable temporary
		$n_t_process_1=0; $n_t_process_2=0; $n_t_process_3=0; $n_t_process_4=0; 
		$n_t_process_5=0; $n_t_process_6=0; $n_t_process_7=0; 
		$n_t_process_8=0; $n_t_process_9=0; $n_t_process_10=0; $n_t_warehouse=0; $n_t_process_14=0;
		$aAllDataProduct=$this->Report_model->getListSerialPhase($sCriteria, 0, 0, $aSort);
		$nTotalRows=count($aAllDataProduct);
		
		foreach($aAllDataProduct as $nRow=>$aEachDataProduct){
			$n_t_process_1+=(!empty($aEachDataProduct['d_process_1']) && empty($aEachDataProduct['d_process_2']) && empty($aEachDataProduct['d_process_3']) && 
							 empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_2+=(!empty($aEachDataProduct['d_process_2']) && empty($aEachDataProduct['d_process_3']) && 
							 empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_3+=(!empty($aEachDataProduct['d_process_3']) && 
							 empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) &&
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_4+=(!empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_5+=(!empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_6+=(!empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_7+=(!empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_8+=(!empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_9+=(!empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_10+=(!empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_warehouse+=(!empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_14+=(!empty($aEachDataProduct['d_process_14']) ? 1 : 0);
		}
		$aTotalData['n_t_process_1']=number_format($n_t_process_1 , 0, ',', '.'); 
		$aTotalData['n_t_process_2']=number_format($n_t_process_2 , 0, ',', '.'); 
		$aTotalData['n_t_process_3']=number_format($n_t_process_3 , 0, ',', '.');
		$aTotalData['n_t_process_4']=number_format($n_t_process_4 , 0, ',', '.');
		$aTotalData['n_t_process_5']=number_format($n_t_process_5 , 0, ',', '.');
		$aTotalData['n_t_process_6']=number_format($n_t_process_6 , 0, ',', '.');
		$aTotalData['n_t_process_7']=number_format($n_t_process_7 , 0, ',', '.');
		$aTotalData['n_t_process_8']=number_format($n_t_process_8 , 0, ',', '.');
		$aTotalData['n_t_process_9']=number_format($n_t_process_9 , 0, ',', '.');
		$aTotalData['n_t_process_10']=number_format($n_t_process_10 , 0, ',', '.');
		$aTotalData['n_t_warehouse']=number_format($n_t_warehouse , 0, ',', '.');
		$aTotalData['n_t_process_14']=number_format($n_t_process_14 , 0, ',', '.');
		$aTotalDatas[]=$aTotalData;
		
		if (empty($sMessage)) {
			$aDataProduct=$this->Report_model->getListSerialPhase($sCriteria, $nLimit, $nOffset, $aSort);
			$aDisplay=array('baseurl'			=> base_url(),
							'basesiteurl'		=> site_url(),
							'siteurl'		=> site_url().'/eg/reportlist/serialphase/',
							
							'MESSAGES'		=> '',
							'PAGE_TITLE'		=> 'SPMS-G. Electric Guitar/Serial No & Phase (Stock)',
							'filterCaption'		=> 'EG Report Filter/Search',
							
							'sGlobalUserName'	=> $this->sUsername,
							'sGlobalUserLevel' 	=> $this->sLevel,
							
							'nTotalRows'		=> $nTotalRows,
							'nRowsPerPage'		=> $this->nRowsPerPage,
							'nCurrOffset'		=> $nOffset,
							
							'tt_report_stock'	=> $aDataProduct,
							'tt_report_stock_total'	=> $aTotalDatas);
			
			$aDisplay = array_merge($aDisplay, $aSearchForm);
			$aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter']);
			$aDisplay['d_production_date_month_filter2'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter2']);
			$aDisplay['s_phase_filter'] = $this->form->selectbox('tm_prod_setup', 's_field_process, s_phase || \' - \' || s_description AS s_description', "s_division='EG' AND n_line=1 GROUP BY s_field_process, s_phase, s_description, n_order, s_phase ORDER BY n_order ASC, s_phase ASC", 's_field_process', 's_description', $sPhaseName);
			$aDisplay['s_buyer_filter'] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='EG' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aDisplay['s_buyer_filter']);
			$this->load->model('Product_model');
			$aDisplay['n_line_filter'] = $this->form->selectboxarray($this->config->item('product_line'), $aSearchForm['n_line_filter']);
			$aDisplay['s_type_filter'] = $this->form->selectboxarray($this->config->item('production_process'), $aSearchForm['s_type_filter']);
			$aDisplay['s_location_filter'] = $this->form->selectboxarray($this->config->item('product_location'), $aSearchForm['s_location_filter']);
			$aDisplay['viewFilter'] = $this->load->view('eg/report/serialphase_filter', $aDisplay, TRUE);
			
			$this->parser->parse('header', $aDisplay);
			$this->parser->parse('eg/report/serialphase', $aDisplay);
			$this->parser->parse('footer', $aDisplay);
		} else {
			$this->load->helper('excel');
			$aHeader = array(
				'd_production_date' => 'Production Date',
				'd_plan_date' => 'Production Plan Date (Input)',
				'd_delivery_date' => 'Production Plan Date (Output)',
				'd_target_date' => 'Export Plan Date',
				's_serial_no' => 'Serial No',
				's_type' => 'Type Process',
				's_buyer_name' => 'Buyer',
				's_po_no' => 'PI Number',
				's_po' => 'PO',
				's_lot_no' => 'Lot No',
				's_model' => 'Model',
				's_model_name' => 'Model Name',
				's_color_name' => 'Color',
				's_smodel' => 'Item Code',
				's_location' => 'Location',
				'd_process_1' => 'WK-I Center Input',
				'd_process_2' => 'WK-I Center Output',
				'd_process_3' => 'WK-II',
				'd_process_4' => 'WK-II Control Center',
				'd_process_5' => 'Coating-I',
				'd_process_6' => 'Coating-IIA',
				'd_process_7' => 'Coating-IIB',
				'd_process_8' => 'Assembly-I_Control Center',
				'd_process_9' => 'Assembly-II',
				'd_process_10' => 'Packing',
				'd_warehouse' => 'Warehouse Incoming',
				'd_process_14' => 'Warehouse Outgoing');
			$aDatas=array();
			foreach($aAllDataProduct as $nRow=>$aEachDataProduct){
				$aData=array();
				foreach($aEachDataProduct as $sField=>$sValue) {
					if (isset($aHeader[$sField])) {
						$aData[$aHeader[$sField]]=$sValue;
					} /*else {
						$aData[$sField]=$sValue;
					}*/
				}
				$aDatas[]=$aData;
			}
			$aTotalDatas=array();
			if (count($aAllDataProduct)>0) {
				$nCounter=1;
				foreach($aAllDataProduct[0] as $sField=>$sValue) {
					if (isset($aHeader[$sField])) {
						if (substr($sField,0,9)=='d_process') {
							$aTotalDatas[$sField] = $aTotalData['n_t_process_'.($nCounter >= 11 ? ($nCounter + 3) : $nCounter)];
							$nCounter++;
						} elseif ($sField=='d_warehouse') {
							$aTotalDatas[$sField] = $aTotalData['n_t_warehouse'];
						} else {
							$aTotalDatas[$sField]=' ';
						}
					}
				}
			}
			$aDatas[]=$aTotalDatas;
			to_excel_array($aDatas, 'report_serial_phase');
		}
	}
	
	function serialdate($sMessage=''){
		$sCriteria='';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata('serialdate_search');
		$aSearchForm=array(
			'n_line_filter'					=> '', 
			's_serial_no_filter'			=> '', 's_serial_no2_filter'			=> '', 
			's_color_filter'				=> '',
			's_lot_no_filter'				=> '', 
			'd_production_date_month_filter'=> '', 'd_production_date_year_filter'	=> '',
			'd_production_date_month_filter2'=> '', 'd_production_date_year_filter2'=> '',
			's_po_no_filter'				=> '',
			's_po_filter'					=> '',
			's_buyer_filter'				=> '',
			's_model_filter'				=> '',
			's_location_filter'				=> '',
			'sSort'						=> 'd_production_date', 'sSortMethod'	=> 'ASC');
		$aSessionSearchForm = $this->session->userdata('serialdate_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('serialdate_sort');
		
		/* -- Pagination -- */
		$nOffset=0;
		$nLimit=$this->nRowsPerPage;
		$aPagination=array();
		$aSessionPagination = $this->session->userdata('serialdate_pagination');
		
		$n_line_filter=($this->input->post('n_line_filter') ? $this->input->post('n_line_filter') : 1);
		
		if( isset($_POST['nOffset']) ){
			/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata('serialdate_search');
				$this->session->unset_userdata('serialdate_search_form');
			}
			
			$aCriteria[]="ttp.n_line = $n_line_filter";
			if ( $this->input->post('s_serial_no_filter') && !$this->input->post('s_serial_no2_filter')) {
				$s_serial_no_filter=$this->input->post('s_serial_no_filter');
				$aCriteria[]="ttp.s_serial_no ILIKE '%$s_serial_no_filter%'";
			}
			if ( $this->input->post('s_serial_no_filter') && $this->input->post('s_serial_no2_filter')) {
				$s_serial_no_filter=$this->input->post('s_serial_no_filter');
				$s_serial_no2_filter=$this->input->post('s_serial_no2_filter');
				$aCriteria[]="(UPPER(ttp.s_serial_no) BETWEEN UPPER('$s_serial_no_filter') AND UPPER('$s_serial_no2_filter'))";
			}
			if ( $this->input->post('s_color_filter') ) {
				$s_color_filter=$this->input->post('s_color_filter');
				$aCriteria[]="ttp.s_color_name ILIKE '%$s_color_filter%'";
			}
			if ( $this->input->post('s_lot_no_filter') ) {
				$s_lot_no_filter=$this->input->post('s_lot_no_filter');
				$aCriteria[]="ttp.s_lot_no ILIKE '%$s_lot_no_filter%'";
			}
			if ( $this->input->post('d_production_date_month_filter') && $this->input->post('d_production_date_year_filter') ) {
				$d_production_date_month_filter=sprintf('%02d', $this->input->post('d_production_date_month_filter'));
				$d_production_date_year_filter=$this->input->post('d_production_date_year_filter');
				$d_production_date_year_month_filter=$d_production_date_year_filter."-".$d_production_date_month_filter."-01";
				$aCriteria[]="cast(ttp.d_production_date as date)>= cast('$d_production_date_year_month_filter' as date)";
			}
			if ( $this->input->post('d_production_date_month_filter2') && $this->input->post('d_production_date_year_filter2') ) {
				$d_production_date_month_filter=sprintf('%02d', $this->input->post('d_production_date_month_filter2'));
				$d_production_date_year_filter=$this->input->post('d_production_date_year_filter2');
				$d=cal_days_in_month(CAL_GREGORIAN,$d_production_date_month_filter,$d_production_date_year_filter);
				$d_production_date_year_month_filter=$d_production_date_year_filter."-".$d_production_date_month_filter."-".$d;
				$aCriteria[]="cast(ttp.d_production_date as date)<= cast('$d_production_date_year_month_filter' as date)";
			}
			if ( $this->input->post('s_po_no_filter') ) {
				$s_po_no_filter=$this->input->post('s_po_no_filter');
				$aCriteria[]="ttp.s_po_no ILIKE '%$s_po_no_filter%'";
			}
			if ( $this->input->post('s_po_filter') ) {
				$s_po_filter=$this->input->post('s_po_filter');
				$aCriteria[]="ttp.s_po ILIKE '%$s_po_filter%'";
			}
			if ( $this->input->post('s_buyer_filter') ) {
				$s_buyer_filter=$this->input->post('s_buyer_filter');
				$aCriteria[]="ttp.s_buyer = '$s_buyer_filter'";
			}
			if ( $this->input->post('s_model_filter') ) {
				$s_model_filter=$this->input->post('s_model_filter');
				$aCriteria[]="ttp.s_model_name ILIKE '%$s_model_filter%'";
			}
			if ( $this->input->post('s_location_filter') ) {
				$s_location_filter=$this->input->post('s_location_filter');
				$aCriteria[]="ttp.s_location ILIKE '%$s_location_filter%'";
			}

			foreach ($aSearchForm as $sField=>$sValue) $aSearchForm[$sField]=$this->input->post($sField);
			if ( !empty($aCriteria) ) {
				$this->session->set_userdata(array('serialdate_search' => $aCriteria));
				$this->session->set_userdata(array('serialdate_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if ( !empty($aSessionSort) ) $this->session->unset_userdata('serialdate_sort');
			if ( $this->input->post('sSort') && $this->input->post('sSortMethod') ) {
				$sSort=$this->input->post('sSort');
				$sSortMethod=$this->input->post('sSortMethod');
				if ( $this->input->post('bSortAction') ) {
					$sSortMethod=($this->input->post('sSortMethod')=='ASC' ? 'DESC' :'ASC');
					$aSearchForm['sSortMethod']=$sSortMethod;
				}
				$aSort=array($sSort => $sSortMethod);
			}
			if ( !empty($aSort) ) $this->session->set_userdata(array('serialdate_sort' => $aSort));
			
			/* -- Pagination -- */
			if ( !empty($aSessionPagination) ) $this->session->unset_userdata('serialdate_pagination');
			$aPagination['nOffset']=($this->input->post('nOffset') ? $this->input->post('nOffset') : 0 );
			$aPagination['nLimit']=($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage );
			if ( !empty($aPagination) ) $this->session->set_userdata(array('serialdate_pagination' => $aPagination));
		} else {
			/* -- Default View -- */
			if (empty($aSessionSearch['n_line_filter'])) {
				$sPartCriteria="ttp.n_line = $n_line_filter";
				$aCriteria[]=$sPartCriteria;
				$aSearch['n_line_filter']=$sPartCriteria;
				$aSearchForm['n_line_filter']=$n_line_filter;
			}
			if (empty($aSessionSearch['d_production_date_month_filter']) && empty($aSessionSearch['d_production_date_year_filter'])) {
				$d_production_date_month_filter=date('m'); $d_production_date_year_filter=date('Y');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$sPartCriteria="TO_CHAR(ttp.d_production_date, 'YYYYMM')>='$d_production_date_year_month_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['d_production_date_month_filter']=$sPartCriteria;
				$aSearch['d_production_date_year_filter']=$sPartCriteria;
				$aSearchForm['d_production_date_month_filter']=$d_production_date_month_filter;
				$aSearchForm['d_production_date_year_filter']=$d_production_date_year_filter;
			}
			if (empty($aSessionSearch['d_production_date_month_filter2']) && empty($aSessionSearch['d_production_date_year_filter2'])) {
				$d_production_date_month_filter=date('m'); $d_production_date_year_filter=date('Y');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$sPartCriteria="TO_CHAR(ttp.d_production_date, 'YYYYMM')<='$d_production_date_year_month_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['d_production_date_month_filter2']=$sPartCriteria;
				$aSearch['d_production_date_year_filter2']=$sPartCriteria;
				$aSearchForm['d_production_date_month_filter2']=$d_production_date_month_filter;
				$aSearchForm['d_production_date_year_filter2']=$d_production_date_year_filter;
			}
			/* -- Searching -- */
			if( !empty($aSessionSearch) ) {
				if ($sMessage!='') {
					$aCriteria=$aSessionSearch;
					$aSearchForm=$aSessionSearchForm;
				} else {
					$this->session->unset_userdata('serialdate_search');
					$this->session->unset_userdata('serialdate_search_form');
				}
			}
			/* -- Sorting -- */
			if( !empty($aSessionSort) ) {
				if ($sMessage!='') 
					$aSort=$aSessionSort;
				else 
					$this->session->unset_userdata('serialdate_sort');
			}
			
			/* -- Pagination -- */
			if( !empty($aSessionPagination) ) {
				if ($sMessage!='') 
					$aPagination=$aSessionPagination;
				else 
					$this->session->unset_userdata('serialdate_pagination');
			}
		}
		
		if ( !empty($aPagination) ) {
			$nOffset=$aPagination['nOffset'];
			$nLimit=$aPagination['nLimit'];
		}
		
		$sCriteria=implode(' AND ', $aCriteria);
		
		//set view report
		$aTotalData=array();
		$aTotalDatas=array();
		//declare variable temporary
		$n_t_process_1s=0; $n_t_process_2s=0; 
		$n_t_process_1=0; $n_t_process_2=0; $n_t_process_3=0; $n_t_process_4=0; $n_t_process_5=0; $n_t_process_6=0; 
		$n_t_process_1_2=0; $n_t_process_2_2=0; 
		$n_t_process_7=0; $n_t_process_8=0; $n_t_process_9=0; $n_t_process_10=0; 
		$n_t_warehouse=0; $n_t_process_14=0;
		if ($n_line_filter=='1') {
			$aAllDataProduct=$this->Report_model->getListSerialDate($sCriteria, 0, 0, $aSort);
		} else {
			$aAllDataProduct=$this->Report_model->getListSerialDate2($sCriteria, 0, 0, $aSort);
		}
		$nTotalRows=count($aAllDataProduct);
		
		foreach($aAllDataProduct as $nRow=>$aEachDataProduct){
			$n_t_process_1s+=(!empty($aEachDataProduct['d_process_1s']) && empty($aEachDataProduct['d_process_2s']) && empty($aEachDataProduct['d_process_3']) && 
							 empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_1+=(!empty($aEachDataProduct['d_process_1']) && empty($aEachDataProduct['d_process_2']) && empty($aEachDataProduct['d_process_3']) && 
							 empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_1_2+=(!empty($aEachDataProduct['d_process_1_2b']) && empty($aEachDataProduct['d_process_2_2b']) &&
							 empty($aEachDataProduct['d_process_3']) ? 1 : 0);
			$n_t_process_2s+=(!empty($aEachDataProduct['d_process_2s']) && empty($aEachDataProduct['d_process_3']) && 
							 empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_2+=(!empty($aEachDataProduct['d_process_2']) && empty($aEachDataProduct['d_process_3']) && 
							 empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_2_2+=(!empty($aEachDataProduct['d_process_2_2b']) && 
							 empty($aEachDataProduct['d_process_3']) ? 1 : 0);
			$n_t_process_3+=(!empty($aEachDataProduct['d_process_3']) && 
							 empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_4+=(!empty($aEachDataProduct['d_process_4']) && empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_5+=(!empty($aEachDataProduct['d_process_5']) && empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_6+=(!empty($aEachDataProduct['d_process_6']) && 
							 empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_7+=(!empty($aEachDataProduct['d_process_7']) && empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_8+=(!empty($aEachDataProduct['d_process_8']) && empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_9+=(!empty($aEachDataProduct['d_process_9']) && 
							 empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_10+=(!empty($aEachDataProduct['d_process_10']) && 
							 empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_warehouse+=(!empty($aEachDataProduct['d_warehouse']) && empty($aEachDataProduct['d_process_14']) ? 1 : 0);
			$n_t_process_14+=(!empty($aEachDataProduct['d_process_14']) ? 1 : 0);
		}
		
		$aTotalData['n_t_process_1s']=number_format($n_t_process_1s , 0, ',', '.'); 
		$aTotalData['n_t_process_1']=number_format($n_t_process_1 , 0, ',', '.'); 
		$aTotalData['n_t_process_1_2']=number_format($n_t_process_1_2 , 0, ',', '.');
		$aTotalData['n_t_process_2s']=number_format($n_t_process_2s , 0, ',', '.'); 
		$aTotalData['n_t_process_2']=number_format($n_t_process_2 , 0, ',', '.'); 
		$aTotalData['n_t_process_2_2']=number_format($n_t_process_2_2 , 0, ',', '.');
		$aTotalData['n_t_process_3']=number_format($n_t_process_3 , 0, ',', '.');
		$aTotalData['n_t_process_4']=number_format($n_t_process_4 , 0, ',', '.');
		$aTotalData['n_t_process_5']=number_format($n_t_process_5 , 0, ',', '.');
		$aTotalData['n_t_process_6']=number_format($n_t_process_6 , 0, ',', '.');
		$aTotalData['n_t_process_7']=number_format($n_t_process_7 , 0, ',', '.');
		$aTotalData['n_t_process_8']=number_format($n_t_process_8 , 0, ',', '.');
		$aTotalData['n_t_process_9']=number_format($n_t_process_9 , 0, ',', '.');
		$aTotalData['n_t_process_10']=number_format($n_t_process_10 , 0, ',', '.');
		$aTotalData['n_t_warehouse']=number_format($n_t_warehouse , 0, ',', '.');
		$aTotalData['n_t_process_14']=number_format($n_t_process_14 , 0, ',', '.');
		$aTotalDatas[]=$aTotalData;
				
		if (empty($sMessage)) {
			if ($n_line_filter=='1') {
				$aDataProduct=$this->Report_model->getListSerialDate($sCriteria, $nLimit, $nOffset, $aSort);
			} else {
				$aDataProduct=$this->Report_model->getListSerialDate2($sCriteria, $nLimit, $nOffset, $aSort);
			}
			$aDisplay=array('baseurl'			=> base_url(),
							'basesiteurl'		=> site_url(),
							'siteurl'			=> site_url().'/eg/reportlist/serialdate/',
							
							'MESSAGES'			=> '',
							'PAGE_TITLE'		=> 'SPMS-G. Electric Guitar/Serial No & Date (Stock)',
							'filterCaption'		=> 'EG Report Filter/Search',
							
							'sGlobalUserName'	=> $this->sUsername,
							'sGlobalUserLevel' 	=> $this->sLevel,
							'nRowsPerPage'		=> $this->nRowsPerPage,
							'nTotalRows'		=> $nTotalRows,
							'nCurrOffset'		=> $nOffset,
							'tt_report_stock'	=> $aDataProduct,
							'tt_report_stock_total'	=> $aTotalDatas);
			
			$aDisplay = array_merge($aDisplay, $aSearchForm);
			$aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter']);
			$aDisplay['d_production_date_month_filter2'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter2']);
			$aDisplay['s_buyer_filter'] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='EG' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aDisplay['s_buyer_filter']);
			$this->load->model('Product_model');
			$aDisplay['n_line_filter'] = $this->form->selectboxarray($this->config->item('product_line'), $aSearchForm['n_line_filter']);
			$aDisplay['s_location_filter'] = $this->form->selectboxarray($this->config->item('product_location'), 
				$aSearchForm['s_location_filter']);
			
			$aDisplay['viewFilter'] = $this->load->view('eg/report/serialdate_filter', $aDisplay, TRUE);
			
			$this->parser->parse('header', $aDisplay);
			$this->parser->parse('eg/report/serialdate', $aDisplay);
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
				's_location' => 'Location',
				'd_process_1s' => 'WK-I Center Input',
				'd_process_1' => 'WK-I Center Input [Bolt-Neck]',
				'd_process_1_2b' => 'WK-I Center Input [Bolt-Body]',
				'd_process_2s' => 'WK-I Center Output',
				'd_process_2' => 'WK-I Center Output [Bolt-Neck]',
				'd_process_2_2b' => 'WK-I Center Output [Bolt-Body]',
				'd_process_3' => 'WK-II',
				'd_process_4' => 'WK-II Control Center',
				'd_process_5' => 'Coating-I',
				'd_process_6' => 'Coating-IIA',
				'd_process_7' => 'Coating-IIB',
				'd_process_8' => 'Assembly-I_Control Center',
				'd_process_9' => 'Assembly-II',
				'd_process_10' => 'Packing',
				'd_warehouse' => 'Warehouse Incoming',
				'd_process_14' => 'Warehouse Outgoing');
			$aDatas=array();
			foreach($aAllDataProduct as $nRow=>$aEachDataProduct){
				$aData=array();
				foreach($aEachDataProduct as $sField=>$sValue) {
					if (isset($aHeader[$sField])) {
						$aData[$aHeader[$sField]]=$sValue;
					} /*else {
						$aData[$sField]=$sValue;
					}*/
				}
				$aDatas[]=$aData;
			}
			$aTotalDatas=array();
			if (count($aAllDataProduct)>0) {
				$nCounter=1; $nCounter2=1; $nCounters=1;
				foreach($aAllDataProduct[0] as $sField=>$sValue) {
					if (isset($aHeader[$sField])) {
						if (substr($sField,0,9)=='d_process' && substr($sField,-1)!='s' && substr($sField,-3)!='_2b') {
							$aTotalDatas[$sField] = $aTotalData['n_t_process_'.($nCounter >= 11 ? ($nCounter + 3) : $nCounter)];
							$nCounter++;
						} elseif (substr($sField,0,9)=='d_process' && substr($sField,-3)=='_2b') {
							$aTotalDatas[$sField] = $aTotalData['n_t_process_'.($nCounter2).'_2'];
							$nCounter2++;
						} elseif (substr($sField,0,9)=='d_process' && substr($sField,-1)=='s') {
							$aTotalDatas[$sField] = $aTotalData['n_t_process_'.($nCounters).'s'];
							$nCounters++;
						} elseif ($sField=='d_warehouse') {
							$aTotalDatas[$sField] = $aTotalData['n_t_warehouse'];
						} else {
							$aTotalDatas[$sField]=' ';
						}
					}
				}
			}
			$aDatas[]=$aTotalDatas;
			to_excel_array($aDatas, 'report_serial_date');
		}
	}
	
	function serial($sMessage=''){
		$sCriteria='';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata('serial_search');
		$aSearchForm=array(
			'n_line_filter'					=> '',
			's_serial_no_filter'			=> '', 's_serial_no2_filter'			=> '', 
			's_color_filter'				=> '',
			's_lot_no_filter'				=> '', 
			'd_production_date_month_filter'=> '', 'd_production_date_year_filter'	=> '',
			'd_production_date_month_filter2'=> '', 'd_production_date_year_filter2'=> '',
			's_po_no_filter'				=> '',
			's_po_filter'					=> '',
			's_buyer_filter'				=> '',
			's_model_filter'				=> '',
			's_status_filter'				=> '',
			's_location_filter'			=> '',
			'sSort'							=> 'd_production_date', 'sSortMethod'	=> 'ASC');
		$aSessionSearchForm = $this->session->userdata('serial_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('serial_sort');
		
		/* -- Pagination -- */
		$nOffset=0;
		$nLimit=$this->nRowsPerPage;
		$aPagination=array();
		$aSessionPagination = $this->session->userdata('serial_pagination');
		
		$n_line_filter=($this->input->post('n_line_filter') ? $this->input->post('n_line_filter') : 1);
		
		if( isset($_POST['nOffset']) ){
			/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata('serial_search');
				$this->session->unset_userdata('serial_search_form');
			}
			
			$aCriteria[]="ttp.n_line = $n_line_filter";
			
			if ( $this->input->post('s_serial_no_filter') && !$this->input->post('s_serial_no2_filter')) {
				$s_serial_no_filter=$this->input->post('s_serial_no_filter');
				$aCriteria[]="ttp.s_serial_no ILIKE '%$s_serial_no_filter%'";
			}
			if ( $this->input->post('s_serial_no_filter') && $this->input->post('s_serial_no2_filter')) {
				$s_serial_no_filter=$this->input->post('s_serial_no_filter');
				$s_serial_no2_filter=$this->input->post('s_serial_no2_filter');
				$aCriteria[]="(UPPER(ttp.s_serial_no) BETWEEN UPPER('$s_serial_no_filter') AND UPPER('$s_serial_no2_filter'))";
			}
			if ( $this->input->post('s_color_filter') ) {
				$s_color_filter=$this->input->post('s_color_filter');
				$aCriteria[]="ttp.s_color_name ILIKE '%$s_color_filter%'";
			}
			if ( $this->input->post('s_lot_no_filter') ) {
				$s_lot_no_filter=$this->input->post('s_lot_no_filter');
				$aCriteria[]="ttp.s_lot_no ILIKE '%$s_lot_no_filter%'";
			}
			if ( $this->input->post('d_production_date_month_filter') && $this->input->post('d_production_date_year_filter') ) {
				$d_production_date_month_filter=sprintf('%02d', $this->input->post('d_production_date_month_filter'));
				$d_production_date_year_filter=$this->input->post('d_production_date_year_filter');
				$d_production_date_year_month_filter=$d_production_date_year_filter."-".$d_production_date_month_filter."-01";
				$aCriteria[]="cast(ttp.d_production_date as date)>= cast('$d_production_date_year_month_filter' as date)";
			}
			if ( $this->input->post('d_production_date_month_filter2') && $this->input->post('d_production_date_year_filter2') ) {
				$d_production_date_month_filter=sprintf('%02d', $this->input->post('d_production_date_month_filter2'));
				$d_production_date_year_filter=$this->input->post('d_production_date_year_filter2');
				$d=cal_days_in_month(CAL_GREGORIAN,$d_production_date_month_filter,$d_production_date_year_filter);
				$d_production_date_year_month_filter=$d_production_date_year_filter."-".$d_production_date_month_filter."-".$d;
				$aCriteria[]="cast(ttp.d_production_date as date)<= cast('$d_production_date_year_month_filter' as date)";
			}
			if ( $this->input->post('s_po_no_filter') ) {
				$s_po_no_filter=$this->input->post('s_po_no_filter');
				$aCriteria[]="ttp.s_po_no ILIKE '%$s_po_no_filter%'";
			}
			if ( $this->input->post('s_po_filter') ) {
				$s_po_filter=$this->input->post('s_po_filter');
				$aCriteria[]="ttp.s_po ILIKE '%$s_po_filter%'";
			}
			if ( $this->input->post('s_buyer_filter') ) {
				$s_buyer_filter=$this->input->post('s_buyer_filter');
				$aCriteria[]="ttp.s_buyer = '$s_buyer_filter'";
			}
			if ( $this->input->post('s_model_filter') ) {
				$s_model_filter=$this->input->post('s_model_filter');
				$aCriteria[]="ttp.s_model_name ILIKE '%$s_model_filter%'";
			}

			if ( $this->input->post('s_location_filter') ) {
				$s_location_filter=$this->input->post('s_location_filter');
				$aCriteria[]="ttp.s_location ILIKE '%$s_location_filter%'";
			}
			if ( $this->input->post('s_status_filter') ) {
				$s_status_filter=$this->input->post('s_status_filter');
				if ($s_status_filter == 'export') {
					$aCriteria[]="ttp.n_process_14 > 0";
				} else {
					$aCriteria[]="ttp.n_process_14 = 0";
				}
			}
			if ( $this->input->post('s_color_filter') ) {
				$s_color_filter=$this->input->post('s_color_filter');
				$aCriteria[]="ttp.s_color_name ILIKE '%$s_color_filter%'";
			}
			foreach ($aSearchForm as $sField=>$sValue) $aSearchForm[$sField]=$this->input->post($sField);
			if ( !empty($aCriteria) ) {
				$this->session->set_userdata(array('serial_search' => $aCriteria));
				$this->session->set_userdata(array('serial_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if ( !empty($aSessionSort) ) $this->session->unset_userdata('serial_sort');
			if ( $this->input->post('sSort') && $this->input->post('sSortMethod') ) {
				$sSort=$this->input->post('sSort');
				$sSortMethod=$this->input->post('sSortMethod');
				if ( $this->input->post('bSortAction') ) {
					$sSortMethod=($this->input->post('sSortMethod')=='ASC' ? 'DESC' :'ASC');
					$aSearchForm['sSortMethod']=$sSortMethod;
				}
				$aSort=array($sSort => $sSortMethod);
			}
			if ( !empty($aSort) ) $this->session->set_userdata(array('serial_sort' => $aSort));
			
			/* -- Pagination -- */
			if ( !empty($aSessionPagination) ) $this->session->unset_userdata('serial_pagination');
			$aPagination['nOffset']=($this->input->post('nOffset') ? $this->input->post('nOffset') : 0 );
			$aPagination['nLimit']=($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage );
			if ( !empty($aPagination) ) $this->session->set_userdata(array('serial_pagination' => $aPagination));
		} else {
			/* -- Default View -- */
			if (empty($aSessionSearch['n_line_filter'])) {
				$sPartCriteria="ttp.n_line = $n_line_filter";
				$aCriteria[]=$sPartCriteria;
				$aSearch['n_line_filter']=$sPartCriteria;
				$aSearchForm['n_line_filter']=$n_line_filter;
			}
			if (empty($aSessionSearch['d_production_date_month_filter']) && empty($aSessionSearch['d_production_date_year_filter'])) {
				$d_production_date_month_filter=date('m'); $d_production_date_year_filter=date('Y');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$sPartCriteria="TO_CHAR(ttp.d_production_date, 'YYYYMM')>='$d_production_date_year_month_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['d_production_date_month_filter']=$sPartCriteria;
				$aSearch['d_production_date_year_filter']=$sPartCriteria;
				$aSearchForm['d_production_date_month_filter']=$d_production_date_month_filter;
				$aSearchForm['d_production_date_year_filter']=$d_production_date_year_filter;
			}
			if (empty($aSessionSearch['d_production_date_month_filter2']) && empty($aSessionSearch['d_production_date_year_filter2'])) {
				$d_production_date_month_filter=date('m'); $d_production_date_year_filter=date('Y');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$sPartCriteria="TO_CHAR(ttp.d_production_date, 'YYYYMM')<='$d_production_date_year_month_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['d_production_date_month_filter2']=$sPartCriteria;
				$aSearch['d_production_date_year_filter2']=$sPartCriteria;
				$aSearchForm['d_production_date_month_filter2']=$d_production_date_month_filter;
				$aSearchForm['d_production_date_year_filter2']=$d_production_date_year_filter;
			}
			/* -- Searching -- */
			if( !empty($aSessionSearch) ) {
				if ($sMessage!='') {
					$aCriteria=$aSessionSearch;
					$aSearchForm=$aSessionSearchForm;
				} else {
					$this->session->unset_userdata('serial_search');
					$this->session->unset_userdata('serial_search_form');
				}
			}
			/* -- Sorting -- */
			if( !empty($aSessionSort) ) {
				if ($sMessage!='') 
					$aSort=$aSessionSort;
				else 
					$this->session->unset_userdata('serial_sort');
			}
			
			/* -- Pagination -- */
			if( !empty($aSessionPagination) ) {
				if ($sMessage!='') 
					$aPagination=$aSessionPagination;
				else 
					$this->session->unset_userdata('serial_pagination');
			}
		}
		
		if ( !empty($aPagination) ) {
			$nOffset=$aPagination['nOffset'];
			$nLimit=$aPagination['nLimit'];
		}
		
		$sCriteria=implode(' AND ', $aCriteria);
		
		//set view report
		$aTotalData=array();
		$aTotalDatas=array();
		//declare variable temporary
		$n_t_process_1s=0; $n_t_process_2s=0; 
		$n_t_process_1=0; $n_t_process_2=0; $n_t_process_3=0; $n_t_process_4=0; $n_t_process_5=0; $n_t_process_6=0; 
		$n_t_process_1_2=0; $n_t_process_2_2=0; 
		$n_t_process_7=0; $n_t_process_8=0; $n_t_process_9=0; $n_t_process_10=0;
		$n_t_warehouse=0; $n_t_process_14=0; 
		if ($n_line_filter=='1') {
			$aAllDataProduct=$this->Report_model->getListSerial($sCriteria);
		} else {
			$aAllDataProduct=$this->Report_model->getListSerial2($sCriteria);
		}
		$nTotalRows=count($aAllDataProduct);
		
		foreach($aAllDataProduct as $nRow=>$aEachDataProduct){
			$n_t_process_1s+=$aEachDataProduct['n_process_1s'];
			$n_t_process_1+=$aEachDataProduct['n_process_1'];
			$n_t_process_1_2+=$aEachDataProduct['n_process_1_2b'];
			$n_t_process_2s+=$aEachDataProduct['n_process_2s'];
			$n_t_process_2+=$aEachDataProduct['n_process_2'];
			$n_t_process_2_2+=$aEachDataProduct['n_process_2_2b'];
			$n_t_process_3+=$aEachDataProduct['n_process_3'];
			$n_t_process_4+=$aEachDataProduct['n_process_4'];
			$n_t_process_5+=$aEachDataProduct['n_process_5'];
			$n_t_process_6+=$aEachDataProduct['n_process_6'];
			$n_t_process_7+=$aEachDataProduct['n_process_7'];
			$n_t_process_8+=$aEachDataProduct['n_process_8'];
			$n_t_process_9+=$aEachDataProduct['n_process_9'];
			$n_t_process_10+=$aEachDataProduct['n_process_10'];
			$n_t_warehouse+=$aEachDataProduct['n_warehouse'];
			$n_t_process_14+=$aEachDataProduct['n_process_14'];
		}
		$aTotalData['n_t_process_1s']=number_format($n_t_process_1s , 0, ',', '.'); 
		$aTotalData['n_t_process_1']=number_format($n_t_process_1 , 0, ',', '.'); 
		$aTotalData['n_t_process_1_2']=number_format($n_t_process_1_2 , 0, ',', '.');
		$aTotalData['n_t_process_2s']=number_format($n_t_process_2s , 0, ',', '.'); 
		$aTotalData['n_t_process_2']=number_format($n_t_process_2 , 0, ',', '.'); 
		$aTotalData['n_t_process_2_2']=number_format($n_t_process_2_2 , 0, ',', '.');
		$aTotalData['n_t_process_3']=number_format($n_t_process_3 , 0, ',', '.');
		$aTotalData['n_t_process_4']=number_format($n_t_process_4 , 0, ',', '.');
		$aTotalData['n_t_process_5']=number_format($n_t_process_5 , 0, ',', '.');
		$aTotalData['n_t_process_6']=number_format($n_t_process_6 , 0, ',', '.');
		$aTotalData['n_t_process_7']=number_format($n_t_process_7 , 0, ',', '.');
		$aTotalData['n_t_process_8']=number_format($n_t_process_8 , 0, ',', '.');
		$aTotalData['n_t_process_9']=number_format($n_t_process_9 , 0, ',', '.');
		$aTotalData['n_t_process_10']=number_format($n_t_process_10 , 0, ',', '.');
		$aTotalData['n_t_warehouse']=number_format($n_t_warehouse , 0, ',', '.');
		$aTotalData['n_t_process_14']=number_format($n_t_process_14 , 0, ',', '.');
		$aTotalDatas[]=$aTotalData;
				
		if (empty($sMessage)) {
			if ($n_line_filter=='1') {
				$aDataProduct=$this->Report_model->getListSerial($sCriteria, $nLimit, $nOffset, $aSort);
			} else {
				$aDataProduct=$this->Report_model->getListSerial2($sCriteria, $nLimit, $nOffset, $aSort);
			}
			$aDisplay=array('baseurl'			=> base_url(),
							'basesiteurl'		=> site_url(),
							'siteurl'			=> site_url().'/eg/reportlist/serial/',
							
							'MESSAGES'			=> '',
							'PAGE_TITLE'		=> 'SPMS-G. Electric Guitar/Serial No (Stock)',
							'filterCaption'		=> 'EG Report Filter/Search',
							
							'sGlobalUserName'	=> $this->sUsername,
							'sGlobalUserLevel' 	=> $this->sLevel,
							'nRowsPerPage'		=> $this->nRowsPerPage,
							'nTotalRows'		=> $nTotalRows,
							'nCurrOffset'		=> $nOffset,
							'tt_report_stock'	=> $aDataProduct,
							'tt_report_stock_total'	=> $aTotalDatas);
			
			$aDisplay = array_merge($aDisplay, $aSearchForm);
			$aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter']);
			$aDisplay['d_production_date_month_filter2'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter2']);
			$aDisplay['s_status_filter'] = $this->form->selectboxarray($this->config->item('status_export'), $aSearchForm['s_status_filter']);
			$aDisplay['s_buyer_filter'] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='EG' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aDisplay['s_buyer_filter']);
			$this->load->model('Product_model');
			$aDisplay['n_line_filter'] = $this->form->selectboxarray($this->config->item('product_line'), $aSearchForm['n_line_filter']);
			$aDisplay['s_location_filter'] = $this->form->selectboxarray($this->config->item('product_location'), 
				$aSearchForm['s_location_filter']);
			$aDisplay['viewFilter'] = $this->load->view('eg/report/serial_filter', $aDisplay, TRUE);
			
			$this->parser->parse('header', $aDisplay);
			$this->parser->parse('eg/report/serial', $aDisplay);
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
				's_location' => 'Location',
				'n_process_1s' => 'WK-I Center Input',
				'n_process_1' => 'WK-I Center Input [Bolt-Neck]',
				'n_process_1_2b' => 'WK-I Center Input [Bolt-Body]',
				'n_process_2s' => 'WK-I Center Output',
				'n_process_2' => 'WK-I Center Output [Bolt-Neck]',
				'n_process_2_2b' => 'WK-I Center Output [Bolt-Body]',
				'n_process_3' => 'WK-II',
				'n_process_4' => 'WK-II Control Center',
				'n_process_5' => 'Coating-I',
				'n_process_6' => 'Coating-IIA',
				'n_process_7' => 'Coating-IIB',
				'n_process_8' => 'Assembly-I_Control Center',
				'n_process_9' => 'Assembly-II',
				'n_process_10' => 'Packing',
				'n_warehouse' => 'Warehouse Incoming',
				'n_process_14' => 'Warehouse Outgoing');
			$aDatas=array();
			foreach($aAllDataProduct as $nRow=>$aEachDataProduct){
				$aData=array();
				foreach($aEachDataProduct as $sField=>$sValue) {
					if (isset($aHeader[$sField])) {
						$aData[$aHeader[$sField]]=$sValue;
					} /*else {
						$aData[$sField]=$sValue;
					}*/
				}
				$aDatas[]=$aData;
			}
			$aTotalDatas=array();
			if (count($aAllDataProduct)>0) {
				$nCounter=1; $nCounter2=1; $nCounters=1;
				foreach($aAllDataProduct[0] as $sField=>$sValue) {
					if (isset($aHeader[$sField])) {
						if (substr($sField,0,9)=='n_process' && substr($sField,-1)!='s' && substr($sField,-3)!='_2b') {
							$aTotalDatas[$sField] = $aTotalData['n_t_process_'.($nCounter >= 11 ? ($nCounter + 3) : $nCounter)];
							$nCounter++;
						} elseif (substr($sField,0,9)=='n_process' && substr($sField,-3)=='_2b') {
							$aTotalDatas[$sField] = $aTotalData['n_t_process_'.($nCounter2).'_2'];
							$nCounter2++;
						} elseif (substr($sField,0,9)=='d_process' && substr($sField,-1)=='s') {
							$aTotalDatas[$sField] = $aTotalData['n_t_process_'.($nCounters).'s'];
							$nCounters++;
						} elseif ($sField=='n_warehouse') {
							$aTotalDatas[$sField] = $aTotalData['n_t_warehouse'];
						} else {
							$aTotalDatas[$sField]=' ';
						}
					}
				}
			}
			$aDatas[]=$aTotalDatas;
			to_excel_array($aDatas, 'report_serial');
		}
	}
}
?>
