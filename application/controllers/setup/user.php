<?php

class User extends Controller {
	var $sUsername;
	var $sLevel;
	
	var $nRowsPerPage=20;
	
	var $_bModal=false;
	var $_sModalTarget='';
	
	var $rules=array();
	var $fields=array();
	
	var $sErrorMessage='';
	var $aDefaultForm=array();
	
	function User(){
		parent::Controller();
		$this->load->library('session');	
		
		if (!$this->session->userdata('b_setup_write') || !$this->session->userdata('b_setup_read')) show_error('Access Denied');
		
		$this->sUsername=$this->session->userdata('s_username');
		$this->sLevel=$this->session->userdata('s_level');
		
		$this->load->model('User_model');
		
		$this->load->helper('url');
		
		$this->load->library('parser');
		$this->load->library('Form');
		
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="message_error">', '</div>');
		$this->load->library('validatejs');
		
		foreach($this->User_model->aContainer as $sKey=>$aProperties){
			if($aProperties['edit']==1){
				$this->rules[$sKey]=$aProperties['rules'];
				$this->fields[$sKey]=$aProperties['caption'];
			}
		}
	}
	
	function index($sUserName='', $sMessage=''){
		$sCriteria='';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata('user_search');
		$aSearchForm=array(
			's_username_filter'	=> '', 
			's_level_filter'		=> '', 
			's_name_filter'		=> '', 
			's_nip_filter'		=> '',
			'sSort'				=> 'd_createtime', 'sSortMethod'	=> 'DESC');
		$aSessionSearchForm = $this->session->userdata('user_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('user_sort');
		
		/* -- Pagination -- */
		$nOffset=0;
		$nLimit=$this->nRowsPerPage;
		$aPagination=array();
		$aSessionPagination = $this->session->userdata('user_pagination');
		
		if( isset($_POST['nOffset']) ){
		/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata('user_search');
				$this->session->unset_userdata('user_search_form');
			}
			
			if ( $this->input->post('s_username_filter') ) {
				$s_username_filter=$this->input->post('s_username_filter');
				$aCriteria[]=" s_username ILIKE '%$s_username_filter%' ";
			}
			if ( $this->input->post('s_level_filter') ) {
				$s_level_filter=$this->input->post('s_level_filter');
				$aCriteria[]=" s_level ILIKE '%$s_level_filter%' ";
			}
			if ( $this->input->post('s_name_filter') ) {
				$s_name_filter=$this->input->post('s_name_filter');
				$aCriteria[]=" s_name ILIKE '%$s_name_filter%' ";
			}
			if ( $this->input->post('s_nip_filter') ) {
				$s_nip_filter=$this->input->post('s_nip_filter');
				$aCriteria[]=" s_nip ILIKE '%$s_nip_filter%' ";
			}
			foreach ($aSearchForm as $sField=>$sValue) $aSearchForm[$sField]=$this->input->post($sField);
			if ( !empty($aCriteria) ) {
				$this->session->set_userdata(array('user_search' => $aCriteria));
				$this->session->set_userdata(array('user_search_form' => $aSearchForm));
			}

			/* -- Sorting -- */
			if ( !empty($aSessionSort) ) $this->session->unset_userdata('user_sort');
			if ( $this->input->post('sSort') && $this->input->post('sSortMethod') ) {
				$sSort=$this->input->post('sSort');
				$sSortMethod=$this->input->post('sSortMethod');
				if ( $this->input->post('bSortAction') ) {
					$sSortMethod=($this->input->post('sSortMethod')=='ASC' ? 'DESC' :'ASC');
					$aSearchForm['sSortMethod']=$sSortMethod;
				}
				$aSort=array($sSort => $sSortMethod);
			}
			if ( !empty($aSort) ) $this->session->set_userdata(array('user_sort' => $aSort));
			
			/* -- Pagination -- */
			if ( !empty($aSessionPagination) ) $this->session->unset_userdata('user_pagination');
			$aPagination['nOffset']=($this->input->post('nOffset') ? $this->input->post('nOffset') : 0 );
			$aPagination['nLimit']=($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage );
			if ( !empty($aPagination) ) $this->session->set_userdata(array('user_pagination' => $aPagination));
		} else {
			/* -- Searching -- */
			if( !empty($aSessionSearch) ) {
				if ($sMessage!='') {
					$aCriteria=$aSessionSearch;
					$aSearchForm=$aSessionSearchForm;
				} else {
					$this->session->unset_userdata('user_search');
					$this->session->unset_userdata('user_search_form');
				}
			}
			/* -- Sorting -- */
			if( !empty($aSessionSort) ) {
				if ($sMessage!='') 
					$aSort=$aSessionSort;
				else 
					$this->session->unset_userdata('user_sort');
			}
			
			/* -- Pagination -- */
			if( !empty($aSessionPagination) ) {
				if ($sMessage!='') 
					$aPagination=$aSessionPagination;
				else 
					$this->session->unset_userdata('user_pagination');
			}
		}
		
		if ( !empty($aPagination) ) {
			$nOffset=$aPagination['nOffset'];
			$nLimit=$aPagination['nLimit'];
		}
		$sCriteria=implode(' AND ', $aCriteria);
		
		$aDataUser=$this->User_model->getList($sCriteria, $nLimit, $nOffset, $aSort);
		$nTotalRows=count($this->User_model->getList($sCriteria));
		$sMessages='';
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/setup/user/',
						
						'MESSAGES'			=> $this->sErrorMessage,
						'PAGE_TITLE'		=> 'SPMS-G. Setup/User',
						'toolCaption'		=> 'User Tool',
						'filterCaption'		=> 'User Filter/Search',
						
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nRowsPerPage'		=> $this->nRowsPerPage,
						'nTotalRows'		=> $nTotalRows,
						'nCurrOffset'		=> $nOffset,
						
						'tm_user'			=> $aDataUser );
		$aDisplay = array_merge($aDisplay, $aSearchForm);
		$aDisplay['viewFilter'] = $this->load->view('setup/user_filter', $aDisplay, TRUE);
		$aDisplay['viewToolbar'] = $this->load->view('setup/user_toolbar', $aDisplay, TRUE);
		
