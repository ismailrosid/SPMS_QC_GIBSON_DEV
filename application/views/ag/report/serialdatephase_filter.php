<form id="frmSearch" name="frmSearch" method="post" onKeyPress="_onPressEnter(this, event)">
	<input type="hidden" name="nOffset" id="nOffset" value="<?=$nCurrOffset?>">
	<input type="hidden" name="nLimit" id="nLimit" value="<?=$nRowsPerPage?>">
	
	<input type="hidden" name="sSort" id="sSort" value="<?=$sSort?>"/>
	<input type="hidden" name="sSortMethod" id="sSortMethod" value="<?=$sSortMethod?>"/>
	<input type="hidden" name="bSortAction" id="bSortAction">
	
	<div class="textboxlist">
		<ul>
			<li>Serial
				<input type="text" id="s_serial_no_filter" name="s_serial_no_filter" value="<?=$s_serial_no_filter?>" size="7">
				<input type="text" id="s_serial_no2_filter" name="s_serial_no2_filter" value="<?=$s_serial_no2_filter?>" size="6">
			</li>
			<li>Phase
				<select name="s_phase_filter" id="s_phase_filter" style="width:128px;">
					<?=$s_phase_filter?>
				</select>
			</li>
			<li>Date
				<input type="text" id="d_transaction_date_filter" name="d_transaction_date_filter" size="8" value="<?=$d_transaction_date_filter?>">
				<input class="button" type="reset" value="..." onclick="return showCalendar('d_transaction_date_filter');" name="cldd_transaction_date_filter"/>
			</li>
		</ul>
	</div>
	
	<div class="textboxlist separator">
		<ul>
			<li>PI No
				<input type="text" id="s_po_no_filter" name="s_po_no_filter" value="<?=$s_po_no_filter?>" size="10">
			</li>
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
			<li>Prod.
				<select name="d_production_date_month_filter" id="d_production_date_month_filter" style="width:78px;">
					<option value="">- - Month - -</option>
					<?=$d_production_date_month_filter?>
				</select>
				<input type="text" id="d_production_date_year_filter" name="d_production_date_year_filter" value=
					"<?=$d_production_date_year_filter?>" size="2">
			</li>
			<li>To
				<select name="d_production_date_month_filter2" id="d_production_date_month_filter2" style="width:78px;">
					<option value="">- - Month - -</option>
					<?=$d_production_date_month_filter2?>
				</select>
				<input name="d_production_date_year_filter2" id="d_production_date_year_filter2" value=
					"<?=$d_production_date_year_filter2?>" size="2">
			</li>
		</ul>
	</div>

	<div class="textboxlist separator">
		<ul>
			<li>Location
				<select id="s_location_filter" name="s_location_filter" style="width:119px;">
					<option value="">- - Select Location - -</option>
					<?=$s_location_filter?>
				</select>
			</li>

		</ul>
	</div>
	
	<div class="button separator">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>')" rel="btnFilterReportSerialDatePhase" title="Search/Filter Serial Date Phase Report"><img src="<?=$baseurl?>images/ribbon/find32.png"/>Search</a>
	</div>
	<div class="button">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>1')" rel="btnExcelReportSerialDatePhase" title="Export Serial Date Phase Report to Excel"><img src="<?=$baseurl?>images/ribbon/excel32.png"/>Excel</a>
	</div>
</form>
