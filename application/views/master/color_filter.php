<form id="frmSearch" name="frmSearch" method="post" onKeyPress="_onPressEnter(this, event)">
	<input type="hidden" name="nOffset" id="nOffset" value="<?=$nCurrOffset?>">
	<input type="hidden" name="nLimit" id="nLimit" value="<?=$nRowsPerPage?>">
	
	<input type="hidden" name="sSort" id="sSort" value="<?=$sSort?>"/>
	<input type="hidden" name="sSortMethod" id="sSortMethod" value="<?=$sSortMethod?>"/>
	<input type="hidden" name="bSortAction" id="bSortAction">
	
	<div class="textboxlist">
		<ul>
			<li>Color Code<input type="text" id="s_code_filter" name="s_code_filter" value="<?=$s_code_filter?>" size="13"></li>
			<li>Division
				<select name="s_division_filter" id="s_division_filter" style="width:94px;">
					<option value="">- - Division - -</option>
					<?=$s_division_filter?>
				</select>
			</li>
		</ul>
	</div>
	<div class="textboxlist separator">
		<ul>
			<li>Description<input type="text" id="s_description_filter" name="s_description_filter" value="<?=$s_description_filter?>" size="15"></li>
			<li>Type<input type="text" id="s_type_filter" name="s_type_filter" value="<?=$s_type_filter?>" size="15"></li>
		</ul>
	</div>
	<div class="button separator">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>')" rel="btnFilterColor" title="Search/Filter Color"><img src="<?=$baseurl?>images/ribbon/find32.png"/>Search</a>
	</div>
</form>