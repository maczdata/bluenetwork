<x-layouts.auth>
@section('seo')
    <title>Password Reset Request &raquo; {{ config('app.name') }}</title>
    <meta name="description" content="{{ config('app.name') }} Password Reset Request">
@stop
@section('content')

    <div class="inline-flex">
        <a href="/" class="inline-flex flex-row items-center">
            <img src="{{ asset('assets/img/logo.png') }}" class="w-10a h-20"/>
        </a>
    </div>
    <div class="text-sm sm:text-base text-gray-600 my-4">Forgot your password?</div>
    <p class="mb-4 text-sm">
        Type in your email and we will send you a code to reset your
        password!
    </p>

    <div class="flex flex-col w-full max-w-md">
        @include('fragments.general.flash')
        <div class="bg-white shadow-md px-4 sm:px-6 md:px-4 lg:px-8 py-8 rounded-md">
            <form action="{{ route('portal.password.process-reset-request') }}" method="post">
                @csrf
                <div class="flex flex-col mb-6">
                    <label for="email" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">
                        E-Mail Address:
                    </label>
                    <div class="relative">
                        <div
                            class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                            <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                 stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>

                        <input id="email" type="email" name="email"
                               class="text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400"
                               placeholder="E-Mail Address"/>
                    </div>
                    <x-form.input-error for="email" class="mt-2"/>
                </div>

                <div class="flex w-full">
                    <button type="submit"
                            class="flex items-center justify-center focus:outline-none text-white text-sm sm:text-base bg-primary rounded py-2 w-full transition duration-150 ease-in">
                        <span class="mr-2 uppercase">Request</span>

                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
</x-layouts.auth>
