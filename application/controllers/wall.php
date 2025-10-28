<?php

class Wall extends Controller {
	var $sUsername;
	var $sLevel;
	
	var $nRowsPerPage=20;
	
	var $_bModal=false;
	var $_sModalTarget='';

	function Wall(){
		parent::Controller();
		$this->load->library('session');	
		
		$this->sUsername=$this->session->userdata('s_username');
		$this->sLevel=$this->session->userdata('s_level');
		
		$this->load->helper('url');
		
		$this->load->library('parser');
		
		$this->load->library('validation');
		$this->load->library('validatejs');
	}
	
	function index($sMessage=''){


		$nOffset=0;
		$nTotalRows=0;
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/wall/',
						'MESSAGES'			=> '',
						'PAGE_TITLE'		=> 'SPMS-G. Wall',
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nRowsPerPage'		=> $this->nRowsPerPage,
						'nTotalRows'		=> $nTotalRows,
						'nCurrOffset'		=> $nOffset);
					
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse('wall', $aDisplay);
		$this->parser->parse('footer', $aDisplay);		
	}
}
?>