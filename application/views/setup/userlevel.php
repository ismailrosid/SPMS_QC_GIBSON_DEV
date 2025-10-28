<form name="frmEdit" id="frmEdit" method="post" action="{formaction}">
	<table align="left">
		<tr><td valign="top" align="left">
				<table class="table" style="min-width:300px;">
					<thead>
						<tr class="table_header">
							<th width="10px" nowrap><input type="checkbox" id="scheckall" name="scheckall" onClick="_setAllChecked(this,0)" ></th>
							<th><a href="#" onClick="_doPost('sSort', 's_level', 'bSortAction')">Level</a></th>
							<th><a href="#" onClick="_doPost('sSort', 's_level_desc', 'bSortAction')">Description</a></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						{tm_user_level}
						<tr><td><input type="checkbox" name="uIdRow[]" value="{s_level}"></td>
							<td><a href="{siteurl}index/{s_level}">{s_level}</a></td>
							<td>{s_level_desc}</td>
							<td><a href="{siteurl}index/{s_level}">Edit</a>
								<a href="#" onClick="ConfirmDelete('{siteurl}delete/{s_level}', '{s_level}')">Delete</a>
							</td>
						</tr>
						{/tm_user_level}
					</tbody>
				</table>
				<div id='catalogPagination' class="pagination"></div> Total {nTotalRows} rows
			</td>
			<td valign="top">
				{MESSAGES}
				{editable}
				<table class="form_view" width="300px">
					<tr><td>Level ID</td>
						<td><input type="text" id="s_level" name="s_level" value="{s_level}" maxlength="10"></td>
					</tr>
					<tr><td>Description</td>
						<td><input type="text" id="s_level_desc" name="s_level_desc" value="{s_level_desc}" maxlength="50"></td>
					</tr>
				</table>
				<table class="form_view" width="300px">
					<thead>
						<tr><th colspan="2" class="table_header">Level Setting</th></tr>
					</thead>
					<tbody>
						<tr><td>Setup Application</td>
							<td><input type="checkbox" id="b_setup_read" name="b_setup_read" value="true" {b_setup_read}><label for="b_setup_read">Read</label> 
								<input type="checkbox" id="b_setup_write" name="b_setup_write" value="true" {b_setup_write}><label for="b_setup_write">Write</label>
							</td>
						</tr>
						<tr><td>Master</td>
							<td><input type="checkbox" id="b_master_read" name="b_master_read" value="true" {b_master_read}><label for="b_master_read">Read</label> 
								<input type="checkbox" id="b_master_write" name="b_master_write" value="true" {b_master_write}><label for="b_master_write">Write</label>
							</td>
						</tr>
						<tr><td>Accoustic Guitar</td>
							<td>Setup : <br>
								<input type="checkbox" id="b_ag_setup_read" name="b_ag_setup_read" value="true" {b_ag_setup_read}><label for="b_ag_setup_read">Read</label> 
								<input type="checkbox" id="b_ag_setup_write" name="b_ag_setup_write" value="true" {b_ag_setup_write}><label for="b_ag_setup_write">Write</label><br>
								Order List : <br>
								<input type="checkbox" id="b_ag_order_read" name="b_ag_order_read" value="true" {b_ag_order_read}><label for="b_ag_order_read">Read</label> 
								<input type="checkbox" id="b_ag_order_write" name="b_ag_order_write" value="true" {b_ag_order_write}><label for="b_ag_order_write">Write</label><br>
								Transaction : <br>
								<input type="checkbox" id="b_ag_transaction_read" name="b_ag_transaction_read" value="true" {b_ag_transaction_read}><label for="b_ag_transaction_read">Read</label> 
								<input type="checkbox" id="b_ag_transaction_write" name="b_ag_transaction_write" value="true" {b_ag_transaction_write}><label for="b_ag_transaction_write">Write</label><br>
								<input type="checkbox" id="b_ag_sales_batch" name="b_ag_sales_batch" value="true" {b_ag_sales_batch}><label for="b_ag_sales_batch">Gudang Produksi (Out)</label><br>
								Report : <br>
								<input type="checkbox" id="b_ag_report_read" name="b_ag_report_read" value="true" {b_ag_report_read}><label for="b_ag_report_read">Read</label>
							</td>
						</tr>
						<tr><td>Electric Guitar</td>
							<td>Setup : <br>
								<input type="checkbox" id="b_eg_setup_read" name="b_eg_setup_read" value="true" {b_eg_setup_read}><label for="b_eg_setup_read">Read</label> 
								<input type="checkbox" id="b_eg_setup_write" name="b_eg_setup_write" value="true" {b_eg_setup_write}><label for="b_eg_setup_write">Write</label><br>
								Order List : <br>
								<input type="checkbox" id="b_eg_order_read" name="b_eg_order_read" value="true" {b_eg_order_read}><label for="b_eg_order_read">Read</label> 
								<input type="checkbox" id="b_eg_order_write" name="b_eg_order_write" value="true" {b_eg_order_write}><label for="b_eg_order_write">Write</label><br>
								Transaction : <br>
								<input type="checkbox" id="b_eg_transaction_read" name="b_eg_transaction_read" value="true" {b_eg_transaction_read}><label for="b_eg_transaction_read">Read</label> 
								<input type="checkbox" id="b_eg_transaction_write" name="b_eg_transaction_write" value="true" {b_eg_transaction_write}><label for="b_eg_transaction_write">Write</label><br>
								<input type="checkbox" id="b_eg_sales_batch" name="b_eg_sales_batch" value="true" {b_eg_sales_batch}><label for="b_eg_sales_batch">Gudang Produksi (Out)</label><br>
								Report : <br>
								<input type="checkbox" id="b_eg_report_read" name="b_eg_report_read" value="true" {b_eg_report_read}><label for="b_eg_report_read">Read</label>
							</td>
						</tr>
						<tr><td>General</td>
							<td>
								<input type="checkbox" id="b_replace_production" name="b_replace_production" value="true" {b_replace_production}><label for="b_replace_production">Replace Production Date</label>
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