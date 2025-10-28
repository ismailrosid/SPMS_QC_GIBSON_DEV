<?php

class Userlevel extends Controller {
	var $sUsername;
	var $sLevel;
	
	var $nRowsPerPage=20;
	
	var $_bModal=false;
	var $_sModalTarget='';
	
	var $rules=array();
	var $fields=array();
	
	var $sErrorMessage='';
	var $aDefaultForm=array();
	
	function Userlevel(){
		parent::Controller();
		$this->load->library('session');	
		
		if (!$this->session->userdata('b_setup_write') || !$this->session->userdata('b_setup_read')) show_error('Access Denied');
		
		$this->sUsername=$this->session->userdata('s_username');
		$this->sLevel=$this->session->userdata('s_level');
		
		$this->load->model('User_level_model');
		
		$this->load->helper('url');
		
		$this->load->library('parser');
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="message_error">', '</div>');
		$this->load->library('validatejs');
		
		foreach($this->User_level_model->aContainer as $sKey=>$aProperties){
			if($aProperties['edit']==1){
				$this->rules[$sKey]=$aProperties['rules'];
				$this->fields[$sKey]=$aProperties['caption'];
			}
		}
	}
	
	function index($sLevel='', $sMessage=''){
		$sCriteria='';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata('userlevel_search');
		$aSearchForm=array(
			'sSort'				=> 'd_createtime', 'sSortMethod'	=> 'DESC');
		$aSessionSearchForm = $this->session->userdata('userlevel_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('userlevel_sort');
		
		/* -- Pagination -- */
		$nOffset=0;
		$nLimit=$this->nRowsPerPage;
		$aPagination=array();
		$aSessionPagination = $this->session->userdata('userlevel_pagination');
		
		if( isset($_POST['nOffset']) ){
			/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata('userlevel_search');
				$this->session->unset_userdata('userlevel_search_form');
			}
			
			foreach ($aSearchForm as $sField=>$sValue) $aSearchForm[$sField]=$this->input->post($sField);
			if ( !empty($aCriteria) ) {
				$this->session->set_userdata(array('userlevel_search' => $aCriteria));
				$this->session->set_userdata(array('userlevel_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if ( !empty($aSessionSort) ) $this->session->unset_userdata('userlevel_sort');
			if ( $this->input->post('sSort') && $this->input->post('sSortMethod') ) {
				$sSort=$this->input->post('sSort');
				$sSortMethod=$this->input->post('sSortMethod');
				if ( $this->input->post('bSortAction') ) {
					$sSortMethod=($this->input->post('sSortMethod')=='ASC' ? 'DESC' :'ASC');
					$aSearchForm['sSortMethod']=$sSortMethod;
				}
				$aSort=array($sSort => $sSortMethod);
			}
			if ( !empty($aSort) ) $this->session->set_userdata(array('userlevel_sort' => $aSort));
			
			/* -- Pagination -- */
			if ( !empty($aSessionPagination) ) $this->session->unset_userdata('userlevel_pagination');
			$aPagination['nOffset']=($this->input->post('nOffset') ? $this->input->post('nOffset') : 0 );
			$aPagination['nLimit']=($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage );
			if ( !empty($aPagination) ) $this->session->set_userdata(array('userlevel_pagination' => $aPagination));
		} else {
			/* -- Searching -- */
			if( !empty($aSessionSearch) ) {
				if ($sMessage!='') {
					$aCriteria=$aSessionSearch;
					$aSearchForm=$aSessionSearchForm;
				} else {
					$this->session->unset_userdata('userlevel_search');
					$this->session->unset_userdata('userlevel_search_form');
				}
			}
			/* -- Sorting -- */
			if( !empty($aSessionSort) ) {
				if ($sMessage!='') 
					$aSort=$aSessionSort;
				else 
					$this->session->unset_userdata('userlevel_sort');
			}
			
			/* -- Pagination -- */
			if( !empty($aSessionPagination) ) {
				if ($sMessage!='') 
					$aPagination=$aSessionPagination;
				else 
					$this->session->unset_userdata('userlevel_pagination');
			}
		}
		
		if ( !empty($aPagination) ) {
			$nOffset=$aPagination['nOffset'];
			$nLimit=$aPagination['nLimit'];
		}
		$sCriteria=implode(' AND ', $aCriteria);
		
		$aDataUserLevel=$this->User_level_model->getList($sCriteria, $nLimit, $nOffset, $aSort);
		$nTotalRows=count($this->User_level_model->getList($sCriteria));
		$sMessages='';
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/setup/userlevel/',
						
						'MESSAGES'			=> $this->sErrorMessage,
						'PAGE_TITLE'		=> 'SPMS-G. Setup/User Level',
						'toolCaption'		=> 'User Level Tool',
						
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nRowsPerPage'		=> $this->nRowsPerPage,
						'nTotalRows'		=> $nTotalRows,
						'nCurrOffset'		=> $nOffset,
						
						'tm_user_level'		=> $aDataUserLevel );
		$aDisplay = array_merge($aDisplay, $aSearchForm);
		$aDisplay['viewToolbar'] = $this->load->view('setup/userlevel_toolbar', $aDisplay, TRUE);
		
		$aEditable=$this->viewedit($sLevel);
		$aDisplay = array_merge($aDisplay, $aEditable);
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse('setup/userlevel', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function viewedit($sLevel=''){
		$aUserDefault=array();
		// set default data user
		$aEditable=array();
		foreach ($this->User_level_model->aContainer as $sField=>$aProperties) {
			$aUserDefault[$sField]=(isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : '');
		}
		if(trim($sLevel!='') && $sLevel!='0'){
			// edit mode
			$aUser=$this->User_level_model->getList("s_level='$sLevel'");
			if (count($aUser) > 0) {
				foreach ($this->User_level_model->aContainer as $sField=>$aProperties) {
					$aUserDefault[$sField]=(isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : $aUser[0][$sField]);
				}
			} 
		}
		$aDataEditable=array();
		foreach($this->User_level_model->aContainer as $sKey=>$aProperties){
			if($aProperties['edit']==1){
				if (substr($sKey,0,2)=='b_') {
					$aDataEditable[$sKey]=(strtolower($aUserDefault[$sKey])=='t' ? 'checked' : '');
				} else {
					$aDataEditable[$sKey]=$aUserDefault[$sKey];
				}
			}
		}
		$aEditable[]=$aDataEditable;
		
		$error=$this->validatejs->setValidate($this->User_level_model->aContainer);
		$validate_js=$this->validatejs->execValidateJS('frmEdit');
		
		$aDisplay=array('formaction'	=> site_url().'/setup/userlevel/'.(trim($sLevel)=='' || $sLevel=='0' ? 'add' : 'edit/'.$sLevel),
						'editable'		=> $aEditable,
						'VALIDATE_JS'	=> $validate_js);
		return $aDisplay;
	}
	
	function add(){
		$sMessages=0;
		
		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE){
			if( isset($_POST['s_level']) ){
				foreach($this->User_level_model->aContainer as $sKey=>$aProperties){
					if($aProperties['edit']==1) {
						if (substr($sKey,0,2)=='b_') {
							$this->aDefaultForm[$sKey]=($this->input->post($sKey) ? 't' : '');
						} else {
							$this->aDefaultForm[$sKey]=$this->input->post($sKey);
						}
					}
				}
				$this->sErrorMessage=$this->validation->error_string;
				$this->index(0, 2);
			} else {
				redirect("setup/userlevel/index/0/2");
			}
		}else{
			foreach($this->User_level_model->aContainer as $sKey=>$aProperties){
				if ($aProperties['edit']==1) {
					if (substr($sKey,0,2)=='b_') {
						$aData[$sKey]=($this->validation->$sKey ? 't' : 'f');
					} else {
						$aData[$sKey]=$this->validation->$sKey;
					}
				}
			}
			$aData['s_update_by']=$this->sUsername;
			$sLevel = $this->User_level_model->insert($aData);
			if ($sLevel===FALSE) {
				$sMessages=0;
			} else {
				$sMessages=1;
			}
			
			redirect("setup/userlevel/index/$sLevel/$sMessages");
		}
	}
	
	function edit($sLevel){
		$sMessages=0;
		$aData=array();
		
		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE){
			if( isset($_POST['s_level']) ){
				foreach($this->User_level_model->aContainer as $sKey=>$aProperties){
					if($aProperties['edit']==1) {
						if (substr($sKey,0,2)=='b_') {
							$this->aDefaultForm[$sKey]=($this->input->post($sKey) ? 't' : '');
						} else {
							$this->aDefaultForm[$sKey]=$this->input->post($sKey);
						}
					}
				}
				
				$this->sErrorMessage=$this->validation->error_string;
				$this->index($sLevel, 2);
			} else {
				redirect("setup/userlevel/index/$sLevel/2");
			}
		}else{
			foreach($this->User_level_model->aContainer as $sKey=>$aProperties){
				if ($aProperties['edit']==1) {
					if (substr($sKey,0,2)=='b_') {
						$aData[$sKey]=($this->validation->$sKey ? 't' : 'f');
					} else {
						$aData[$sKey]=$this->validation->$sKey;
					}
				}
			}
			$aData['s_update_by']=$this->sUsername;
			$sLevel = $this->User_level_model->update($sLevel, $aData);
			if ($sLevel===FALSE) {
				$sMessages=0;
			} else {
				$sMessages=1;
			}
			
			redirect("setup/userlevel/index/$sLevel/$sMessages");
		}
	}
	
	function delete($sLevel=''){
		$rDelete=TRUE;
		if (isset($_POST['uIdRow'])) {
			foreach ($_POST['uIdRow'] as $uIdRow) {
				$rDelete=$this->User_level_model->delete($uIdRow);
				if ($rDelete===FALSE) break;
			}
		} else {
			if ($sLevel!='') $rDelete=$this->User_level_model->delete($sLevel);
		}
		
		if ($rDelete===FALSE) {
			$sMessages=0;
		} else {
			$sMessages=1;
		}
		
		$this->load->helper('url');
		redirect("setup/userlevel/index/0/$sMessages");
	}
}
?>