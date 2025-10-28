<table class="table">
	{tt_report_stock_total}
	<thead>
		<tr class="table_header">
			<th width="120px" align="right">Past Stock</th>
			<th width="80px" align="right">{n_t_first_stock}</th>
			<th align="right">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td align="right">On Progress</td>
			<td align="right">{n_t_on_progress}</td>
			<th align="right">&nbsp;</th>
		</tr>
		<tr>
			<td align="right">In</td>
			<td align="right">{n_t_in}</td>
			<th align="right">&nbsp;</th>
		</tr>
		<tr>
			<td align="right">Out</td>
			<td align="right">{n_t_out}</td>
			<th align="right">&nbsp;</th>
		</tr>
	</tbody>
	<tfoot class="table_footer">
		<tr><td align="right">Last Stock</td>
			<td align="right">{n_t_last_stock}</td>
			<th align="right">&nbsp;</th>
		</tr>
	</tfoot>
	{/tt_report_stock_total}
</table>
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