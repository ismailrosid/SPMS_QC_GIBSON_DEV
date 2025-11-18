<form id="frmSearch" name="frmSearch" method="post" onKeyPress="_onPressEnter(this, event)">
    <input type="hidden" name="nOffset" id="nOffset" value="<?= $nCurrOffset ?>">
    <input type="hidden" name="nLimit" id="nLimit" value="<?= $nRowsPerPage ?>">

    <input type="hidden" name="sSort" id="sSort" value="<?= $sSort ?>" />
    <input type="hidden" name="sSortMethod" id="sSortMethod" value="<?= $sSortMethod ?>" />
    <input type="hidden" name="bSortAction" id="bSortAction">
    <div style="display: flex; align-items: center;" class="textboxlist textboxlistCostume">
        <table class="custom-table">
            <tr>
                <td>Prod</td>
                <td> <input type="text" id="d_production_date_year_filter" name="d_production_date_year_filter" value="<?= $d_production_date_year_filter ?>" size="4"></td>
                <td> Until </td>
                <td> <input type="text" id="d_production_date_year_filter2" name="d_production_date_year_filter2" value="<?= $d_production_date_year_filter2 ?>" size="4"></td>
            </tr>
        </table>
    </div>
    <div class="button separator">
        <a href="javascript:_doPost(null, null, null, 'frmSearch', '<?= $siteurl ?>')" rel="btnFilterReportSerial" title="Search/Filter Serial Report"><img src="<?= $baseurl ?>images/ribbon/find32.png" />Search</a>
    </div>
    <div class="button">
        <a href="javascript:_doPost(null, null, null, 'frmSearch', '<?= $siteurl ?>1')" rel="btnExcelReportSerial" title="Export Serial Report to Excel"><img src="<?= $baseurl ?>images/ribbon/excel32.png" />Excel</a>
    </div>
</form>
