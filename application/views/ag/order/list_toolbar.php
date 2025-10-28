<div class="button">
	<a href="javascript:_doPost(null, null, null, 'frmSearch', '<?=$siteurl?>excel/<?=$sDivision?>')" rel="btnExcelOrder" title="Export Data Order to Excel"><img src="<?=$baseurl?>images/ribbon/excel32.png"/>Excel</a>
</div>
<?php
if ($this->session->userdata('b_ag_order_write')) { ?>
<div class="button">
	<a href="<?=$siteurl?>viewedit/<?=$sDivision?>" rel="btnNewOrder" title="Create New Order"><img src="<?=$baseurl?>images/ribbon/new32.png"/>New</a>
</div>
<!--
<div class="button">
	<a href="javascript:_doRedirect('<?=$basesiteurl?>/production/product/viewedit/<?=$sDivision?>/0/' + _getFirstCheckedRow())" rel="btnNewOrder" title="Add New Product by Selected Order"><img src="<?=$baseurl?>images/ribbon/new32.png"/>Add Product</a>
</div>
-->
<div class="button">
	<a href="javascript:_doPost(null, null, null, 'frmEdit', '<?=$siteurl?>delete/<?=$sDivision?>/', true, 'Are you sure you want to delete the selected item ?')" rel="btnDeleteOrder" title="Remove Order"><img src="<?=$baseurl?>images/ribbon/delete32.png"/>Delete</a>
</div>
<?php
} ?>