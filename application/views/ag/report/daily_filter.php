<form id="frmSearch" name="frmSearch" method="post" onKeyPress="_onPressEnter(this, event)">
	<input type="hidden" name="nOffset" id="nOffset" value="<?=$nCurrOffset?>">
	<input type="hidden" name="nLimit" id="nLimit" value="<?=$nRowsPerPage?>">
	
	<input type="hidden" name="sSort" id="sSort" value="{sSort}"/>
	<input type="hidden" name="sSortMethod" id="sSortMethod" value="{sSortMethod}"/>
	<input type="hidden" name="bSortAction" id="bSortAction">
	
	<div class="textboxlist">
		<ul>
			<li>Prod.
				<select name="d_production_date_month_filter" id="d_production_date_month_filter" style="width:85px;">
					<?=$d_production_date_month_filter?>
				</select>
				<input type="text" name="d_production_date_year_filter" id="d_production_date_year_filter" value="<?=$d_production_date_year_filter?>" size="3">
			</li>
		</ul>
	</div>
	

	<!--
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
	-->

	<div class="button separator">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>')" rel="btnFilterReportDaily" title="Search/Filter Daily Report"><img src="<?=$baseurl?>images/ribbon/find32.png"/>Search</a>
	</div>
	<div class="button">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>1')" rel="btnExcelReportDaily" title="Export Daily Report to Excel"><img src="<?=$baseurl?>images/ribbon/excel32.png"/>Excel</a>
	</div>
</form>