		$aEditable=$this->viewedit($sUserName);
		$aDisplay = array_merge($aDisplay, $aEditable);
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse('setup/user', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function viewedit($sUserName=''){
		$aUserDefault=array();
		// set default data user
		$aEditable=array();
		foreach ($this->User_model->aContainer as $sField=>$aProperties) {
			$aUserDefault[$sField]=(isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : '');
		}
		if(trim($sUserName!='') && $sUserName!='0'){
			// edit mode
			$aUser=$this->User_model->getList("s_username='$sUserName'");
			if (count($aUser) > 0) {
				foreach ($this->User_model->aContainer as $sField=>$aProperties) {
					$aUserDefault[$sField]=(isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : $aUser[0][$sField]);
				}
			} 
		}
		$aDataEditable=array();
		foreach($this->User_model->aContainer as $sKey=>$aProperties){
			if($aProperties['edit']==1){
				if ($sKey=='b_active') {
					$aDataEditable[$sKey]=(strtolower($aUserDefault[$sKey])=='t' ? 'checked' : '');
				} else {
					$aDataEditable[$sKey]=$aUserDefault[$sKey];
				}
			}
		}
		$aDataEditable['s_level']=$this->form->selectbox('tm_user_level','s_level','','s_level','s_level',$aUserDefault['s_level']);
				
		$aEditable[]=$aDataEditable;
		
		if (trim($sUserName)=='' || $sUserName=='0') {
			$this->User_model->aContainer['s_password'] = array('caption'=>'Password', 'rules'=>'trim|required|matches[s_password_confirm]','view'=>1,'edit'=>1);
		}
		$error=$this->validatejs->setValidate($this->User_model->aContainer);
		$validate_js=$this->validatejs->execValidateJS('frmEdit');
		
		$aDisplay=array('formaction'	=> site_url().'/setup/user/'.(trim($sUserName)=='' || $sUserName=='0' ? 'add' : 'edit/'.$sUserName),
						'editable'		=> $aEditable,
						'VALIDATE_JS'	=> $validate_js);
		return $aDisplay;
	}
	
	function add(){

		$sMessages=0;
		
		$this->User_model->aContainer['s_password'] = array('caption'=>'Password', 'rules'=>'trim|required|matches[s_password_confirm]','view'=>1,'edit'=>1);
		foreach($this->User_model->aContainer as $key=>$array){
			$this->rules[$key]=$array['rules'];
			$this->fields[$key]=$array['caption'];			
		}
		
		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE){
			if( isset($_POST['s_username']) ){
				foreach($this->User_model->aContainer as $sKey=>$aProperties){
					if($aProperties['edit']==1) {
						if ($sKey=='b_active') {
							$this->aDefaultForm[$sKey]=($this->input->post($sKey) ? 't' : '');
						} else {
							$this->aDefaultForm[$sKey]=$this->input->post($sKey);
						}
					}
				}
				$this->sErrorMessage=$this->validation->error_string;
				$this->index(0, 2);
			} else {
				redirect("setup/user/index/0/2");
			}
		}else{
			foreach($this->User_model->aContainer as $sKey=>$aProperties){
				if ($aProperties['edit']==1) {
					if ($sKey=='b_active') {
						$aData[$sKey]=($this->validation->$sKey ? 't' : 'f');
					} else {
						$aData[$sKey]=$this->validation->$sKey;
					}
				}
			}
			$aData['s_update_by']=$this->sUsername;
			$sUserName = $this->User_model->insert($aData);
			if ($sUserName===FALSE) {
				$sMessages=0;
			} else {
				$sMessages=1;
			}
			
			redirect("setup/user/index/$sUserName/$sMessages");
		}
	}
	
