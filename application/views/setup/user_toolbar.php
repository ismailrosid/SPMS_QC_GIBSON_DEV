<div class="button">
	<a href="<?=$siteurl?>index" rel="btnNewUser" title="Create New User"><img src="<?=$baseurl?>images/ribbon/new32.png"/>New</a>
</div>
<div class="button">
	<a href="javascript:_doRedirect('<?=$siteurl?>index/' + _getFirstCheckedRow() + '/1')" rel="btnEditUser" title="Modify User"><img src="<?=$baseurl?>images/ribbon/edit32.png"/>Edit</a>
</div>
<div class="button">
	<a href="javascript:_doPost(null, null, null, 'frmEdit', '<?=$siteurl?>delete', true, 'Are you sure you want to delete the selected item ?')" rel="btnDeleteUser" title="Remove User"><img src="<?=$baseurl?>images/ribbon/delete32.png"/>Delete</a>
</div>
<div class="button separator">
	<a href="javascript:document.frmEdit.submit()" rel="btnSaveUser" title="Save User"><img src="<?=$baseurl?>images/ribbon/save32.png"/>Save</a>
</div>
<div class="button">
	<a href="<?=$siteurl?>index" rel="btnCancelUser" title="Cancel Save User"><img src="<?=$baseurl?>images/ribbon/cancel32.png"/>Cancel</a>
</div>