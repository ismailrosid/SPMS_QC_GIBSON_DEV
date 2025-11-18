<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<link rel="stylesheet" href="{baseurl}css/custom.css" type="text/css">

	<link rel="stylesheet" href="{baseurl}css/jquery/ui-lightness/jquery-ui-1.7.2.custom.css" type="text/css">
	<link rel="stylesheet" href="{baseurl}css/jquery/jquery.ribbon.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" media="all" href="{baseurl}calendar/calendar-mos.css" title="green">
	<link rel="stylesheet" type="text/css" href="{baseurl}css/jquery/pagination.css">

	<script type="text/javascript" src="{baseurl}js/jquery/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="{baseurl}js/jquery/jquery-ui-1.7.2.custom.min.js"></script>

	<script type="text/javascript" src="{baseurl}js/jquery/colorizerow.js"></script>
	<script type="text/javascript" src="{baseurl}js/jquery/cruid.js"></script>
	<script type="text/javascript" src="{baseurl}js/jquery/jquery.officebar.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$("#cmdRibbon").officebar({
				onSelectTab: function(e) {
					//$("#log").append("tab clicked: "+e.rel+"<br/>"); 
				},
				onBeforeShowSplitMenu: function(e) {
					//$("#log").append("before split open: "+e.rel+"<br/>"); 
				},
				onAfterShowSplitMenu: function(e) {
					//$("#log").append("after split open: "+e.rel+"<br/>"); 
				},
				onAfterHideSplit: function(e) {
					//$("#log").append("split menu closed<br/>"); 
				},
				onShowDropdown: function(e) {
					//$("#log").append("opened dropdown: "+e.rel+"<br/>"); 
				},
				onHideDropdown: function(e) {
					//$("#log").append("closed dropdown: "+e.rel+"<br/>"); 
				},
				onClickButton: function(e) {
					//$("#log").append("clicked on: "+e.rel+"<br/>"); 
				}
			})

			$("#cmdRibbon").officebarBind("textboxes", function(e) {
				//$("#log").append("custom bind on textboxes tab<br/>"); 
			});
			$("#cmdRibbon").officebarBind("home>blablabutton", function(e) {
				//$("#log").append("custom bind on blablabutton<br/>"); 
			});
			$("#cmdRibbon").officebarBind("insert>new", function(e) {
				//$("#log").append("custom bind on new<br/>"); 
			});

			$("#cmdRibbon").selectTab('tabSetup');
			$("#cmdRibbon").selectTab('tabMaster');
			$("#cmdRibbon").selectTab('tabElectricGuitar');
			$("#cmdRibbon").selectTab('tabAcousticGuitar');

			// -- Default select tab --
			if ('<?= $this->uri->segment(1) ?>' == 'production') {
				if ('<?= $this->uri->segment(4) ?>' == 'ag') {
					$("#cmdRibbon").selectTab('tabAcousticGuitar');
				} else if ('<?= $this->uri->segment(4) ?>' == 'eg') {
					$("#cmdRibbon").selectTab('tabElectricGuitar');
				}
			} else if ('<?= $this->uri->segment(1) ?>' == 'master') {
				$("#cmdRibbon").selectTab('tabMaster');
			} else if ('<?= $this->uri->segment(1) ?>' == 'setup') {
				$("#cmdRibbon").selectTab('tabSetup');
			} else if ('<?= $this->uri->segment(1) ?>' == 'ag') {
				$("#cmdRibbon").selectTab('tabAcousticGuitar');
			} else if ('<?= $this->uri->segment(1) ?>' == 'eg') {
				$("#cmdRibbon").selectTab('tabElectricGuitar');
			}

			colorizeRowTable(null);
			colorizeRowForm(null);
		});
	</script>

	<style>
		div.officetab div.panel {
			background: transparent url({baseurl}application/panel_right.png) no-repeat scroll right top;
			background-size: cover;
			padding: 75px 12px 5px 6px;
		}

		@media (max-width: 1920px) {
			.img-samick {
				display: none;
			}
		}

		div.officetab div.panel .textboxlistCostume .custom-table {
			border-collapse: collapse;
			width: 100%;
		}

		div.officetab div.panel .textboxlistCostume .custom-table th,
		.textboxlistCostume .custom-table td {
			text-align: left;
			padding: 1px;
		}

		div.officetab div.panel .textboxlistCostume .custom-table input {
			width: 100%;
			box-sizing: border-box;
		}

		div.officetab div.panel .textboxlistCostume .custom-table select {
			width: 100%;
			box-sizing: border-box;
		}

		.combo-box-container {
			width: 100%;
			box-sizing: border-box;
		}

		.relative {
			position: relative;
		}

		#combo-box-input {
			padding: 5px;
			width: 100%;
			box-sizing: border-box;
		}

		.combo-box-dropdown {
			position: absolute;
			z-index: 1;
			background-color: #fff;
			border: 1px solid #ccc;
			list-style-type: none;
			padding: 0;
			margin: 0;
			width: 100%;
			max-height: 150px;
			overflow-y: auto;
			display: none;
		}

		.combo-box-dropdown li {
			padding: 5px;
			cursor: pointer;
		}

		.combo-box-dropdown li:hover {
			background-color: #1867D2;
			color: #FFFFFF;
		}

		.selected {
			background-color: #1867D2;
			color: #FFFFFF;
		}

		#search-box {
			width: 100%;
			padding: 8px;
			box-sizing: border-box;
			display: none;
		}

		.input-read-only {
			background-color: #f2f2f2;
			color: #808080;
			border: 1px solid #d9d9d9;
			cursor: not-allowed;

		}
	</style>

	<!--[if IE]>
		<link rel="stylesheet" href="/204gaesales/css/blueprint/ie.css" type="text/css" media="screen, projection">
		<![endif]-->
	<script type="text/javascript" src="{baseurl}js/_other/js.js"></script>
	<script type="text/javascript" src="{baseurl}js/calendar.js"></script>
	<script src="{baseurl}calendar/calendar_mini.js" type="text/javascript"></script>
	<script src="{baseurl}calendar/lang/calendar-en.js" type="text/javascript"></script>
	<script src="{baseurl}js/ntcore/showmodal.js" type="text/javascript"></script>
	<script type="text/javascript" src="{baseurl}js/jquery/jquery.pagination.js"></script>
	<script type="text/javascript" src="{baseurl}js/validate.js"></script>

	<title>{PAGE_TITLE}</title>


