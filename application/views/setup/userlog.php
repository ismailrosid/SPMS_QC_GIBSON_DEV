<table class="table">
	<thead>
		<tr class="table_header">
			<th><a href="#" onClick="_doPost('sSort', 's_username', 'bSortAction')">User Name</a></th>
			<th><a href="#" onClick="_doPost('sSort', 's_name', 'bSortAction')">Name</a></th>
			<th><a href="#" onClick="_doPost('sSort', 's_level', 'bSortAction')">Level</a></th>
			<th><a href="#" onClick="_doPost('sSort', 'd_login', 'bSortAction')">Log In</a></th>
			<th><a href="#" onClick="_doPost('sSort', 'd_logout', 'bSortAction')">Log Out</a></th>
			<th><a href="#" onClick="_doPost('sSort', 'ip_address', 'bSortAction')">IP</a></th>
		</tr>
	</thead>
	<tbody>
		{tl_user_log}
		<tr><td>{s_username}</td>
			<td>{s_username}</td>
			<td>{s_level}</td>
			<td>{d_login}</td>
			<td>{d_logout}</td>
			<td>{ip_address}</td>
		</tr>
		{/tl_user_log}
	</tbody>
</table>
<div id='catalogPagination' class="pagination"></div> Total {nTotalRows} rows
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