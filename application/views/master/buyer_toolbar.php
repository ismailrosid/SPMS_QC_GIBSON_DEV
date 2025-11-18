<div class="button">
	<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?= $siteurl ?>excel')" rel="btnExcelBuyer" title="Export Data Buyer to Excel"><img src="<?= $baseurl ?>images/ribbon/excel32.png" />Excel</a>
</div>
<?php
if ($this->session->userdata('b_master_write')) { ?>
	<div class="button">
		<a href="<?= $siteurl ?>index" rel="btnNewBuyer" title="Create New Buyer"><img src="<?= $baseurl ?>images/ribbon/new32.png" />New</a>
	</div>
	<div class="button">
		<a href="javascript:_doRedirect('<?= $siteurl ?>index/' + _getFirstCheckedRow() + '/1')" rel="btnEditBuyer" title="Modify Buyer"><img src="<?= $baseurl ?>images/ribbon/edit32.png" />Edit</a>
	</div>
	<div class="button">
		<a href="javascript:_doPost(null, null, null, 'frmEdit', '<?= $siteurl ?>editStatus/1', true, 'Are you sure you want to Active the selected item ?')" rel="btnDeleteModel" title="Active Model"><img src="<?= $baseurl ?>images/ribbon/edit32.png" />Active</a>
	</div>
	<div class="button">
		<a href="javascript:_doPost(null, null, null, 'frmEdit', '<?= $siteurl ?>editStatus/0', true, 'Are you sure you want to Non Active the selected item ?')" rel="btnDeleteModel" title="Non Active Model"><img src="<?= $baseurl ?>images/ribbon/edit32.png" />Non Active</a>
	</div>
	<div class="button">
		<a href="javascript:_doPost(null, null, null, 'frmEdit', '<?= $siteurl ?>delete', true, 'Are you sure you want to delete the selected item ?')" rel="btnDeleteBuyer" title="Remove Buyer"><img src="<?= $baseurl ?>images/ribbon/delete32.png" />Delete</a>
	</div>
	<div class="button separator">
		<a href="javascript:document.frmEdit.submit()" rel="btnSaveBuyer" title="Save Buyer"><img src="<?= $baseurl ?>images/ribbon/save32.png" />Save</a>
	</div>
<?php
} ?>
<div class="button">
	<a href="<?= $siteurl ?>index" rel="btnCancelBuyer" title="Cancel Save Buyer"><img src="<?= $baseurl ?>images/ribbon/cancel32.png" />Cancel</a>
</div>
