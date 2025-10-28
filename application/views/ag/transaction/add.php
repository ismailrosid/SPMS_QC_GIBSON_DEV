<form name="frmEdit" id="frmEdit" method="post" action="{siteurl}add/{sDivision}" enctype="multipart/form-data">
	<div style="text-align:left;">
		<input type="checkbox" id="bValidation" name="bValidation" value="1" checked><label for="bValidation" id="lblValidation">Set Validation</label>
	</div>
	<table class="form_view" width="370px">
		<thead>
			<tr><th colspan="2" class="table_header">Transaction Info</th></tr>
		</thead>
		<tr><td>Date</td>
			<td><input type="text" id="d_transaction_date" name="d_transaction_date" value="{d_transaction_date}">
				<input class="button" type="reset" value="..." onclick="return showCalendar('d_transaction_date');" name="cldd_transaction_date"/>
			</td>
		</tr>
		<tr><td>Phase</td>
			<td><select name="s_phase" id="s_phase">
					{s_phase_filter}
				</select>
<?php if ($this->session->userdata('b_replace_production')){?>
				<input type="checkbox" id="bRework" name="bRework" value="1" {isRework}><label for="bRework" id="lblRework" title="Remove all transaction from selected phase">Rework?</label>
<?php }?>
			</td>
		</tr>
	</table>
	{MESSAGES}
	<table align="left">
		<tr><td valign="top">
				<table class="table" style="min-width:300px;">
					<thead>
						<tr class="table_header">
							<th></th>
							<th>Serial No</th>
							<th>Location</th>
						</tr>
					</thead>
					<tbody>
<?php					for ($iCount = 1; $iCount <= 10; $iCount++){?>
						<tr><td width="10px" align="right"><?=$iCount?></td>
							<td><input type="text" id="s_serial_no:<?=$iCount?>" name="s_serial_no[]" value="{s_serial_no:<?=$iCount?>}"></td>
							<td><select id="s_location:<?=$iCount?>" name="s_location:<?=$iCount?>">
									<option value="">- - Select Location - -</option>
									{s_location:<?=$iCount?>}
								</select>
							</td>
						</tr>
<?php					}?>
					</tbody>
				</table>
			</td>
			<td valign="top">
				<table class="table" style="min-width:300px;">
					<thead>
						<tr class="table_header">
							<th></th>
							<th>Serial No</th>
							<th>Location</th>
						</tr>
					</thead>
					<tbody>
<?php					for ($iCount = 11; $iCount <= 20; $iCount++){?>
						<tr><td width="10px" align="right"><?=$iCount?></td>
							<td><input type="text" id="s_serial_no:<?=$iCount?>" name="s_serial_no[]" value="{s_serial_no:<?=$iCount?>}"></td>
							<td><select id="s_location:<?=$iCount?>" name="s_location:<?=$iCount?>">
									<option value="">- - Select Location - -</option>
									{s_location:<?=$iCount?>}
								</select>
							</td>
						</tr>
<?php					}?>
					</tbody>
				</table>
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	{VALIDATE_JS}
</script>