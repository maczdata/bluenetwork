<x-layouts.auth>
@section('seo')
    <title>Registration success &raquo; {{ config('app.name') }}</title>
@stop
@section('content')
    <div class="flex flex-col justify-content-center">
        <div class="bg-white shadow-md px-4 sm:px-6 md:px-4 lg:px-8 py-8 rounded-md">
            <div class="text-center">
                <div class="mb-1">
                    <i class="fa fa-check-circle fa-5x text-success"></i>
                </div>
                @if($newSignup)
                    <h5>
                        Registration was successful.
                    </h5>
                @endif
                <div class="mt-2">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @elseif($newSignup)
                        <div class="alert alert-success" role="alert">
                            {{ __('Before proceeding, please check your email for a verification link.') }}
                        </div>

                    @endif
                    {{ __('If you did not receive the email') }},
                    <a href="{{ route('portal.verification.resend') }}"
                       class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
</x-layouts.auth>
