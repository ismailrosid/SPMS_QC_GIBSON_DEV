<?php

class Product extends Controller {
	var $sUsername;
	var $sLevel;
	
	var $nRowsPerPage=50;
	
	var $_bModal=false;
	var $_sModalTarget='';
	
	var $rules=array();
	var $fields=array();
	
	var $sErrorMessage='';
	var $aDefaultForm=array();
	
	var $aContainer=array();
	
	var $sDivision='';
	var $aDivision = array();
	
	function Product(){
		parent::Controller();
		$this->load->library('session');	
		
		$this->sUsername=$this->session->userdata('s_username');
		$this->sLevel=$this->session->userdata('s_level');
		
		$this->load->model('Product_model');
		$this->load->model('Order_model');
		$this->load->model('Util_model');
		
		$this->load->helper('url');
		$this->load->library('form');
		
		$this->load->library('parser');
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="message_error">', '</div>');
		$this->load->library('validatejs');
		
		foreach($this->Product_model->aContainer as $sKey=>$aProperties){
			if($aProperties['edit']==1){
				$this->rules[$sKey]=$aProperties['rules'];
				$this->fields[$sKey]=$aProperties['caption'];
			}
		}
		
		$this->aDivision = $this->config->item('division');
	}
	
	function index($sDivision, $sMessage=''){
		if (!$this->session->userdata('b_ag_order_read') && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_order_read') && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$sCriteria='';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata('product_search');
		$aSearchForm=array(
			's_serial_no_filter'			=> '', 's_serial_no2_filter'			=> '', 
			's_lot_no_filter'				=> '', 
			's_color_filter'				=> '', 
			'd_order_date_month_filter'		=> '', 'd_order_date_year_filter'		=> '',
			's_po_no_filter'				=> '',
			's_po_filter'					=> '',
			's_buyer_filter'				=> '',
			's_model_filter'				=> '',
			's_smodel_filter'				=> '',
			's_location_filter'				=> '',
			's_type_filter'					=> '',
			'n_line_filter'					=> '',
			'd_production_date_month_filter'=> '', 'd_production_date_year_filter'	=> '',
			'sSort'							=> 'ttpo.d_createtime', 'sSortMethod'	=> 'DESC');
		$aSessionSearchForm = $this->session->userdata('product_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod'], 'n_serial_no'=>'ASC');
		$aSessionSort = $this->session->userdata('product_sort');
		
		/* -- Pagination -- */
		$nOffset=0;
		$nLimit=$this->nRowsPerPage;
		$aPagination=array();
		$aSessionPagination = $this->session->userdata('product_pagination');
		
		if( isset($_POST['nOffset']) ){
			/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata('product_search');
				$this->session->unset_userdata('product_search_form');
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
			if ( $this->input->post('s_smodel_filter') ) {
				$s_smodel_filter=$this->input->post('s_smodel_filter');
				$aCriteria[]="tmm.s_smodel ILIKE '%$s_smodel_filter%'";
			}
			if ( $this->input->post('d_production_date_month_filter') ) {
				$d_production_date_month_filter=$this->input->post('d_production_date_month_filter');
				$aCriteria[]="EXTRACT(MONTH FROM ttp.d_production_date)='$d_production_date_month_filter'";
			}
			if ( $this->input->post('d_production_date_year_filter') ) {
				$d_production_date_year_filter=$this->input->post('d_production_date_year_filter');
				$aCriteria[]="EXTRACT(YEAR FROM ttp.d_production_date)='$d_production_date_year_filter'";
			}
			if ( $this->input->post('s_location_filter') ) {
				$s_location_filter=$this->input->post('s_location_filter');
				$aCriteria[]="ttp.s_location = '$s_location_filter'";
			}
			if ( $this->input->post('s_type_filter') ) {
				$s_type_filter=$this->input->post('s_type_filter');
				$aCriteria[]="ttp.s_type = '$s_type_filter'";
			}
			if ( $this->input->post('n_line_filter') ) {
				$n_line_filter=$this->input->post('n_line_filter');
				$aCriteria[]="ttp.n_line = $n_line_filter";
			}
			foreach ($aSearchForm as $sField=>$sValue) $aSearchForm[$sField]=$this->input->post($sField);
			if ( !empty($aCriteria) ) {
				$this->session->set_userdata(array('product_search' => $aCriteria));
				$this->session->set_userdata(array('product_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if ( !empty($aSessionSort) ) $this->session->unset_userdata('product_sort');
			if ( $this->input->post('sSort') && $this->input->post('sSortMethod') ) {
				$sSort=$this->input->post('sSort');
				$sSortMethod=$this->input->post('sSortMethod');
				if ( $this->input->post('bSortAction') ) {
					$sSortMethod=($this->input->post('sSortMethod')=='ASC' ? 'DESC' :'ASC');
					$aSearchForm['sSortMethod']=$sSortMethod;
				}
				$aSort=array($sSort => $sSortMethod);
			}
			if ( !empty($aSort) ) $this->session->set_userdata(array('product_sort' => $aSort));
			
			/* -- Pagination -- */
			if ( !empty($aSessionPagination) ) $this->session->unset_userdata('product_pagination');
			$aPagination['nOffset']=($this->input->post('nOffset') ? $this->input->post('nOffset') : 0 );
			$aPagination['nLimit']=($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage );
			if ( !empty($aPagination) ) $this->session->set_userdata(array('product_pagination' => $aPagination));
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
					$this->session->unset_userdata('product_search');
					$this->session->unset_userdata('product_search_form');
				}
			}
			/* -- Sorting -- */
			if( !empty($aSessionSort) ) {
				if ($sMessage!='') 
					$aSort=$aSessionSort;
				else 
					$this->session->unset_userdata('product_sort');
			}
			
			/* -- Pagination -- */
			if( !empty($aSessionPagination) ) {
				if ($sMessage!='') 
					$aPagination=$aSessionPagination;
				else 
					$this->session->unset_userdata('product_pagination');
			}
		}
		
		if ( !empty($aPagination) ) {
			$nOffset=$aPagination['nOffset'];
			$nLimit=$aPagination['nLimit'];
		}
		$aCriteria[]="ttp.s_division='".strtoupper($sDivision)."'";
		$sCriteria=implode(' AND ', $aCriteria);
		
		$aDataProduct=$this->Product_model->getList($sCriteria, $nLimit, $nOffset, $aSort);
		$nTotalRows=$this->Product_model->getListCount($sCriteria);
		$sMessages='';
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/production/product/',
						'sDivision'			=> $sDivision,
						
						'MESSAGES'			=> '',
						'PAGE_TITLE'		=> 'SPMS-G. '.$this->aDivision[strtoupper($sDivision)].'/Product',
						'toolCaption'		=> 'Production Tool',
						'filterCaption'		=> 'Production Filter/Search',
						
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nRowsPerPage'		=> $this->nRowsPerPage,
						'nTotalRows'		=> $nTotalRows,
						'nCurrOffset'		=> $nOffset,
						
						'tt_prod_product'		=> $aDataProduct );
		$aDisplay = array_merge($aDisplay, $aSearchForm);
		$aDisplay['d_order_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_order_date_month_filter']);
		$aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter']);
		$aDisplay['s_location_filter'] = $this->form->selectboxarray($this->config->item('product_location'), $aSearchForm['s_location_filter']);
		$aDisplay['s_type_filter'] = $this->form->selectboxarray($this->config->item('production_process'), $aSearchForm['s_type_filter']);
		$aDisplay['s_buyer_filter'] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='".strtoupper($sDivision)."' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aDisplay['s_buyer_filter']);
		$aDisplay['n_line_filter'] = $this->form->selectboxarray($this->config->item('product_line'), $aSearchForm['n_line_filter']);
		
		$aDisplay['viewFilter'] = $this->load->view($sDivision.'/product/list_filter', $aDisplay, TRUE);
		$aDisplay['viewToolbar'] = $this->load->view($sDivision.'/product/list_toolbar', $aDisplay, TRUE);
		
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse($sDivision.'/product/list', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function viewedit($sDivision, $sSerialNo='', $uIdPo=''){
		$aEditDefault=array();
		$sSerialNo=str_replace('&#40;','(',$sSerialNo);
		$sSerialNo=str_replace('&#41;',')',$sSerialNo);
		// set default data user
		$aEditable=array();
		foreach ($this->Product_model->aContainer as $sField=>$aProperties) {
			$aEditDefault[$sField]=(isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : '');
		}
		if(trim($sSerialNo!='') && $sSerialNo!='0'){
			// edit mode
			$aProduct=$this->Product_model->getList("ttp.s_serial_no='$sSerialNo' AND ttp.s_division='".strtoupper($sDivision)."'");
			if (count($aProduct) > 0) {
				foreach ($this->Product_model->aContainer as $sField=>$aProperties) {
					$aEditDefault[$sField]=(isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : $aProduct[0][$sField]);
				}
			} 
		} elseif ( (trim($sSerialNo=='') || $sSerialNo=='0') && $uIdPo!='') {
			// new mode
			$aProduct=$this->Order_model->getList("ttpo.u_id='$uIdPo'");
			if (count($aProduct) > 0) {
				foreach ($this->Product_model->aContainer as $sField=>$aProperties) {
					if ( isset($aProduct[0][$sField]) ) {
						$aEditDefault[$sField]=(isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : $aProduct[0][$sField]);
					}
				}
			} 
		}
		$aDataEditable=array();
		foreach($this->Product_model->aContainer as $sKey=>$aProperties){
			if ($sKey=='s_model') {
				$aDataEditable[$sKey] = $this->form->selectbox('tm_model', 's_code, s_code || \' - \' || s_description AS s_description, s_description AS s_names', "s_division='".strtoupper($sDivision)."' OR s_division IS NULL ORDER BY s_names ASC", 's_code', 's_description', $aEditDefault[$sKey]);
			} elseif ($sKey=='s_color') {
				$aDataEditable[$sKey] = $this->form->selectbox('tm_color', 's_code, s_code || \' - \' || s_description AS s_description, s_description AS s_names', "s_division='".strtoupper($sDivision)."' OR s_division IS NULL ORDER BY s_names ASC", 's_code', 's_description', $aEditDefault[$sKey]);
			} elseif ($sKey=='s_buyer') {
				$aDataEditable[$sKey] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='".strtoupper($sDivision)."' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aEditDefault[$sKey]);
			} elseif (substr($sKey, -8)=='location') {
				$aDataEditable[$sKey] = $this->form->selectboxarray($this->config->item('product_location'), $aEditDefault[$sKey]);
			//} elseif ($sKey=='s_type') {
			//	$aDataEditable[$sKey] = $this->form->selectboxarray($this->config->item('production_process'), $aEditDefault[$sKey]);
			} else {
				$aDataEditable[$sKey]=$aEditDefault[$sKey];
				
				/* -- Displaying Default Serial No -- */
				if ($sKey=='s_serial_no' && !empty($aEditDefault['d_production_date']) && !empty($aEditDefault['s_buyer']) && $uIdPo!='' ) {
					$aSerialNo=$this->Util_model->getSerialNo(strtoupper($sDivision), $aEditDefault['d_production_date'], $aEditDefault['s_buyer']);
					$nCount=$aSerialNo['count'];
					$sSerialNoParse=$aSerialNo['serial_no'];
					$nDigit=$aSerialNo['digit'];
					$sSerialNoDefault=str_replace('{number}', sprintf('%0'.$nDigit.'d',$nCount), $sSerialNoParse);
					$aDataEditable['n_serial_no']=$nCount;
					if (!empty($aEditDefault['s_serial_no'])) {
						$aDataEditable['s_serial_no']=$sSerialNoDefault;
					}
				}
			}
		}
		$aEditable[]=$aDataEditable;
		
		$error=$this->validatejs->setValidate($this->Product_model->aContainer);
		$validate_js=$this->validatejs->execValidateJS('frmEdit');
		
		$aDisplay=array('formaction'		=> site_url().'/production/product/'.(trim($sSerialNo)=='' || $sSerialNo=='0' ? 'add/'.$sDivision.'/'.$uIdPo : 'edit/'.$sDivision.'/'.$sSerialNo),
						'editable'			=> $aEditable,
						'operation'			=> (trim($sSerialNo)=='' || $sSerialNo=='0' ? 'add' : 'edit'),
						'VALIDATE_JS'		=> $validate_js,
						'baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/production/product/',
						'sDivision'			=> $sDivision,
						
						'MESSAGES'			=> $this->sErrorMessage,
						'PAGE_TITLE'		=> 'SPMS-G. '.$this->aDivision[strtoupper($sDivision)].'/Product',
						'toolCaption'		=> 'Production Tool',
						
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel );
		
		$aDisplay['viewToolbar'] = $this->load->view($sDivision.'/product/add'.(isset($aProduct[0]['n_line']) && $aProduct[0]['n_line']!=1 ? $aProduct[0]['n_line'] : '').'_toolbar', $aDisplay, TRUE);
		
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse($sDivision.'/product/add'.(isset($aProduct[0]['n_line']) && $aProduct[0]['n_line']!=1 ? $aProduct[0]['n_line'] : ''), $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function add($sDivision, $uIdPo='') {
		if (!$this->session->userdata('b_ag_order_write') && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_order_write') && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$sMessages=0;
		$this->sDivision=$sDivision;
		
		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE){
			foreach($this->Product_model->aContainer as $sKey=>$aProperties){
				if($aProperties['edit']==1) {
					$this->aDefaultForm[$sKey]=$this->input->post($sKey);
				}
			}
			$this->sErrorMessage=$this->validation->error_string;
			$this->viewedit($sDivision, '0', $uIdPo);
		}else{
			/* -- Check for accepted user to update sales warehouse -- */
			$prod_phases = array();
			$this->db->select('s_field_process, s_phase, s_description, n_order');
			$this->db->where('s_division', strtoupper($sDivision));
			$this->db->where('n_line', 1);
			$this->db->where('s_field_process <>', 'd_warehouse');
			if ($this->session->userdata('b_'.strtolower($sDivision).'_sales_batch') == FALSE)
			{
				$this->db->where('s_field_process <>', 'd_process_14');
			}
			$this->db->order_by('n_order', 'ASC');
			$this->db->order_by('s_phase', 'ASC');
			$this->db->group_by(array('s_field_process', 's_phase', 's_description', 'n_order'));
			$tm_prod_setup = $this->db->get('tm_prod_setup');
			$prod_setups = $tm_prod_setup->result();
			foreach ($prod_setups as $prod_setup)
			{
				$prod_phases[$prod_setup->s_field_process] = $prod_setup->s_phase;
			}
			
			$is_found_error = FALSE;
			if (!isset($prod_phases['d_process_14']) && !empty($this->validation->d_process_14))
			{
				$is_found_error = TRUE;
				$this->sErrorMessage = "Access denied for update export date. ";
			}
			
			if ($is_found_error == FALSE)
			{
				$process_counter = 1;
				foreach($this->Product_model->aContainer as $sKey=>$aProperties){
					if ($aProperties['edit']==1) {
						$aData[$sKey]=$this->validation->$sKey;
						if (substr($sKey,0,10)=='d_process_') 
						{
							if ($process_counter > 9)
								$process_length = 10;
							else
								$process_length = 9;
							$aData['s_'.(substr($sKey, 2, $process_length)).'_update_by']=$this->sUsername;
							$process_counter++;
						}
					}
				}
				$aData['u_id_po_no']=$uIdPo;
				$aData['s_division']=strtoupper($sDivision);
				$aData['s_update_by']=$this->sUsername;
				$sSerialNo = $this->Product_model->insert($aData, strtoupper($sDivision));
				if ($sSerialNo === FALSE) {
					$sMessages=0;
				} else {
					$sMessages=1;
				}
				
				redirect("production/order/index/$sDivision/$sMessages");
			}
			else
			{
				foreach($this->Product_model->aContainer as $sKey=>$aProperties){
					if($aProperties['edit']==1) {
						$this->aDefaultForm[$sKey]=$this->input->post($sKey);
					}
				}
				$this->viewedit($sDivision, '0', $uIdPo);
			}
		}
	}
	
	function edit($sDivision, $sSerialNo) {
		if (!$this->session->userdata('b_ag_order_write') && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_order_write') && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$sSerialNo=str_replace('&#40;','(',$sSerialNo);
		$sSerialNo=str_replace('&#41;',')',$sSerialNo);
		
		$sMessages=0;
		$this->sDivision=$sDivision;
		
		$this->rules=array();
		$this->fields=array();
		foreach($this->Product_model->aContainer as $sKey=>$aProperties){
			if($aProperties['edit']==1){
				if ($sKey=='s_serial_no') {
					$this->rules[$sKey]='trim|callback_serialexist_check|required';
				} else {
					$this->rules[$sKey]=$aProperties['rules'];
				}
				$this->fields[$sKey]=$aProperties['caption'];
			}
		}
		
		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE){
			foreach($this->Product_model->aContainer as $sKey=>$aProperties){
				if($aProperties['edit']==1) {
					$this->aDefaultForm[$sKey]=$this->input->post($sKey);
				}
			}
			$this->sErrorMessage=$this->validation->error_string;
			$this->viewedit($sDivision, $sSerialNo);
		}else{
			$prod_phases = array();
			$this->db->select('s_field_process, s_phase, s_description, n_order');
			$this->db->where('s_division', strtoupper($sDivision));
			$this->db->where('n_line', 1);
			$this->db->where('s_field_process <>', 'd_warehouse');
			if ($this->session->userdata('b_'.strtolower($sDivision).'_sales_batch') == FALSE)
			{
				$this->db->where('s_field_process <>', 'd_process_14');
			}
			$this->db->order_by('n_order', 'ASC');
			$this->db->order_by('s_phase', 'ASC');
			$this->db->group_by(array('s_field_process', 's_phase', 's_description', 'n_order'));
			$tm_prod_setup = $this->db->get('tm_prod_setup');
			$prod_setups = $tm_prod_setup->result();
			foreach ($prod_setups as $prod_setup)
			{
				$prod_phases[$prod_setup->s_field_process] = $prod_setup->s_phase;
			}
			
			/* -- Check for accepted user to replacing production date -- */
			$is_found_error = FALSE;
			
			$tt_production_data = NULL;
			$is_production_exists = FALSE;
			$this->db->where('s_serial_no', strtoupper($sSerialNo));
			$tt_production = $this->db->get('tt_production');
			if ($tt_production->num_rows() > 0)
			{
				$tt_production_data = $tt_production->first_row();
				
				if (!isset($prod_phases['d_process_14']) && $this->validation->d_process_14 != $tt_production_data->d_process_14)
				{
					$is_found_error = TRUE;
					$this->sErrorMessage .= "Access denied for update export date. ";
				}
				
				if ($is_found_error == FALSE)
				{
					foreach($this->Product_model->aContainer as $sKey=>$aProperties)
					{
						if (substr($sKey,0,10) == 'd_process_')
						{
							if ($this->validation->$sKey != $tt_production_data->$sKey)
								$is_production_exists = TRUE;
							
							if ($this->session->userdata('b_replace_production') == FALSE && $is_production_exists == TRUE)
							{
								$is_found_error = TRUE;
								$this->sErrorMessage .= $sSerialNo." production date has already exist, you can't update. ";
								break;
							}
						}
					}
				}
			}
			else
			{
				$this->sErrorMessage .= $sSerialNo.' is not found. ';
				$is_found_error = TRUE;
			}
			
			if ($is_found_error == FALSE)
			{
				$process_counter = 1;
				foreach($this->Product_model->aContainer as $sKey=>$aProperties){
					if ($aProperties['edit']==1) {
						$aData[$sKey]=$this->validation->$sKey;
						if (substr($sKey,0,10)=='d_process_' && substr($sKey,-4)=='plan') 
						{
							if ($process_counter > 9)
								$process_length = 10;
							else
								$process_length = 9;
							$aData['s_'.(substr($sKey, 2, $process_length)).'_update_by']=$this->sUsername;
							$process_counter++;
						}
					}
				}
				$aData['s_division']=strtoupper($sDivision);
				$aData['s_update_by']=$this->sUsername;
				
				$bValid=($this->input->post('bValidation') ? TRUE : FALSE);
				$sSerial = $sSerialNo;
				$sSerialNo = $this->Product_model->update($sSerialNo, $aData, strtoupper($sDivision), '', $bValid);
				if ($sSerialNo===FALSE) {
					$sMessages=0;
				} else {
					$sMessages=1;
				}
				redirect("production/product/index/$sDivision/$sMessages/$sSerial");
			}
			else
			{
				foreach($this->Product_model->aContainer as $sKey=>$aProperties){
					if($aProperties['edit']==1) {
						$this->aDefaultForm[$sKey]=$this->input->post($sKey);
					}
				}
				$this->viewedit($sDivision, $sSerialNo);
			}
		}
	}
	
	function delete($sDivision, $sSerialNo=''){
		if (!$this->session->userdata('b_ag_order_write') && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_order_write') && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$rDelete=TRUE;
		if (isset($_POST['uIdRow'])) {
			foreach ($_POST['uIdRow'] as $uIdRow) {
				$rDelete=$this->Product_model->delete($uIdRow, strtoupper($sDivision));
				if ($rDelete===FALSE) break;
			}
		} else {
			$sSerialNo=str_replace('&#40;','(',$sSerialNo);
			$sSerialNo=str_replace('&#41;',')',$sSerialNo);
			if ($sSerialNo!='') $rDelete=$this->Product_model->delete($sSerialNo, strtoupper($sDivision));
		}
		
		if ($rDelete===FALSE) {
			$sMessages=0;
		} else {
			$sMessages=1;
		}
		
		$this->load->helper('url');
		redirect("production/product/index/$sDivision/$sMessages");
	}
	
	function excel($sDivision) {
		if (!$this->session->userdata('b_ag_order_read') && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_order_read') && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$this->load->helper('excel');
		$aSessionSearch = $this->session->userdata('product_search');
		$aSessionSort = $this->session->userdata('product_sort');
		
		/* -- Default View -- */
		if( empty($aSessionSearch) ) {
			$d_production_date_month_filter=date('m');
			$sPartCriteria="EXTRACT(MONTH FROM ttp.d_production_date)='$d_production_date_month_filter'";
			$aSessionSearch[]=$sPartCriteria;

			$d_production_date_year_filter=date('Y');
			$sPartCriteria="EXTRACT(YEAR FROM ttp.d_production_date)='$d_production_date_year_filter'";
			$aSessionSearch[]=$sPartCriteria;
		}
		
		$sSearch=(!empty($aSessionSearch) ? implode(' AND ', $aSessionSearch).' AND ' : '')." ttp.s_division='".strtoupper($sDivision)."' ";
		
		$aDataProduct=$this->Product_model->getList($sSearch, 0, 0, $aSessionSort);
		$aHeader=array(
			's_serial_no' => 'Serial No',
			's_po_no' => 'PI Number',
			's_po' => 'PO',
			'd_order_date' => 'Receive Order',
			'd_production_date' => 'Production Date',
			// 'd_plan_date' => 'Production Plan Date (Input)',
			'd_delivery_date' => 'Production Plan Date (Output)',
			'd_target_date' => 'Export Plan Date',
			's_lot_no' => 'Lot Number',
			's_buyer_name' => 'Buyer',
			's_brand' => 'Brand',
			's_bench' => 'Bench Mark',
			's_model' => 'Model',
			's_model_name' => 'Model Name',
			's_color_name' => 'Color',
			's_smodel' => 'Item Code',
			's_location' => 'Loc'
		);
		$aDatas=array();
		foreach($aDataProduct as $nRow=>$aRecordProduct){
			$aData=array();
			foreach($aRecordProduct as $sField=>$sValue) {
				if (isset($aHeader[$sField])) {
					$aData[$aHeader[$sField]]=$sValue;
				/*} else {
					$aData[$sField]=$sValue;*/
				}
			}
			$aDatas[]=$aData;
		}
		$aDatas[]=array(
			's_serial_no' => ' ',
			's_po_no' => ' ',
			's_po' => ' ',
			'd_order_date' => ' ',
			'd_production_date' => ' ',
			// 'd_plan_date' => ' ',
			'd_delivery_date' => ' ',
			'd_target_date' => ' ',
			's_lot_no' => ' ',
			's_buyer_name' => ' ',
			's_brand' => ' ',
			's_bench' => ' ',
			's_model' => ' ',
			's_model_name' => ' ',
			's_color_name' => ' ',
			's_smodel' => ' ',
			's_location' => count($aDataProduct)
		);
		to_excel_array($aDatas, 'productlist');
	}
	
	/* -- Check Validation -- */
	function serial_check($sSerialNo) {
		$aTt_product= $this->Product_model->getList("ttp.s_serial_no='$sSerialNo' AND ttp.s_division='".strtoupper($this->sDivision)."'");
		if (count($aTt_product)) {
			$this->validation->set_message('serial_check', "The data of '$sSerialNo' on %s field is already exists");
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	function serialexist_check($sSerialNo) {
		$aTt_product= $this->Product_model->getList("ttp.s_serial_no='$sSerialNo' AND ttp.s_division='".strtoupper($this->sDivision)."'");
		if (count($aTt_product)) {
			return TRUE;
		} else {
			$this->validation->set_message('serialexist_check', "The data of '$sSerialNo' on %s field is not exists");
			return FALSE;
		}
	}
	
	function serial2_check($sSerialNo) {
		$aTt_product= $this->Product_model->getList("ttp.s_serial_no='$sSerialNo' AND ttp.n_line=2 AND ttp.s_division='".strtoupper($this->sDivision)."'");
		if (count($aTt_product)) {
			return TRUE;
		} else {
			$this->validation->set_message('serial2_check', "The data of '$sSerialNo' on %s body field is not exists");
			return FALSE;
		}
	}
	
	function buyer_check($sCode) {
		$this->load->model('Buyer_model');
		$aTm_customer = $this->Buyer_model->getList("s_code='$sCode' AND (s_division='".strtoupper($this->sDivision)."' OR s_division IS NULL)");
		if (count($aTm_customer)) {
			return TRUE;
		} else {
			$this->validation->set_message('buyer_check', "The data of '$sCode' on %s field is not found");
			return FALSE;
		}
	}
	
	function model_check($sCode) {
		$this->load->model('Model_model');
		$aTm_model = $this->Model_model->getList("s_code='$sCode' AND (s_division='".strtoupper($this->sDivision)."' OR s_division IS NULL)");
		if (count($aTm_model)) {
			return TRUE;
		} else {
			$this->validation->set_message('model_check', "The data of '$sCode' on %s field is not found");
			return FALSE;
		}
	}
	
	function color_check($sCode) {
		$this->load->model('Color_model');
		$aTm_color = $this->Color_model->getList("s_code='$sCode' AND (s_division='".strtoupper($this->sDivision)."' OR s_division IS NULL)");
		if (count($aTm_color)) {
			return TRUE;
		} else {
			$this->validation->set_message('color_check', "The data of '$sCode' on %s field is not found");
			return FALSE;
		}
	}
}
?>
