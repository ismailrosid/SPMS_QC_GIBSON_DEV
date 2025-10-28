<?php	if ($this->session->userdata('b_setup_write')) { ?>
<div class="button">
	<a href="javascript:document.frmEdit.submit()" rel="btnSavePreferences" title="Save Preferences"><img src="<?=$baseurl?>images/ribbon/save32.png"/>Save</a>
</div>
<div class="button">
	<a href="<?=$siteurl?>index" rel="btnCancelPreferences" title="Cancel Save Preferences"><img src="<?=$baseurl?>images/ribbon/cancel32.png"/>Cancel</a>
</div>
<?php	} ?>