</head>

<body>
	<div style="text-align:right; position:fixed; right:0; top:0; z-index:1; width:auto;">
		<img class="img-samick" src="{baseurl}images/samick.png" />
		<div style="padding: 5px 5px 0px 10px;">
			Welcome [{sGlobalUserName}] | <a href="{basesiteurl}/wall">Home</a> | <a href="{basesiteurl}/main/logout/">Logout</a>
		</div>
	</div>
	<div id="cmdRibbon" class="officebar">
		<ul>
			<li class="current">
				<a href="#" rel="tabAcousticGuitar">Accoustic Guitar</a>
				<ul>
					<li>
						<span>AG Production</span>
						<?php if ($this->session->userdata('b_ag_order_read')) { ?>
							<div class="button split">
								<a href="{basesiteurl}/production/order/index/ag" rel="cmdProductionAG" title="Production Data">
									<img src="{baseurl}images/ribbon/order32.png" alt="" /><span>Product</span>
								</a>
								<div>
									<ul>
										<li><a href="{basesiteurl}/production/order/index/ag" title="Order List"><img src="{baseurl}images/ribbon/order16.png" alt="" />Order</a></li>
										<li><a href="{basesiteurl}/production/product/index/ag" title="Production List"><img src="{baseurl}images/ribbon/production_list.png" alt="" />Production</a></li>
									</ul>
								</div>
							</div>
						<?php						} ?>
						<div class="button list">
							<ul>
								<?php if ($this->session->userdata('b_ag_order_read') && $this->session->userdata('b_ag_order_write')) { ?>
									<li><a href="{basesiteurl}/production/order/viewedit/ag/0" title="New Order"><img src="{baseurl}images/ribbon/new16.png" alt="" /></a></li>
								<?php								} ?>
								<?php if ($this->session->userdata('b_ag_order_read')) { ?>
									<li><a href="{basesiteurl}/production/product/index/ag" title="Production List"><img src="{baseurl}images/ribbon/production_list.png" alt="" /></a></li>
								<?php								} ?>
								<?php if ($this->session->userdata('b_ag_setup_read')) { ?>
									<li><a href="{basesiteurl}/production/setup/index/ag" title="Setting Production Process"><img src="{baseurl}images/ribbon/production_setup.png" alt="" /></a></li>
								<?php								} ?>
							</ul>
						</div>
					</li>
					<?php if ($this->session->userdata('b_ag_transaction_read')) { ?>
						<li>
							<span>AG Transaction</span>
							<div class="button textlist">
								<ul>
									<li class="dropdown">
										<a href="#" rel="cmdProductTransactionAG" title="Process Transaction"><img src="{baseurl}images/ribbon/production_transaction16.png" alt="" />Transaction</a>
										<div>
											<ul>
												<?php if ($this->session->userdata('b_ag_transaction_write')) { ?>
													<li><a href="{basesiteurl}/production/transaction/add/ag" title="Upload Production Process Manualy"><img src="{baseurl}images/ribbon/production_update16.png" alt="" />Update Daily</a></li>
												<?php											} ?>
												<?php if ($this->session->userdata('b_ag_sales_batch')) { ?>
													<li><a href="{basesiteurl}/production/transaction/export_list/ag" title="Export or Local AG Process"><img src="{baseurl}images/ribbon/production_update16.png" alt="" />Gudang Marketing (Out)</a></li>
												<?php											} ?>
												<li><a href="{basesiteurl}/production/transaction/index/ag" title="List of Daily Transaction"><img src="{baseurl}images/ribbon/production_list16.png" alt="" />List Transaction</a></li>
											</ul>
										</div>
									</li>
									<?php if ($this->session->userdata('b_ag_transaction_write')) { ?>
										<li class="dropdown">
											<a href="#" rel="cmdProductConvertAG" title="Upload or Dowonload Transaction"><img src="{baseurl}images/ribbon/production_convert16.png" alt="" />Convert</a>
											<div>
												<ul>
													<li><a href="{basesiteurl}/production/upload/index/ag" title="Upload Production Process from Text File/PDT"><img src="{baseurl}images/ribbon/production_upload.png" alt="" />Upload</a></li>
													<li><a href="{basesiteurl}/production/download/index/ag" title="Download Production to Text File for PDT Master"><img src="{baseurl}images/ribbon/production_download.png" alt="" />Download</a></li>
												</ul>
											</div>
										</li>
									<?php								} ?>
									<?php if ($this->session->userdata('b_ag_report_read')) { ?>
										<li><a href="{basesiteurl}/ag/reportlist/daily" title="Transaction Daily Activity"><img src="{baseurl}images/ribbon/production_cek_activity16.png" alt="" />Activity Check</a></li>
									<?php								} ?>
								</ul>
							</div>
						</li>
					<?php					} ?>
					<?php if (
						isset($viewToolbar) &&
						(($this->uri->segment(1) == 'production' && $this->uri->segment(4) == 'ag') || $this->uri->segment(1) == 'ag')
					) { ?>
						<li>
							<span>{toolCaption}</span>
							{viewToolbar}
						</li>
					<?php					} ?>
					<?php if (
						isset($viewFilter) &&
						(($this->uri->segment(1) == 'production' && $this->uri->segment(4) == 'ag') || $this->uri->segment(1) == 'ag')
					) { ?>
						<li>
							<span>{filterCaption}</span>
							{viewFilter}
						</li>
					<?php					} ?>
				</ul>
			</li>
			<li class="current">
				<a href="#" rel="tabElectricGuitar">Electric Guitar</a>
				<ul>
					<li>
						<?php if ($this->session->userdata('b_eg_order_read')) { ?>
							<span>EG Production</span>
							<div class="button split">
								<a href="{basesiteurl}/production/order/index/eg" rel="cmdProductionEG" title="Production Data">
									<img src="{baseurl}images/ribbon/order32.png" alt="" /><span>Product</span>
								</a>
								<div>
									<ul>
										<li><a href="{basesiteurl}/production/order/index/eg" title="Order List"><img src="{baseurl}images/ribbon/order16.png" alt="" />Order</a></li>
										<li><a href="{basesiteurl}/production/product/index/eg" title="Production List"><img src="{baseurl}images/ribbon/production_list.png" alt="" />Production</a></li>
									</ul>
								</div>
							</div>
						<?php						} ?>
						<div class="button list">
							<ul>
								<?php if ($this->session->userdata('b_eg_order_read') && $this->session->userdata('b_eg_order_write')) { ?>
									<li><a href="{basesiteurl}/production/order/viewedit/eg/0" title="New Order"><img src="{baseurl}images/ribbon/new16.png" alt="" /></a></li>
								<?php								} ?>
								<?php if ($this->session->userdata('b_eg_order_read')) { ?>
									<li><a href="{basesiteurl}/production/product/index/eg" title="Production List"><img src="{baseurl}images/ribbon/production_list.png" alt="" /></a></li>
								<?php								} ?>
								<?php if ($this->session->userdata('b_eg_setup_read')) { ?>
									<li><a href="{basesiteurl}/production/setup/index/eg" title="Setting Production Process"><img src="{baseurl}images/ribbon/production_setup.png" alt="" /></a></li>
								<?php								} ?>
							</ul>
						</div>
					</li>
					<li>
						<span>EG Transaction</span>
						<div class="button textlist">
							<ul>
								<li class="dropdown">
									<a href="#" rel="cmdProductTransactionEG" title="Process Transaction"><img src="{baseurl}images/ribbon/production_transaction16.png" alt="" />Transaction</a>
									<div>
										<ul>
											<?php if ($this->session->userdata('b_eg_transaction_write')) { ?>
												<li><a href="{basesiteurl}/production/transaction/add/eg" title="Upload Production Process Manualy"><img src="{baseurl}images/ribbon/production_update16.png" alt="" />Update Daily</a></li>
											<?php											} ?>
											<?php if ($this->session->userdata('b_eg_sales_batch')) { ?>
												<li><a href="{basesiteurl}/production/transaction/export_list/eg" title="Export or Local EG Process"><img src="{baseurl}images/ribbon/production_update16.png" alt="" />Gudang Marketing (Out)</a></li>
											<?php											} ?>
											<li><a href="{basesiteurl}/production/transaction/index/eg" title="List of Daily Transaction"><img src="{baseurl}images/ribbon/production_list16.png" alt="" />List Transaction</a></li>
										</ul>
									</div>
								</li>
								<?php if ($this->session->userdata('b_eg_transaction_write')) { ?>
									<li class="dropdown">
										<a href="#" rel="cmdProductConvertEG" title="Upload or Dowonload Transaction"><img src="{baseurl}images/ribbon/production_convert16.png" alt="" />Convert</a>
										<div>
											<ul>
												<li><a href="{basesiteurl}/production/upload/index/eg" title="Upload Production Process from Text File/PDT"><img src="{baseurl}images/ribbon/production_upload.png" alt="" />Upload</a></li>
												<li><a href="{basesiteurl}/production/download/index/eg" title="Download Production to Text File for PDT Master"><img src="{baseurl}images/ribbon/production_download.png" alt="" />Download</a></li>
											</ul>
										</div>
									</li>
								<?php								} ?>
								<?php if ($this->session->userdata('b_eg_report_read')) { ?>
									<li><a href="{basesiteurl}/eg/reportlist/daily" title="Transaction Daily Activity"><img src="{baseurl}images/ribbon/production_cek_activity16.png" alt="" />Activity Check</a></li>
								<?php								} ?>
							</ul>
						</div>
					</li>
					<?php if (
						isset($viewToolbar) &&
						(($this->uri->segment(1) == 'production' && $this->uri->segment(4) == 'eg') || $this->uri->segment(1) == 'eg')
					) { ?>
						<li>
							<span>{toolCaption}</span>
							{viewToolbar}
						</li>
					<?php					} ?>
					<?php if (
						isset($viewFilter) &&
						(($this->uri->segment(1) == 'production' && $this->uri->segment(4) == 'eg') || $this->uri->segment(1) == 'eg')
					) { ?>
						<li>
							<span>{filterCaption}</span>
							{viewFilter}
						</li>
					<?php					} ?>
				</ul>
			</li>
			<?php if ($this->session->userdata('b_master_read')) { ?>
				<li class="current">
					<a href="#" rel="tabMaster">Master</a>
					<ul>
						<li>
							<span>Master Data</span>
							<div class="button split">
								<a href="{basesiteurl}/master/buyer" rel="btnMasterBuyer" title="Buyer">
									<img src="{baseurl}images/ribbon/master_buyer.png" /><span>Buyer</span>
								</a>
								<div>
									<ul>
										<li><a href="{basesiteurl}/master/buyer" title="New Buyer"><img src="{baseurl}images/ribbon/new16.png" />New Buyer</a></li>
										<li><a href="{basesiteurl}/master/buyer" title="List Buyer Data"><img src="{baseurl}images/ribbon/master_buyer_list16.png" />Buyer List</a></li>
										<li><a href="{basesiteurl}/master/buyer_model" title="Special Buyer for Assign Model"><img src="{baseurl}images/ribbon/master_buyer_assign16.png" />Assign Model</a></li>
									</ul>
								</div>
							</div>
							<div class="button split">
								<a href="{basesiteurl}/master/smodel" rel="btnMasterModel" title="Model">
									<img src="{baseurl}images/ribbon/master_model.png" /><span>Model</span>
								</a>
								<div>
									<ul>
										<li><a href="{basesiteurl}/master/smodel" title="New Model"><img src="{baseurl}images/ribbon/new16.png" />New Model</a></li>
										<li><a href="{basesiteurl}/master/smodel" title="List Model Data"><img src="{baseurl}images/ribbon/list16.png" />Model List</a></li>
									</ul>
								</div>
							</div>
							<div class="button split">
								<a href="{basesiteurl}/master/color" rel="btnMasterColor" title="Color">
									<img src="{baseurl}images/ribbon/master_color.png" /><span>Color</span>
								</a>
								<div>
									<ul>
										<li><a href="{basesiteurl}/master/color" title="New Color"><img src="{baseurl}images/ribbon/new16.png" />New Color</a></li>
										<li><a href="{basesiteurl}/master/color" title="List Color Data"><img src="{baseurl}images/ribbon/list16.png" />Color List</a></li>
									</ul>
								</div>
							</div>
						</li>
						<?php if (isset($viewToolbar) && $this->uri->segment(1) == 'master') { ?>
							<li>
								<span>{toolCaption}</span>
								{viewToolbar}
							</li>
						<?php					} ?>
						<?php if (isset($viewFilter) && $this->uri->segment(1) == 'master') { ?>
							<li>
								<span>{filterCaption}</span>
								{viewFilter}
							</li>
						<?php					} ?>
					</ul>
				</li>
			<?php			} ?>
			<?php if ($this->session->userdata('b_setup_read')) { ?>
				<li class="current">
					<a href="#" rel="tabSetup">Setup</a>
					<ul>
						<li>
							<span>Setting</span>
							<div class="button">
								<a href="{basesiteurl}/setup/application" rel="btnSetupModify" title="Preferences"><img src="{baseurl}images/ribbon/setup_preferences.png" />Modify</a>
							</div>
						</li>
						<?php if ($this->session->userdata('b_setup_write')) { ?>
							<li>
								<span>User Manager</span>
								<div class="button split">
									<a href="{basesiteurl}/setup/user" rel="btnSetupUser" title="User Management">
										<img src="{baseurl}images/ribbon/setup_user.png" /><span>User</span>
									</a>
									<div>
										<ul>
											<li class="menutitle">Master</li>
											<li><a href="{basesiteurl}/setup/user" title="List Master User Data"><img src="{baseurl}images/ribbon/setup_user_list.png" />User List</a></li>
											<li class="separator"><a href="{basesiteurl}/setup/userlevel" title="User Group Access Level"><img src="{baseurl}images/ribbon/setup_user_group.png" />Level Access</a></li>
											<li class="menutitle">Log</li>
											<li><a href="{basesiteurl}/setup/userlog" title="List User Log"><img src="{baseurl}images/ribbon/setup_user_log.png" />Access Log</a></li>
										</ul>
									</div>
								</div>
								<div class="button list">
									<ul>
										<li><a href="{basesiteurl}/setup/user" title="Create New User"><img src="{baseurl}images/ribbon/new16.png" /></a></li>
										<li><a href="javascript:ConfirmDelete('{basesiteurl}/setup/userlog/delete','All Log')" title="Empty User Log"><img src="{baseurl}images/ribbon/setup_user_log_empty.png" /></a></li>
									</ul>
								</div>
							</li>
						<?php					} ?>
						<?php if (isset($viewToolbar) && $this->uri->segment(1) == 'setup') { ?>
							<li>
								<span>{toolCaption}</span>
								{viewToolbar}
							</li>
						<?php					} ?>
						<?php if (isset($viewFilter) && $this->uri->segment(1) == 'setup') { ?>
							<li>
								<span>{filterCaption}</span>
								{viewFilter}
							</li>
						<?php					} ?>
					</ul>
				</li>
			<?php			} ?>
		</ul>
	</div>
	<div class="body">
		<table cellpadding="0" cellspacing="0" align="center" width="100%">
			<tr>
				<td colspan="2">
