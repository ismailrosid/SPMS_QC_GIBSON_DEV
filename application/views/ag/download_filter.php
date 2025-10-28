<form id="frmSearch" name="frmSearch" method="post" onKeyPress="_onPressEnter(this, event)">
	<input type="hidden" name="nOffset" id="nOffset" value="<?=$nCurrOffset?>">
	<input type="hidden" name="nLimit" id="nLimit" value="<?=$nRowsPerPage?>">
	
	<input type="hidden" name="sSort" id="sSort" value="<?=$sSort?>"/>
	<input type="hidden" name="sSortMethod" id="sSortMethod" value="<?=$sSortMethod?>"/>
	<input type="hidden" name="bSortAction" id="bSortAction">
	
	<div class="textboxlist">
		<ul>
			<li>Serial No
				<input type="text" id="s_serial_no_filter" name="s_serial_no_filter" value="<?=$s_serial_no_filter?>" size="13">
			</li>
			<li>Lot No
				<input type="text" id="s_lot_no_filter" name="s_lot_no_filter" value="<?=$s_lot_no_filter?>" size="13">
			</li>
			<li>PI No
				<input type="text" id="s_po_no_filter" name="s_po_no_filter" value="<?=$s_po_no_filter?>" size="13">
			</li>
		</ul>
	</div>
	
	<div class="textboxlist separator">
		<ul>
			<li>Color
				<input type="text" id="s_color_filter" name="s_color_filter" value="<?=$s_color_filter?>" size="15">
			</li>
			<li>Model
				<input type="text" id="s_model_filter" name="s_model_filter" value="<?=$s_model_filter?>" size="15">
			</li>
			<li>PO
				<input type="text" id="s_po_filter" name="s_po_filter" value="<?=$s_po_filter?>" size="15">
			</li>
		</ul>
	</div>
	
	<div class="textboxlist separator">
		<ul>
			<li>Buyer
				<select name="s_buyer_filter" id="s_buyer_filter" style="width:129px;">
					<option value="">- - Buyer - -</option>
					<?=$s_buyer_filter?>
				</select>
			</li>
			<li>Order
				<select name="d_order_date_month_filter" id="d_order_date_month_filter" style="width:85px;">
					<option value="">- - Month - -</option>
					<?=$d_order_date_month_filter?>
				</select>
				<input type="text" id="d_order_date_year_filter" name="d_order_date_year_filter" value="<?=$d_order_date_year_filter?>" size="2">
			</li>
			<li>Prod.
				<select name="d_production_date_month_filter" id="d_production_date_month_filter" style="width:85px;">
					<option value="">- - Month - -</option>
					<?=$d_production_date_month_filter?>
				</select>
				<input type="text" id="d_production_date_year_filter" name="d_production_date_year_filter" value="<?=$d_production_date_year_filter?>" size="2">
			</li>
		</ul>
	</div>
	
	<div class="button separator">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>index/<?=$sDivision?>')" rel="btnFilterDownload" title="Search/Filter Download"><img src="<?=$baseurl?>images/ribbon/find32.png"/>Search</a>
	</div>
</form>