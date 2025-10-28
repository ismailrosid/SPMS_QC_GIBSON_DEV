<div class="button">
	<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>excel')" rel="btnExcelColor" title="Export Data Color to Excel"><img src="<?=$baseurl?>images/ribbon/excel32.png"/>Excel</a>
</div>
<?php
if ($this->session->userdata('b_master_write')) { ?>
<div class="button">
	<a href="<?=$siteurl?>index" rel="btnNewColor" title="Create New Color"><img src="<?=$baseurl?>images/ribbon/new32.png"/>New</a>
</div>
<div class="button">
	<a href="javascript:_doRedirect('<?=$siteurl?>index/' + _getFirstCheckedRow() + '/1')" rel="btnEditColor" title="Modify Color"><img src="<?=$baseurl?>images/ribbon/edit32.png"/>Edit</a>
</div>
<div class="button">
	<a href="javascript:_doPost(null, null, null, 'frmEdit', '<?=$siteurl?>delete', true, 'Are you sure you want to delete the selected item ?')" rel="btnDeleteColor" title="Remove Color"><img src="<?=$baseurl?>images/ribbon/delete32.png"/>Delete</a>
</div>
<div class="button separator">
	<a href="javascript:document.frmEdit.submit()" rel="btnSaveColor" title="Save Color"><img src="<?=$baseurl?>images/ribbon/save32.png"/>Save</a>
</div>
<?php
} ?>
<div class="button">
	<a href="<?=$siteurl?>index" rel="btnCancelColor" title="Cancel Save Color"><img src="<?=$baseurl?>images/ribbon/cancel32.png"/>Cancel</a>
</div>