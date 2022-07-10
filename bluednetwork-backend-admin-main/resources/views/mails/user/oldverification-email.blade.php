@extends('mails.layouts.master')
@section('content')
<div>
   <div style="text-align: center;">
      <a href="{{ config('app.url') }}">
         @include ('mails.layouts.logo')
      </a>
   </div>

   <div style="font-size:16px; color:#242424; font-weight:600; margin-top: 60px; margin-bottom: 15px">
      {!! config('app.name') !!} - Email Verification
   </div>
   <p>
      Hi <b>{{ ucwords($user->first_name) }}</b>
   </p>
   <p>
      Please click on the below link to verify your email account
      <br />
      <a href="{{ route('portal.verification.verify',['verification_source'=>'email','verification_token'=>$token->token]) }}">Verify Email</a>
   </p>
   <p>
      if you believe you received this email in error, please contact us at <a href="mailto:support@hailatutor.com">support@hailatutor.com</a>
   </p>
   <p>
      Thank you
      <br />
      The Hailatutor Team
   </p>
</div>

@endsection
