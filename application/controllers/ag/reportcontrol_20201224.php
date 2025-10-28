<?php

class Reportcontrol extends Controller {
	var $sUsername;
	var $sLevel;
	
	var $nRowsPerPage=50;
	
	var $_bModal=false;
	var $_sModalTarget='';

	function Reportcontrol(){
		parent::Controller();
		$this->load->library('session');	
		
		if (!$this->session->userdata('b_ag_report_read')) show_error('Access Denied');
		
		$this->sUsername=$this->session->userdata('s_username');
		$this->sLevel=$this->session->userdata('s_level');
		
		$this->load->model('ag/Reportcontrol_model');
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
						'siteurl'			=> site_url().'/ag/reportcontrol/',
						'MESSAGES'			=> '',
						'PAGE_TITLE'		=> 'SPMS-G. Accoustic Guitar/Report List',
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nRowsPerPage'		=> $this->nRowsPerPage,
						'nTotalRows'		=> $nTotalRows,
						'nCurrOffset'		=> $nOffset);
		
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse('ag/reportlist', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function color($sMessage=''){
		$sCriteria='';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata('reportcontrol_color_search');
		$aSearchForm=array(
			's_color_filter'			=> '',
			'd_production_date_month_filter'	=> '', 'd_production_date_year_filter'	=> '',
			'd_production_date_month_filter2'	=> '', 'd_production_date_year_filter2'=> '',
			's_po_no_filter'			=> '',
			's_po_filter'				=> '',
			's_buyer_filter'			=> '',
			's_model_filter'			=> '',
			's_location_filter'			=> '',
			'sSort'					=> 'd_production_date', 'sSortMethod'	=> 'ASC');
		$aSessionSearchForm = $this->session->userdata('reportcontrol_color_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('reportcontrol_color_sort');
		
		/* -- Pagination -- */
		$nOffset=0;
		$nLimit=$this->nRowsPerPage;
		$aPagination=array();
		$aSessionPagination = $this->session->userdata('reportcontrol_color_pagination');
		
		if( isset($_POST['nOffset']) ){
			/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata('reportcontrol_color_search');
				$this->session->unset_userdata('reportcontrol_color_search_form');
			}
			if ( $this->input->post('s_color_filter') ) {
				$s_color_filter=$this->input->post('s_color_filter');
				$aCriteria[]="ttp.s_color_name ILIKE '%$s_color_filter%'";
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
				$aCriteria[]="ttp.s_model_name ILIKE '%$s_model_filter%'";
			}
			if ( $this->input->post('s_location_filter') ) {
				$s_location_filter=$this->input->post('s_location_filter');
				$aCriteria[]="ttp.s_location ILIKE '%$s_location_filter%'";
			}
			foreach ($aSearchForm as $sField=>$sValue) $aSearchForm[$sField]=$this->input->post($sField);
			if ( !empty($aCriteria) ) {
				$this->session->set_userdata(array('reportcontrol_color_search' => $aCriteria));
				$this->session->set_userdata(array('reportcontrol_color_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if ( !empty($aSessionSort) ) $this->session->unset_userdata('reportcontrol_color_sort');
			if ( $this->input->post('sSort') && $this->input->post('sSortMethod') ) {
				$sSort=$this->input->post('sSort');
				$sSortMethod=$this->input->post('sSortMethod');
				if ( $this->input->post('bSortAction') ) {
					$sSortMethod=($this->input->post('sSortMethod')=='ASC' ? 'DESC' :'ASC');
					$aSearchForm['sSortMethod']=$sSortMethod;
				}
				$aSort=array($sSort => $sSortMethod);
			}
			if ( !empty($aSort) ) $this->session->set_userdata(array('reportcontrol_color_sort' => $aSort));
			
			/* -- Pagination -- */
			if ( !empty($aSessionPagination) ) $this->session->unset_userdata('reportcontrol_color_pagination');
			$aPagination['nOffset']=($this->input->post('nOffset') ? $this->input->post('nOffset') : 0 );
			$aPagination['nLimit']=($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage );
			if ( !empty($aPagination) ) $this->session->set_userdata(array('reportcontrol_color_pagination' => $aPagination));
		} else {
			/* -- Default View -- */
			if (empty($aSessionSearch['d_production_date_month_filter']) && empty($aSessionSearch['d_production_date_year_filter'])) {
				$d_production_date_month_filter=date('m'); $d_production_date_year_filter=date('Y');
				$d_production_date_year_month_filter=$d_production_date_year_filter.$d_production_date_month_filter;
				$sPartCriteria="TO_CHAR(ttp.d_production_date, 'YYYYMM')>='$d_production_date_year_month_filter'";
				//$sPartCriteria="EXTRACT(MONTH FROM ttp.d_production_date)='$d_production_date_month_filter' AND EXTRACT(YEAR FROM ttp.d_production_date)='$d_production_date_year_filter'";
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
					$this->session->unset_userdata('reportcontrol_color_search');
					$this->session->unset_userdata('reportcontrol_color_search_form');
				}
			}
			/* -- Sorting -- */
			if( !empty($aSessionSort) ) {
				if ($sMessage!='') 
					$aSort=$aSessionSort;
				else 
					$this->session->unset_userdata('reportcontrol_color_sort');
			}
			/* -- Pagination -- */
			if( !empty($aSessionPagination) ) {
				if ($sMessage!='') 
					$aPagination=$aSessionPagination;
				else 
					$this->session->unset_userdata('reportcontrol_color_pagination');
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
		$n_t_qty=0;
		$aAllDataProduct=$this->Reportcontrol_model->getListColor($sCriteria, 0, 0, $aSort);
		$nTotalRows=count($aAllDataProduct);
		
		foreach($aAllDataProduct as $nRow=>$aEachDataProduct){
			$n_t_qty+=$aEachDataProduct['n_qty'];
		}
		$aTotalData['n_t_qty']=$n_t_qty;
		$aTotalDatas[]=$aTotalData;
				
		if (empty($sMessage)) {
			$aDataProduct=$this->Reportcontrol_model->getListColor($sCriteria, $nLimit, $nOffset, $aSort);
			$aDisplay=array('baseurl'			=> base_url(),
							'basesiteurl'		=> site_url(),
							'siteurl'			=> site_url().'/ag/reportcontrol/color/',
							
							'MESSAGES'			=> '',
							'PAGE_TITLE'		=> 'SPMS-G. Accoustic Guitar/Time Lead Control by Color',
							'filterCaption'		=> 'AG Report Filter/Search',
							
							'sGlobalUserName'	=> $this->sUsername,
							'sGlobalUserLevel' 	=> $this->sLevel,
							
							'nTotalRows'		=> $nTotalRows,
							'nRowsPerPage'		=> $this->nRowsPerPage,
							'nCurrOffset'		=> $nOffset,
							
							'tt_report'		=> $aDataProduct,
							'tt_report_total'	=> $aTotalDatas);
			
			$aDisplay = array_merge($aDisplay, $aSearchForm);
			$aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter']);
			$aDisplay['d_production_date_month_filter2'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter2']);
			$aDisplay['s_buyer_filter'] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='AG' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aDisplay['s_buyer_filter']);

			$aDisplay['s_location_filter'] = $this->form->selectboxarray($this->config->item('product_location'), 
			$aSearchForm['s_location_filter']);

			$aDisplay['viewFilter'] = $this->load->view('ag/reportcontrol/color_filter', $aDisplay, TRUE);
			
			$this->parser->parse('header', $aDisplay);
			$this->parser->parse('ag/reportcontrol/color', $aDisplay);
			$this->parser->parse('footer', $aDisplay);
		} else {
			$this->load->helper('excel');
			$aHeader = array(
				'd_production_date' => 'Production Date',
				'd_plan_date' => 'Production Plan Date (Input)',
				'd_delivery_date' => 'Production Plan Date (Output)',
				'd_target_date' => 'Export Plan Date',
				's_buyer_name' => 'Buyer',
				's_po_no' => 'PI Number',
				's_po' => 'PO',
				's_model' => 'Model',
				's_model_name' => 'Model Name',
				's_color_name' => 'Color',
				's_smodel' => 'Item Code',
				's_location' => 'Location',
				's_type_difficult' => 'Difficult',
				'n_line' => 'Line',
				'n_qty' => 'Qty',
				'd_process_1_plan' => 'Plan WK Center Input',
				'd_process_1' => 'Actual WK Center Input',
				's_process_1_location' => 'Location WK Center Input',
				'd_process_2_plan' => 'Plan WK Center Output',
				'd_process_2' => 'Actual WK Center Output',
				's_process_2_location' => 'Location WK Center Output',
				'd_process_3_plan' => 'Plan Wood Working',
				'd_process_3' => 'Actual Wood Working',
				's_process_3_location' => 'Location Wood Working',
				'd_process_4_plan' => 'Plan Coating-I - Neck',
				'd_process_4' => 'Actual Coating-I - Neck',
				's_process_4_location' => 'Location Coating-I - Neck',
				'd_process_5_plan' => 'Plan Sanding',
				'd_process_5' => 'Actual Sanding',
				's_process_5_location' => 'Location Sanding',
				'd_process_6_plan' => 'Plan Coating-IIA',
				'd_process_6' => 'Actual Coating-IIA',
				's_process_6_location' => 'Location Coating-IIA',
				'd_process_7_plan' => 'Plan Coating-IIB',
				'd_process_7' => 'Actual Coating-IIB',
				's_process_7_location' => 'Location Coating-IIB',
				'd_process_8_plan' => 'Plan Assembly-I_Control Center',
				'd_process_8' => 'Actual Assembly-I_Control Center',
				's_process_8_location' => 'Location Assembly-I_Control Center',
				'd_process_9_plan' => 'Plan Assembly-II',
				'd_process_9' => 'Actual Assembly-II',
				's_process_9_location' => 'Location Assembly-II',
				'd_process_10_plan' => 'Plan Packing',
				'd_process_10' => 'Actual Packing',
				's_process_10_location' => 'Location Packing',
				'd_process_14_plan' => 'Plan Warehouse Outgoing',
				'd_process_14' => 'Actual Warehouse Outgoing',
				's_process_14_location' => 'Location Warehouse Outgoing');
			
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
						if ($sField=='n_qty') {
							$aTotalDatas[$sField] = $aTotalData['n_t_qty'];
							$nCounter++;
						} else {
							$aTotalDatas[$sField]=' ';
						}
					}
				}
			}
			$aDatas[]=$aTotalDatas;
			to_excel_array($aDatas, 'report_lead_time_process_by_color');
		}
	}
}
