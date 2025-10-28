<?php

class Application extends Controller {
	var $sUsername;
	var $sJenis;
	
	var $nRowsPerPage=20;
	
	var $_bModal=false;
	var $_sModalTarget='';
	
	var $rules=array();
	var $fields=array();
	
	var $sErrorMessage='';
	var $aDefaultForm=array();

	function Application(){
		parent::Controller();
		$this->load->library('session');	
		
		if (!$this->session->userdata('b_setup_read')) show_error('Access Denied');
		
		$this->sUsername=$this->session->userdata('s_username');
		$this->sLevel=$this->session->userdata('s_level');
		
		$this->load->model('Application_model');
		
		$this->load->helper('url');
		
		$this->load->library('parser');
		
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="message_error">', '</div>');
		$this->load->library('validatejs');
		
		foreach($this->Application_model->aContainer as $sKey=>$aProperties){
			if($aProperties['edit']==1){
				$this->rules[$sKey]=$aProperties['rules'];
				$this->fields[$sKey]=$aProperties['caption'];
			}
		}
	}
	
	function index($uId='', $sMessage=''){
		$nOffset=0;
		$nTotalRows=0;
		
		$aDataEditable=array();
		$aData = $this->Application_model->getList();
		if (count($aData)>0) {
			$uId=$aData[0]['u_id'];
			$aDataEditable[]=$aData[0];
		}
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/setup/application/',
						'MESSAGES'			=> '',
						'PAGE_TITLE'		=> 'SPMS-G. Setup/Application',
						'toolCaption'		=> 'Preferences Tool',
						
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nRowsPerPage'		=> $this->nRowsPerPage,
						'nTotalRows'		=> $nTotalRows,
						'nCurrOffset'		=> $nOffset,
						
						'editable'			=> $aDataEditable);
		
		$aEditable=$this->viewedit($uId);
		$aDisplay['viewToolbar'] = $this->load->view('setup/application_toolbar', $aDisplay, TRUE);
		
		$aDisplay = array_merge($aDisplay, $aEditable);
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse('setup/application', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function viewedit($uId=''){
		$aKomentarDefault=array();
		// set default data application
		$aEditable=array();
		foreach ($this->Application_model->aContainer as $sField=>$aProperties) {
			$aKomentarDefault[$sField]=(isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : '');
		}
		if(trim($uId!='') && $uId!='0'){
			// edit mode
			$aKomentar=$this->Application_model->getList("u_id='$uId'");
			if (count($aKomentar) > 0) {
				foreach ($this->Application_model->aContainer as $sField=>$aProperties) {
					$aKomentarDefault[$sField]=(isset($this->aDefaultForm[$sField]) ? $this->aDefaultForm[$sField] : $aKomentar[0][$sField]);
				}
			} 
		}
		$aDataEditable=array();
		foreach($this->Application_model->aContainer as $sKey=>$aProperties){
			if($aProperties['edit']==1){
				$aDataEditable[$sKey]=$aKomentarDefault[$sKey];
			}
		}
		$aEditable[]=$aDataEditable;
		
		$error=$this->validatejs->setValidate($this->Application_model->aContainer);
		$validate_js=$this->validatejs->execValidateJS('frmEdit');
		
		$aDisplay=array('formaction'	=> site_url().'/setup/application/'.(trim($uId)=='' || $uId=='0' ? 'add' : 'edit/'.$uId),
						'editable'		=> $aEditable,
						'VALIDATE_JS'	=> $validate_js);
		return $aDisplay;
	}
	
	function add(){
		if (!$this->session->userdata('b_setup_write')) show_error('Access Denied');
		$sMessages=0;
		
		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE){
			if( isset($_POST['s_company_name']) ){
				foreach($this->Application_model->aContainer as $sKey=>$aProperties){
					if($aProperties['edit']==1) $this->aDefaultForm[$sKey]=$this->input->post($sKey);
				}
				$this->sErrorMessage=$this->validation->error_string;
				$this->index(0, 2);
			} else {
				redirect("setup/application/index/0/2");
			}
		}else{
			$aData=array();
			foreach($this->Application_model->aContainer as $sKey=>$aProperties){
				if($aProperties['edit']==1) $aData[$sKey]=$this->validation->$sKey;
			}
			$aData['s_update_by']=$this->sUsername;
			$uApplicationId = $this->Application_model->insert($aData);
			if ($uApplicationId===FALSE) {
				$sMessages=0;
			} else {
				$sMessages=1;
			}
			
			redirect("setup/application/index/$uApplicationId/$sMessages");
		}
	}
	
	function edit($uId){
		if (!$this->session->userdata('b_setup_write')) show_error('Access Denied');
		
		$sMessages=0;
		$aData=array();
		
		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);

		$this->load->helper('url');
		if ($this->validation->run() == FALSE){
			if( isset($_POST['s_company_name']) ){
				foreach($this->Application_model->aContainer as $sKey=>$aProperties){
					if($aProperties['edit']==1) $this->aDefaultForm[$sKey]=$this->input->post($sKey);
				}
				$this->sErrorMessage=$this->validation->error_string;
				$this->index($uId, 2);
			} else {
				redirect("setup/application/index/$uId/2");
			}
		}else{
			foreach($this->Application_model->aContainer as $sKey=>$aProperties){
				if($aProperties['edit']==1) $aData[$sKey]=$this->validation->$sKey;
			}
			$aData['u_id']=$uId;
			$aData['s_update_by']=$this->sUsername;
			$uApplicationId = $this->Application_model->update($aData);
			if ($uApplicationId===FALSE) {
				$sMessages=0;
			} else {
				$sMessages=1;
			}
			
			redirect("setup/application/index/$uId/$sMessages");
		}
	}
}
?>