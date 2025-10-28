<form method="post" id="form1" name="form1" onSubmit="ValidateForm('form1'); return false; " enctype="multipart/form-data">
	<table class="table" style="min-width:450px; width:600px;" align="left">
		<thead>
			<tr class="table_header">
				<th></th>
				<th>Field</th>
				<th>Code</th>
				<th>Process Name</th>
				<th>Type</th>
				<th>Line</th>
				<th>Order</th>
			</tr>
		</thead>
		<tbody>
			{tm_prod_setup}
			<tr><td width="10px"><input type="checkbox" name="uIdRow[]" value="{n_number}"></td>
				<td><select id="s_field_process:{n_number}" name="s_field_process:{n_number}">
						{s_field_process}
					</select>
				</td>
				<td><select id="s_type:{n_number}" name="s_type:{n_number}">
						{s_type}
					</select>
				</td>
				<td><input type="text" id="s_phase:{n_number}" name="s_phase:{n_number}" value="{s_phase}" maxlength="6"></td>
				<td><input type="text" id="s_description:{n_number}" name="s_description:{n_number}" value="{s_description}" maxlength="255"></td>
				<td><input type="text" id="n_line:{n_number}" name="n_line:{n_number}" value="{n_line}" maxlength="3" size="2"></td>
				<td><input type="text" id="n_order:{n_number}" name="n_order:{n_number}" value="{n_order}" maxlength="3" size="2"></td>
			</tr>
			{/tm_prod_setup}
		</tbody>
	</table>
</form>
<script type="text/javascript">
	{VALIDATE_JS}
</script>