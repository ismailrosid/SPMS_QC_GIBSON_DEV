<?php

class Userlog extends Controller {
	var $sUsername;
	var $sLevel;
	
	var $nRowsPerPage=20;
	
	var $_bModal=false;
	var $_sModalTarget='';
	
	var $rules=array();
	var $fields=array();
	
	var $sErrorMessage='';
	var $aDefaultForm=array();
	
	var $aContainer=array();

	function Userlog(){
		parent::Controller();
		$this->load->library('session');	
		
		if (!$this->session->userdata('b_setup_write') || !$this->session->userdata('b_setup_read')) show_error('Access Denied');
		
		$this->sUsername=$this->session->userdata('s_username');
		$this->sLevel=$this->session->userdata('s_level');
		
		$this->load->model('User_log_model');
		$this->load->helper('url');
		
		$this->load->library('parser');
		
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="message_error">', '</div>');
		$this->load->library('validatejs');
	}
	
	function index($sMessage=''){
		$sCriteria='';
		/* -- Searching -- */
		$aCriteria=array();
		$aSessionSearch = $this->session->userdata('order_search');
		$aSearchForm=array(
			's_username_filter'	=> '', 
			's_level_filter'	=> '', 
			'd_login_filter'	=> '', 
			's_name_filter'		=> '', 
			'ip_address_filter'	=> '',
			'sSort'				=> 'd_login', 'sSortMethod'	=> 'DESC');
		$aSessionSearchForm = $this->session->userdata('order_search_form');
		
		/* -- Sorting -- */
		$aSort = array($aSearchForm['sSort']=>$aSearchForm['sSortMethod']);
		$aSessionSort = $this->session->userdata('order_sort');
		
		/* -- Pagination -- */
		$nOffset=0;
		$nLimit=$this->nRowsPerPage;
		$aPagination=array();
		$aSessionPagination = $this->session->userdata('order_pagination');
		
		if( isset($_POST['nOffset']) ){
			/* -- Searching -- */
			if(!empty($aSessionSearch)) {
				$this->session->unset_userdata('order_search');
				$this->session->unset_userdata('order_search_form');
			}

			if ( $this->input->post('s_username_filter') ) {
				$s_username_filter=$this->input->post('s_username_filter');
				$aCriteria[]="tlul.s_username  ILIKE '%$s_username_filter%'";
			}
			if ( $this->input->post('s_level_filter') ) {
				$s_level_filter=$this->input->post('s_level_filter');
				$aCriteria[]="tlul.s_level ILIKE '%$s_level_filter%'";
			}
			if ( $this->input->post('d_login_filter') ) {
				$d_login_filter=$this->input->post('d_login_filter');
				$aCriteria[]="TO_CHAR(tlul.d_login, 'YYYY-MM-DD') = '$d_login_filter'";
			}
			if ( $this->input->post('s_name_filter') ) {
				$s_name_filter=$this->input->post('s_name_filter');
				$aCriteria[]="tlul.s_name ILIKE '%$s_name_filter%'";
			}
			if ( $this->input->post('ip_address_filter') ) {
				$ip_address_filter=$this->input->post('ip_address_filter');
				$aCriteria[]="tlul.ip_address ILIKE '%$ip_address_filter%'";
			}
			foreach ($aSearchForm as $sField=>$sValue) $aSearchForm[$sField]=$this->input->post($sField);
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
			
			/* -- Pagination -- */
			if ( !empty($aSessionPagination) ) $this->session->unset_userdata('order_pagination');
			$aPagination['nOffset']=($this->input->post('nOffset') ? $this->input->post('nOffset') : 0 );
			$aPagination['nLimit']=($this->input->post('nLimit') ? $this->input->post('nLimit') : $this->nRowsPerPage );
			if ( !empty($aPagination) ) $this->session->set_userdata(array('order_pagination' => $aPagination));
		} else {
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
			
			/* -- Pagination -- */
			if( !empty($aSessionPagination) ) {
				if ($sMessage!='') 
					$aPagination=$aSessionPagination;
				else 
					$this->session->unset_userdata('order_pagination');
			}
		}
		
		if ( !empty($aPagination) ) {
			$nOffset=$aPagination['nOffset'];
			$nLimit=$aPagination['nLimit'];
		}
		$sCriteria=implode(' AND ', $aCriteria);
		
		$aDataUserLog=$this->User_log_model->getList($sCriteria, $nLimit, $nOffset, $aSort);
		$nTotalRows=count($this->User_log_model->getList($sCriteria));
		$sMessages='';
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/setup/userlog/',
						
						'MESSAGES'			=> '',
						'PAGE_TITLE'		=> 'SPMS-G. Setup/User Log',
						'toolCaption'		=> 'User Log Tool',
						'filterCaption'		=> 'User Log Filter/Search',
						
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nRowsPerPage'		=> $this->nRowsPerPage,
						'nTotalRows'		=> $nTotalRows,
						'nCurrOffset'		=> $nOffset,
						
						'tl_user_log'		=> $aDataUserLog );
		$aDisplay = array_merge($aDisplay, $aSearchForm);
		
		$aDisplay['viewFilter'] = $this->load->view('setup/userlog_filter', $aDisplay, TRUE);
		$aDisplay['viewToolbar'] = $this->load->view('setup/userlog_toolbar', $aDisplay, TRUE);
		
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse('setup/userlog', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function delete() {
		$aSessionSearch = $this->session->userdata('order_search');
		$aCriteria=$aSessionSearch;
		$sCriteria='';
		if ($aCriteria && count($aCriteria)>0 )	$sCriteria=implode(' AND ', $aCriteria);
		$aDataUserLog=$this->User_log_model->delete($sCriteria);
		if ($aDataUserLog===FALSE) {
			$sMessages=0;
		} else {
			$sMessages=1;
		}
		
		redirect("setup/userlog/index/$sMessages");
	}
}
?>