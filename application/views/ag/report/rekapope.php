<table class="table" style="width:auto;">
    <thead>
        <tr class="table_header">
            <th>Tahun</th>
            <th>Order</th>
            <th>Packing</th>
            <th>Export</th>
        </tr>
    </thead>
    <tbody>
        {tt_rekap}
        <tr>
            <td>{tahun}</td>
            <td>{order}</td>
            <td>{packing}</td>
            <td>{export}</td>
        </tr>
        {/tt_rekap}
    </tbody>
    <tfoot class="table_footer">
        {t_rekap_total}
        <tr>
            <td></td>
            <td align="right">{t_order}</td>
            <td align="right">{t_packing}</td>
            <td align="right">{t_export}</td>
        </tr>
        {/t_rekap_total}
    </tfoot>
</table>
Total {nTotalRows} rows
<script type="text/javascript">
    function _onPressEnter(fForm, eEvent) {
        var nCode;
        if (!eEvent) var eEvent = window.event;
        if (eEvent.keyCode)
            nCode = eEvent.keyCode;
        else if (eEvent.which)
            nCode = eEvent.which;
        if (nCode == 13) fForm.submit();
    }
</script>
