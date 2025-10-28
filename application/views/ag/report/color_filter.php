<!-- This is AG -->
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
				<td><input type="text" id="s_po_no_filter" name="s_po_no_filter" value="<?= $s_po_no_filter ?>"></td>
			</tr>
			<tr>
				<td>PO</td>
				<td><input type="text" id="s_po_filter" name="s_po_filter" value="<?= $s_po_filter ?>"></td>
			</tr>
			<tr>
				<td>Buyer</td>
				<td>
					<select name="s_buyer_filter" id="s_buyer_filter" style="width:130px;">
						<option value="">- - Buyer - -</option>
						<?= $s_buyer_filter ?>
					</select>
				</td>
			</tr>
		</table>

	</div>

	<div class="textboxlist separator textboxlistCostume">
		<table class="custom-table">
			<tr>
				<td>Color</td>
				<td> <input type="text" id="s_color_filter" name="s_color_filter" value="<?= $s_color_filter ?>" size="10"></td>
			</tr>
			<tr>
				<td>Model</td>
				<td><input type="text" id="s_model_filter" name="s_model_filter" value="<?= $s_model_filter ?>" size="10"></td>
			</tr>
			<tr>
				<td>Status</td>
				<td>
					<select name="s_status_filter" id="s_status_filter" style="width:78px;">
						<option value="">Total</option>
						<?= $s_status_filter ?>
					</select>
				</td>
			</tr>
		</table>

	</div>

	<div class="textboxlist separator textboxlistCostume">
		<table class="custom-table">
			<tr>
				<td>Location</td>
				<td colspan="2">
					<select id="s_location_filter" name="s_location_filter" style="width:119px;">
						<option value="">- - Select Location - -</option>
						<?= $s_location_filter ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Prod.</td>
				<td>
					<select name="d_production_date_month_filter" id="d_production_date_month_filter" style="width:78px;">
						<option value="">- - Month - -</option>
						<?= $d_production_date_month_filter ?>
					</select>
				</td>
				<td> <input type="text" id="d_production_date_year_filter" name="d_production_date_year_filter" value="<?= $d_production_date_year_filter ?>" size="4"></td>
			</tr>
			<tr>
				<td>To</td>
				<td>
					<select name="d_production_date_month_filter2" id="d_production_date_month_filter2" style="width:78px;">
						<option value="">- - Month - -</option>
						<?= $d_production_date_month_filter2 ?>
					</select>
				</td>
				<td><input name="d_production_date_year_filter2" id="d_production_date_year_filter2" value="<?= $d_production_date_year_filter2 ?>" size="4"></td>
			</tr>
		</table>
	</div>

	<div style="display: flex; align-items: center;" class="textboxlist separator textboxlistCostume">
		<table class="custom-table">
			<tr>
				<td>SKU</td>
				<td> <input type="text" id="s_sku" name="s_sku" value="<?= $s_sku ?>"></td>
			</tr>
		</table>
	</div>

	<div class="button separator">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?= $siteurl ?>')" rel="btnFilterReportColor" title="Search/Filter Color Report"><img src="<?= $baseurl ?>images/ribbon/find32.png" />Search</a>
	</div>
	<div class="button">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?= $basesiteurl ?>/ag/reportlist/groupexcel/<?= $sViewReport ?>')" rel="btnExcelReportColor" title="Export Color Report to Excel"><img src="<?= $baseurl ?>images/ribbon/excel32.png" />Excel</a>
	</div>
</form>