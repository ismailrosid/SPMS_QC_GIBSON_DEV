<table class="table" style="width:1400px;">
	<thead>
		<tr class="table_header">
			<th><a href="#" onClick="_doPost('sSort', 'd_production_date', 'bSortAction')">Month</a></th>
			<th><a href="#" onClick="_doPost('sSort', 'd_plan_date', 'bSortAction')">Production Plan Date (Input)</a></th>
			<th><a href="#" onClick="_doPost('sSort', 'd_delivery_date', 'bSortAction')">Production Plan Date (Output)</a></th>
			<th><a href="#" onClick="_doPost('sSort', 'd_target_date', 'bSortAction')">Export Plan Date</a></th>
			<th><a href="#" onClick="_doPost('sSort', 's_buyer_name', 'bSortAction')">Buyer</a></th>
			<th><a href="#" onClick="_doPost('sSort', 's_po_no', 'bSortAction')">PI Number</a></th>
			<th><a href="#" onClick="_doPost('sSort', 's_po', 'bSortAction')">PO</a></th>
			<th><a href="#" onClick="_doPost('sSort', 's_location', 'bSortAction')">Location</a></th>
			<th width="70px"><a href="#" onClick="_doPost('sSort', 'n_qty', 'bSortAction')">Qty</a></th>
			<th width="70px"><a href="#" onClick="_doPost('sSort', 'n_process_1', 'bSortAction')">WK Center Input</a></th>
			<th width="70px"><a href="#" onClick="_doPost('sSort', 'n_process_2', 'bSortAction')">WK Center Output - Body</a></th>
			<th width="70px"><a href="#" onClick="_doPost('sSort', 'n_process_3', 'bSortAction')">Wood Working</a></th>
			<th width="70px"><a href="#" onClick="_doPost('sSort', 'n_process_4', 'bSortAction')">Coating-I - Neck</a></th>
			<th width="70px"><a href="#" onClick="_doPost('sSort', 'n_process_5', 'bSortAction')">Sanding</a></th>
			<th width="70px"><a href="#" onClick="_doPost('sSort', 'n_process_6', 'bSortAction')">Coating-IIA</a></th>
			<th width="70px"><a href="#" onClick="_doPost('sSort', 'n_process_7', 'bSortAction')">Coating-IIB</a></th>
			<th width="70px"><a href="#" onClick="_doPost('sSort', 'n_process_8', 'bSortAction')">Assembly-I_Control Center</a></th>
			<th width="70px"><a href="#" onClick="_doPost('sSort', 'n_process_9', 'bSortAction')">Assembly-II</a></th>
			<th width="70px"><a href="#" onClick="_doPost('sSort', 'n_process_10', 'bSortAction')">Packing</a></th>
			<th width="70px"><a href="#" onClick="_doPost('sSort', 'n_warehouse', 'bSortAction')">Warehouse Incoming</a></th>
			<th width="70px"><a href="#" onClick="_doPost('sSort', 'n_process_14', 'bSortAction')">Warehouse Outgoing</a></th>
		</tr>
	</thead>
	<tbody>
	{tt_report_stock}
		<tr><td>{d_production_date}</td>
			<td>{d_plan_date}</td>
			<td>{d_delivery_date}</td>
			<td>{d_target_date}</td>
			<td>{s_buyer_name}</td>
			<td>{s_po_no}</td>
			<td>{s_po}</td>
			<td>{s_location}</td>
			<td align="right">{n_qty}</td>
			<td align="right">{n_process_1}</td>
			<td align="right">{n_process_2}</td>
			<td align="right">{n_process_3}</td>
			<td align="right">{n_process_4}</td>
			<td align="right">{n_process_5}</td>
			<td align="right">{n_process_6}</td>
			<td align="right">{n_process_7}</td>
			<td align="right">{n_process_8}</td>
			<td align="right">{n_process_9}</td>
			<td align="right">{n_process_10}</td>
			<td align="right">{n_warehouse}</td>
			<td align="right">{n_process_14}</td>
		</tr>
	{/tt_report_stock}
	</tbody>
	<tfoot class="table_footer">
	{tt_report_stock_total}
		<tr><td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td align="right">{n_t_qty}</td>
			<td align="right">{n_t_process_1}</td>
			<td align="right">{n_t_process_2}</td>
			<td align="right">{n_t_process_3}</td>
			<td align="right">{n_t_process_4}</td>
			<td align="right">{n_t_process_5}</td>
			<td align="right">{n_t_process_6}</td>
			<td align="right">{n_t_process_7}</td>
			<td align="right">{n_t_process_8}</td>
			<td align="right">{n_t_process_9}</td>
			<td align="right">{n_t_process_10}</td>
			<td align="right">{n_t_warehouse}</td>
			<td align="right">{n_t_process_14}</td>
		</tr>
	{/tt_report_stock_total}
	</tfoot>
</table>
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
