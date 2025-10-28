<?php
class Excelservice extends Controller {
    var $nusoap_server;
    
    function Excelservice(){
        parent::Controller();
        
        $this->load->library("Nusoap_lib");
        $this->nusoap_server = new soap_server();
        $this->nusoap_server->configureWSDL("excelservice", $this->sNameSpace());
        $this->nusoap_server->wsdl->ports = array(
			'excelservicePort'=> array(
				"binding"  => "excelserviceBinding",
				"location" => $this->sNameSpace(),
				"bindingType"=> "http://schemas.xmlsoap.org/wsdl/soap/"
				)
		);
		
		$this->nusoap_server->register(
			'getdata',									// method name
			array(	'skey'		=> 'xsd:string',
					'sSerialNo1' => 'xsd:string',
					'sSerialNo2' => 'xsd:string',
					'nlimit'	=> 'xsd:int',
					'noffset'	=> 'xsd:int'),			// input parameters
			array(	'return' 	=> 'xsd:string'),		// output parameters
			"urn:excelservice",							// namespace
			"urn:".$this->sNameSpace()."/getdata",		// soapaction
			'rpc',										// style
			'encoded',									// use
			'Mendapatkan data'							// documentation
		);
		
		$this->nusoap_server->register(
			'getdatacount',								// method name
			array(	'skey'		=> 'xsd:string',
					'sSerialNo1' => 'xsd:string',
					'sSerialNo2' => 'xsd:string'),	// input parameters
			array(	'return' 	=> 'xsd:int'),			// output parameters
			"urn:dataservice",							// namespace
			"urn:".$this->sNameSpace()."/getdatacount",	// soapaction
			'rpc',										// style
			'encoded',									// use
			'Mendapatkan jumlah data'					// documentation
		);
		
		$this->nusoap_server->register(
			'login',									// method name
			array(	'skey'		=> 'xsd:string',
					'suser' 	=> 'xsd:string',
					'spassword' => 'xsd:string'),		// input parameters
			array(	'return' 	=> 'xsd:string'),		// output parameters
			"urn:dataservice",							// namespace
			"urn:".$this->sNameSpace()."/login",		// soapaction
			'rpc',										// style
			'encoded',									// use
			'Mendapatkan session id'					// documentation
		);
		
		$this->nusoap_server->register(
			'logout',									// method name
			array(	'skey'		=> 'xsd:string'),		// input parameters
			array(	'return' 	=> 'xsd:boolean'),		// output parameters
			"urn:dataservice",							// namespace
			"urn:".$this->sNameSpace()."/logout",		// soapaction
			'rpc',										// style
			'encoded',									// use
			'Logout/Clear Session'						// documentation
		);
		
		$this->nusoap_server->register(
			'sessioncheck',								// method name
			array(	'skey'		=> 'xsd:string'),		// input parameters
			array(	'return' 	=> 'xsd:string'),		// output parameters
			"urn:dataservice",							// namespace
			"urn:".$this->sNameSpace()."/sessioncheck",		// soapaction
			'rpc',										// style
			'encoded',									// use
			'Cek login dan kembalikan session id jika sudah login'	// documentation
		);
		
		function getdata($sKey, $sSerialNo1, $sSerialNo2, $nLimit, $nOffset){
			$sData='';
			if($sKey=='e2mdiq8hcbzorpfj9t56ds047sa0476jkqyrpowlfj'){
				$CI =& get_instance();
				$CI->load->library('session');
				$CI->load->model('excelservice_model');
				$sCriteria='';
				if ($sSerialNo1!='' && $sSerialNo2!='') {
					$sCriteria=" (UPPER(ttp.s_serial_no) BETWEEN UPPER('$sSerialNo1') AND UPPER('$sSerialNo2')) ";
				} 
				$aSort = array('s_serial_no' => 'asc');
				$sData = $CI->excelservice_model->getList($sCriteria, $nLimit, $nOffset, $aSort);
			}
			return $sData;
		}
		
		function getdatacount($sKey, $sSerialNo1, $sSerialNo2){
			$nData=0;
			if($sKey=='e2mdiq8hcbzorpfj9t56ds047sa0476jkqyrpowlfj'){
				$CI =& get_instance();
				$CI->load->library('session');
				$CI->load->model('excelservice_model');
				$sCriteria='';
				if ($sSerialNo1!='' && $sSerialNo2!='') {
					$sCriteria=" (UPPER(ttp.s_serial_no) BETWEEN UPPER('$sSerialNo1') AND UPPER('$sSerialNo2')) ";
				} 
				$nData = $CI->excelservice_model->getDataCount($sCriteria);
			}
			return $nData;
		}
		
		function login($sKey, $sUser, $sPassword) {
			$sSessionId='';
			if($sKey=='e2mdiq8hcbzorpfj9t56ds047sa0476jkqyrpowlfj'){
				$CI =& get_instance();
				$_POST['user']=$sUser;
				$_POST['pass']=$sPassword;
				$CI->load->library('session');
				$sSessionId = $CI->session->userdata('session_id');
			}
			return $sSessionId;
		}
		
		function logout($sKey) {
			$bResult=false;
			if($sKey=='e2mdiq8hcbzorpfj9t56ds047sa0476jkqyrpowlfj'){
				$CI =& get_instance();
				$CI->load->library('session');
				$CI->session->sess_destroy();
				$bResult=true;
			}
			return $bResult;
		}
		
		function sessioncheck($sKey) {
			$sSessionId='';
			if($sKey=='e2mdiq8hcbzorpfj9t56ds047sa0476jkqyrpowlfj'){
				$CI =& get_instance();
				$CI->load->library('session');
				$sSessionId = $CI->session->userdata('session_id');
			}
			return $sSessionId;
		}
    }
    
    function index(){
        $this->nusoap_server->service(file_get_contents("php://input"));    
    }
	
    function webservice(){
        if($this->uri->rsegment(3) == "wsdl") {
                $_SERVER['QUERY_STRING'] = "wsdl";
            } else {
                $_SERVER['QUERY_STRING'] = "";
            }
        $this->nusoap_server->service(file_get_contents("php://input"));    
    }
	
	function sNameSpace() {
		$this->load->helper('url');
		return site_url().'/excelservice';
	}
}
?>
