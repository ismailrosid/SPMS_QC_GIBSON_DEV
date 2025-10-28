<form id="frmEdit" name="frmEdit" method="post" action="{siteurl}uploaded/{sDivision}/{sFileName}" onKeyPress="_onPressEnter(this, event)">
	{MESSAGES}
	<div style="text-align:left;">
		<input type="checkbox" id="bValidation" name="bValidation" value="1" checked><label for="bValidation" id="lblValidation">Set Validation</label>
	</div>
	<table class="table" style="width:500px;" align="left">
		<thead>
			<tr class="table_header">
				<th width="10px" nowrap><input type="checkbox" id="scheckall" name="scheckall" onClick="_setAllChecked(this,0)" ></th>
				<th><a href="#" onClick="_doPost('sSort', 's_serial_no', 'bSortAction')">Serial No</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_phase', 'bSortAction')">Phase</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_transaction_date', 'bSortAction')">Date</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_update_by', 'bSortAction')">Update By</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_location', 'bSortAction')">Location</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_quality', 'bSortAction')">Quality</a></th>
			</tr>
		</thead>
		<tbody>
		{sData}
			<tr><td width="10px"><input type="checkbox" name="uIdRow[]" value="{n_number}"></td>
				<td><input type="text" id="s_serial_no:{n_number}" name="s_serial_no:{n_number}" value="{s_serial_no}" size="21" maxlength="21"></td>
				<td><input type="text" id="s_phase:{n_number}" name="s_phase:{n_number}" value="{s_phase}" size="6" maxlength="6"></td>
				<td><input type="text" id="d_transaction_date:{n_number}" name="d_transaction_date:{n_number}" value="{d_transaction_date}" size="7" maxlength="10">
					<input class="button" type="reset" value="..." onclick="return showCalendar('d_transaction_date:{n_number}');" name="cldd_transaction_date:{n_number}"/>
				</td>
				<td>{s_update_by}
					<input type="hidden" id="s_update_by:{n_number}" name="s_update_by:{n_number}" value="{s_update_by}" maxlength="12">
				</td>
				<td><input type="text" id="s_location:{n_number}" name="s_location:{n_number}" value="{s_location}" size="15" maxlength="10"></td>
				<td><input type="text" id="s_quality:{n_number}" name="s_quality:{n_number}" value="{s_quality}" size="50" maxlength="15"></td>

			</tr>
		{/sData}
		</tbody>
	</table>
</form>
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
