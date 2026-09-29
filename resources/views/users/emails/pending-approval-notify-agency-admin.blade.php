<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<title>Permohonan Kelulusan Akaun | Sistem Tender Online Selangor</title>
</head>
<body style="padding: 0; margin: 0; font-family: Arial, Helvetica, sans-serif;">
<div align="center" style="background: #eff3f8; padding: 24px;">
  <table width="100%" style="max-width: 680px; background: #fff;" cellpadding="0" cellspacing="0">
    <tr>
      <td style="padding: 32px 24px; text-align: center;">
        <h2 style="color: #57697e; font-size: 24px; margin: 0 0 16px;">Permohonan Kelulusan Akaun</h2>
        <p style="color: #4db3a4; font-size: 16px;">Hi, {{ $admin->name }}!</p>
        <p style="color: #57697e; font-size: 15px; line-height: 1.5;">
          Pengguna <strong>{{ $user->name }}</strong> ({{ $user->email }}) telah mengesahkan emel dan menetapkan kata laluan.
          Sila semak dan luluskan permohonan akaun di halaman kelulusan pengguna.
        </p>
        <p style="margin-top: 28px;">
          <a href="{{ url('users/pending-approval') }}" target="_blank" style="background: #F3565D; color: #fff; padding: 14px 28px; text-decoration: none; font-size: 14px;">
            Semak Permohonan
          </a>
        </p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
