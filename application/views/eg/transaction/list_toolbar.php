<div class="button">
	<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>excel/<?=$sDivision?>/<?=$sPhaseName?>')" rel="btnExcelProcess" title="Export Data Process to Excel"><img src="<?=$baseurl?>images/ribbon/excel32.png"/>Excel</a>
</div>
<?php
if ($this->session->userdata('b_eg_transaction_write')) { ?>
<div class="button">
	<a href="javascript:_doPost(null, null, null, 'frmEdit', '<?=$siteurl?>add/<?=$sDivision?>')" rel="btnEditProcess" title="Modify Selected Process"><img src="<?=$baseurl?>images/ribbon/edit32.png"/>Edit</a>
</div>
<?php
	if ($this->session->userdata('b_replace_production')) { ?>
<div class="button">
	<a href="javascript:_doPost(null, null, null, 'frmEdit', '<?=$siteurl?>delete/<?=$sDivision?>/', true, 'Are you sure you want to delete the selected item ?')" rel="btnDeleteProcess" title="Remove Process"><img src="<?=$baseurl?>images/ribbon/delete32.png"/>Delete</a>
</div>
<?php
	}
}?>
