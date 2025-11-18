<form name="frmEdit" id="frmEdit" method="post" action="{formaction}" enctype="multipart/form-data">
	{MESSAGES}
	{editable}
	<table align="left">
		<tr>
			<td valign="top">
				<table class="form_view" width="370px">
					<thead>
						<tr>
							<th colspan="2" class="table_header">Order Info</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>PI Number</td>
							<td><input type="text" id="s_po_no" name="s_po_no" value="{s_po_no}" maxlength="25"></td>
						</tr>
						<tr>
							<td>PO</td>
							<td><input type="text" id="s_po" name="s_po" value="{s_po}" maxlength="25"></td>
						</tr>
						<tr>
							<td>Type</td>
							<td><select id="s_type" name="s_type">
									{s_type}
								</select>
							</td>
						</tr>
						<tr>
							<td>Location</td>
							<td><select id="s_location" name="s_location">
									{s_location}
								</select>
							</td>
						</tr>
						<tr>
							<td>Buyer</td>
							<td>
								<!-- <select id="s_buyer" name="s_buyer" style="width:250px;">
									<option value="">- - Select Buyer - -</option>
									{s_buyer}
								</select> -->
								<div id="combo-box-buyer" class="combo-box-container">
									<input name="s_buyer" id="s_buyer" type="hidden">
									<input id="combo-box-input" type="text" readonly placeholder="Select buyer">
									<input autocomplete="off" type="text" id="search-box" placeholder="Search buyer..." />
									<ul class="combo-box-dropdown" id="combo-box-dropdown">
										{s_buyer}
									</ul>
								</div>
							</td>
						</tr>
						<tr>
							<td>Brand</td>
							<td><input type="text" id="s_brand" name="s_brand" value="{s_brand}" maxlength="50"></td>
						</tr>
						<tr>
							<td>Bench</td>
							<td><input type="text" id="s_bench" name="s_bench" value="{s_bench}" maxlength="50"></td>
						</tr>
						<tr>
							<td>Model</td>
							<td>
								<!-- <select id="s_model" name="s_model" style="width:250px;">
									<option value="">- - Select Model - -</option>
									{s_model}
								</select> -->
								<div id="combo-box-model" class="combo-box-container">
									<!-- <input type="text" id="combo-box-input" placeholder="Select items..." readonly /> -->
									<input id="combo-box-input" type="text" readonly placeholder="Select model">
									<input autocomplete="off" type="text" id="search-box" placeholder="Search model..." />
									<ul class="combo-box-dropdown" id="combo-box-dropdown">
										{s_model}
									</ul>
								</div>
							</td>
						</tr>
						<tr>
							<td>Sku</td>
							<td>
								<input class="input-read-only" style="width: 100%; padding: 5px; box-sizing: border-box;" readonly type="text" id="s_model" name="s_model">
							</td>
						</tr>
						<!---
						<tr><td>Item Code</td>
							<td><input type="text" id="s_smodel" name="s_smodel" value="{s_smodel}" maxlength="15"></td>
						</tr>
						--->

						<tr>
							<td>Color</td>
							<td>
								<!-- <select id="s_color" name="s_color" style="width:250px;">
									<option value="">- - Select Color - -</option>
									{s_color}
								</select> -->
								<input readonly type="hidden" id="s_color" name="s_color">
								<input class="input-read-only" style="width: 100%; padding: 5px; box-sizing: border-box;" readonly type="text" id="s_colorShow" name="s_colorShow">
							</td>
						</tr>
						<tr>
							<td>Serial Begin</td>
							<td><input type="text" id="n_begin_number" name="n_begin_number" value="{n_begin_number}" maxlength="5" size="5" style="text-align:right;">
								<?php if ($operation == 'add') { ?>
									<br>
									<font class="message_notes">Set 0 to automatically begin number</font>
								<?php						} ?>
							</td>
						</tr>
						<tr>
							<td>Serial Qty</td>
							<td><input type="text" id="n_qty" name="n_qty" value="{n_qty}" maxlength="5" size="5" style="text-align:right;"></td>
						</tr>
						<tr>
							<td>Notes 1</td>
							<td><textarea cols="30" rows="3" id="s_notes1" name="s_notes1">{s_notes1}</textarea></td>
						</tr>
						<tr>
							<td>Notes 2</td>
							<td><textarea cols="30" rows="3" id="s_notes2" name="s_notes2">{s_notes2}</textarea></td>
						</tr>
					</tbody>
				</table>
			</td>
			<td valign="top">
				<table class="form_view" width="300px">
					<thead>
						<tr>
							<th colspan="2" class="table_header">Date</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Order</td>
							<td><input type="text" id="d_order_date" name="d_order_date" value="{d_order_date}" maxlength="10">
								<input class="button" type="reset" value="..." onclick="return showCalendar('d_order_date');" name="cldd_order_date" />
							</td>
						</tr>
						<tr>
							<td>Production</td>
							<td><input type="text" id="d_production_date" name="d_production_date" value="{d_production_date}" maxlength="10">
								<input class="button" type="reset" value="..." onclick="return showCalendar('d_production_date');" name="cldd_production_date" />
							</td>
						</tr>
						<!-- <tr>
							<td>Plan</td>
							<td><input type="text" id="d_plan_date" name="d_plan_date" value="{d_plan_date}" maxlength="10">
								<input class="button" type="reset" value="..." onclick="return showCalendar('d_plan_date');" name="cldd_plan_date" />
							</td>
						</tr> -->
						<tr>
							<td>Delivery</td>
							<td><input type="text" id="d_delivery_date" name="d_delivery_date" value="{d_delivery_date}" maxlength="10">
								<input class="button" type="reset" value="..." onclick="return showCalendar('d_delivery_date');" name="cldd_delivery_date" />
							</td>
						</tr>
						<tr>
							<td>Target</td>
							<td><input type="text" id="d_target_date" name="d_target_date" value="{d_target_date}" maxlength="10">
								<input class="button" type="reset" value="..." onclick="return showCalendar('d_target_date');" name="cldd_target_date" />
							</td>
						</tr>
					</tbody>
				</table>
				<!--<table class="form_view" width="300px">
					<thead>
						<tr><th colspan="2" class="table_header">Price</th></tr>
					</thead>
					<tbody>
						<tr><td>Material</td>
							<td>
