<?php

class Buyer_model extends Controller {
	var $sUsername;
	var $sLevel;
	
	var $nRowsPerPage=20;
	
	var $_bModal=false;
	var $_sModalTarget='';
	
	var $rules=array();
	var $fields=array();
	
	var $sErrorMessage='';
	var $aDefaultForm=array();

	function Buyer_model(){
		parent::Controller();
		$this->load->library('session');	
		
		if (!$this->session->userdata('b_master_read')) show_error('Access Denied');
		
		$this->sUsername=$this->session->userdata('s_username');
		$this->sLevel=$this->session->userdata('s_level');
		
		$this->load->model('Buyer_model_model');
		$this->load->model('Util_model');
		
		$this->load->helper('url');
		
		$this->load->library('parser');
		$this->load->library('form');
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="message_error">', '</div>');
		$this->load->library('validatejs');
		
		foreach($this->Buyer_model_model->aContainer as $sKey=>$aProperties){
			if($aProperties['edit']==1){
				$this->rules[$sKey]=$aProperties['rules'];
				$this->fields[$sKey]=$aProperties['caption'];
			}
		}
	}
	
	function index($uId='', $sMessage=''){
		$sCriteria='';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata('buyer_model_search');
		$aSearchForm=array(
			's_buyer_filter'	=> '',
			's_model_filter'	=> '',
			'sSort'				=> 'd_createtime', 'sSortMethod'	=> 'DESC');
		$aSessionSearchForm = $this->session->userdata('buyer_model_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('buyer_model_sort');
		
		/* -- Pagination -- */
		$nOffset=0;
		$nLimit=$this->nRowsPerPage;
		$aPagination=array();
		$aSessionPagination = $this->session->userdata('buyer_model_pagination');
		
		if( isset($_POST['nOffset']) ){
			/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata('buyer_model_search');
				$this->session->unset_userdata('buyer_model_search_form');
			}
			if($this->input->post('s_buyer_filter')){
				 $s_buyer_filter=$this->input->post('s_buyer_filter');
				 $aCriteria[]="(tmbm.s_code_customer ILIKE '%$s_buyer_filter%' OR tmc.s_name ILIKE '%$s_buyer_filter%')";
			}
			if($this->input->post('s_model_filter')){
				 $s_model_filter=$this->input->post('s_model_filter');
				 $aCriteria[]="(tmbm.s_code_model ILIKE '%$s_model_filter%' OR tmm.s_description ILIKE '%$s_model_filter%')";
			}
			foreach ($aSearchForm as $sField=>$sValue) $aSearchForm[$sField]=$this->input->post($sField);
			if ( !empty($aCriteria) ) {
				$this->session->set_userdata(array('buyer_model_search' => $aCriteria));
				$this->session->set_userdata(array('buyer_model_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if ( !empty($aSessionSort) ) $this->session->unset_userdata('buyer_model_sort');
			if ( $this->input->post('sSort') && $this->input->post('sSortMethod') ) {
				$sSort=$this->input->post('sSort');
				$sSortMethod=$this->input->post('sSortMethod');
				if ( $this->input->post('bSortAction') ) {
					$sSortMethod=($this->input->post('sSortMethod')=='ASC' ? 'DESC' :'ASC');
					$aSearchForm['sSortMethod']=$sSortMethod;
				}
				$aSort=array($sSort => $sSortMethod);
			}
			if ( !empty($aSort) ) $this->session->set_userdata(array('buyer_model_sort' => $aSort));
			
			/* -- Pagination -- */
			if ( !empty($aSessionPagination) ) $this->session->unset_userdata('buyer_model_pagination');
			$aPagination['nOffset']=($this->input->post('nOffset') ? $this->input->post('nOffset') : 0 );
			$aPagination['nLimit']=($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage );
			if ( !empty($aPagination) ) $this->session->set_userdata(array('buyer_model_pagination' => $aPagination));
		} else {
			/* -- Searching -- */
			if( !empty($aSessionSearch) ) {
				if ($sMessage!='') {
					$aCriteria=$aSessionSearch;
					$aSearchForm=$aSessionSearchForm;
				} else {
					$this->session->unset_userdata('buyer_model_search');
					$this->session->unset_userdata('buyer_model_search_form');
				}
			}
			/* -- Sorting -- */
			if( !empty($aSessionSort) ) {
				if ($sMessage!='') 
					$aSort=$aSessionSort;
				else 
					$this->session->unset_userdata('buyer_model_sort');
			}
			
			/* -- Pagination -- */
			if( !empty($aSessionPagination) ) {
				if ($sMessage!='') 
					$aPagination=$aSessionPagination;
				else 
					$this->session->unset_userdata('buyer_model_pagination');
			}
		}
		
		if ( !empty($aPagination) ) {
			$nOffset=$aPagination['nOffset'];
			$nLimit=$aPagination['nLimit'];
		}
		$sCriteria=implode(' AND ', $aCriteria);
		
		$aBuyer_model=$this->Buyer_model_model->getList($sCriteria, $nLimit, $nOffset, $aSort);
		$nTotalRows=count($this->Buyer_model_model->getList($sCriteria));
		$sMessages='';
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/master/buyer_model/',
						
						'MESSAGES'			=> $this->sErrorMessage,
						'PAGE_TITLE'		=> 'SPMS-G. Master/Buyer Assign for Model',
						'toolCaption'		=> 'Buyer Tool',
						'filterCaption'		=> 'Buyer Filter/Search',
						
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nRowsPerPage'		=> $this->nRowsPerPage,
						'nTotalRows'		=> $nTotalRows,
						'nCurrOffset'		=> $nOffset,
						
						'tm_buyer_model'	=> $aBuyer_model );
		$aDisplay = array_merge($aDisplay, $aSearchForm);
		
		$aDisplay['viewFilter'] = $this->load->view('master/buyer_model_filter', $aDisplay, TRUE);
		$aDisplay['viewToolbar'] = $this->load->view('master/buyer_model_toolbar', $aDisplay, TRUE);
		
		$aEditable=$this->viewedit($uId);
		$aDisplay = array_merge($aDisplay, $aEditable);
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse('master/buyer_model', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function viewedit($uId=''){
		$aBuyerModelDefault=array();
		// set default data user
		$aEditable=array();
		foreach ($this->Buyer_model_model->aContainer as $sField=>$aProperties) {
			$aBuyerModelDefault[$sField]=(isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : '');
		}
		if(trim($uId!='') && $uId!='0'){
			// edit mode
			$aUser=$this->Buyer_model_model->getList("u_id='$uId'");
			if (count($aUser) > 0) {
				foreach ($this->Buyer_model_model->aContainer as $sField=>$aProperties) {
					$aBuyerModelDefault[$sField]=(isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : $aUser[0][$sField]);
				}
			} 
		}
		$aDataEditable=array();
		foreach($this->Buyer_model_model->aContainer as $sKey=>$aProperties){
			if($aProperties['edit']==1){
				if ($sKey=='s_code_customer') {
					$aDataEditable[$sKey] = $this->form->selectbox('tm_customer', 's_code, s_code || \' - \' || s_name AS s_name', '', 's_code', 's_name', $aBuyerModelDefault[$sKey]);
				} elseif ($sKey=='s_code_model') {
					$aDataEditable[$sKey] = $this->form->selectbox('tm_model', 's_code, s_code || \' - \' || s_description AS s_description', '', 's_code', 's_description', $aBuyerModelDefault[$sKey]);
				} else {
					$aDataEditable[$sKey]=$aBuyerModelDefault[$sKey];
				}
			}
		}
		$aEditable[]=$aDataEditable;
		
		$error=$this->validatejs->setValidate($this->Buyer_model_model->aContainer);
		$validate_js=$this->validatejs->execValidateJS('frmEdit');
		
		$aDisplay=array('formaction'	=> site_url().'/master/buyer_model/'.(trim($uId)=='' || $uId=='0' ? 'add' : 'edit/'.$uId),
						'editable'		=> $aEditable,
						'VALIDATE_JS'	=> $validate_js);
		return $aDisplay;
	}
	
	function add(){
		if (!$this->session->userdata('b_master_write')) show_error('Access Denied');
		
		$sMessages=0;
		
		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE){
			if( isset($_POST['u_id']) ){
				foreach($this->Buyer_model_model->aContainer as $sKey=>$aProperties){
					if($aProperties['edit']==1) {
						$this->aDefaultForm[$sKey]=$this->input->post($sKey);
					}
				}
				$this->sErrorMessage=$this->validation->error_string;
				$this->index(0, 2);
			} else {
				redirect("master/buyer_model/index/0/2");
			}
		}else{
			foreach($this->Buyer_model_model->aContainer as $sKey=>$aProperties){
				if ($aProperties['edit']==1) {
					$aData[$sKey]=$this->validation->$sKey;
				}
			}
			$aData['s_update_by']=$this->sUsername;
			$uId = $this->Buyer_model_model->insert($aData);
			if ($uId===FALSE) {
				$sMessages=0;
			} else {
				$sMessages=1;
			}
			
			redirect("master/buyer_model/index/$uId/$sMessages");
		}
	}
	
	function edit($uId){
		if (!$this->session->userdata('b_master_write')) show_error('Access Denied');
		
		$sMessages=0;
		$aData=array();
		
		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE){
			if( isset($_POST['u_id']) ){
				foreach($this->Buyer_model_model->aContainer as $sKey=>$aProperties){
					if($aProperties['edit']==1) {
						$this->aDefaultForm[$sKey]=$this->input->post($sKey);
					}
				}
				
				$this->sErrorMessage=$this->validation->error_string;
				$this->index($uId, 2);
			} else {
				redirect("master/buyer_model/index/$uId/2");
			}
		}else{
			foreach($this->Buyer_model_model->aContainer as $sKey=>$aProperties){
				if ($aProperties['edit']==1) {
					$aData[$sKey]=$this->validation->$sKey;
				}
			}
			$aData['s_update_by']=$this->sUsername;
			$uId = $this->Buyer_model_model->update($uId, $aData);
			if ($uId===FALSE) {
				$sMessages=0;
			} else {
				$sMessages=1;
			}
			
			redirect("master/buyer_model/index/$uId/$sMessages");
		}
	}
	
	function delete($uId=''){
		if (!$this->session->userdata('b_master_write')) show_error('Access Denied');
		
		$rDelete=TRUE;
		if (isset($_POST['uIdRow'])) {
			foreach ($_POST['uIdRow'] as $uIdRow) {
				$rDelete=$this->Buyer_model_model->delete($uIdRow);
				if ($rDelete===FALSE) break;
			}
		} else {
			if ($uId!='') $rDelete=$this->Buyer_model_model->delete($uId);
		}
		
		if ($rDelete===FALSE) {
			$sMessages=0;
		} else {
			$sMessages=1;
		}
		
		$this->load->helper('url');
		redirect("master/buyer_model/index/0/$sMessages");
	}
	
	function excel() {
		$this->load->helper('excel');
		$aSessionSearch = $this->session->userdata('buyer_model_search');
		$aSessionSort = $this->session->userdata('buyer_model_sort');
		
		$sSearch=(!empty($aSessionSearch) ? implode(' AND ', $aSessionSearch) : '');
		
		$aBuyer_model=$this->Buyer_model_model->getList($sSearch, 0, 0, $aSessionSort);
		to_excel_array($aBuyer_model, 'buyer_model_master');
	}
}
?>