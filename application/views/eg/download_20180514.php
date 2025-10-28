<form id="frmEdit" name="frmEdit" method="post" onKeyPress="_onPressEnter(this, event)">
	<table class="table">
		<thead>
			<tr class="table_header">
				<th><a href="#" onClick="_doPost('sSort', 's_serial_no', 'bSortAction')">Serial No</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_po_no', 'bSortAction')">PI Number</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_po', 'bSortAction')">PO</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_order_date', 'bSortAction')">Receive Order</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_production_date', 'bSortAction')">Production Date</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_plan_date', 'bSortAction')">Production Plan Date (Input)</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_delivery_date', 'bSortAction')">Production Plan Date (Output)</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_target_date', 'bSortAction')">Export Plan Date</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_lot_no', 'bSortAction')">Lot Number</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_buyer_name', 'bSortAction')">Buyer</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_brand', 'bSortAction')">Brand</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_bench', 'bSortAction')">Bench Mark</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_model_name', 'bSortAction')">Model</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_color_name', 'bSortAction')">Color</a></th>
			</tr>
		</thead>
		<tbody>
			{tt_prod_product}
			<tr><td>{s_serial_no}</td>
				<td>{s_po_no}</td>
				<td>{s_po}</td>
				<td>{d_order_date}</td>
				<td>{d_production_date}</td>
				<td>{d_plan_date}</td>
				<td>{d_delivery_date}</td>
				<td>{d_target_date}</td>
				<td>{s_lot_no}</td>
				<td>{s_buyer_name}</td>
				<td>{s_brand}</td>
				<td>{s_bench}</td>
				<td>{s_model_name}</td>
				<td>{s_color_name}</td>
			</tr>
			{/tt_prod_product}
		</tbody>
	</table>
</form>
<div id='catalogPagination' class="pagination"></div> Total {nTotalRows} rows
<script type="text/javascript">
	$(document).ready(function(){
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
			 current_page:tmpCurrentPageIndex,
			 num_display_entries:10,
			 num_edge_entries:2}
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
</script>
