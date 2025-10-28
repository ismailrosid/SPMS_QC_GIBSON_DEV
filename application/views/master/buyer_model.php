<form name="frmEdit" id="frmEdit" method="post" action="{formaction}">
	<table align="left">
		<tr><td valign="top" align="left">
				<table class="table" style="min-width:620px;">
					<thead>
						<tr class="table_header">
							<th width="10px" nowrap><input type="checkbox" id="scheckall" name="scheckall" onClick="_setAllChecked(this,0)" ></th>
							<th><a href="#" onClick="_doPost('sSort', 's_code_customer', 'bSortAction')">B.Code</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_name', 'bSortAction')">B.Name</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_code_model', 'bSortAction')">M.Code</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_description', 'bSortAction')">M.Description</a></th>
							<!--<th></th>-->
						</tr>
					</thead>
					<tbody>
						{tm_buyer_model}
						<tr><td><input type="checkbox" name="uIdRow[]" value="{u_id}"></td>
							<td>{s_code_customer}</td>
							<td>{s_name}</td>
							<td>{s_code_model}</td>
							<td>{s_description}</td>
							<!--<td><a href="{siteurl}index/{u_id}">Edit</a>
								<a href="#" onClick="ConfirmDelete('{siteurl}delete/{u_id}', '{s_code_customer} & {s_code_model}')">Delete</a>
							</td>-->
						</tr>
						{/tm_buyer_model}
					</tbody>
				</table>
				<div id='catalogPagination' class="pagination"></div> Total {nTotalRows} rows
			</td>
			<td valign="top" align="left">
				{MESSAGES}
				{editable}
				<table class="form_view" width="360px">
					<thead>
						<tr><th colspan="2" class="table_header">Basic Information</th></tr>
					</thead>
					<tbody>
						<tr><td>Buyer</td>
							<td><select id="s_code_customer" name="s_code_customer" style="width:250px;">
									<option value="">- - Select Buyer - -</option>
									{s_code_customer}
								</select>
							</td>
						</tr>
						<tr><td>Model</td>
							<td><select id="s_code_model" name="s_code_model" style="width:250px;">
									<option value="">- - Select Model - -</option>
									{s_code_model}
								</select>
							</td>
						</tr>
						<tr><td>Price 1</td>
							<td><input type="text" id="n_price_1" name="n_price_1" value="{n_price_1}" maxlength="12" size="12" style="text-align:right;"></td>
						</tr>
						<tr><td>Price 2</td>
							<td><input type="text" id="n_price_2" name="n_price_2" value="{n_price_2}" maxlength="12" size="12" style="text-align:right;"></td>
						</tr>
						<tr><td>Notes</td>
							<td><textarea cols="30" rows="3" id="s_notes" name="s_notes">{s_notes}</textarea></td>
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