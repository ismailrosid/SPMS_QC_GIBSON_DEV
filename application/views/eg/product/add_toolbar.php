<?php
if ($this->session->userdata('b_eg_order_write')) { ?>
<div class="button">
	<a href="javascript:if(setProcessValidation()) document.frmEdit.submit()" rel="btnSaveProduct" title="Save Product"><img src="<?=$baseurl?>images/ribbon/save32.png"/>Save</a>
</div>
<?php
} ?>
<div class="button">
	<a href="<?=$siteurl?>index/<?=$sDivision?>/1" rel="btnCancelProduct" title="Cancel Save Product"><img src="<?=$baseurl?>images/ribbon/cancel32.png"/>Cancel</a>
</div>