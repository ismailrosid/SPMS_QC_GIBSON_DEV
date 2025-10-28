<?php

class Setup extends Controller {
	var $sUsername;
	var $sLevel;
	
	var $nRowsPerPage=20;
	
	var $_bModal=false;
	var $_sModalTarget='';
	
	var $rules=array();
	var $fields=array();
	
	var $sErrorMessage='';
	var $aDefaultForm=array();
	var $aDivision = array();
	
	function Setup(){
		parent::Controller();
		$this->load->library('session');	
		
		$this->sUsername=$this->session->userdata('s_username');
		$this->sLevel=$this->session->userdata('s_level');
		
		$this->load->model('Setup_model');
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
		if (!$this->session->userdata('b_ag_setup_read') && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_setup_read') && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$nOffset=0;
		$nLimit=0;
		
		$sCriteria='';
		$aCriteria[]="s_division='".strtoupper($sDivision)."'";
		$sCriteria=implode(' AND ', $aCriteria);
		
		$aSort=array('n_order' => 'ASC', 'n_line' => 'ASC', 'n_line' => 'ASC', 's_field_process' => 'ASC');
		
		$aSetup=$this->Setup_model->getList($sCriteria, $nLimit, $nOffset, $aSort);
		$nTotalRows=count($this->Setup_model->getList($sCriteria));
		
		$aDataSetup=array();
		$nNumber=1;
		foreach ($aSetup as $nRow=>$aData) {
			$aData['n_number']=$nNumber;
			$aData['s_field_process']=$this->form->selectboxarray($this->config->item('production_step'), $aData['s_field_process'], 1);
			$aData['s_type']=$this->form->selectboxarray($this->config->item('production_process'), $aData['s_type']);
			$aDataSetup[]=$aData;
			$nNumber++;
		}
		
		$sMessages='';
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/production/setup/index/'.$sDivision,
						'sDivision'			=> $sDivision,
						
						'MESSAGES'			=> '',
						'PAGE_TITLE'		=> 'SPMS-G. '.$this->aDivision[strtoupper($sDivision)].'/Setup',
						'toolCaption'		=> 'Setup '.strtoupper($sDivision).' Tool',
						
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nRowsPerPage'		=> $this->nRowsPerPage,
						'nTotalRows'		=> $nTotalRows,
						'nCurrOffset'		=> $nOffset,
						'tm_prod_setup'		=> $aDataSetup);
		
		$aDisplay['viewToolbar'] = $this->load->view($sDivision.'/setup_toolbar', $aDisplay, TRUE);
		
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse($sDivision.'/setup', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function save($sDivision) {
		if (!$this->session->userdata('b_ag_setup_write') && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if (!$this->session->userdata('b_eg_setup_write') && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$sMessages=1;
		if (isset($_POST['uIdRow'])) {
			foreach($_POST['uIdRow'] as $n_number) {
				foreach($this->Setup_model->aContainer as $sKey=>$aProperties){
					if ($aProperties['edit']==1) {
						$aData[$sKey]=$this->input->post($sKey.':'.$n_number);
					}
				}
				$aData['s_update_by']=$this->sUsername;
				$aData['s_division']=strtoupper($sDivision);
				$sCode = $this->Setup_model->update($aData['s_phase'], $aData);
			}
		}
		
		redirect("production/setup/index/$sDivision/$sMessages");
	}
}
?>