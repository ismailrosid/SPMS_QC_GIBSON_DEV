<form id="frmSearch" name="frmSearch" method="post" onKeyPress="_onPressEnter(this, event)">
	<input type="hidden" name="nOffset" id="nOffset" value="{nCurrOffset}">
	<input type="hidden" name="nLimit" id="nLimit" value="{nRowsPerPage}">
	
	<input type="hidden" name="sSort" id="sSort" value="<?=$sSort?>"/>
	<input type="hidden" name="sSortMethod" id="sSortMethod" value="<?=$sSortMethod?>"/>
	<input type="hidden" name="bSortAction" id="bSortAction">
	
	<div class="textboxlist">
		<ul>
			<li>Buyer
				<select name="s_buyer_filter" id="s_buyer_filter" style="width:130px;">
					<option value="">- - Buyer - -</option>
					<?=$s_buyer_filter?>
				</select>
			</li>
		</ul>
	</div>
	
	<div class="textboxlist separator">
		<ul>
			<li>Prod.
				<select name="d_production_date_month_filter" id="d_production_date_month_filter" style="width:78px;">
					<option value="">- - Month - -</option>
					<?=$d_production_date_month_filter?>
				</select>
				<input type="text" id="d_production_date_year_filter" name="d_production_date_year_filter" value="<?=$d_production_date_year_filter?>" size="2">
			</li>
			<li>To
				<select name="d_production_date_month_filter2" id="d_production_date_month_filter2" style="width:78px;">
					<option value="">- - Month - -</option>
					<?=$d_production_date_month_filter2?>
				</select>
				<input name="d_production_date_year_filter2" id="d_production_date_year_filter2" value="<?=$d_production_date_year_filter2?>" size="2">
			</li>
		</ul>
	</div>
	
	<div class="button separator">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>')" rel="btnFilterReportBuyer" title="Search/Filter Buyer Report"><img src="<?=$baseurl?>images/ribbon/find32.png"/>Search</a>
	</div>
	<div class="button">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$basesiteurl?>/eg/reportlist2/groupexcel/<?=$sViewReport?>')" rel="btnExcelReportBuyer" title="Export Buyer Report to Excel"><img src="<?=$baseurl?>images/ribbon/excel32.png"/>Excel</a>
	</div>
</form>