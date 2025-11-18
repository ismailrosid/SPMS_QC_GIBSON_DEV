<form id="frmSearch" name="frmSearch" method="post" onKeyPress="_onPressEnter(this, event)">
	<input type="hidden" name="nOffset" id="nOffset" value="<?= $nCurrOffset ?>">
	<input type="hidden" name="nLimit" id="nLimit" value="<?= $nRowsPerPage ?>">

	<input type="hidden" name="sSort" id="sSort" value="<?= $sSort ?>" />
	<input type="hidden" name="sSortMethod" id="sSortMethod" value="<?= $sSortMethod ?>" />
	<input type="hidden" name="bSortAction" id="bSortAction">

	<div class="textboxlist textboxlistCostume">
		<table class="custom-table">
			<tr>
				<td>Model Code</td>
				<td><input type="text" id="s_code_filter" name="s_code_filter" value="<?= $s_code_filter ?>" size="20"></td>
			</tr>
			<tr>
				<td>Item Code</td>
				<td><input type="text" id="s_smodel_filter" name="s_smodel_filter" value="<?= $s_smodel_filter ?>" size="20"></td>
			</tr>
			<tr>
				<td>Description</td>
				<td><input type="text" id="s_description_filter" name="s_description_filter" value="<?= $s_description_filter ?>" size="20"></td>
			</tr>
		</table>
	</div>
	<div class="textboxlist separator textboxlistCostume">
		<table class="custom-table">
			<tr>
				<td>Division</td>
				<td>
					<select name="s_division_filter" id="s_division_filter" style="width:94px;">
						<option value="">- - Division - -</option>
						<?= $s_division_filter ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Type</td>
				<td><input type="text" id="s_type_filter" name="s_type_filter" value="<?= $s_type_filter ?>" size="10"></td>
			</tr>
			<tr>
				<td>UPC</td>
				<td><input type="text" id="s_upc_code_filter" name="s_upc_code_filter" value="<?= $s_upc_code_filter ?>" size="10"></td>
			</tr>
		</table>
	</div>
	<div style="display: flex; align-items: center;" class="textboxlist separator textboxlistCostume">
		<table class="custom-table">
			<tr>
				<td>Status</td>
				<td>
					<select name="s_status_filter" id="s_status_filter" style="width:94px;">
						<option value="">- - Status - -</option>
						<?= $s_status_filter ?>
					</select>
				</td>
			</tr>
		</table>
	</div>
	<div class="button separator">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?= $siteurl ?>')" rel="btnFilterModel" title="Search/Filter Model"><img src="<?= $baseurl ?>images/ribbon/find32.png" />Search</a>
	</div>
</form>
