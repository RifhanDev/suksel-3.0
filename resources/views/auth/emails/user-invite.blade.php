<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
		<title>Pengesahan Emel & Tetapan Kata Laluan | Tender Online SUK Selangor</title>
		<style type="text/css">
		html { -webkit-text-size-adjust:none; -ms-text-size-adjust: none;}
			.table_width_100 { width: 680px; }
		</style>
	</head>
	<body style="padding: 0px; margin: 0px;">
		<div align="center">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width: 320px;">
				<tr>
					<td align="center" bgcolor="#eff3f8">
						<table border="0" cellspacing="0" cellpadding="0" class="table_width_100" width="100%" style="max-width: 680px;">
							<tr>
								<td align="center" bgcolor="#ffffff">
									<table width="90%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="center">
												<div style="height: 40px; line-height: 40px;">&nbsp;</div>
												<font face="Arial, Helvetica, sans-serif" size="5" color="#57697e" style="font-size: 28px;">
													Pengesahan Emel & Tetapan Kata Laluan
												</font>
												<div style="height: 20px;">&nbsp;</div>
												<font face="Arial, Helvetica, sans-serif" size="4" color="#4db3a4" style="font-size: 17px;">
													Hi, {{ $user ? $user->name : 'Pengguna' }}!
												</font>
												<div style="height: 25px;">&nbsp;</div>
												<font face="Arial, Helvetica, sans-serif" size="4" color="#57697e" style="font-size: 15px;">
													Akaun anda telah didaftarkan oleh pentadbir sistem. Sila tekan butang di bawah untuk mengesahkan alamat emel anda dan menetapkan kata laluan.
												</font>
												<div style="height: 35px;">&nbsp;</div>
												<a href="{{ url('auth/reset/'.$token) }}" target="_blank" style="background: #F3565D; font-family: Arial, Helvetica, sans-serif; font-size: 13px; padding: 15px 30px; text-decoration: none; color: #fff;">
													Sahkan Emel & Tetapkan Kata Laluan
												</a>
												<div style="height: 50px;">&nbsp;</div>
												<font face="Arial, Helvetica, sans-serif" size="2" color="#929ca8" style="font-size: 12px;">
													Selepas kata laluan ditetapkan, akaun anda akan menunggu kelulusan Agensi Admin sebelum boleh log masuk.
												</font>
												<div style="height: 40px;">&nbsp;</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
