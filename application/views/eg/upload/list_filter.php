<form id="frmSearch" name="frmSearch" method="post" action="<?=$siteurl?>uploaded/<?=$sDivision?>/<?=$sFileName?>" onKeyPress="_onPressEnter(this, event)">
	<div class="textboxlist">
		<ul><li>Serial No<input type="text" id="s_serial_no_filter" name="s_serial_no_filter" value="<?=$s_serial_no_filter?>" size="18"></li>
			<li>Date
				<input type="text" id="d_transaction_date_filter" name="d_transaction_date_filter" value="<?=$d_transaction_date_filter?>" size="11">
				<input class="button" type="reset" value="..." onclick="return showCalendar('d_transaction_date_filter');" name="cldd_transaction_date_filter"/>
			</li>
		</ul>
	</div>
	<div class="textboxlist separator">
		<ul><li>Phase
				<select name="s_phase_filter" id="s_phase_filter" style="width:85px;">
					<option value="">- - Phase - -</option>
					<?=$s_phase_filter?>
				</select>
			</li>
		</ul>
	</div>
	<div class="button separator">
		<a href="javascript:document.frmSearch.submit()" rel="btnFilterUpload" title="Search/Filter Upload"><img src="<?=$baseurl?>images/ribbon/find32.png"/>Search</a>
	</div>
</form>