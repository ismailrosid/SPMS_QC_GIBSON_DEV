<div class="button">
	<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>excel/<?=$sDivision?>')" rel="btnExcelProduct" title="Export Data Product to Excel"><img src="<?=$baseurl?>images/ribbon/excel32.png"/>Excel</a>
</div>
<?php
if ($this->session->userdata('b_eg_order_write')) { ?>
<div class="button">
	<a href="javascript:_doPost(null, null, null, 'frmEdit', '<?=$siteurl?>delete/<?=$sDivision?>/', true, 'Are you sure you want to delete the selected item ?')" rel="btnDeleteProduct" title="Remove Product"><img src="<?=$baseurl?>images/ribbon/delete32.png"/>Delete</a>
</div>
<?php
} ?>