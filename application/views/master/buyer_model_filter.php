<form id="frmSearch" name="frmSearch" method="post" onKeyPress="_onPressEnter(this, event)">
	<input type="hidden" name="nOffset" id="nOffset" value="<?=$nCurrOffset?>">
	<input type="hidden" name="nLimit" id="nLimit" value="<?=$nRowsPerPage?>">
	
	<input type="hidden" name="sSort" id="sSort" value="<?=$sSort?>"/>
	<input type="hidden" name="sSortMethod" id="sSortMethod" value="<?=$sSortMethod?>"/>
	<input type="hidden" name="bSortAction" id="bSortAction">
	
	<div class="textboxlist">
		<ul>
			<li>Buyer<input type="text" id="s_buyer_filter" name="s_buyer_filter" value="<?=$s_buyer_filter?>" size="13"></li>
			<li>Model<input type="text" id="s_model_filter" name="s_model_filter" value="<?=$s_model_filter?>" size="13"></li>
		</ul>
	</div>
	<div class="button separator">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>')" rel="btnFilterBuyerModel" title="Search/Filter Buyer Model"><img src="<?=$baseurl?>images/ribbon/find32.png"/>Search</a>
	</div>
</form>