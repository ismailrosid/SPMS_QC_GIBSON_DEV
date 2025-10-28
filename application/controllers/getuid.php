<?php

class Getuid extends Controller {
	function Getuid(){
		parent::Controller();
		$this->load->library('session');	

		$this->load->model('Util_model');
	}
	
	function index($sMessage=''){
		for ($i=1; $i<=254; $i++) {
			echo $this->Util_model->getUuid()."<br>";
		}
	}
}
?>