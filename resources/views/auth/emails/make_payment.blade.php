<h3>{{$company->name}} has successfully been verified. Click the below link to continue</h3>
<a href="{{action('AuthController@payment', $company->registration_code)}}">Click here to continue</a>