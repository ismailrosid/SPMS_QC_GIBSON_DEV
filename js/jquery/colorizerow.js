function colorizeRowTable(tablename){
	if(tablename==null)tablename='table';
	$("."+tablename +" tbody>tr:odd").attr("class","table_row_odd");
	$("."+tablename +" tbody>tr:even").attr("class","table_row_even");
}

function colorizeRowForm(tablename){
	if(tablename==null)tablename='form_view';
	$("."+tablename +" tbody>tr:odd>td:even").attr("class","form_view_cell_1 form_view_title");
	$("."+tablename +" tbody>tr:odd>td:odd").attr("class","form_view_cell_1");
	
	$("."+tablename +" tbody>tr:even>td:even").attr("class","form_view_cell_2 form_view_title");
	$("."+tablename +" tbody>tr:even>td:odd").attr("class","form_view_cell_2");
}