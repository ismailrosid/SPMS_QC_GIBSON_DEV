<form id="frmEdit" name="frmEdit" method="post">
	<input type="hidden" name="d_transaction_date" id="d_transaction_date"/>
	<input type="hidden" name="s_product_location" id="s_product_location"/>
	
	<table class="table" style="width:1550px;">
		<thead>
			<tr class="table_header">
				<th width="10px" nowrap><input type="checkbox" id="scheckall" name="scheckall" onClick="_setAllChecked(this,0)" ></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_production_date', 'bSortAction')">Month</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_plan_date', 'bSortAction')">Production Plan Date (Input)</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_delivery_date', 'bSortAction')">Production Plan Date (Output)</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_target_date', 'bSortAction')">Export Plan Date</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_serial_no', 'bSortAction')">Serial No</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_buyer_name', 'bSortAction')">Buyer</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_po_no', 'bSortAction')">PI Number</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_po', 'bSortAction')">PO</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_lot_no', 'bSortAction')">Lot No</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_model_name', 'bSortAction')">Model</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_color_name', 'bSortAction')">Color</a></th>
				
				<th width="70px"><a href="#" onClick="_doPost('sSort', 'd_warehouse', 'bSortAction')">Warehouse Incoming</a></th>
				<th width="70px"><a href="#" onClick="_doPost('sSort', 'd_process_10', 'bSortAction')">Packing</a></th>
				<th width="70px"><a href="#" onClick="_doPost('sSort', 'd_process_1', 'bSortAction')">WK Center Input</a></th>
				<th width="70px"><a href="#" onClick="_doPost('sSort', 'd_process_2', 'bSortAction')">WK Center Output - Body</a></th>
				<th width="70px"><a href="#" onClick="_doPost('sSort', 'd_process_3', 'bSortAction')">Wood Working</a></th>
				<th width="70px"><a href="#" onClick="_doPost('sSort', 'd_process_4', 'bSortAction')">Coating-I - Neck</a></th>
				<th width="70px"><a href="#" onClick="_doPost('sSort', 'd_process_5', 'bSortAction')">Sanding</a></th>
				<th width="70px"><a href="#" onClick="_doPost('sSort', 'd_process_6', 'bSortAction')">Coating-IIA</a></th>
				<th width="70px"><a href="#" onClick="_doPost('sSort', 'd_process_7', 'bSortAction')">Coating-IIB</a></th>
				<th width="70px"><a href="#" onClick="_doPost('sSort', 'd_process_8', 'bSortAction')">Assembly-I_Control Center</a></th>
				<th width="70px"><a href="#" onClick="_doPost('sSort', 'd_process_9', 'bSortAction')">Assembly-II</a></th>
				
			</tr>
		</thead>
		<tbody>
<?php 	foreach($tt_report_stock as $nIndex=>$aValue){?>
			<tr><td width="10px"><input type="checkbox" name="uIdRow[]" value="<?=$aValue['s_serial_no']?>"></td>
				<td><?=$aValue['d_production_date']?></td>
				<td><?=$aValue['d_plan_date']?></td>
				<td><?=$aValue['d_delivery_date']?></td>
				<td><?=$aValue['d_target_date']?></td>
				<td><?=$aValue['s_serial_no']?></td>
				<td><?=$aValue['s_buyer_name']?></td>
				<td><?=$aValue['s_po_no']?></td>
				<td><?=$aValue['s_po']?></td>
				<td><?=$aValue['s_lot_no']?></td>
				<td><?=$aValue['s_model_name']?></td>
				<td><?=$aValue['s_color_name']?></td>
				<td align="center"><?=($aValue['d_warehouse'] == '1900-01-01' ? '####-##-##' : $aValue['d_warehouse'])?></td>
				<td align="center"><?=($aValue['d_process_10'] == '1900-01-01' ? '####-##-##' : $aValue['d_process_10'])?></td>
				<td align="center"><?=($aValue['d_process_1'] == '1900-01-01' ? '####-##-##' : $aValue['d_process_1'])?></td>
				<td align="center"><?=($aValue['d_process_2'] == '1900-01-01' ? '####-##-##' : $aValue['d_process_2'])?></td>
				<td align="center"><?=($aValue['d_process_3'] == '1900-01-01' ? '####-##-##' : $aValue['d_process_3'])?></td>
				<td align="center"><?=($aValue['d_process_4'] == '1900-01-01' ? '####-##-##' : $aValue['d_process_4'])?></td>
				<td align="center"><?=($aValue['d_process_5'] == '1900-01-01' ? '####-##-##' : $aValue['d_process_5'])?></td>
				<td align="center"><?=($aValue['d_process_6'] == '1900-01-01' ? '####-##-##' : $aValue['d_process_6'])?></td>
				<td align="center"><?=($aValue['d_process_7'] == '1900-01-01' ? '####-##-##' : $aValue['d_process_7'])?></td>
				<td align="center"><?=($aValue['d_process_8'] == '1900-01-01' ? '####-##-##' : $aValue['d_process_8'])?></td>
				<td align="center"><?=($aValue['d_process_9'] == '1900-01-01' ? '####-##-##' : $aValue['d_process_9'])?></td>
			</tr>
<?php 	}?>
		</tbody>
	</table>
</form>
<div id='catalogPagination' class="pagination"></div> Total {nTotalRows} rows
<script type="text/javascript">
	$(document).ready(function(){
		//$('#menu').flickrmenu();
        //$('ul.sf-menu').superfish();
    
		var isFirstTime = 1;
		
		// First Parameter: number of items
		// Second Parameter: options object
		var myPageIndex = 1;
		var itemsPerPage = {nRowsPerPage};
		var currentOffset = {nCurrOffset};
		var limit = {nRowsPerPage};
		var currentNode = "";
		var totalItems = {nTotalRows};
		var tmpCurrentPageIndex = currentOffset/itemsPerPage;
		
		$("#catalogPagination").pagination(totalItems, 
			{items_per_page:itemsPerPage, 
			 callback:handlePaginationClick, 
			 current_page:tmpCurrentPageIndex}
		);
	
		function handlePaginationClick(new_page_index, pagination_container) {
			if(!isFirstTime)
			{
				myPageIndex = new_page_index;
				var nextOffset = (itemsPerPage * myPageIndex);
				$("#nOffset").val(nextOffset);
				$("#nLimit").val(limit+currentNode);
				
				$("Offset").val(nextOffset);
				_doPost();
			}
			isFirstTime = 0;
			return false;
		}
	});
	
	function _onPressEnter(fForm, eEvent) {
		var nCode;
		if (!eEvent) var eEvent = window.event;
		if (eEvent.keyCode) 
			nCode = eEvent.keyCode;
		else if (eEvent.which) 
			nCode = eEvent.which;
		if (nCode==13) fForm.submit();
	}
	
	function _onExportAction()
	{
		var d_transaction_date_input = $('#d_transaction_date_input').val();
		if (d_transaction_date_input != '')
		{
			$('#d_transaction_date').val(d_transaction_date_input);
			$('#s_product_location').val($('#s_product_location_input').val());
			
			_doPost(null, null, null, 'frmEdit', '<?=$siteurl?>export_action/<?=$sDivision?>/', true, 'Are you sure you want to export the selected item ?');
		}
		else
		{
			alert('Please insert the Export date');
		}
	}
</script>