	function edit($sUserName){
		$sMessages=0;
		$aData=array();
		
		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE){
			if( isset($_POST['s_username']) ){
				foreach($this->User_model->aContainer as $sKey=>$aProperties){
					if($aProperties['edit']==1) {
						if ($sKey=='b_active') {
							$this->aDefaultForm[$sKey]=($this->input->post($sKey) ? 't' : '');
						} else {
							$this->aDefaultForm[$sKey]=$this->input->post($sKey);
						}
					}
				}
				
				$this->sErrorMessage=$this->validation->error_string;
				$this->index($sUserName, 2);
			} else {
				redirect("setup/user/index/$sUserName/2");
			}
		}else{
			foreach($this->User_model->aContainer as $sKey=>$aProperties){
				if ($aProperties['edit']==1) {
					if ($sKey=='b_active') {
						$aData[$sKey]=($this->validation->$sKey ? 't' : 'f');
					} else {
						$aData[$sKey]=$this->validation->$sKey;
					}
				}
			}
			$aData['s_update_by']=$this->sUsername;
			$aData['s_username']=$sUserName;
			$sUserName = $this->User_model->update($aData);
			if ($sUserName===FALSE) {
				$sMessages=0;
			} else {
				$sMessages=1;
			}
			
			redirect("setup/user/index/$sUserName/$sMessages");
		}
	}
	
	function delete($sUserName=''){
		$rDelete=TRUE;
		if (isset($_POST['uIdRow'])) {
			foreach ($_POST['uIdRow'] as $uIdRow) {
				$rDelete=$this->User_model->delete($uIdRow);
				if ($rDelete===FALSE) break;
			}
		} else {
			if ($sUserName!='') $rDelete=$this->User_model->delete($sUserName);
		}
		
		if ($rDelete===FALSE) {
			$sMessages=0;
		} else {
			$sMessages=1;
		}
		
		$this->load->helper('url');
		redirect("setup/user/index/0/$sMessages");
	}
}
?>