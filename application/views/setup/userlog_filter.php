<form id="frmSearch" name="frmSearch" method="post" onKeyPress="_onPressEnter(this, event)">
	<input type="hidden" name="nOffset" id="nOffset" value="<?=$nCurrOffset?>">
	<input type="hidden" name="nLimit" id="nLimit" value="<?=$nRowsPerPage?>">
	
	<input type="hidden" name="sSort" id="sSort" value="<?=$sSort?>"/>
	<input type="hidden" name="sSortMethod" id="sSortMethod" value="<?=$sSortMethod?>"/>
	<input type="hidden" name="bSortAction" id="bSortAction">
	<div class="textboxlist">
		<ul>
			<li>User Name<input type="text" id="s_username_filter" name="s_username_filter" value="<?=$s_username_filter?>" size="15"></li>
			<li>Level<input type="text" id="s_level_filter" name="s_level_filter" value="<?=$s_level_filter?>" size="15"></li>
		</ul>
	</div>
	<div class="textboxlist separator">
		<ul>
			<li>Name<input type="text" id="s_name_filter" name="s_name_filter" value="<?=$s_name_filter?>" size="15"></li>
			<li>IP<input type="text" id="ip_address_filter" name="ip_address_filter" value="<?=$ip_address_filter?>" size="15"></li>
		</ul>
	</div>
	<div class="textboxlist separator">
		<ul>
			<li>In Date
				<input class="button" type="reset" value="..." onclick="return showCalendar('d_login_filter');" name="cld_d_login_filter"/>
				<input type="text" id="d_login_filter" name="d_login_filter" value="<?=$d_login_filter?>" size="7">
			</li>
		</ul>
	</div>
	<div class="button separator">
		<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>')" rel="btnFilterUser" title="Search/Filter User"><img src="<?=$baseurl?>images/ribbon/find32.png"/>Search</a>
	</div>
</form>