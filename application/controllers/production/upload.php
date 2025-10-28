<?php
class Upload extends Controller {
	var $sUsername;
	var $sLevel;
	
	var $nRowsPerPage=20;
	var $sUploadPath='';
	var $sErrorMessage='';
	
	var $aDivision = array();
	
	function Upload(){
		parent::Controller();
		$this->load->library('session');	
		
		$this->sUsername=$this->session->userdata('s_username');
		$this->sLevel=$this->session->userdata('s_level');
		
		$this->load->model('Product_model');
		$this->load->model('Setup_model');
		$this->load->model('Util_model');
		
		$this->load->helper('url');
		$this->load->library('form');
		
		$this->load->library('parser');
		
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="message_error">', '</div>');
		$this->load->library('validatejs');
		
		$this->sUploadPath = './docs/'.date('Ymd');
		
		$this->aDivision = $this->config->item('division');
	}
	
	function index($sDivision, $sMessage=''){
		if ( (!$this->session->userdata('b_ag_transaction_read') || !$this->session->userdata('b_ag_transaction_write')) && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if ( (!$this->session->userdata('b_eg_transaction_read') || !$this->session->userdata('b_eg_transaction_write')) && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$nOffset=0;
		$nTotalRows=0;
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/production/upload/',
						'sDivision'			=> $sDivision,
						
						'MESSAGES'			=> '',
						'PAGE_TITLE'		=> 'SPMS-G. '.$this->aDivision[strtoupper($sDivision)].'/Upload from PDT',
						'toolCaption'		=> 'Upload Tool',
						
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						'nRowsPerPage'		=> $this->nRowsPerPage,
						'nTotalRows'		=> $nTotalRows,
						'nCurrOffset'		=> $nOffset);
		
		$aDisplay['viewToolbar'] = $this->load->view($sDivision.'/upload/upload_toolbar', $aDisplay, TRUE);
		
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse($sDivision.'/upload/upload', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function doupload($sDivision){
		if ( (!$this->session->userdata('b_ag_transaction_read') || !$this->session->userdata('b_ag_transaction_write')) && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if ( (!$this->session->userdata('b_eg_transaction_read') || !$this->session->userdata('b_eg_transaction_write')) && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$sElementName='f_file_name';
		$sAllowedType='txt';
		
		// get file name
		$sFileName = $_FILES[$sElementName]['name'];
		
		// get path result "./docs/200906/51516-56556-5626-2625/"
		$this->_forcePath($this->sUploadPath);
		
		$aConfig = array();
		$aConfig['upload_path'] = $this->sUploadPath;
		$aConfig['allowed_types'] = $sAllowedType;
		$aConfig['overwrite'] = TRUE;
		$aConfig['file_name'] = $sFileName;
		$this->load->library('upload', $aConfig);
		
		if($this->upload->do_upload($sElementName, $sFileName)){
			$aFileInfo = $this->upload->data();
			redirect("production/upload/uploaded/$sDivision/$sFileName");
		}else{
			$aFileInfo = $this->upload->data();
			$sErrorMessage = $this->upload->display_errors();
			redirect("production/upload/index/$sDivision/2");
		}
		
	}
	
	function uploaded($sDivision, $sFileName='', $aSerialError=array()){
		if ( (!$this->session->userdata('b_ag_transaction_read') || !$this->session->userdata('b_ag_transaction_write')) && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if ( (!$this->session->userdata('b_eg_transaction_read') || !$this->session->userdata('b_eg_transaction_write')) && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$nCount=0;
		$aDatas=array();
		$aData=array();
		
		if (count($aSerialError)>0 || $sFileName=='') {
			foreach ($aSerialError as $nCounter=>$aDataSerial) {
				$aData=array();
				$aData=$aDataSerial;
				$aData['s_update_by']=$this->sUsername;
				$aData['n_number']=$nCount;
				$nCount++;
				$aDatas[]=$aData;
			}
		} else {
			//$this->_readfile($sFileName);
			$aFileData=$this->_readfile($sFileName);
			foreach($aFileData as $sFileData){
				if (empty($sFileData)) continue;
				$aExData=explode(';', $sFileData);
				$aData['s_serial_no'] = $aExData[0];
				$nNextPos = 0;
				if (count($aExData) == 6) {
					$nNextPos = 1;
					$aData['s_serial_no_2'] = $aExData[0 + $nNextPos];
				} else {
					$aData['s_serial_no_2'] = '';
				}
				$aData['s_phase'] = $aExData[1 + $nNextPos];
				$aData['d_transaction_date'] = $aExData[2 + $nNextPos];
				$aData['s_update_by'] = $aExData[3 + $nNextPos];
				$aData['s_location'] = $aExData[4 + $nNextPos];
				$aData['n_number'] = $nCount;
				$nCount++;
				$bSetRow=true;
				if ( $this->input->post('s_serial_no_filter') && $bSetRow == true ) {
					if ( strpos($aData['s_serial_no'], $this->input->post('s_serial_no_filter')) !== false ) {
						$bSetRow = true;
					} else {
						$bSetRow = false;
					}
				}
				if ( $this->input->post('d_transaction_date_filter') && $bSetRow == true ) {
					if ( $aData['d_transaction_date'] == $this->input->post('s_lot_no_filter') ) {
						$bSetRow = true;
					} else {
						$bSetRow = false;
					}
				}
				if ( $this->input->post('s_phase_filter') && $bSetRow == true ) {
					if ( $aData['s_phase'] == $this->input->post('s_phase_filter') ) {
						$bSetRow = true;
					} else {
						$bSetRow = false;
					}
				}
				if ($bSetRow==true) $aDatas[] = $aData;
			}
		}
		
		$aDisplay=array('baseurl'			=> base_url(),
						'basesiteurl'		=> site_url(),
						'siteurl'			=> site_url().'/production/upload/',
						'sDivision'			=> $sDivision,
						
						'MESSAGES'			=> $this->sErrorMessage,
						'PAGE_TITLE'		=> 'SPMS-G. '.$this->aDivision[strtoupper($sDivision)].'/Data Uploaded',
						'toolCaption'		=> 'Upload Tool',
						'filterCaption'		=> 'Upload Filter/Search',
						
						'sFileName'			=> $sFileName,
						'sData'				=> $aDatas, 
						'sGlobalUserName'	=> $this->sUsername,
						'sGlobalUserLevel' 	=> $this->sLevel,
						's_serial_no_filter' 		=> $this->input->post('s_serial_no_filter'),
						'd_transaction_date_filter' => $this->input->post('d_transaction_date_filter') );
		
		$sales_batch_query = '';
		if ($this->session->userdata('b_'.strtolower($sDivision).'_sales_batch') == FALSE)
		{
			$sales_batch_query = " AND s_field_process <> 'd_process_14' ";
		}
		$aDisplay['s_phase_filter'] = $this->form->selectbox('tm_prod_setup', 's_phase, s_phase || \' - \' || s_description AS s_description', "s_division='".strtoupper($sDivision)."' AND n_line=1 AND s_field_process<>'d_warehouse' ".(!empty($sales_batch_query) ? $sales_batch_query : '')." ORDER BY n_order ASC, s_phase ASC", 's_phase', 's_description', $this->input->post('s_phase_filter'));
		
		$aDisplay['viewFilter'] = $this->load->view($sDivision.'/upload/list_filter', $aDisplay, TRUE);
		$aDisplay['viewToolbar'] = $this->load->view($sDivision.'/upload/list_toolbar', $aDisplay, TRUE);
		
		$this->parser->parse('header', $aDisplay);
		$this->parser->parse($sDivision.'/upload/list', $aDisplay);
		$this->parser->parse('footer', $aDisplay);
	}
	
	function saveupload($sDivision){
		if ( (!$this->session->userdata('b_ag_transaction_read') || !$this->session->userdata('b_ag_transaction_write')) && strtoupper($sDivision)=='AG') show_error('Access Denied');
		if ( (!$this->session->userdata('b_eg_transaction_read') || !$this->session->userdata('b_eg_transaction_write')) && strtoupper($sDivision)=='EG') show_error('Access Denied');
		
		$sMessages=1;
		$aSerialError=array();
		if (isset($_POST['uIdRow'])) {
			$prod_phases = array();
			$this->db->select('s_field_process, s_phase, s_description, n_order');
			$this->db->where('s_division', strtoupper($sDivision));
			$this->db->where('n_line', 1);
			$this->db->where('s_field_process <>', 'd_warehouse');
			if ($this->session->userdata('b_'.strtolower($sDivision).'_sales_batch') == FALSE)
			{
				$this->db->where('s_field_process <>', 'd_process_14');
			}
			$this->db->order_by('n_order', 'ASC');
			$this->db->order_by('s_phase', 'ASC');
			$this->db->group_by(array('s_field_process', 's_phase', 's_description', 'n_order'));
			$tm_prod_setup = $this->db->get('tm_prod_setup');
			$prod_setups = $tm_prod_setup->result();
			$aDataPhase = array();
			foreach ($prod_setups as $prod_setup)
			{
				$aDataPhase[$prod_setup->s_phase] = $prod_setup->s_field_process;
			}
			
			foreach($_POST['uIdRow'] as $n_number) {
				$aData = array();
				$bValid = ($this->input->post('bValidation') ? TRUE : FALSE);
				$sPhase = $this->input->post('s_phase:'.$n_number);
				$sSerialNo = trim($this->input->post('s_serial_no:'.$n_number));
				if (isset($aDataPhase[$sPhase])) {
					if (!empty($sSerialNo)) {
						$aData[$aDataPhase[$sPhase]] = trim($this->input->post('d_transaction_date:'.$n_number));
						if (strlen($aDataPhase[$sPhase]) >= 12)
							$process_length = 10;
						else
							$process_length = 9;
						$aData['s_'.(substr($aDataPhase[$sPhase], 2, $process_length)).'_update_by'] = trim($this->input->post('s_update_by:'.$n_number));
						$aData['s_'.(substr($aDataPhase[$sPhase], 2, $process_length)).'_location'] = trim($this->input->post('s_location:'.$n_number));
						if ($aDataPhase[$sPhase] == 'd_process_2' && strtoupper($sDivision) == 'EG') {
							$aData['s_serial_no_2'] = trim($this->input->post('s_serial_no_2:'.$n_number));
						}
						$aData['s_update_by'] = $this->sUsername;
						
						/* -- Check for accepted user to replacing production date -- */
						$is_found_error = FALSE;
						
						$tt_production_data = NULL;
						$is_production_exists = FALSE;
						$this->db->where('s_serial_no', strtoupper($sSerialNo));
						$tt_production = $this->db->get('tt_production');
						if ($tt_production->num_rows() > 0)
						{
							$tt_production_data = $tt_production->first_row();
							$product_phase_update = $aDataPhase[$sPhase];
							if ($tt_production_data->$product_phase_update != $aData[$product_phase_update])
								$is_production_exists = TRUE;
							
							if ($this->session->userdata('b_replace_production') == FALSE && $is_production_exists == TRUE)
							{
								$is_found_error = TRUE;
								$this->sErrorMessage .= $sSerialNo." production date has already exist, you can't update. ";
							}
							else
							{
								$sSerialNo = $this->Product_model->update($sSerialNo, $aData, strtoupper($sDivision), '', $bValid);
								if ($sSerialNo === FALSE)
								{
									$this->sErrorMessage .= trim($this->input->post('s_serial_no:'.$n_number)).' update failed. ';
									$is_found_error = TRUE;
								}
							}
						}
						else
						{
							$this->sErrorMessage .= $sSerialNo.' is not found. ';
							$is_found_error = TRUE;
						}
						
						if ($is_found_error == TRUE)
						{
							$aSerialError[] = array(
								's_serial_no'		=> trim($this->input->post('s_serial_no:'.$n_number)), 
								's_serial_no_2'		=> (isset($aData['s_serial_no_2']) ? $aData['s_serial_no_2'] : ''), 
								's_phase'			=> $sPhase,
								'd_transaction_date'=> trim($this->input->post('d_transaction_date:'.$n_number)),
								's_location'		=> trim($this->input->post('s_location:'.$n_number)));
						}
					}
				}
				else
				{
					$this->sErrorMessage .= $sSerialNo.':'.$sPhase.' not found or access denied. ';
					$aSerialError[] = array(
						's_serial_no'		=> trim($this->input->post('s_serial_no:'.$n_number)), 
						's_serial_no_2'		=> (isset($aData['s_serial_no_2']) ? $aData['s_serial_no_2'] : ''), 
						's_phase'			=> $sPhase,
						'd_transaction_date'=> trim($this->input->post('d_transaction_date:'.$n_number)),
						's_location'		=> trim($this->input->post('s_location:'.$n_number)));
				}
			}
		}
		if (count($aSerialError)) {
			//$this->sErrorMessage='Data Error! May Serial No is not found or production date is failed.';
			$this->uploaded($sDivision, '', $aSerialError);
		} else {
			redirect("production/upload/index/$sDivision/$sMessages");
		}
	}
	
	function _readfile($sFileName) {
		$aData = array();
		$fSource = fopen($this->sUploadPath.'/'.$sFileName, 'r');
		while (!feof($fSource)) {
			$aData[]= fgets($fSource);
		}
		fclose($fSource);
		return $aData;
	}
	
	function _forcePath($sPath){
		$sPathTemp=explode('/', $sPath);
		$sDirectori='';
		foreach($sPathTemp as $sDirTemp){
			if ($sDirTemp!='') {
				$sDirectori .= $sDirTemp.'/';
				if (!is_dir($sDirectori)) {
					mkdir($sDirectori);
				}
			}
		}
	}
}
?>