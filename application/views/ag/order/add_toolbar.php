<?php
if ($this->session->userdata('b_ag_order_write')) { ?>
<div class="button">
	<a href="javascript:document.frmEdit.submit()" rel="btnSaveOrder" title="Save Order"><img src="<?=$baseurl?>images/ribbon/save32.png"/>Save</a>
</div>
<?php
} ?>
<div class="button">
	<a href="<?=$siteurl?>index/<?=$sDivision?>/1" rel="btnCancelOrder" title="Cancel Save Order"><img src="<?=$baseurl?>images/ribbon/cancel32.png"/>Cancel</a>
</div>