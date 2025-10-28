<form id="frmSearch" name="frmSearch" method="post" onKeyPress="_onPressEnter(this, event)">
	<input type="hidden" name="nOffset" id="nOffset" value="<?=$nCurrOffset?>">
	<input type="hidden" name="nLimit" id="nLimit" value="<?=$nRowsPerPage?>">
	
	<input type="hidden" name="sSort" id="sSort" value="<?=$sSort?>"/>
	<input type="hidden" name="sSortMethod" id="sSortMethod" value="<?=$sSortMethod?>"/>
	<input type="hidden" name="bSortAction" id="bSortAction">
	
	<div class="textboxlist">
		<ul>
			<li>Serial
				<input type="text" id="s_serial_no_filter" name="s_serial_no_filter" value="<?=$s_serial_no_filter?>" size="18">
			</li>
			<li>PI No
				<input type="text" id="s_po_no_filter" name="s_po_no_filter" value="<?=$s_po_no_filter?>" size="18">
			</li>
			<li>PO
				<input type="text" id="s_po_filter" name="s_po_filter" value="<?=$s_po_filter?>" size="18">
			</li>
		</ul>
	</div>
	
	<div class="textboxlist separator">
		<ul>
			<li>Model
				<input type="text" id="s_model_filter" name="s_model_filter" value="<?=$s_model_filter?>" size="10">
			</li>
			<li>Color
				<input type="text" id="s_color_filter" name="s_color_filter" value="<?=$s_color_filter?>" size="10">
			</li>
		</ul>
	</div>
	
	<div class="textboxlist separator">
		<ul>
			<li>Buyer
				<select name="s_buyer_filter" id="s_buyer_filter" style="width:118px;">
					<option value="">- - Buyer - -</option>
					<?=$s_buyer_filter?>
				</select>
			</li>
			<li>Lot
				<input type="text" id="s_lot_no_filter" name="s_lot_no_filter" value="<?=$s_lot_no_filter?>" size="18">
			</li>
			<li>Prod.
				<select name="d_production_date_month_filter" id="d_production_date_month_filter" style="width:78px;">
					<?=$d_production_date_month_filter?>
				</select>
				<input type="text" id="d_production_date_year_filter" name="d_production_date_year_filter" value="<?=$d_production_date_year_filter?>" size="2">
			</li>
		</ul>
	</div>
	
	<div class="button separator">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>')" rel="btnFilterReportStock" title="Search/Filter Stock Report"><img src="<?=$baseurl?>images/ribbon/find32.png"/>Search</a>
	</div>
	<!--div class="button">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>1')" rel="btnExcelReportStock" title="Export Stock Report to Excel"><img src="<?=$baseurl?>images/ribbon/excel32.png"/>Excel</a>
	</div-->
</form>