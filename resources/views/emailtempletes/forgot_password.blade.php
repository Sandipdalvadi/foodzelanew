Hello {{$user->name}}<br>
You are requested to change password Click bellow link to reset that password<br>
<a href="{{URL::To('/ForgotPassword/'.$user->id.'/'.$passwordResetCode)}}">click here</a>;