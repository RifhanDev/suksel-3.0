<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Jemputan Mesyuarat | Sistem Tender Online Selangor</title>
<style type="text/css">
html { -webkit-text-size-adjust:none; -ms-text-size-adjust: none;}
@media only screen and (max-device-width: 680px), only screen and (max-width: 680px) {
  *[class="table_width_100"] { width: 96% !important; }
  *[class="mob_100"] { width: 100% !important; }
  *[class="mob_center"] { text-align: center !important; }
  *[class="mob_center_bl"] { float: none !important; display: block !important; margin: 0px auto; }
}
.table_width_100 { width: 680px; }
</style>
</head>

<body style="padding: 0px; margin: 0px;">
<div id="mailsub" class="notification" align="center">

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width: 320px;"><tr><td align="center" bgcolor="#eff3f8">

<table border="0" cellspacing="0" cellpadding="0" class="table_width_100" width="100%" style="max-width: 680px; min-width: 300px;">
  <tr><td align="center" bgcolor="#eff3f8">
    <div style="height: 20px; line-height: 20px; font-size: 10px;">&nbsp;</div>
    <table width="96%" border="0" cellspacing="0" cellpadding="0">
      <tr><td align="left">
        <div class="mob_center_bl" style="float: left; display: inline-block; width: 115px;">
          <table class="mob_center" width="115" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
            <tr><td align="left" valign="middle">
              <div style="height: 20px; line-height: 20px; font-size: 10px;">&nbsp;</div>
              <table width="115" border="0" cellspacing="0" cellpadding="0">
                <tr><td align="left" valign="top" class="mob_center">
                  <a href="{{ URL::to('/') }}" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; font-size: 13px;">
                  <img src="{{ asset('assets/images/header.png') }}" height="75" alt="Tender Online SUK Selangor" border="0" style="display: block;" /></a>
                </td></tr>
              </table>
            </td></tr>
          </table>
        </div>
      </td></tr>
    </table>
    <div style="height: 30px; line-height: 30px; font-size: 10px;">&nbsp;</div>
  </td></tr>

  <tr><td align="center" bgcolor="#ffffff">
    <table width="90%" border="0" cellspacing="0" cellpadding="0">
      <tr><td align="center">
        <div style="height: 50px; line-height: 50px; font-size: 10px;">&nbsp;</div>
        <div style="line-height: 36px;">
          <font face="Arial, Helvetica, sans-serif" size="5" color="#57697e" style="font-size: 26px;">
          <span style="font-family: Arial, Helvetica, sans-serif; font-size: 26px; color: #57697e;">
            Jemputan Mesyuarat
          </span></font>
        </div>
        <div style="height: 20px; line-height: 20px; font-size: 10px;">&nbsp;</div>
      </td></tr>

      <tr><td align="center">
        <div style="line-height: 30px;">
          <font face="Arial, Helvetica, sans-serif" size="5" color="#4db3a4" style="font-size: 17px;">
          <span style="font-family: Arial, Helvetica, sans-serif; font-size: 17px; color: #4db3a4;">
            YBhg. {{ $emailUser->name }},
          </span></font>
        </div>
        <div style="height: 20px; line-height: 20px; font-size: 10px;">&nbsp;</div>
      </td></tr>

      <tr><td align="center">
        <table width="80%" align="center" border="0" cellspacing="0" cellpadding="0">
          <tr><td align="left">
            <div style="line-height: 24px;">
              <font face="Arial, Helvetica, sans-serif" size="4" color="#57697e" style="font-size: 15px;">
              <span style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #57697e;">
                Anda dijemput hadir ke mesyuarat sebagai <strong>{{ $perananLabel }}</strong> dalam <strong>{{ $jenisLabel }}</strong> bagi tender / sebut harga berikut:<br><br>

                <strong>Nama:</strong> {{ $tenderName }}<br>
                <strong>No. Tender:</strong> {{ $tenderRefNumber }}<br>
                <strong>PTJ:</strong> {{ $tenderPtj }}<br><br>

                <strong>Perincian Mesyuarat:</strong><br>
                @foreach($meetings as $meeting)
                &bull; Tarikh: {{ $meeting['tarikh'] }}, Masa: {{ $meeting['masa'] }}, Tempat: {{ $meeting['tempat'] }}<br>
                @endforeach
                <br>
                Surat memo pemakluman mesyuarat dilampirkan bersama e-mel ini.<br>
                Sila log masuk ke sistem untuk maklumat lanjut.<br><br>
              </span>
              </font>
            </div>
          </td></tr>
        </table>
        <div style="height: 30px; line-height: 30px; font-size: 10px;">&nbsp;</div>
      </td></tr>

      <tr><td align="center">
        <div style="line-height:24px;">
          <a href="{{ url('/') }}" target="_blank" style="background: #2C3E9E; font-family: Arial, Helvetica, sans-serif; font-size: 13px; padding: 15px 30px; text-decoration: none; color: #fff; border-radius: 4px;">
            <font face="Arial, Helvetica, sans-serif; font-size: 13px;" size="3" color="#fff">
              Log Masuk Sistem
            </font>
          </a>
        </div>
        <div style="height: 60px; line-height: 60px; font-size: 10px;">&nbsp;</div>
      </td></tr>
    </table>
  </td></tr>

  <tr><td align="center" bgcolor="#eff3f8">
    <div style="height: 40px; line-height: 40px; font-size: 10px;">&nbsp;</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr><td align="center">
        <font face="Arial, Helvetica, sans-serif" size="3" color="#96a5b5" style="font-size: 13px;">
        <span style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #96a5b5;">
          &copy;{{ date('Y') }} Setiausaha Kerajaan Negeri Selangor. Hak Cipta Terpelihara.
        </span></font>
      </td></tr>
    </table>
    <div style="height: 50px; line-height: 50px; font-size: 10px;">&nbsp;</div>
  </td></tr>
</table>

</td></tr>
</table>

</div>
</body>
</html>
