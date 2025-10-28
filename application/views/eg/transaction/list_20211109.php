<form id="frmEdit" name="frmEdit" method="post">
	<input type="hidden" name="s_phase_filter" id="s_phase_filter_2" value="{sPhaseName}">
	<table class="table">
		<thead>
			<tr class="table_header">
				<th width="10px" nowrap><input type="checkbox" id="scheckall" name="scheckall" onClick="_setAllChecked(this,0)" ></th>
				<th><a href="#" onClick="_doPost('sSort', 's_serial_no', 'bSortAction')">Serial No</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_po_no', 'bSortAction')">PI Number</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_po', 'bSortAction')">PO</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_order_date', 'bSortAction')">Receive Order</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_phase', 'bSortAction')">Phase</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_transaction_date', 'bSortAction')">Date</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_transaction_plan_date', 'bSortAction')">Plan Date</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_transaction_location', 'bSortAction')">Location</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_transaction_by', 'bSortAction')">Scaned By</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_production_date', 'bSortAction')">Production Date</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_plan_date', 'bSortAction')">Production Plan Date (Input)</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_delivery_date', 'bSortAction')">Production Plan Date (Output)</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_target_date', 'bSortAction')">Export Plan Date</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_lot_no', 'bSortAction')">Lot Number</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_buyer_name', 'bSortAction')">Buyer</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_model', 'bSortAction')">Model</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_model_name', 'bSortAction')">Model Name</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_color_name', 'bSortAction')">Color</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_smodel', 'bSortAction')">Item Code</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_quality', 'bSortAction')">Quality Status</a></th>
			</tr>
		</thead>
		<tbody>
			{tt_prod_product}
			<tr><td width="10px"><input type="checkbox" name="uIdRow[]" value="{s_serial_no}"></td>
				<td>{s_serial_no}</td>
				<td>{s_po_no}</td>
				<td>{s_po}</td>
				<td>{d_order_date}</td>
				<td>{s_phase}</td>
				<td>{d_transaction_date}</td>
				<td>{d_transaction_plan_date}</td>
				<td>{s_transaction_location}</td>
				<td>{s_transaction_by}</td>
				<td>{d_production_date}</td>
				<td>{d_plan_date}</td>
				<td>{d_delivery_date}</td>
				<td>{d_target_date}</td>
				<td>{s_lot_no}</td>
				<td>{s_buyer_name}</td>
				<td>{s_model}</td>
				<td>{s_model_name}</td>
				<td>{s_color_name}</td>
				<td>{s_smodel}</td>
				<td>{s_quality}</td>
			</tr>
			{/tt_prod_product}
		</tbody>
	</table>
</form>
Total {nTotalRows} rows
<script type="text/javascript">
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
