<form method="post" id="frmEdit" name="frmEdit" action="{formaction}" enctype="multipart/form-data">
	{MESSAGES}
	{editable}
	<table align="left">
		<tr><td valign="top">
				<table class="form_view" width="310px">
					<thead>
						<tr><th colspan="2" class="table_header">Company Info</th></tr>
					</thead>
					<tbody>
						<tr><td>Company Name</td>
							<td><input type="text" id="s_company_name" name="s_company_name" value="{s_company_name}" maxlength="255"></td>
						</tr>
						<tr><td>Address</td>
							<td><textarea cols="30" rows="3" id="s_address" name="s_address">{s_address}</textarea></td>
						</tr>
						<tr><td>City</td>
							<td><input type="text" id="s_city" name="s_city" value="{s_city}" maxlength="255"></td>
						</tr>
						<tr><td>Province</td>
							<td><input type="text" id="s_province" name="s_province" value="{s_province}" maxlength="255"></td>
						</tr>
						<tr><td>Country</td>
							<td><input type="text" id="s_country" name="s_country" value="{s_country}" maxlength="255"></td>
						</tr>
						<tr><td>NPWP</td>
							<td><input type="text" id="s_npwp" name="s_npwp" value="{s_npwp}" maxlength="255"></td>
						</tr>
					</tbody>
				</table>
			</td>
			<td valign="top">
				<table class="form_view" width="300px">
					<thead>
						<tr><th colspan="2" class="table_header">&nbsp;</th></tr>
					</thead>
					<tbody>
						<tr><td>Po Box</td>
							<td><input type="text" id="s_pobox" name="s_pobox" value="{s_pobox}" maxlength="255"></td>
						</tr>
						<tr><td>Phone 1</td>
							<td><input type="text" id="s_phone1" name="s_phone1" value="{s_phone1}" maxlength="255"></td>
						</tr>
						<tr><td>Phone 2</td>
							<td><input type="text" id="s_phone2" name="s_phone2" value="{s_phone2}" maxlength="255"></td>
						</tr>
						<tr><td>Fax</td>
							<td><input type="text" id="s_fax" name="s_fax" value="{s_fax}" maxlength="255"></td>
						</tr>
						<tr><td>Email</td>
							<td><input type="text" id="s_email1" name="s_email1" value="{s_email1}" maxlength="255"></td>
						</tr>
						<tr><td>Email CS</td>
							<td><input type="text" id="s_email2" name="s_email2" value="{s_email2}" maxlength="255"></td>
						</tr>
						<tr><td>Home Page</td>
							<td><input type="text" id="s_website" name="s_website" value="{s_website}" maxlength="255"></td>
						</tr>
					</tbody>
				</table>
			</td>
			<td valign="top">
				<table class="form_view" width="300px">
					<thead>
						<tr><th colspan="2" class="table_header">Settings</th></tr>
					</thead>
					<tbody>
						<tr><td>AG First Stock</td>
							<td><input type="text" id="n_stock_ag" name="n_stock_ag" value="{n_stock_ag}" maxlength="9" size="5" style="text-align:right;"></td>
						</tr>
						<tr><td>EG First Stock</td>
							<td><input type="text" id="n_stock_eg" name="n_stock_eg" value="{n_stock_eg}" maxlength="9" size="5" style="text-align:right;"></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</table>
	{/editable}
</form>
<script type="text/javascript">
	{VALIDATE_JS}
</script>