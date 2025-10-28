<?php
if ($this->session->userdata('b_eg_transaction_write')) { ?>
<div class="button">
	<a href="javascript:document.frmEdit.submit()" rel="btnSaveTransaction" title="Save Transaction"><img src="<?=$baseurl?>images/ribbon/save32.png"/>Save</a>
</div>
<?php
} ?>
<div class="button">
	<a href="<?=$siteurl?>index/<?=$sDivision?>/1" rel="btnCancelTransaction" title="Cancel Save Transaction"><img src="<?=$baseurl?>images/ribbon/cancel32.png"/>Cancel</a>
</div>