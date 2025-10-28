<form id="frmSearch" name="frmSearch" method="post" onKeyPress="_onPressEnter(this, event)">
	<table class="table" style="width:1150px;">
		<thead>
			<tr class="table_header">
				<th><a href="#" onClick="_doPost('sSort', 's_code', 'bSortAction')">Code</a></th>
				<th><a href="#" onClick="_doPost('sSort', 's_name', 'bSortAction')">Process Name</a></th>
				<th><a href="#" onClick="_doPost('sSort', 'n_line', 'bSortAction')">Line</a></th>
				<th width="50px"><a href="#" onClick="_doPost('sSort', 'n_total', 'bSortAction')">QTY</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_1', 'bSortAction')">1</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_2', 'bSortAction')">2</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_3', 'bSortAction')">3</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_4', 'bSortAction')">4</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_5', 'bSortAction')">5</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_6', 'bSortAction')">6</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_7', 'bSortAction')">7</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_8', 'bSortAction')">8</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_9', 'bSortAction')">9</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_10', 'bSortAction')">10</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_11', 'bSortAction')">11</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_12', 'bSortAction')">12</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_13', 'bSortAction')">13</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_14', 'bSortAction')">14</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_15', 'bSortAction')">15</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_16', 'bSortAction')">16</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_17', 'bSortAction')">17</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_18', 'bSortAction')">18</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_19', 'bSortAction')">19</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_20', 'bSortAction')">20</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_21', 'bSortAction')">21</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_22', 'bSortAction')">22</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_23', 'bSortAction')">23</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_24', 'bSortAction')">24</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_25', 'bSortAction')">25</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_26', 'bSortAction')">26</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_27', 'bSortAction')">27</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_28', 'bSortAction')">28</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_29', 'bSortAction')">29</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_30', 'bSortAction')">30</a></th>
				<th width="25px"><a href="#" onClick="_doPost('sSort', 'n_date_31', 'bSortAction')">31</a></th>
			</tr>
		</thead>
		<tbody>
			{aData}
			<tr><td>{s_code}</td>
				<td>{s_name}</td>
				<td>{n_line}</td>
				<td align="right">{n_total}</td>
				<td align="right">{n_date_1}</td>
				<td align="right">{n_date_2}</td>
				<td align="right">{n_date_3}</td>
				<td align="right">{n_date_4}</td>
				<td align="right">{n_date_5}</td>
				<td align="right">{n_date_6}</td>
				<td align="right">{n_date_7}</td>
				<td align="right">{n_date_8}</td>
				<td align="right">{n_date_9}</td>
				<td align="right">{n_date_10}</td>
				<td align="right">{n_date_11}</td>
				<td align="right">{n_date_12}</td>
				<td align="right">{n_date_13}</td>
				<td align="right">{n_date_14}</td>
				<td align="right">{n_date_15}</td>
				<td align="right">{n_date_16}</td>
				<td align="right">{n_date_17}</td>
				<td align="right">{n_date_18}</td>
				<td align="right">{n_date_19}</td>
				<td align="right">{n_date_20}</td>
				<td align="right">{n_date_21}</td>
				<td align="right">{n_date_22}</td>
				<td align="right">{n_date_23}</td>
				<td align="right">{n_date_24}</td>
				<td align="right">{n_date_25}</td>
				<td align="right">{n_date_26}</td>
				<td align="right">{n_date_27}</td>
				<td align="right">{n_date_28}</td>
				<td align="right">{n_date_29}</td>
				<td align="right">{n_date_30}</td>
				<td align="right">{n_date_31}</td>
			</tr>
			{/aData}
		</tbody>
		<tfoot class="table_footer">
			{aDataTotal}
			<tr><td></td>
				<td></td>
				<td></td>
				<td align="right">{n_t_total}</td>
				<td align="right">{n_t_date_1}</td>
				<td align="right">{n_t_date_2}</td>
				<td align="right">{n_t_date_3}</td>
				<td align="right">{n_t_date_4}</td>
				<td align="right">{n_t_date_5}</td>
				<td align="right">{n_t_date_6}</td>
				<td align="right">{n_t_date_7}</td>
				<td align="right">{n_t_date_8}</td>
				<td align="right">{n_t_date_9}</td>
				<td align="right">{n_t_date_10}</td>
				<td align="right">{n_t_date_11}</td>
				<td align="right">{n_t_date_12}</td>
				<td align="right">{n_t_date_13}</td>
				<td align="right">{n_t_date_14}</td>
				<td align="right">{n_t_date_15}</td>
				<td align="right">{n_t_date_16}</td>
				<td align="right">{n_t_date_17}</td>
				<td align="right">{n_t_date_18}</td>
				<td align="right">{n_t_date_19}</td>
				<td align="right">{n_t_date_20}</td>
				<td align="right">{n_t_date_21}</td>
				<td align="right">{n_t_date_22}</td>
				<td align="right">{n_t_date_23}</td>
				<td align="right">{n_t_date_24}</td>
				<td align="right">{n_t_date_25}</td>
				<td align="right">{n_t_date_26}</td>
				<td align="right">{n_t_date_27}</td>
				<td align="right">{n_t_date_28}</td>
				<td align="right">{n_t_date_29}</td>
				<td align="right">{n_t_date_30}</td>
				<td align="right">{n_t_date_31}</td>
			</tr>
			{/aDataTotal}
		</tfoot>
	</table>
</form>
<script type="text/javascript">
	function _onPressEnter(fForm, eEvent) {
		var nCode;
		if (!eEvent) var eEvent = window.event;
		if (eEvent.keyCode) 
			nCode = eEvent.keyCode;
		else if (eEvent.which) 
			nCode = eEvent.which;
		if (nCode==13) fForm.submit();
	}
</script>