<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Readexcel{
	var $CI;
	
	function Readexcel() {
		$this->CI =& get_instance();
	}
	
	function getdata($temp_name, $key_header_begin='', $data_array=array(), $aPengaduanDataType=array()){
		$this->CI->load->helper('date');
		
		require_once 'excel_reader.php';
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');
		$data->read($temp_name);
		
		$header='';
		$bheader=0;
		$value=array();
		$i=1; $j=1; $iRowCount=1; $iCol=1;
		for($i=1; $i<=$data->sheets[0]['numRows']; $i++){
			if($bheader==0){
				for($j=1; $j<=$data->sheets[0]['numCols']; $j++){
					/* -- get header column -- */
					$header=strtolower(trim($data->sheets[0]['cells'][$i][$j]));
					if ( ($header <> '' && $header==strtolower($key_header_begin)) || $bheader==1 ){
						foreach($data_array as $key=>$field){
							if(strtolower(trim($key))==strtolower(trim($data->sheets[0]['cells'][$i][$j]))){
								$value['header'][$field] = $j;
								break;
							}
						}
						$bheader=1;
					}
				}
			}else{
				/* -- get data -- */
				foreach($data_array as $key=>$field){
					if(isset($value['header'][$field])){
						$iCol=$value['header'][$field];
						if(isset($data->sheets[0]['cells'][$i][$iCol])){
							if ( isset($aPengaduanDataType[$field]) ) {
								if ( $aPengaduanDataType[$field] == 'date' ) {
									$aDateData=$data->createDate($data->sheets[0]['cells'][$i][$iCol]);
									$value['data'][$field][$iRowCount] = date('Y-m-d', $aDateData[1]);
								} else {
									$value['data'][$field][$iRowCount] = $data->sheets[0]['cells'][$i][$iCol];
								}
							} else {
								$value['data'][$field][$iRowCount] = $data->sheets[0]['cells'][$i][$iCol];
							}	
						}else{
							$value['data'][$field][$iRowCount] = "";
						}
					}else{
						$value['data'][$field][$iRowCount] = "";
					}
				}
				$iRowCount++;
			}
		}
		
		$aResult=array();
		foreach ($value['data'][$key_header_begin] as $nRowNumber=>$sDataRow) {
			$aData=array();
			foreach ($value['header'] as $sHeader=>$nColNumber) {
				$aData[$sHeader]=$value['data'][$sHeader][$nRowNumber];
			}
			$aResult[]=$aData;
		}
		return $aResult;
	}
}
?>