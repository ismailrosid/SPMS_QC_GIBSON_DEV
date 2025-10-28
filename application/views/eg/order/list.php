<form id="frmEdit" name="frmEdit" method="post">
	<table class="table">
		<thead>
			<tr class="table_header">
				<th width="10px" nowrap><input type="checkbox" id="scheckall" name="scheckall" onClick="_setAllChecked(this,0)" ></th>
				<th><a href="#" onClick="_doPost('sSort', 's_po_no', 'bSortAction')">PI Number</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_po', 'bSortAction')">PO</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_type', 'bSortAction')">Type</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_order_date', 'bSortAction')">Receive Order</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_production_date', 'bSortAction')">Production Date</a></th>
				<!-- <th><a href="#" onClick="_doPost('sSort', 'd_plan_date', 'bSortAction')">Production Plan Date (Input)</a></th> -->
				<th><a href="#" onClick="_doPost('sSort', 'd_delivery_date', 'bSortAction')">Production Plan Date (Output)</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'd_target_date', 'bSortAction')">Export Plan Date</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_buyer_name', 'bSortAction')">Buyer</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_brand', 'bSortAction')">Brand</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_model', 'bSortAction')">Model</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_model_name', 'bSortAction')">Model Name</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_color_name', 'bSortAction')">Color</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_smodel', 'bSortAction')">Item Code</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_location', 'bSortAction')">Loc</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'n_qty', 'bSortAction')">Qty</a></th>
			</tr>
		</thead>
		<tbody>
<?php 		$nTotal = 0;
			foreach($tt_prod_order as $nIndex=>$aValue){
				$nTotal += $aValue['n_qty'];?>
			<tr><td width="10px"><input type="checkbox" name="uIdRow[]" value="<?=$aValue['u_id']?>"></td>
				<td><a href="{siteurl}viewedit/{sDivision}/<?=$aValue['u_id']?>"><?=$aValue['s_po_no']?></a></td>
				<td><?=$aValue['s_po']?></td>
				<td><?=$aValue['s_type']?></td>
				<td><?=$aValue['d_order_date']?></td>
				<td><?=$aValue['d_production_date']?></td>
				<!-- <td><?=$aValue['d_plan_date']?></td> -->
				<td><?=$aValue['d_delivery_date']?></td>
				<td><?=$aValue['d_target_date']?></td>
				<td><?=$aValue['s_buyer_name']?></td>
				<td><?=$aValue['s_brand']?></td>
				<td><?=$aValue['s_model']?></td>
				<td><?=$aValue['s_model_name']?></td>
				<td><?=$aValue['s_color_name']?></td>
				<td><?=$aValue['s_smodel']?></td>
				<td><?=$aValue['s_location']?></td>
				<td align="right"><?=$aValue['n_qty']?></td>
			</tr>
<?php 		}?>
		</tbody>
		<tfoot class="table_footer">
			<tr><td colspan="15" align="right">TOTAL</td>
				<td align="right"><?=$nTotal?></td>
			</tr>
		</tfoot>
	</table>
</form>
Total {nTotalRows} rows
<script type="text/javascript">
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
