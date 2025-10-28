<form id="frmSearch" name="frmSearch" method="post" onKeyPress="_onPressEnter(this, event)">
	<input type="hidden" name="nOffset" id="nOffset" value="<?= $nCurrOffset ?>">
	<input type="hidden" name="nLimit" id="nLimit" value="<?= $nRowsPerPage ?>">
	<input type="hidden" name="sSort" id="sSort" value="<?= $sSort ?>" />

	<input type="hidden" name="sSortMethod" id="sSortMethod" value="<?= $sSortMethod ?>" />
	<input type="hidden" name="bSortAction" id="bSortAction">

	<div class="textboxlist textboxlistCostume">
		<table class="custom-table">
			<tr>
				<td>
					PI No
				</td>
				<td>
					<input type="text" id="s_po_no_filter" name="s_po_no_filter" value="<?= $s_po_no_filter ?>">
				</td>
			</tr>
			<tr>
				<td>
					Color
				</td>
				<td>
					<input type="text" id="s_color_filter" name="s_color_filter" value="<?= $s_color_filter ?>">
				</td>
			</tr>
			<tr>
				<td>
					Model
				</td>
				<td>
					<input type="text" id="s_model_filter" name="s_model_filter" value="<?= $s_model_filter ?>" size="12">
				</td>
			</tr>
		</table>
	</div>

	<div class="textboxlist separator textboxlistCostume">
		<table class="custom-table">
			<tr>
				<td>Serial No</td>
				<td><input type="text" id="s_serial_no_filter" name="s_serial_no_filter" value="<?= $s_serial_no_filter ?>" size="7"></td>
				<td> <input type="text" id="s_serial_no2_filter" name="s_serial_no2_filter" value="<?= $s_serial_no2_filter ?>" size="7"></td>
			</tr>
			<tr>
				<td>Date</td>
				<td colspan="2">
					<div style="display: flex; align-items: center;">
						<div style="width: 35%;">
							<input type="text" id="ago_d_transaction_date_filter" name="ago_d_transaction_date_filter" value="<?= $ago_d_transaction_date_filter ?>" size="10">
						</div>
						<div style="width: 10%; margin-left: 1px;">
							<input class="button" type="reset" value="..." onclick="return showCalendar('ago_d_transaction_date_filter');" name="cldd_transaction_date_filter" />
						</div>
						<div style="width: 20%; margin-left: 1px; text-align: center;">
							Until
						</div>
						<div style="width: 35%;">
							<input type="text" id="now_d_transaction_date_filter" name="now_d_transaction_date_filter" value="<?= $now_d_transaction_date_filter ?>" size="10">
						</div>
						<div style="width: 10%; margin-left: 1px;">
							<input class="button" type="reset" value="..." onclick="return showCalendar('now_d_transaction_date_filter');" name="cldd_transaction_date_filter" />
						</div>
					</div>
				</td>

			</tr>
			<tr>
				<td>Phase</td>
				<td colspan="2">
					<select name="s_phase_filter" id="s_phase_filter">
						<?= $s_phase_filter ?>
					</select>
				</td>
			</tr>
		</table>
	</div>

	<div style="width: 200px;" class="textboxlist separator textboxlistCostume">
		<table class="custom-table">
			<tr>
				<td>Location</td>
				<td>
					<select id="s_location_filter" name="s_location_filter" class="costume-select">
						<option value="">- - Select Location - -</option>
						<?= $s_location_filter ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Buyer</td>
				<td>
					<select name="s_buyer_filter" id="s_buyer_filter" class="costume-select">
						<option value="">- - Buyer - -</option>
						<?= $s_buyer_filter ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Prod</td>
				<td>
					<div style="display: flex; align-items: center; width: 100%;">
						<div style="width: 70%;">
							<select name="d_production_date_month_filter" id="d_production_date_month_filter">
								<option value="">- - Month - -</option>
								<?= $d_production_date_month_filter ?>
							</select>
						</div>
						<div style="width: 30%; margin-left: 1px;">
							<input type="text" id="d_production_date_year_filter" name="d_production_date_year_filter" value="<?= $d_production_date_year_filter ?>">
						</div>
					</div>

				</td>
			</tr>
		</table>
	</div>
	<div style="display: flex; align-items: center; width: 150px;" class="textboxlist separator textboxlistCostume">
		<table class="custom-table">
			<tr>
				<td>SKU</td>
				<td> <input type="text" id="s_sku" name="s_sku" value="<?= $s_sku ?>"></td>
			</tr>
		</table>
	</div>
	<div class="button separator">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?= $siteurl ?>index/<?= $sDivision ?>')" rel="btnFilterBuyer" title="Search/Filter Buyer"><img src="<?= $baseurl ?>images/ribbon/find32.png" />Search</a>
	</div>
</form>