<div class="button">
	<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>excel')" rel="btnExcelModel" title="Export Data Model to Excel"><img src="<?=$baseurl?>images/ribbon/excel32.png"/>Excel</a>
</div>
<?php
if ($this->session->userdata('b_master_write')) { ?>
<div class="button">
	<a href="<?=$siteurl?>index" rel="btnNewModel" title="Create New Model"><img src="<?=$baseurl?>images/ribbon/new32.png"/>New</a>
</div>
<div class="button">
	<a href="javascript:_doRedirect('<?=$siteurl?>index/' + _getFirstCheckedRow() + '/1')" rel="btnEditModel" title="Modify Model"><img src="<?=$baseurl?>images/ribbon/edit32.png"/>Edit</a>
</div>
<div class="button">
	<a href="javascript:_doPost(null, null, null, 'frmEdit', '<?=$siteurl?>delete', true, 'Are you sure you want to delete the selected item ?')" rel="btnDeleteModel" title="Remove Model">
	<img src="<?=$baseurl?>images/ribbon/delete32.png"/>Delete</a>
</div>
<div class="button separator">
	<a href="javascript:document.frmEdit.submit()" rel="btnSaveModel" title="Save Model"><img src="<?=$baseurl?>images/ribbon/save32.png"/>Save</a>
</div>
<?php
} ?>
<div class="button">
	<a href="<?=$siteurl?>index" rel="btnCancelModel" title="Cancel Save Model"><img src="<?=$baseurl?>images/ribbon/cancel32.png"/>Cancel</a>
</div>
