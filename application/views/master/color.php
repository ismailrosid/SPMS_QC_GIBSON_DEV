<form name="frmEdit" id="frmEdit" method="post" action="{formaction}">
	<table align="left">
		<tr><td valign="top" align="left">
				<table class="table" style="min-width:500px;">
					<thead>
						<tr class="table_header">
							<th width="10px" nowrap><input type="checkbox" id="scheckall" name="scheckall" onClick="_setAllChecked(this,0)" ></th>
							<th><a href="#" onClick="_doPost('sSort', 's_code', 'bSortAction')">Code</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_division', 'bSortAction')">Division</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_type', 'bSortAction')">Type</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_description', 'bSortAction')">Description</a></th>
							<!--<th></th>-->
						</tr>
					</thead>
					<tbody>
						{tm_color}
						<tr><td><input type="checkbox" name="uIdRow[]" value="{s_code}"></td>
							<td><a href="{siteurl}index/{s_code_2}">{s_code}</a></td>
							<td>{s_division}</td>
							<td>{s_type}</td>
							<td>{s_description}</td>
							<!--<td><a href="{siteurl}index/{s_code_2}">Edit</a>
								<a href="#" onClick="ConfirmDelete('{siteurl}delete/{s_code_2}', '{s_code}')">Delete</a>
							</td>-->
						</tr>
						{/tm_color}
					</tbody>
				</table>
				<div id='catalogPagination' class="pagination"></div> Total {nTotalRows} rows
			</td>
			{editable}
			<td valign="top" align="left">
				{MESSAGES}
				<table class="form_view" width="280px">
					<thead>
						<tr><th colspan="2" class="table_header">Basic Information</th></tr>
					</thead>
					<tbody>
						<tr><td>Color Code</td>
							<td><input type="text" id="s_code" name="s_code" value="{s_code}" maxlength="15"></td>
						</tr>
						<tr><td>Division</td>
							<td><select name="s_division" id="s_division">
									<option value="">- - Select Division - -</option>
									{s_division}
								</select>
							</td>
						</tr>
						<tr><td>Type</td>
							<td><input type="text" id="s_type" name="s_type" value="{s_type}" maxlength="100"></td>
						</tr>
						<tr><td>Description</td>
							<td><input type="text" id="s_description" name="s_description" value="{s_description}" maxlength="255"></td>
						</tr>
						<tr><td>Notes</td>
							<td><textarea cols="30" rows="3" id="s_notes" name="s_notes">{s_notes}</textarea></td>
						</tr>
					</tbody>
				</table>
			</td>
			<td valign="top" align="left">
				<table class="form_view" width="200px">
					<thead>
						<tr><th colspan="2" class="table_header">More Information</th></tr>
					</thead>
					<tbody>
						<tr><td>Price</td>
							<td><input type="text" id="n_price" name="n_price" value="{n_price}" maxlength="12" size="12" style="text-align:right;"></td>
						</tr>
						<tr><td>Price A</td>
							<td><input type="text" id="n_price_a" name="n_price_a" value="{n_price_a}" maxlength="12" size="12" style="text-align:right;"></td>
						</tr>
						<tr><td>Price E</td>
							<td><input type="text" id="n_price_e" name="n_price_e" value="{n_price_e}" maxlength="12" size="12" style="text-align:right;"></td>
						</tr>
						<tr><td>Price PT</td>
							<td><input type="text" id="n_price_pt" name="n_price_pt" value="{n_price_pt}" maxlength="12" size="12" style="text-align:right;"></td>
						</tr>
						<tr><td>Price PTE</td>
							<td><input type="text" id="n_price_pte" name="n_price_pte" value="{n_price_pte}" maxlength="12" size="12" style="text-align:right;"></td>
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