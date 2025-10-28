<?php

class Order extends Controller {
	var $sUsername;
	var $sLevel;
	
	var $nRowsPerPage=0;
	
	var $_bModal=false;
	var $_sModalTarget='';
	
	var $rules=array();
	var $fields=array();
	
	var $sErrorMessage='';
	var $aDefaultForm=array();
	
	var $aContainer=array();
	var $aDivision = array();
	
	function Order(){
		parent::Controller();
		$this->load->library('session');	
		
		$this->sUsername=$this->session->userdata('s_username');
		$this->sLevel=$this->session->userdata('s_level');
		
		$this->load->model('Order_model');
		$this->load->model('Util_model');
		
		$this->load->helper('url');
		$this->load->library('form');
		
		$this->load->library('parser');
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="message_error">', '</div>');
		
		$this->load->library('validatejs');
		
		foreach($this->Order_model->aContainer as $sKey=>$aProperties){
			$this->rules[$sKey]=$aProperties['rules'];
			$this->fields[$sKey]=$aProperties['caption'];
		}
		
		$this->aDivision = $this->config->item('division');
	}
	
	function index($sDivision, $sMessage=''){
		if (!$this->session->userdata('b_ag_order_read') && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_order_read') && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$sCriteria='';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata('order_search');
		$aSearchForm=array(
			's_po_no_filter'	=> '', 
			's_po_filter'		=> '', 
			's_color_filter'	=> '', 
			'd_order_date_month_filter'	=> '', 
			'n_order_date_year_filter'	=> '',
			's_buyer_filter'	=> '',
			's_model_filter'	=> '',
			's_brand_filter'	=> '',
			's_location_filter'	=> '',
			's_type_filter'		=> '',
			'd_production_date_month_filter'=> '', 
			'n_production_date_year_filter'	=> '',
			'sSort'				=> 'd_createtime', 
			'sSortMethod'	=> 'DESC');
		$aSessionSearchForm = $this->session->userdata('order_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('order_sort');
		
		if( isset($_POST['sSort']) ){
			/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata('order_search');
				$this->session->unset_userdata('order_search_form');
			}
			
			if ( $this->input->post('s_po_no_filter') ) {
				$s_po_no_filter=$this->input->post('s_po_no_filter');
				$aCriteria[]="ttpo.s_po_no ILIKE '%$s_po_no_filter%'";
			}
			if ( $this->input->post('s_po_filter') ) {
				$s_po_filter=$this->input->post('s_po_filter');
				$aCriteria[]="ttpo.s_po ILIKE '%$s_po_filter%'";
			}
			if ( $this->input->post('s_location_filter') ) {
				$s_location_filter=$this->input->post('s_location_filter');
				$aCriteria[]="ttpo.s_location = '$s_location_filter'";
			}
			if ( $this->input->post('s_type_filter') ) {
				$s_type_filter=$this->input->post('s_type_filter');
				$aCriteria[]="ttpo.s_type = '$s_type_filter'";
			}
			if ( $this->input->post('s_color_filter') ) {
				$s_color_filter=$this->input->post('s_color_filter');
				$aCriteria[]="tmcl.s_description ILIKE '%$s_color_filter%'";
			}
			if ( $this->input->post('d_order_date_month_filter') ) {
				$d_order_date_month_filter=$this->input->post('d_order_date_month_filter');
				$aCriteria[]="EXTRACT(MONTH FROM ttpo.d_order_date)='$d_order_date_month_filter'";
			}
			if ( $this->input->post('n_order_date_year_filter') ) {
				$n_order_date_year_filter=$this->input->post('n_order_date_year_filter');
				$aCriteria[]="EXTRACT(YEAR FROM ttpo.d_order_date)='$n_order_date_year_filter'";
			}
			if ( $this->input->post('s_buyer_filter') ) {
				$s_buyer_filter=$this->input->post('s_buyer_filter');
				$aCriteria[]="ttpo.s_buyer = '$s_buyer_filter'";
			}
			if ( $this->input->post('s_model_filter') ) {
				$s_model_filter=$this->input->post('s_model_filter');
				$aCriteria[]="tmm.s_description ILIKE '%$s_model_filter%'";
			}
			if ( $this->input->post('s_brand_filter') ) {
				$s_brand_filter=$this->input->post('s_brand_filter');
				$aCriteria[]="ttpo.s_brand ILIKE '%$s_brand_filter%'";
			}
			if ( $this->input->post('d_production_date_month_filter') ) {
				$d_production_date_month_filter=$this->input->post('d_production_date_month_filter');
				$aCriteria[]="EXTRACT(MONTH FROM ttpo.d_production_date)='$d_production_date_month_filter'";
			}
			if ( $this->input->post('n_production_date_year_filter') ) {
				$n_production_date_year_filter=$this->input->post('n_production_date_year_filter');
				$aCriteria[]="EXTRACT(YEAR FROM ttpo.d_production_date)='$n_production_date_year_filter'";
			}
			foreach ($aSearchForm as $sField=>$sValue) {$aSearchForm[$sField]=$this->input->post($sField);}
			if ( !empty($aCriteria) ) {
				$this->session->set_userdata(array('order_search' => $aCriteria));
				$this->session->set_userdata(array('order_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if ( !empty($aSessionSort) ) $this->session->unset_userdata('order_sort');
			if ( $this->input->post('sSort') && $this->input->post('sSortMethod') ) {
				$sSort=$this->input->post('sSort');
				$sSortMethod=$this->input->post('sSortMethod');
				if ( $this->input->post('bSortAction') ) {
					$sSortMethod=($this->input->post('sSortMethod')=='ASC' ? 'DESC' :'ASC');
					$aSearchForm['sSortMethod']=$sSortMethod;
				}
				$aSort=array($sSort => $sSortMethod);
			}
			if ( !empty($aSort) ) $this->session->set_userdata(array('order_sort' => $aSort));
		} else {
			/* -- Default View -- */
			if (empty($aSessionSearch['d_order_date_month_filter'])) {
				$d_order_date_month_filter=date('m');
				$sPartCriteria="EXTRACT(MONTH FROM ttpo.d_order_date)='$d_order_date_month_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['d_order_date_month_filter']=$sPartCriteria;
				$aSearchForm['d_order_date_month_filter']=$d_order_date_month_filter;
			}
			if (empty($aSessionSearch['n_order_date_year_filter'])) {
				$n_order_date_year_filter=date('Y');
				$sPartCriteria="EXTRACT(YEAR FROM ttpo.d_order_date)='$n_order_date_year_filter'";
				$aCriteria[]=$sPartCriteria;
				$aSearch['n_order_date_year_filter']=$sPartCriteria;
				$aSearchForm['n_order_date_year_filter']=$n_order_date_year_filter;
			}
			/* -- Searching -- */
			if( !empty($aSessionSearch) ) {
				if ($sMessage!='') {
					$aCriteria=$aSessionSearch;
					$aSearchForm=$aSessionSearchForm;
				} else {
					$this->session->unset_userdata('order_search');
					$this->session->unset_userdata('order_search_form');
				}
			}
			/* -- Sorting -- */
			if( !empty($aSessionSort) ) {
				if ($sMessage!='') 
					$aSort=$aSessionSort;
				else 
					$this->session->unset_userdata('order_sort');
			}
		}
		
		$aCriteria[]="ttpo.s_division='".strtoupper($sDivision)."'";
		$sCriteria=implode(' AND ', $aCriteria);
		
		$aDataOrder = $this->Order_model->getList($sCriteria, 0, 0, $aSort);
		$nTotalRows = count($aDataOrder);
		$sMessages='';
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/production/order/',
						'sDivision'			=> $sDivision,
						
						'MESSAGES'			=> '',
						'PAGE_TITLE'		=> 'SPMS-G. '.$this->aDivision[strtoupper($sDivision)].'/Order',
						'toolCaption'		=> strtoupper($sDivision).' Order Tool',
						'filterCaption'		=> strtoupper($sDivision).' Order Filter/Search',
						
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nTotalRows'		=> $nTotalRows,
						
						'tt_prod_order'		=> $aDataOrder );
		$aDisplay = array_merge($aDisplay, $aSearchForm);
		$aDisplay['d_order_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_order_date_month_filter']);
		$aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter']);
		$aDisplay['s_location_filter'] = $this->form->selectboxarray($this->config->item('product_location'), $aSearchForm['s_location_filter']);
		$aDisplay['s_type_filter'] = $this->form->selectboxarray($this->config->item('production_process'), $aSearchForm['s_type_filter']);
		$aDisplay['s_buyer_filter'] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='".strtoupper($sDivision)."' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aDisplay['s_buyer_filter']);
		
		$aDisplay['viewFilter'] = $this->load->view($sDivision.'/order/list_filter', $aDisplay, TRUE);
		$aDisplay['viewToolbar'] = $this->load->view($sDivision.'/order/list_toolbar', $aDisplay, TRUE);
		
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse($sDivision.'/order/list', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function viewedit($sDivision, $uId=''){
		$aEditDefault=array();
		// set default data user
		$aEditable=array();
		foreach ($this->Order_model->aContainer as $sField=>$aProperties) {
			$aEditDefault[$sField]=(isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : '');
		}
		if(trim($uId!='') && $uId!='0'){
			// edit mode
			$aUser=$this->Order_model->getList("ttpo.u_id='$uId' AND ttpo.s_division='".strtoupper($sDivision)."'");
			if (count($aUser) > 0) {
				foreach ($this->Order_model->aContainer as $sField=>$aProperties) {
					if($aProperties['edit']==1){
						$aEditDefault[$sField]=(isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : $aUser[0][$sField]);
					}
				}
			} 
		}
		$aDataEditable=array();
		foreach($this->Order_model->aContainer as $sKey=>$aProperties){
			if ($sKey=='s_model') {
				$aDataEditable[$sKey] = $this->form->selectbox('tm_model', 's_code, s_code || \' - \' || s_description AS s_description, s_description AS s_names', "s_division='".strtoupper($sDivision)."' OR s_division IS NULL ORDER BY s_names ASC", 's_code', 's_description', $aEditDefault[$sKey]);
			} elseif ($sKey=='s_color') {
				$aDataEditable[$sKey] = $this->form->selectbox('tm_color', 's_code, s_code || \' - \' || s_description AS s_description, s_description AS s_names', "s_division='".strtoupper($sDivision)."' OR s_division IS NULL ORDER BY s_names ASC", 's_code', 's_description', $aEditDefault[$sKey]);
			} elseif ($sKey=='s_buyer') {
				$aDataEditable[$sKey] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='".strtoupper($sDivision)."' OR s_division IS NULL ORDER BY s_name ASC", 's_code', 's_name', $aEditDefault[$sKey]);
			} elseif ($sKey=='s_location') {
				$aDataEditable[$sKey] = $this->form->selectboxarray($this->config->item('product_location'), $aEditDefault[$sKey]);
			} elseif ($sKey=='s_type') {
				$aDataEditable[$sKey] = $this->form->selectboxarray($this->config->item('production_process'), $aEditDefault[$sKey]);
			} else {
				$aDataEditable[$sKey]=$aEditDefault[$sKey];
			}
		}
		$aEditable[]=$aDataEditable;
		
		$error=$this->validatejs->setValidate($this->Order_model->aContainer);
		$validate_js=$this->validatejs->execValidateJS('frmEdit');
		
		$aDisplay=array('formaction'		=> site_url().'/production/order/'.(trim($uId)=='' || $uId=='0' ? 'add/'.$sDivision : 'edit/'.$sDivision.'/'.$uId),
						'operation'			=> (trim($uId)=='' || $uId=='0' ? 'add' : 'edit'),
						'editable'			=> $aEditable,
						'VALIDATE_JS'		=> $validate_js,
						'baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/production/order/',
						'sDivision'			=> $sDivision,
						
						'MESSAGES'			=> $this->sErrorMessage,
						'PAGE_TITLE'		=> 'SPMS-G. '.$this->aDivision[strtoupper($sDivision)].'/Order',
						'toolCaption'		=> strtoupper($sDivision).' Order Tool',
						
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel );
		
		$aDisplay['viewToolbar'] = $this->load->view($sDivision.'/order/add_toolbar', $aDisplay, TRUE);
		
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse($sDivision.'/order/add', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function add($sDivision) {
		if (!$this->session->userdata('b_ag_order_write') && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_order_write') && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$sMessages=0;
		
		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE){
			foreach($this->Order_model->aContainer as $sKey=>$aProperties){
				$this->aDefaultForm[$sKey]=$this->input->post($sKey);
			}
			$this->sErrorMessage=$this->validation->error_string;
			$this->viewedit($sDivision, '0');
		}else{
			foreach($this->Order_model->aContainer as $sKey=>$aProperties){
				if ($aProperties['edit']==1) {
					$aData[$sKey]=$this->validation->$sKey;
				}
			}
			$aData['s_update_by']=$this->sUsername;
			$uId = $this->Order_model->insert($aData, strtoupper($sDivision), $this->validation->n_begin_number);
			if ($uId===FALSE) {
				$sMessages=0;
			} else {
				$sMessages=1;
			}
			
			redirect("production/order/index/$sDivision/$sMessages");
		}
	}
	
	function edit($sDivision, $uId) {
		if (!$this->session->userdata('b_ag_order_write') && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_order_write') && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$sMessages=0;
		
		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE){
			foreach($this->Order_model->aContainer as $sKey=>$aProperties){
				if($aProperties['edit']==1) {
					$this->aDefaultForm[$sKey]=$this->input->post($sKey);
				}
			}
			$this->sErrorMessage=$this->validation->error_string;
			$this->viewedit($sDivision, $uId);
		}else{
			foreach($this->Order_model->aContainer as $sKey=>$aProperties){
				if ($aProperties['edit']==1) {
					$aData[$sKey]=$this->validation->$sKey;
				}
			}
			$aData['s_update_by']=$this->sUsername;
			$uId = $this->Order_model->update($uId, $aData, strtoupper($sDivision));
			if ($uId===FALSE) {
				$sMessages=0;
			} else {
				$sMessages=1;
			}
			
			redirect("production/order/index/$sDivision/$sMessages");
		}
	}
	
	function delete($sDivision, $uId=''){
		if (!$this->session->userdata('b_ag_order_write') && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_order_write') && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$rDelete=TRUE;
		if (isset($_POST['uIdRow'])) {
			foreach ($_POST['uIdRow'] as $uId) {
				$rDelete=$this->Order_model->delete($uId, strtoupper($sDivision));
				if ($rDelete===FALSE) break;
			}
		} else {
			if ($uId!='') $rDelete=$this->Order_model->delete($uId, strtoupper($sDivision));
		}
		
		if ($rDelete===FALSE) {
			$sMessages=0;
		} else {
			$sMessages=1;
		}
		
		$this->load->helper('url');
		redirect("production/order/index/$sDivision/$sMessages");
	}
	
	function excel($sDivision) {
		if (!$this->session->userdata('b_ag_order_read') && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_order_read') && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$this->load->helper('excel');
		$aSessionSearch = $this->session->userdata('order_search');
		$aSessionSort = $this->session->userdata('order_sort');
		
		/* -- Default View -- */
		if( empty($aSessionSearch) ) {
			$d_order_date_month_filter=date('m');
			$sPartCriteria="EXTRACT(MONTH FROM ttpo.d_order_date)='$d_order_date_month_filter'";
			$aSessionSearch[]=$sPartCriteria;

			$n_order_date_year_filter=date('Y');
			$sPartCriteria="EXTRACT(YEAR FROM ttpo.d_order_date)='$n_order_date_year_filter'";
			$aSessionSearch[]=$sPartCriteria;
		}
		
		$sSearch=(!empty($aSessionSearch) ? implode(' AND ', $aSessionSearch).' AND ' : '')." ttpo.s_division='".strtoupper($sDivision)."' ";
		
		$aDataOrder=$this->Order_model->getList($sSearch, 0, 0, $aSessionSort);
		$aHeader = array(
			's_po_no' => 'PI Number',
			's_po' => 'PO',
			'd_order_date' => 'Receive Order',
			'd_production_date' => 'Production Date',
			'd_plan_date' => 'Production Plan Date (Input)',
			'd_delivery_date' => 'Production Plan Date (Output)',
			'd_target_date' => 'Export Plan Date',
			's_buyer_name' => 'Buyer',
			's_brand' => 'Brand',
			's_model_name' => 'Model',
			's_smodel' => 'Item Code',
			's_color_name' => 'Color',
			's_location' => 'Loc',
			'n_qty' => 'Qty'
		);
		
		$nQtyTotal=0;
		$aDatas=array();
		foreach($aDataOrder as $nRow=>$aRecordOrder){
			$aData=array();
			foreach($aRecordOrder as $sField=>$sValue) {
				if (isset($aHeader[$sField])) {
					$aData[$aHeader[$sField]]=$sValue;
					if ($sField=='n_qty') {
						$nQtyTotal+=$sValue;
					}
				/*} else {
					$aData[$sField]=$sValue;*/
				}
			}
			$aDatas[]=$aData;
		}
		$aDatas[]=array(
			's_po_no' => ' ',
			's_po' => ' ',
			'd_order_date' => ' ',
			'd_production_date' => ' ',
			'd_plan_date' => ' ',
			'd_delivery_date' => ' ',
			'd_target_date' => ' ',
			's_buyer_name' => ' ',
			's_brand' => ' ',
			's_model_name' => ' ',
			's_smodel' => ' ',
			's_color_name' => ' ',
			's_location' => count($aDataOrder),
			'n_qty' => $nQtyTotal);
		to_excel_array($aDatas, 'orderlist');
	}
}