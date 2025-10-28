<form name="frmEdit" id="frmEdit" method="post" action="{siteurl}add/{sDivision}" enctype="multipart/form-data">
	<div style="text-align:left;">
		<input type="checkbox" id="bValidation" name="bValidation" value="1" checked><label for="bValidation" id="lblValidation">Set Validation</label>
	</div>
	<table class="form_view" width="370px">
		<thead>
			<tr><th colspan="2" class="table_header">Transaction Info</th></tr>
		</thead>
		<tbody>
			<tr><td>Date</td>
				<td><input type="text" id="d_transaction_date" name="d_transaction_date" value="{d_transaction_date}">
					<input class="button" type="reset" value="..." onclick="return showCalendar('d_transaction_date');" name="cldd_transaction_date"/>
				</td>
			</tr>
			<tr><td>Phase</td>
				<td><select name="s_phase" id="s_phase">
						{s_phase_filter}
					</select>
					<input type="checkbox" id="bRework" name="bRework" value="1" {isRework}><label for="bRework" id="lblRework" title="Remove all transaction from selected phase">Rework?</label>
				</td>
			</tr>
		</tbody>
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
<?php					for ($iCount = 1; $iCount <= 20; $iCount++){?>
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
				<div id="fraNeck">
				<table class="table" style="min-width:140px;">
					<thead>
						<tr class="table_header">
							<th><a href="#" onClick="_doPost('sSort', 's_serial_no', 'bSortAction')">Serial No Body</a></th>
						</tr>
					</thead>
					<tbody>
<?php					for ($iCount = 1; $iCount <= 20; $iCount++){?>
						<tr><td><input type="text" id="s_serial_no_2:<?=$iCount?>" name="s_serial_no_2:<?=$iCount?>" value="{s_serial_no_2:<?=$iCount?>}"></td></tr>
<?php					}?>
					</tbody>
				</table>
				</div>
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	$(document).ready(function(){
		$("#s_phase").change(
			function() {    
				fSetPhaseView();
			});
		fSetPhaseView();
	});
	
	function fSetPhaseView() {
		if ($("#s_phase").val().toLowerCase()=='d_process_2') {
			$("#fraNeck").show();
		} else {
			$("#fraNeck").hide();
		}
	}
	
	/*{VALIDATE_JS}*/
</script>