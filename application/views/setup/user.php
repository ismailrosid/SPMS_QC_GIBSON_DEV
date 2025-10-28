<form name="frmEdit" id="frmEdit" method="post" action="{formaction}">
	<table align="left">
		<tr><td valign="top" align="left">
				<table class="table" style="min-width:400px;">
					<thead>
						<tr class="table_header">
							<th width="10px" nowrap><input type="checkbox" id="scheckall" name="scheckall" onClick="_setAllChecked(this,0)" ></th>
							<th><a href="#" onClick="_doPost('sSort', 's_username', 'bSortAction')">User Name</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_name', 'bSortAction')">Name</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_level', 'bSortAction')">Level</a></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						{tm_user}
						<tr><td><input type="checkbox" name="uIdRow[]" value="{s_username}"></td>
							<td><a href="{siteurl}index/{s_username}">{s_username}</a></td>
							<td>{s_name}</td>
							<td>{s_level}</td>
							<td><a href="{siteurl}index/{s_username}">Edit</a>
								<a href="#" onClick="ConfirmDelete('{siteurl}delete/{s_username}', '{s_username}')">Delete</a>
							</td>
						</tr>
						{/tm_user}
					</tbody>
				</table>
				<div id='catalogPagination' class="pagination"></div> Total {nTotalRows} rows
			</td>
			<td valign="top" align="left">
				{MESSAGES}
				{editable}
				<table class="form_view" width="320px">
					<thead>
						<tr><th colspan="2" class="table_header">User Editable</th></tr>
					</thead>
					<tbody>
						<tr><td>User ID</td>
							<td><input type="text" id="s_username" name="s_username" value="{s_username}" maxlength="12" {readonly}></td>
						</tr>
						<tr><td>Name</td>
							<td><input type="text" id="s_name" name="s_name" value="{s_name}" maxlength="50"></td>
						</tr>
						<tr><td>Level</td>
							<td><select name="s_level" id="s_level">
									<option value="">- - Select Level - -</option>
									{s_level}
								</select>
							</td>
						</tr>
						<tr><td>Password</td>
							<td><input type="password" id="s_password" name="s_password" maxlength="50"></td>
						</tr>
						<tr><td>Password Confirm</td>
							<td><input type="password" id="s_password_confirm" name="s_password_confirm" maxlength="50"></td>
						</tr>
						<tr><td>Active</td>
							<td><input type="checkbox" id="b_active" name="b_active" value="1" {b_active}></td>
						</tr>
						<tr><td>NIP</td>
							<td><input type="text" id="s_nip" name="s_nip" value="{s_nip}" maxlength="20"></td>
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
<script type="text/javascript">
	{VALIDATE_JS}
</script>
