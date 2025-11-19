<h3>{{$user->company->name}} successfully registered. You can login using the below credentials:</h3>

<p>Username: {{$user->username}}</p>
<p>Password: {{$pass}}</p>

<a href="{{action('HomeController@index')}}">Click here to login</a>