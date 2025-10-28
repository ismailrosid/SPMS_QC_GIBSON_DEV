<?php
if ($this->session->userdata('b_ag_setup_write')) { ?>
<div class="button">
	<a href="javascript:document.form1.submit()" rel="btnSaveSetup" title="Save Setup"><img src="<?=$baseurl?>images/ribbon/save32.png"/>Save</a>
</div>
<?php
} ?>
<div class="button">
	<a href="<?=$siteurl?>index" rel="btnCancelSetup" title="Cancel Save Setup"><img src="<?=$baseurl?>images/ribbon/cancel32.png"/>Cancel</a>
</div>