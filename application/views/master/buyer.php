<form name="frmEdit" id="frmEdit" method="post" action="{formaction}">
	<table align="left">
		<tr>
			<td valign="top" align="left">
				<table class="table" style="min-width:400px;">
					<thead>
						<tr class="table_header">
							<th width="10px" nowrap><input type="checkbox" id="scheckall" name="scheckall" onClick="_setAllChecked(this,0)"></th>
							<th><a href="#" onClick="_doPost('sSort', 's_code', 'bSortAction')">Code</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_name', 'bSortAction')">Name</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_division', 'bSortAction')">Division</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_serial_parse', 'bSortAction')">Serial Parse</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_status', 'bSortAction')">Status</a></th>
							<!--<th></th>-->
						</tr>
					</thead>
					<tbody>
						{tm_customer}
						<tr>
							<td><input type="checkbox" name="uIdRow[]" value="{s_code}"></td>
							<td><a href="{siteurl}index/{s_code_2}">{s_code}</a></td>
							<td>{s_name}</td>
							<td>{s_division}</td>
							<td>{s_serial_parse}</td>
							<td>
								<a href="#" onClick="ConfirmStatus('{siteurl}editStatus/{s_code_2}/{s_status_2}','{s_status_2}','{s_code}')">
									{s_status}
								</a>
							</td>
							<!--<td><a href="{siteurl}index/{s_code_2}">Edit</a>
								<a href="#" onClick="ConfirmDelete('{siteurl}delete/{s_code_2}', '{s_code}')">Delete</a>
							</td>-->
						</tr>
						{/tm_customer}
					</tbody>
				</table>
				<div id='catalogPagination' class="pagination"></div> Total {nTotalRows} rows
			</td>
			{editable}
			<td valign="top" align="left">
				{MESSAGES}
				<table class="form_view" width="280px">
					<thead>
						<tr>
							<th colspan="2" class="table_header">Basic Information</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Buyer Code</td>
							<td><input type="text" id="s_code" name="s_code" value="{s_code}" maxlength="15"></td>
						</tr>
						<tr>
							<td>Division</td>
							<td><select name="s_division" id="s_division">
									<option value="">- - Select Division - -</option>
									{s_division}
								</select>
							</td>
						</tr>
						<tr>
							<td>Name</td>
							<td><input type="text" id="s_name" name="s_name" value="{s_name}" maxlength="255"></td>
						</tr>
						<tr>
							<td valign="top">Serial Parse</td>
							<td><input type="text" id="s_serial_parse" name="s_serial_parse" value="{s_serial_parse}" maxlength="255">
								<select name="s_serial_reset" id="s_serial_reset">
									{s_serial_reset}
								</select><br>
								variable:<br>
								<select name="n_serial_digit" id="n_serial_digit">
									{n_serial_digit}
								</select>
								{number} = Auto Number<br>
								{buyercode} = Buyer Code<br>
								{yymm} = Production Date (Year Month)
							</td>
						</tr>
						<tr>
							<td>Notes</td>
							<td><textarea cols="30" rows="3" id="s_notes" name="s_notes">{s_notes}</textarea></td>
						</tr>
					</tbody>
				</table>
			</td>
			<td valign="top" align="left">
				<table class="form_view" width="300px">
					<thead>
						<tr>
							<th colspan="2" class="table_header">More Information</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Full Name 1</td>
							<td><input type="text" id="s_fullname_1" name="s_fullname_1" value="{s_fullname_1}" maxlength="255" size="35"></td>
						</tr>
						<tr>
							<td>Full Name 2</td>
							<td><input type="text" id="s_fullname_2" name="s_fullname_2" value="{s_fullname_2}" maxlength="255" size="35"></td>
						</tr>
						<tr>
							<td>Manager Name</td>
							<td><input type="text" id="s_manager_name" name="s_manager_name" value="{s_manager_name}" maxlength="100" size="30"></td>
						</tr>
						<tr>
							<td>Assistant Name</td>
							<td><input type="text" id="s_assistant_name" name="s_assistant_name" value="{s_assistant_name}" maxlength="100" size="30"></td>
						</tr>
						<tr>
							<td>Address 1</td>
							<td><textarea cols="30" rows="3" id="s_address_1" name="s_address_1">{s_address_1}</textarea></td>
						</tr>
						<tr>
							<td>Address 2</td>
							<td><textarea cols="30" rows="3" id="s_address_2" name="s_address_2">{s_address_2}</textarea></td>
						</tr>
						<tr>
							<td>Country</td>
							<td><input type="text" id="s_country" name="s_country" value="{s_country}"></td>
						</tr>
						<tr>
							<td>Phone 1</td>
							<td><input type="text" id="s_phone_1" name="s_phone_1" value="{s_phone_1}"></td>
						</tr>
						<tr>
							<td>Phone 2</td>
							<td><input type="text" id="s_phone_2" name="s_phone_2" value="{s_phone_2}"></td>
						</tr>
						<tr>
							<td>Fax 1</td>
							<td><input type="text" id="s_fax_1" name="s_fax_1" value="{s_fax_1}"></td>
						</tr>
						<tr>
							<td>Fax 2</td>
							<td><input type="text" id="s_fax_2" name="s_fax_2" value="{s_fax_2}"></td>
						</tr>
						<tr>
							<td>Email 1</td>
							<td><input type="text" id="s_email_1" name="s_email_1" value="{s_email_1}"></td>
						</tr>
						<tr>
							<td>Email 2</td>
							<td><input type="text" id="s_email_2" name="s_email_2" value="{s_email_2}"></td>
						</tr>
					</tbody>
				</table>
			</td>
			{/editable}
		</tr>
	</table>
</form>
<script type="text/javascript">
	{VALIDATE_JS}
</script>
<script type="text/javascript">
	$(document).ready(function() {
		var isFirstTime = 1;

		// First Parameter: number of items
		// Second Parameter: options object
		var myPageIndex = 1;
		var itemsPerPage = {nRowsPerPage};
		var currentOffset = {nCurrOffset};
		var limit = {nRowsPerPage};
		var currentNode = "";
		var totalItems = {nTotalRows};
		var tmpCurrentPageIndex = currentOffset / itemsPerPage;

		$("#catalogPagination").pagination(totalItems, {
			items_per_page: itemsPerPage,
			callback: handlePaginationClick,
			current_page: tmpCurrentPageIndex,
			num_display_entries: 10,
			num_edge_entries: 2
		});

		function handlePaginationClick(new_page_index, pagination_container) {
			if (!isFirstTime) {
				myPageIndex = new_page_index;
				var nextOffset = (itemsPerPage * myPageIndex);
				$("#nOffset").val(nextOffset);
				$("#nLimit").val(limit + currentNode);

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
		if (nCode == 13) fForm.submit();
	}

	function ConfirmStatus(url, status, name) {
		status = status == "Active" ? "Non Active" : "Active";
		var where_to = confirm("Are you sure to" + " " + status + " " + name + "?");
		if (where_to == true) {
			window.location = url;
		}
	}
</script>