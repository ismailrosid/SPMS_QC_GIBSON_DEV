<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Docfile{
	var $sTableName;
	var $sTtDocFileCat;
	var $sTmDocFileCat;
	var $CI;
	var $aField;
	
	var $sCriteria;
	var $getCurrentTotal;
	var $bCustomCriteria;
	
	function Docfile(){
		$this->CI =& get_instance();
		$this->CI->load->database();
		
		$this->sTableName = 'tt_document';
		$this->sTtDocFileCat = 'tt_docfilecat';
		$this->sTmDocFileCat = 'tm_document_category';
	
		$this->aField = array(	'uploadCategory' 	=> $this->CI->input->post('uploadCategory'),
								'uploadTitle'		=> $this->CI->input->post('uploadTitle'),
								'uploadDescription'	=> $this->CI->input->post('uploadDescription') );
		
    }
	
	function doUpload($sRefId='', $sCategory='', $sElementName='uploadFile', $sFileName='', $sTitle='', $sDescription=''){
		$uId = $this->_getUuid();
		$sAllowedType='txt|xls|zip|doc|pdf|gif|jpg|png|xlsx';
		if($sFileName==''){
			// get file name
			$sFileName = $_FILES[$sElementName]['name'];
			// get extention position
			$nPos = strrpos($sFileName, '.');
			// get extention
			$sFileExt = explode('.', $sFileName);
			$sAllowedType = end($sFileExt).'|'.$sAllowedType;
			$sFileExt = '.'.end($sFileExt);
			// get file name without extention
			$sFileName = substr($sFileName, 0, $nPos);
			// max length file name 200
			$sFileName = substr($sFileName, 0, 200);
			// combine file name + uuid + extention
			$sFileName = $sFileName.$uId;
		}
		
		$sPathRefId = '';
		if ($sRefId!='') $sPathRefId = $sRefId.'/';
		
		// set data for save to DB
		if ( empty($sCategory) ) $sCategory = $this->aField['uploadCategory'];
		if ( empty($sTitle) ) $sTitle = $this->aField['uploadTitle'];
		if ( empty($sDescription) ) $sDescription = $this->aField['uploadDescription'];
		
		// get path result "./docs/200906/51516-56556-5626-2625/"
		$sUploadPath = './docs/'.date('Ym').'/'.$sPathRefId;
		$this->_forcePath($sUploadPath);
		
		$aConfig = array();
		$aConfig['upload_path'] = $sUploadPath;
		$aConfig['allowed_types'] = $sAllowedType;
		$aConfig['overwrite'] = TRUE;
		$aConfig['file_name'] = $sFileName;
		$this->CI->load->library('upload', $aConfig);
		
		if($this->CI->upload->do_upload($sElementName, $sFileName)){
			$aFileInfo = $this->CI->upload->data();
			
			$this->CI->db->set('id', $uId);
			$this->CI->db->set('uRefId', $sRefId);
			$this->CI->db->set('sFilename', $aFileInfo['file_name']);
			$this->CI->db->set('sPath', $sUploadPath);
			$this->CI->db->set('sCategory', $sCategory);
			$this->CI->db->set('sJudul', $sTitle);
			$this->CI->db->set('sKeterangan', $sDescription);
			
			$this->CI->db->set('dCreateTime', 'NOW()', FALSE);
			$this->CI->db->set('dLastUpdate', 'NOW()', FALSE);
			
			$this->CI->db->insert($this->sTableName);
		
			if ( is_array($sCategory) ) {
				foreach ($sCategory as $sCat) {
					$this->newCategory($sCat);
					$this->setFileCategory($uId, $sCat);
				}
			} elseif ( !empty($sCategory) ) {
				$this->newCategory($sCategory);
				$this->setFileCategory($uId, $sCategory);
			}
			
			return TRUE;
		}else{
			$aFileInfo = $this->CI->upload->data();
			$sErrorMessage = $this->CI->upload->display_errors();
			return $sErrorMessage;
		}
	}
	
	function createForm($sFunctionUrl, $sRefId='', $sCategory=''){
		$this->CI->load->helper('form');
		
		$sUploadForm = form_open_multipart($sFunctionUrl, array('name' => 'frmUpload', 'id' => 'frmUpload'));
		
		$sUploadForm .= form_hidden('uploadCategory', $sCategory);
		
		$sUploadForm .= form_label('File', 'uploadFile', array('class' => 'uploadElement'));
		$sUploadForm .= form_upload(array('name' => 'uploadFile', 'id' => 'uploadFile', 'class' => 'uploadElement'));
		$sUploadForm .= "<br>";
		
		$sUploadForm .= form_label('Title', 'uploadTitle', array('class' => 'uploadElement'));
		$sUploadForm .= form_input(array('name' => 'uploadTitle', 'id' => 'uploadTitle', 'class' => 'uploadElement', 'size' => '40'));
		$sUploadForm .= "<br>";
		
		$sUploadForm .= form_label('Description', 'uploadDescription', array('class' => 'uploadElement'));
		$sUploadForm .= form_textarea(array('name' => 'uploadDescription', 'id' => 'uploadDescription', 'class' => 'uploadElement', 'rows' => '3', 'cols' => '30'));
		$sUploadForm .= "<br>";
		
		$sUploadForm .= form_submit('uploadPost', 'Upload');
		
		$sUploadForm .= form_close();
		
		return $sUploadForm;
	}
	
	function newCategory($sCategory){
		$this->CI->db->where('sCategory', $sCategory); 
		$tmDocFileCat=$this->CI->db->get($this->sTmDocFileCat);
		if ($tmDocFileCat->num_rows()==0) {
			$this->CI->db->set('sCategory', $sCategory);
			$this->CI->db->insert($this->sTmDocFileCat);
		}
	}
	
	function setFileCategory($uFileId, $sCategory){
		$this->CI->db->where('sCategory', $sCategory); 
		$tmDocFileCat=$this->CI->db->get($this->sTmDocFileCat);
		if ($tmDocFileCat->num_rows()>0) {
			$dataCategory = $tmDocFileCat->first_row();
			$this->CI->db->set('id', $this->_getUuid());
			$this->CI->db->set('sIdDocFile', $uFileId);
			$this->CI->db->set('sCategory', $dataCategory->sCategory);
			$this->CI->db->insert($this->sTtDocFileCat);
		}
	}
	
	function removeFile($uId='', $uRefId=''){
		$this->CI->load->helper('file');
		if ($uId!='') {
			$this->CI->db->where('id', $uId); 
			$docFile=$this->CI->db->get($this->sTableName);
			if ($docFile->num_rows()>0) {
				$dataFile = $docFile->first_row();
				$this->CI->db->delete($this->sTableName, array('id' => $uId));
				unlink($dataFile->sPath.$dataFile->sFilename);
			}
			return true;
		} 
		if ($uRefId!='') {
			$this->CI->db->where('uRefId', $uRefId); 
			$docFile=$this->CI->db->get($this->sTableName);
			if ($docFile->num_rows()>0) {
				$dataFile = $docFile->first_row();
				$this->CI->db->delete($this->sTableName, array('uRefId' => $uRefId));
				delete_files($dataFile->sPath, TRUE);
				if (is_dir($dataFile->sPath)) {
					$bRmDir = rmdir($dataFile->sPath);
				}
			}
			return true;
		}
	}
	
	function getFile($uId){
		$this->CI->load->helper('download');
		
		$this->CI->db->where('id', $uId); 
		$ttDocFile=$this->CI->db->get($this->sTableName);
		if ($ttDocFile->num_rows()>0) {
			$dataDocFile = $ttDocFile->first_row();
			force_download($dataDocFile->sFilename, file_get_contents($dataDocFile->sPath.$dataDocFile->sFilename));
			return true;
		} else {
			return false;
		}
	}
	
	function getInfo($sRefId='', $sCategory=''){
		if ( is_array($sRefId) ) {
			$this->CI->db->where_in($this->sTableName.'.uRefId', $sRefId);
		} elseif (!empty($sRefId)) {
			$this->CI->db->where($this->sTableName.'.uRefId', $sRefId); 
		}
		if ( is_array($sCategory) ) {
			$this->CI->db->where_in($this->sTtDocFileCat.'.sCategory', $sCategory);
		} elseif (!empty($sCategory)) {
			$this->CI->db->where($this->sTtDocFileCat.'.sCategory', $sCategory); 
		}
		$this->CI->db->select($this->sTableName.'.*');
		$this->CI->db->from($this->sTableName);
		$this->CI->db->join($this->sTtDocFileCat, $this->sTableName.'.id='.$this->sTtDocFileCat.'.sIdDocFile', 'left');
		$this->CI->db->order_by($this->sTableName.'.sCategory', 'asc');
		$this->CI->db->order_by($this->sTableName.'.sFilename', 'asc');
		$docFile=$this->CI->db->get();
		
		$nTotal=$docFile->num_rows();
		$this->_setCurrentTotal($nTotal);
		
		return $docFile;
	}
	
	function getListJoin($sTable, $sKey='id', $sTypeJoin='inner', $sCriteria='',$sRefId='',$sCategory=''){
		if ( is_array($sRefId) ) {
			$this->CI->db->where_in($this->sTableName.'.uRefId', $sRefId);
		} elseif (!empty($sRefId)) {
			$this->CI->db->where($this->sTableName.'.uRefId', $sRefId); 
		}
		if ( is_array($sCategory) ) {
			$this->CI->db->where_in($this->sTtDocFileCat.'.sCategory', $sCategory);
		} elseif (!empty($sCategory)) {
			$this->CI->db->where($this->sTtDocFileCat.'.sCategory', $sCategory); 
		}		
		if($sCriteria<>''){			
			$this->CI->db->where($sCriteria);
		}
		
		$this->CI->db->select('*');
		$this->CI->db->from($sTable);
		$this->CI->db->join($this->sTableName, $this->sTableName.".uRefId=$sTable.$sKey", $sTypeJoin);
		$this->CI->db->join($this->sTtDocFileCat, $this->sTableName.'.id='.$this->sTtDocFileCat.'.sIdDocFile', 'left');
		$docFile=$this->CI->db->get();
		
		$this->bCustomCriteria = FALSE;
		
		$nTotal=$docFile->num_rows();
		$this->_setCurrentTotal($nTotal);
		
		return $docFile;
	}
	
	function getList($sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array(), $aFields=array()){
		$this->sCriteria = $sCriteria;
		if(count($aOrderby)>0){
			foreach($aOrderby as $sFieldOrder=>$sOrderMethod){
				$this->db->order_by($sFieldOrder,$sOrderMethod);
			}
		}
		if(count($aFields)>0)
			$this->db->select(implode(',',$aFields));
		
		if($this->sCriteria==''){
			if($nLimit==0 && $nOffset==0){
				$docFile = $this->db->get($this->sTableName);
			}else{																
				$docFile=$this->db->get($this->sTableName, $nLimit, $nOffset);
			}	
		}else{
			if($nLimit==0 && $nOffset==0){
				$docFile = $this->db->get_where($this->sTableName, $this->sCriteria);
			}else{																
				$docFile = $this->db->get_where($this->sTableName, $this->sCriteria, $nLimit, $nOffset);
			}								
		}
		
		$this->bCustomCriteria = TRUE;
		
		$nTotal=$docFile->num_rows();
		$this->_setCurrentTotal($nTotal);
		
		return $docFile;
	}
	
	function getRowTotal($sCriteria='', $sTable=''){
		if ($this->bCustomCriteria == TRUE) {
			$nTotal = 0;
			
			if ($sCriteria=='') $sCriteria = $this->sCriteria;
			if ($sTable=='') $sTable = $this->sTableName;
			
			$this->CI->db->select("COUNT(id) AS nCount");
			$oTable = $this->db->get_where($sTable, $sCriteria);
			if ($oTable->num_rows()>0) {
				$dTable = $oTable->first_row();
				$nTotal = $dTable->nCount;
			}
			
			return $nTotal;
		} else {
			return $this->getCurrentTotal;
		}
	}
	
	function _setCurrentTotal($nTotal) {
		$this->getCurrentTotal = $nTotal;
	}
	
	function _getUuid(){
		$this->CI =& get_instance();
		$this->CI->load->model('Util_model');
		$uId=Util_model::getUuid();
		return $uId;
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