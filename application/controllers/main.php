<?php

class Main extends Controller {
	var $rules=array();
	var $fields=array();
	var $container=array();
	var $forms=array();
	
	var $aJenisWaktu;
	var $aTahunList;
	var $aPeriodeList;
	
	var $nRowsPerPage=20;
	
	function Main(){
		parent::Controller();
		$this->load->library('session');	
		
		$this->load->helper('url');
		
		$this->container=array(
			'user'=>array(
				'fields'=>'User Name',
				'rules'=>'required|xss_clean',
				'form'=>array('type'=>'text', 'name'=>'user', 'id'=>'user', 'maxlength'=>'50', 'size'=>'10', 'class'=>'input-login')),
			'pass'=>array(
				'fields'=>'Password',
				'rules'=>'required',
				'form'=>array('type'=>'password', 'name'=>'pass', 'id'=>'pass', 'maxlength'=>'50', 'size'=>'10', 'class'=>'input-login')),
			);
		foreach($this->container as $key=>$array){
			$this->rules[$key]=$array['rules'];
			$this->fields[$key]=$array['fields'];	
			$this->forms[$key]=$array['form'];
		}
	}
	
	function index($sMessage=''){
		$this->load->library('parser');
		$this->load->library('validation');
		
		$this->load->helper('form');

		$this->validation->set_rules($this->rules);
		$this->validation->set_fields($this->fields);
		
		if ($sMessage==1) {
			$sMessage="Session Expired";
		} elseif ($sMessage==2) {
			$sMessage="Login tidak sesuai!";
		}
		
		$data=array('baseurl'		=> base_url(),
					'basesiteurl'	=> site_url(),
					'siteurl'		=> site_url().'/');
		if ($this->validation->run() == FALSE){
			$data['formstart']= form_open();
			$data['username']= form_input($this->forms['user']);
			$data['password']= form_input($this->forms['pass']);				
			$data['submit']	= form_submit('cmdlogin','Login');
			$data['formend']= form_close();
			$data['error_string']= $this->validation->error_string;
			$data['MESSAGES']= $sMessage;
			$this->parser->parse('login', $data);
		} 
	}
	
	function logout(){
		$this->session->sess_redirect();
	}
}
?>