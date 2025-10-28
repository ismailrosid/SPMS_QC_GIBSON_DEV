<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<title>SPMS-G. Login</title>
	<link rel="stylesheet" href="{baseurl}/css/custom.css" type="text/css">
</head>

<body class="login_page">
	<table class="login_frame">
		<tr>
			<td valign="middle" align="center">
				{formstart}
				<table class="login_form" cellspacing="5px">
					<tr>
						<td rowspan="6"><img src="{baseurl}images/password.png" /></td>
						<td class="login_title">L o g i n</td>
					</tr>
					<tr>
						<td>User Name</td>
						<td>{username}</td>
					</tr>
					<tr>
						<td>Password</td>
						<td>{password}</td>
					</tr>
					<tr>
						<td></td>
						<td>{submit}</td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="message_error">
								{error_string}<br>
								{MESSAGES}
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							Samick Production Management <br>System Guitar (SPMS-G)
						</td>
					</tr>
				</table>
				{formend}
			</td>
		</tr>
	</table>
</body>

</html>