<?php if ($operation == 'add') {	?>
								<select name="s_currency_material" id="s_currency_material">
									{s_currency_material}
								</select> #
<?php						} else {	?> 
								{s_currency_material} {n_price_material}
<?php						}	?>
							</td>
						</tr>
						<tr><td>Loss</td>
							<td>
<?php if ($operation == 'add') {	?>
								<select name="s_currency_loss" id="s_currency_loss">
									{s_currency_loss}
								</select>
								<input type="text" id="n_price_loss" name="n_price_loss" value="{n_price_loss}" maxlength="13" size="13" style="text-align:right;">
<?php						} else {	?> 
									{s_currency_loss} {n_price_loss}
<?php						}	?>
							</td>
						</tr>
						<tr><td>Add 1</td>
							<td>
<?php if ($operation == 'add') {	?>
								<select name="s_currency_add1" id="s_currency_add1">
									{s_currency_add1}
								</select>
								<input type="text" id="n_price_add1" name="n_price_add1" value="{n_price_add1}" maxlength="13" size="13" style="text-align:right;">
<?php						} else {	?> 
									{s_currency_add1} {n_price_add1}
<?php						}	?>
							</td>
						</tr>
						<tr><td>Add 2</td>
							<td>
<?php if ($operation == 'add') {	?>
								<select name="s_currency_add2" id="s_currency_add2">
									{s_currency_add2}
								</select>
								<input type="text" id="n_price_add2" name="n_price_add2" value="{n_price_add2}" maxlength="13" size="13" style="text-align:right;">
<?php						} else {	?> 
									{s_currency_add2} {n_price_add2}
<?php						}	?>
							</td>
						</tr>
						<tr><td>Total</td>
							<td>
<?php if ($operation == 'add') {	?>
								<select name="s_currency_total" id="s_currency_total">
									{s_currency_total}
								</select> #
<?php						} else {	?> 
									{s_currency_add2} {n_price_total}
<?php						}	?>
							</td>
						</tr>
					</tbody>
				</table>-->
			</td>
			<td valign="top">
				<table class="form_view" width="300px">
					<thead>
						<tr>
							<th colspan="2" class="table_header">More Info</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Price</td>
							<td><input type="text" id="n_price" name="n_price" value="{n_price}" maxlength="12" size="12" style="text-align:right;"></td>
						</tr>
						<tr>
							<td>Amount</td>
							<td><input type="text" id="n_amount" name="n_amount" value="{n_amount}" maxlength="13" size="13" style="text-align:right;"></td>
						</tr>
						<tr>
							<td>UPC Code</td>
							<td><input type="text" id="s_upc_code" name="s_upc_code" value="{s_upc_code}" maxlength="20"></td>
						</tr>
						<tr>
							<td>Ship 1</td>
							<td><input type="text" id="s_ship1" name="s_ship1" value="{s_ship1}" maxlength="50"></td>
						</tr>
						<tr>
							<td>Ship 2</td>
							<td><input type="text" id="s_ship2" name="s_ship2" value="{s_ship2}" maxlength="50"></td>
						</tr>
						<tr>
							<td>Rank 1</td>
							<td><input type="text" id="n_rank1" name="n_rank1" value="{n_rank1}" maxlength="4" size="4" style="text-align:right;"></td>
						</tr>
						<tr>
							<td>Rank 2</td>
							<td><input type="text" id="n_rank2" name="n_rank2" value="{n_rank2}" maxlength="4" size="4" style="text-align:right;"></td>
						</tr>
						<tr>
							<td>Proforma</td>
							<td><input type="text" id="s_proforma" name="s_proforma" value="{s_proforma}" maxlength="50"></td>
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
</script>

<script>
	document.addEventListener("DOMContentLoaded", function() {

		initializeComboBox("combo-box-buyer", "s_buyer", "hidden");
		initializeComboBox("combo-box-model", "s_model", "getColor");

		function initializeComboBox(containerId, dataPlace, act) {
			var container = document.getElementById(containerId);
			var inputShow = container.querySelector("#combo-box-input");
			var input = document.getElementById(dataPlace);
			var dropdown = container.querySelector("#combo-box-dropdown");
			var searchBox = container.querySelector("#search-box");
			addComboBoxListeners(
				inputShow,
				input,
				dropdown,
				searchBox,
				container,
				act
			);
		}
		getColor();
	});

	function getColor(sCode = '') {
		$.ajax({
			type: "POST",
			url: "{baseurl}index.php/production/order/getColor",
			data: {
				s_code: sCode
			},
			dataType: "json",
			success: function(response) {
				$('#s_color').val(response.s_code);
				$('#s_colorShow').val(response.s_description);
			}
		});
	}
</script>
