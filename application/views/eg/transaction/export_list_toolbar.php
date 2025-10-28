<div class="button textlist">
	<ul>
		<li>
			<input type="text" id="d_transaction_date_input" name="d_transaction_date_input" size="8" value="<?=$d_transaction_date_input?>">
			<input class="button" type="reset" value="..." onclick="return showCalendar('d_transaction_date_input');" name="cldd_transaction_date_input"/>
			<select id="s_product_location_input" name="s_product_location_input">
				<?=$s_product_location_input?>
			</select>
		</li>
		<li><a href="javascript:_onExportAction()" title="Export"><img src="<?=$baseurl?>images/ribbon/production_cek_activity16.png" alt=""/>Gudang Marketing (Out)</a></li>
	</ul>
</div>