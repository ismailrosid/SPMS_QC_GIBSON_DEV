<form name="frmEdit" id="frmEdit" method="post" action="{formaction}" enctype="multipart/form-data">
	{MESSAGES}
	{editable}
	<table align="left">
		<tr><td valign="top">
				<table class="form_view" width="370px">
					<thead>
						<tr><th colspan="2" class="table_header">Product Info</th></tr>
					</thead>
					<tbody>
						<tr><td>Number</td>
							<td>{n_serial_no}</td>
						</tr>
						<tr><td>Serial No</td>
							<td><input type="text" id="s_serial_no" name="s_serial_no" value="{s_serial_no}" maxlength="21">
<?php 							if ($operation=='add') { ?>
								<br>
								<font class="message_notes">Set {AUTO} to automatically create serial number order by<br>number and buyer</font>
<?php 							} ?>
							</td>
						</tr>
						<tr><td>PI Number</td>
							<td>{s_po_no}</td>
						</tr>
						<tr><td>PO</td>
							<td>{s_po}</td>
						</tr>
						<tr><td>Location</td>
							<td><select id="s_location" name="s_location">
									<option value="">- - Select Location - -</option>
									{s_location}
								</select>
							</td>
						</tr>
						<tr><td>Lot Number</td>
							<td><input type="text" id="s_lot_no" name="s_lot_no" value="{s_lot_no}" maxlength="25"></td>
						</tr>
						<tr><td>Buyer</td>
							<td><select id="s_buyer" name="s_buyer" style="width:250px;">
									<option value="">- - Select Buyer - -</option>
									{s_buyer}
								</select>
							</td>
						</tr>
						<tr><td>Brand</td>
							<td><input type="text" id="s_brand" name="s_brand" value="{s_brand}" maxlength="50"></td>
						</tr>
						<tr><td>Bench Mark</td>
							<td><input type="text" id="s_bench" name="s_bench" value="{s_bench}" maxlength="50"></td>
						</tr>
						<tr><td>Model</td>
							<td><select id="s_model" name="s_model" style="width:250px;">
									<option value="">- - Select Model - -</option>
									{s_model}
								</select>
							</td>
						</tr>
						<tr><td>Color</td>
							<td><select id="s_color" name="s_color" style="width:250px;">
									<option value="">- - Select Color - -</option>
									{s_color}
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
			<td valign="top">
				<table class="form_view" width="300px">
					<thead>
						<tr><th colspan="2" class="table_header">Production Date</th></tr>
					</thead>
					<tbody>
						<tr><td>Order</td>
							<td><input type="text" id="d_order_date" name="d_order_date" value="{d_order_date}" maxlength="10">
								<input class="button" type="reset" value="..." onclick="return showCalendar('d_order_date');" name="cldd_order_date"/>
							</td>
						</tr>
						<tr><td>Production</td>
							<td><input type="text" id="d_production_date" name="d_production_date" value="{d_production_date}" maxlength="10">
								<input class="button" type="reset" value="..." onclick="return showCalendar('d_production_date');" name="cldd_production_date"/>
							</td>
						</tr>
						<tr><td>Plan</td>
							<td><input type="text" id="d_plan_date" name="d_plan_date" value="{d_plan_date}" maxlength="10">
								<input class="button" type="reset" value="..." onclick="return showCalendar('d_plan_date');" name="cldd_plan_date"/>
							</td>
						</tr>
						<tr><td>Delivery</td>
							<td><input type="text" id="d_delivery_date" name="d_delivery_date" value="{d_delivery_date}" maxlength="10">
								<input class="button" type="reset" value="..." onclick="return showCalendar('d_delivery_date');" name="cldd_delivery_date"/>
							</td>
						</tr>
						<tr><td>Target</td>
							<td><input type="text" id="d_target_date" name="d_target_date" value="{d_target_date}" maxlength="10">
								<input class="button" type="reset" value="..." onclick="return showCalendar('d_target_date');" name="cldd_target_date"/>
							</td>
						</tr>
					</tbody>
				</table>
				<table class="form_view" width="300px">
					<thead>
						<tr><th colspan="2" class="table_header">More Info</th></tr>
					</thead>
					<tbody>
						<tr><td>S Model</td>
							<td><input type="text" id="s_smodel" name="s_smodel" value="{s_smodel}" maxlength="15"></td>
						</tr>
						<tr><td>Invoice</td>
							<td><input type="text" id="s_invoice" name="s_invoice" value="{s_invoice}" maxlength="15"></td>
						</tr>
						<tr><td>Price</td>
							<td><input type="text" id="n_price" name="n_price" value="{n_price}" maxlength="12" size="12" style="text-align:right;"></td>
						</tr>
						<tr><td>Proforma</td>
							<td><input type="text" id="s_proforma" name="s_proforma" value="{s_proforma}" maxlength="50"></td>
						</tr>
					</tbody>
				</table>
			</td>
			<td valign="top" align="left">
				<input type="checkbox" id="bValidation" name="bValidation" value="1" checked><label for="bValidation" id="lblValidation">Set Validation</label>
				<table class="table" style="min-width:430px;">
				<thead>
					<tr class="table_header">
						<th colspan="3">Production Process</th>
					</tr>
				</thead>
				<tbody>
					<tr><td>b21020 (WK Center Input)</td>
						<td><input type="text" id="d_process_1" name="d_process_1" value="{d_process_1}" maxlength="10">
							<input class="button" type="reset" value="..." onclick="return showCalendar('d_process_1');" name="cldd_process_1"/><br/>
							Plan : {d_process_1_plan}
						</td>
						<td><select id="s_process_1_location" name="s_process_1_location">
								<option value="">- - Select Location - -</option>
								{s_process_1_location}
							</select>
						</td>
					</tr>
					<tr><td>b21030 (WK Center Output - Body)</td>
						<td><input type="text" id="d_process_2" name="d_process_2" value="{d_process_2}" maxlength="10">
							<input class="button" type="reset" value="..." onclick="return showCalendar('d_process_2');" name="cldd_process_2"/><br/>
							Plan : {d_process_2_plan}
						</td>
						<td><select id="s_process_2_location" name="s_process_2_location">
								<option value="">- - Select Location - -</option>
								{s_process_2_location}
							</select>
						</td>
					</tr>
					<tr><td>b21040 (Wood Working)</td>
						<td><input type="text" id="d_process_3" name="d_process_3" value="{d_process_3}" maxlength="10">
							<input class="button" type="reset" value="..." onclick="return showCalendar('d_process_3');" name="cldd_process_3"/><br/>
							Plan : {d_process_3_plan}
						</td>
						<td><select id="s_process_3_location" name="s_process_3_location">
								<option value="">- - Select Location - -</option>
								{s_process_3_location}
							</select>
						</td>
					</tr>
					<tr><td>b21050 (Coating-I - Neck)</td>
						<td><input type="text" id="d_process_4" name="d_process_4" value="{d_process_4}" maxlength="10">
							<input class="button" type="reset" value="..." onclick="return showCalendar('d_process_4');" name="cldd_process_4"/><br/>
							Plan : {d_process_4_plan}
						</td>
						<td><select id="s_process_4_location" name="s_process_4_location">
								<option value="">- - Select Location - -</option>
								{s_process_4_location}
							</select>
						</td>
					</tr>
					<tr><td>b21060 (Sanding)</td>
						<td><input type="text" id="d_process_5" name="d_process_5" value="{d_process_5}" maxlength="10">
							<input class="button" type="reset" value="..." onclick="return showCalendar('d_process_5');" name="cldd_process_5"/><br/>
							Plan : {d_process_5_plan}
						</td>
						<td><select id="s_process_5_location" name="s_process_5_location">
								<option value="">- - Select Location - -</option>
								{s_process_5_location}
							</select>
						</td>
					</tr>
					<tr><td>b21070 (Coating-IIA)</td>
						<td><input type="text" id="d_process_6" name="d_process_6" value="{d_process_6}" maxlength="10">
							<input class="button" type="reset" value="..." onclick="return showCalendar('d_process_6');" name="cldd_process_6"/><br/>
							Plan : {d_process_6_plan}
						</td>
						<td><select id="s_process_6_location" name="s_process_6_location">
								<option value="">- - Select Location - -</option>
								{s_process_6_location}
							</select>
						</td>
					</tr>
					<tr><td>b21080 (Coating-IIB)</td>
						<td><input type="text" id="d_process_7" name="d_process_7" value="{d_process_7}" maxlength="10">
							<input class="button" type="reset" value="..." onclick="return showCalendar('d_process_7');" name="cldd_process_7"/><br/>
							Plan : {d_process_7_plan}
						</td>
						<td><select id="s_process_7_location" name="s_process_7_location">
								<option value="">- - Select Location - -</option>
								{s_process_7_location}
							</select>
						</td>
					</tr>
					<tr><td>b21090 (Assembly-I_Control Center)</td>
						<td><input type="text" id="d_process_8" name="d_process_8" value="{d_process_8}" maxlength="10">
							<input class="button" type="reset" value="..." onclick="return showCalendar('d_process_8');" name="cldd_process_8"/><br/>
							Plan : {d_process_8_plan}
						</td>
						<td><select id="s_process_8_location" name="s_process_8_location">
								<option value="">- - Select Location - -</option>
								{s_process_8_location}
							</select>
						</td>
					</tr>
					<tr><td>b21100 (Assembly-II)</td>
						<td><input type="text" id="d_process_9" name="d_process_9" value="{d_process_9}" maxlength="10">
							<input class="button" type="reset" value="..." onclick="return showCalendar('d_process_9');" name="cldd_process_9"/><br/>
							Plan : {d_process_9_plan}
						</td>
						<td><select id="s_process_9_location" name="s_process_9_location">
								<option value="">- - Select Location - -</option>
								{s_process_9_location}
							</select>
						</td>
					</tr>
					<tr><td>b21110 (Packing)</td>
						<td><input type="text" id="d_process_10" name="d_process_10" value="{d_process_10}" maxlength="10">
							<input class="button" type="reset" value="..." onclick="return showCalendar('d_process_10');" name="cldd_process_10"/><br/>
							Plan : {d_process_10_plan}
						</td>
						<td><select id="s_process_10_location" name="s_process_10_location">
								<option value="">- - Select Location - -</option>
								{s_process_10_location}
							</select>
						</td>
					</tr>
					<tr><td>b21130 (Warehouse Outgoing)</td>
						<td><input type="text" id="d_process_14" name="d_process_14" value="{d_process_14}" maxlength="10">
							<input class="button" type="reset" value="..." onclick="return showCalendar('d_process_14');" name="cldd_process_14"/><br/>
							Plan : {d_process_14_plan}
						</td>
						<td><select id="s_process_14_location" name="s_process_14_location">
								<option value="">- - Select Location - -</option>
								{s_process_14_location}
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</table>
	{/editable}
</form>
<script type="text/javascript">
	{VALIDATE_JS}
	
	function setProcessValidation() {
		var bValid=true;
		if ( 	$('#d_process_10').val().replace(' ', '')=='' && 
				$('#d_process_14').val().replace(' ', '')!='') {
			if ( $('#bValidation').attr('checked')==true ) {
				bValid=false;
			} else {
				//$('#d_process_10').val('1900-01-01');
			}
		}
		for (var iCount=10; iCount>=2; iCount--) {
			if ( 	$('#d_process_' + (iCount - 1).toString()).val().replace(' ', '')=='' && 
					$('#d_process_' + iCount.toString()).val().replace(' ', '')!='' ) {
				if ( $('#bValidation').attr('checked')==true ) {
					bValid=false;
				} else {
					//$('#d_process_' + (iCount - 1).toString()).val('1900-01-01');
				}
			}
		}
		if (bValid==false) {
			alert('Process Date is Failed');
		}
		return bValid;
	}
</script>