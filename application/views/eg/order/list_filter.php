<form id="frmSearch" name="frmSearch" method="post" onKeyPress="_onPressEnter(this, event)">
	<input type="hidden" name="nOffset" id="nOffset" value="{nCurrOffset}">
	<input type="hidden" name="nLimit" id="nLimit" value="{nRowsPerPage}">

	<input type="hidden" name="sSort" id="sSort" value="<?= $sSort ?>" />
	<input type="hidden" name="sSortMethod" id="sSortMethod" value="<?= $sSortMethod ?>" />
	<input type="hidden" name="bSortAction" id="bSortAction">

	<div class="textboxlist textboxlistCostume">
		<table class="custom-table">
			<tr>
				<td>PI No</td>
				<td><input type="text" id="s_po_no_filter" name="s_po_no_filter" value="<?= $s_po_no_filter ?>" size="12"></td>
			</tr>
			<tr>
				<td>Color</td>
				<td><input type="text" id="s_color_filter" name="s_color_filter" value="<?= $s_color_filter ?>" size="12"></td>
			</tr>
			<tr>
				<td>Model</td>
				<td><input type="text" id="s_model_filter" name="s_model_filter" value="<?= $s_model_filter ?>" size="12"></td>
			</tr>
		</table>
	</div>

	<div class="textboxlist separator textboxlistCostume">
		<table class="custom-table">
			<tr>
				<td>PO</td>
				<td><input type="text" id="s_po_filter" name="s_po_filter" value="<?= $s_po_filter ?>" size="18"></td>
			</tr>
			<tr>
				<td>Type</td>
				<td>
					<select id="s_type_filter" name="s_type_filter" style="width:119px;">
						<option value="">- - Select Type - -</option>
						<?= $s_type_filter ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Location</td>
				<td>
					<select id="s_location_filter" name="s_location_filter" style="width:119px;">
						<option value="">- - Select Location - -</option>
						<?= $s_location_filter ?>
					</select>
				</td>
			</tr>
		</table>
	</div>

	<div class="textboxlist separator textboxlistCostume">
		<table class="custom-table">
			<tr>
				<td>Buyer</td>
				<td colspan="2">
					<select name="s_buyer_filter" id="s_buyer_filter" style="width:129px;">
						<option value="">- - Buyer - -</option>
						<?= $s_buyer_filter ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Order</td>
				<td>
					<select name="d_order_date_month_filter" id="d_order_date_month_filter" style="width:85px;">
						<option value="">- - Month - -</option>
						<?= $d_order_date_month_filter ?>
					</select>
				</td>
				<td><input type="text" id="n_order_date_year_filter" name="n_order_date_year_filter" size="4" value="<?= $n_order_date_year_filter ?>"></td>
			</tr>
			<tr>
				<td>Prod.</td>
				<td>
					<select name="d_production_date_month_filter" id="d_production_date_month_filter" style="width:85px;">
						<option value="">- - Month - -</option>
						<?= $d_production_date_month_filter ?>
					</select>
				</td>
				<td><input type="text" id="n_production_date_year_filter" name="n_production_date_year_filter" size="4" value="<?= $n_production_date_year_filter ?>"></td>
			</tr>
		</table>
	</div>

	<div class="button separator">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '')" rel="btnFilterBuyer" title="Search/Filter Buyer"><img src="<?= $baseurl ?>images/ribbon/find32.png" />Search</a>
	</div>
</form>
