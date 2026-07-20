Hi, {{ $user->name }}

Sila sahkan alamat emel anda dengan melawat pautan di bawah:

{{ URL::to("auth/confirm/{$user->confirmation_code}") }}

Sekian, Terima Kasih.
Setiausaha Kerajaan Negeri Selangor
