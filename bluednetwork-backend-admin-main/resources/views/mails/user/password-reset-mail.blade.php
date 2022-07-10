@extends('mails.layouts.master')
@section('content')
<div>
   <div style="text-align: center;">
      <a href="{{ config('app.url') }}">
         @include ('mails.layouts.logo')
      </a>
   </div>

   <div style="font-size:16px; color:#242424; font-weight:600; margin-top: 60px; margin-bottom: 15px">
      {!! config('app.name') !!} - Password Reset Request
   </div>
   <p>
      You are receiving this email because we received a password reset request for your account.
   </p>

   <div style="margin-top: 40px; text-align: center">
      <a href="{{ $url }}" style="font-size: 16px;
            color: #FFFFFF; text-align: center; background: #0031F0; padding: 10px 100px;text-decoration: none;">
         Reset Password
      </a>
   </div>
   <p style="martin-top:4px;">
      This password reset link will expire in {{ $resetMinutes }} minutes.
   </p>
   <p>
      If you did not request a password reset, no further action is required.
   </p>
</div>

@endsection