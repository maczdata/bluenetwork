@extends('mails.layouts.master')
@section('content')
	<div>
		<div style="text-align: center;">
			<a href="{{ config('app.url') }}">
				@include ('mails.layouts.logo')
			</a>
		</div>

		<div style="padding: 30px;">
			<div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">

				<p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
					Hello
					{{ ucwords($user->full_name) }}
				</p>

				<p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
					@if($user->hasRole('Tutor'))
						Your account has being upgraded to a tutor
					@else
						Your account has being downgraded to just a tutee
					@endif

				</p>
			</div>

			<div style="margin-top: 65px;font-size: 16px;color: #5E5E5E;line-height: 24px;display: inline-block">

				<p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
					Thanks
				</p>
			</div>
		</div>
	</div>

@endsection
