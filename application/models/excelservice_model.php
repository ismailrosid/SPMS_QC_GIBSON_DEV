<?php
class Excelservice_model extends Model{
   
    function Excelservice_model(){
		parent::Model();
	}
	
	function getList($sCriteria='', $nLimit=0, $nOffset=0, $aOrderby=array()) {
		$nAllRows=0;
		$this->load->model('Util_model');
		
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		$sOrderBy=Util_model::getOrderBy($aOrderby);
		$sLimit=($nLimit==0)?"":"LIMIT $nLimit";
		$sOffset=($nOffset==0)?"":"OFFSET $nOffset";
		$sLimitRows=$sLimit." ".$sOffset;
		
        $oQuery = $this->db->query("
			SELECT 	ttp.s_serial_no,
				tmc.s_name AS s_buyer_name,
				ttpo.s_po_no, 
				ttpo.s_po, 
				ttp.s_model AS s_model,
				tmm.s_smodel AS s_smodel,
				tmm.s_description AS s_model_name,
				tmm.s_upc_code,
				tmco.s_description AS s_color_name,
				ttp.s_lot_no AS s_lot_no
			FROM tt_production ttp 	INNER JOIN tt_prod_order ttpo ON (ttp.u_id_po_no = ttpo.u_id)
									LEFT JOIN tm_model tmm ON (ttp.s_model=tmm.s_code)
									LEFT JOIN tm_customer tmc ON (ttp.s_buyer=tmc.s_code)
									LEFT JOIN tm_color tmco ON (ttp.s_color=tmco.s_code)
			$sCriteria $sOrderBy $sLimitRows ");
		$aData=$oQuery->result_array();
		$aFields=array();
		$aFields['s_serial_no'] = array('name' => 'Serial No', 'format' => '', 'formula' => '');
		$aFields['s_buyer_name'] = array('name' => 'Buyer', 'format' => '', 'formula' => '');
		$aFields['s_po_no'] = array('name' => 'PI Number', 'format' => '', 'formula' => '');
		$aFields['s_po'] = array('name' => 'PO', 'format' => '', 'formula' => '');
		$aFields['s_model'] = array('name' => 'Model Code', 'format' => '', 'formula' => '');
		$aFields['s_model_Name'] = array('name' => 'Model Name', 'format' => '', 'formula' => '');
		$aFields['s_color_Name'] = array('name' => 'Color', 'format' => '', 'formula' => '');
		$aFields['s_smodel'] = array('name' => 'Item Code', 'format' => '', 'formula' => '');
		$aFields['s_upc_code'] = array('name' => 'UPC Code', 'format' => '', 'formula' => '');
		$aFields['s_lot_no'] = array('name' => 'Lot No', 'format' => '', 'formula' => '');
		
		if ($nOffset==0) {
			$nAllRows=$this->getDataCount();
		}
		
		$sXml=$this->getxml($aData, $aFields, $nAllRows);
		return $sXml;
	}
	
	function getDataCount($sCriteria='') {
		$nAllRows=0;
		
		$sCriteria=($sCriteria!='' ? " WHERE ".$sCriteria : '');
		
        $oQuery = $this->db->query("
			SELECT 	COUNT(ttp.s_serial_no) AS n_all_rows
			FROM 	tt_production ttp INNER JOIN tt_prod_order ttpo ON (ttp.u_id_po_no = ttpo.u_id)
			$sCriteria ");
		if ($oQuery->num_rows() > 0) {
			$oDataTable = $oQuery->first_row();
			$nAllRows = $oDataTable->n_all_rows;
		}
		
		return $nAllRows;
	}
	
	/* Last Update on August 04, 2009 16:47 */
	function getxml( $aData=array(), $aFields=array(), $nAllRows=0 ) {
		//Creates XML string and XML document using the DOM 
		$doc = new DomDocument('1.0'); 
		
		$root = $doc->createElement('root');
		$doc->appendChild($root); 
			// Header
			$header = $doc->createElement('header');
			$root->appendChild($header);
				foreach ($aFields as $sField=>$aProperties) {
					// Field
					$field = $doc->createElement('field');
					$header->appendChild($field);
					$field->appendChild($doc->createTextNode($aProperties['name']));
						$fieldAttribute = $doc->createAttribute('format');
						$field->appendChild($fieldAttribute);
						$fieldAttribute->appendChild($doc->createTextNode($aProperties['format'])); 
						
						$fieldAttribute = $doc->createAttribute('formula');
						$field->appendChild($fieldAttribute);
						$fieldAttribute->appendChild($doc->createTextNode($aProperties['formula'])); 
				}
			// Body
			$body = $doc->createElement('body');
			$root->appendChild($body);
				// Table
				$table = $doc->createElement('table');
				$body->appendChild($table);
						$tableName = $doc->createAttribute('name');
						$table->appendChild($tableName);
						$tableName->appendChild($doc->createTextNode('sheet1')); 
					foreach ($aData as $nRow=>$vData) {
						// Record 1
						$record = $doc->createElement('record');
						$table->appendChild($record);
								$recordId = $doc->createAttribute('id');
								$record->appendChild($recordId);
								$recordId->appendChild($doc->createTextNode($nRow)); 
							// Field 1
							foreach ($vData as $sField=>$sValue) {
								$recordField = $doc->createElement('field');
								$record->appendChild($recordField);
								$recordField->appendChild($doc->createTextNode($sValue));
							}
					}
		// save XML as string
		$sData = $doc->saveXML(); // put string in $sData 
		return $sData;
	}
}
