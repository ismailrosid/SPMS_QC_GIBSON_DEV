<form name="frmEdit" id="frmEdit" method="post" action="{formaction}">
	<table align="left">
		<tr>
			<td valign="top" align="left">
				<table class="table" style="min-width:550px;">
					<thead>
						<tr class="table_header">
							<th width="10px" nowrap><input type="checkbox" id="scheckall" name="scheckall" onClick="_setAllChecked(this,0)"></th>
							<th><a href="#" onClick="_doPost('sSort', 's_code', 'bSortAction')">Code</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_smodel', 'bSortAction')">Item Code</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_division', 'bSortAction')">Division</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_description', 'bSortAction')">Description</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_type', 'bSortAction')">Difficult</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_upc_code', 'bSortAction')">UPC Code</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_color', 'bSortAction')">Color</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_status', 'bSortAction')">Status</a></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						{tm_model}
						<tr>
							<td><input type="checkbox" name="uIdRow[]" value="{s_code}"></td>
							<td><a href="{siteurl}index/{s_code_2}">{s_code}</a></td>
							<td>{s_smodel}</td>
							<td>{s_division}</td>
							<td>{s_description}</td>
							<td>{s_type}</td>
							<td>{s_upc_code}</td>
							<td>{s_color}</td>
							<td>
								<a href="#" onClick="ConfirmStatus('{siteurl}editStatus/{s_code_2}/{s_status_2}','{s_status_2}','{s_code}')">
									{s_status}</a>
							</td>
							<td><a href="#" onClick="ConfirmDelete('{siteurl}delete/{s_code_2}', '{s_code}')">Delete</a></td>
						</tr>
						{/tm_model}
					</tbody>
				</table>
				<div id='catalogPagination' class="pagination"></div> Total {nTotalRows} rows
			</td>
			<td valign="top" align="left">
				{MESSAGES}
				{editable}
				<table class="form_view" width="320px">
					<thead>
						<tr>
							<th colspan="2" class="table_header">Basic Information</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Model Code</td>
							<td><input type="text" id="s_code" name="s_code" value="{s_code}" maxlength="20"></td>
						</tr>
						<tr>
							<td>Item Code</td>
							<td><input type="text" id="s_smodel" name="s_smodel" value="{s_smodel}" maxlength="15"></td>
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
							<td>Description</td>
							<td><input type="text" id="s_description" name="s_description" value="{s_description}" maxlength="255" size="30"></td>
						</tr>
						<tr>
							<td>Color</td>
							<td>
								<div id="combo-box-color" class="combo-box-container">
									<input name="s_color" id="s_color" type="hidden">
									<input id="combo-box-input" type="text" placeholder="Select Color" readonly>
									<input autocomplete="off" type="text" id="search-box" placeholder="Search color..." />
									<ul class="combo-box-dropdown" id="combo-box-dropdown">
										{s_color}
									</ul>
								</div>
							</td>
						</tr>
						<tr>
							<td>Difficult</td>
							<td><select name="s_type" id="s_type">
									<option value="">- - Select Difficulty - -</option>
									{s_type}
								</select>
							</td>
						</tr>
						<tr>
							<td>UPC Code</td>
							<td><input type="text" id="s_upc_code" name="s_upc_code" value="{s_upc_code}" maxlength="20"></td>
						</tr>
					<tbody>
				</table>
				<table class="form_view" width="320px">
					<thead>
						<tr>
							<th colspan="2" class="table_header">More Information</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td valign="top" align="left">
								<table class="form_view">
									<tr>
										<td>HS No</td>
										<td><input type="text" id="s_hsno" name="s_hsno" value="{s_hsno}" maxlength="15" size="15"></td>
									</tr>
									<tr>
										<td>Price 1</td>
										<td><input type="text" id="n_price_1" name="n_price_1" value="{n_price_1}" maxlength="12" size="12" style="text-align:right;"></td>
									</tr>
									<tr>
										<td>Price 2</td>
										<td><input type="text" id="n_price_2" name="n_price_2" value="{n_price_2}" maxlength="12" size="12" style="text-align:right;"></td>
									</tr>
									<tr>
										<td>Brand</td>
										<td><input type="text" id="s_brand" name="s_brand" value="{s_brand}" maxlength="50"></td>
									</tr>
									<tr>
										<td>Ef Date</td>
										<td><input type="text" id="d_efdate" name="d_efdate" value="{d_efdate}" maxlength="10" size="10">
											<input class="button" type="reset" value="..." onclick="return showCalendar('d_efdate');" name="cldd_efdate" />
										</td>
									</tr>
									<tr>
										<td>Validate</td>
										<td><input type="text" id="d_validate" name="d_validate" value="{d_validate}" maxlength="10" size="10">
											<input class="button" type="reset" value="..." onclick="return showCalendar('d_validate');" name="cldd_validate" />
										</td>
									</tr>
								</table>
							</td>
							<td valign="top" align="left">
								<table class="form_view">
									<tr>
										<td>CBM</td>
										<td><input type="text" id="n_cbm" name="n_cbm" value="{n_cbm}" maxlength="13" size="13" style="text-align:right;"></td>
									</tr>
									<tr>
										<td>KG</td>
										<td><input type="text" id="n_kg" name="n_kg" value="{n_kg}" maxlength="11" size="11" style="text-align:right;"></td>
									</tr>
									<tr>
										<td>Option</td>
										<td><input type="text" id="s_opt_1" name="s_opt_1" value="{s_opt_1}" maxlength="2" size="2">
											<input type="text" id="s_opt_2" name="s_opt_2" value="{s_opt_2}" maxlength="2" size="2">
										</td>
									</tr>
									<tr>
										<td>Factory</td>
										<td><input type="text" id="s_factory" name="s_factory" value="{s_factory}" maxlength="3" size="3"></td>
									</tr>
									<tr>
										<td>Notes</td>
										<td><textarea cols="30" rows="3" id="s_notes" name="s_notes">{s_notes}</textarea></td>
									</tr>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				{/editable}
			</td>
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

	document.addEventListener("DOMContentLoaded", function() {

		initializeComboBox("combo-box-color", "s_color", "hidden");

		function initializeComboBox(containerId, dataPlace, act) {
			var container = document.getElementById(containerId);
			var inputShow = container.querySelector("#combo-box-input");
			var input = document.getElementById(dataPlace);
			var dropdown = container.querySelector("#combo-box-dropdown");
			var searchBox = container.querySelector("#search-box");
			addComboBoxListeners(
				inputShow,
				input,
				dropdown,
				searchBox,
				container,
				act
			);
		}
	});
</script>