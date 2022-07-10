<x-layouts.auth>
    @section('seo')
        <title>Login &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} portal login">
    @stop
    @section('content')

        <div class="inline-flex">
            <a href="/" class="inline-flex flex-row items-center">
                <!--            <svg class="w-10 h-10 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M11.757 2.034a1 1 0 01.638.519c.483.967.844 1.554 1.207 2.03.368.482.756.876 1.348 1.467A6.985 6.985 0 0117 11a7.002 7.002 0 01-14 0c0-1.79.684-3.583 2.05-4.95a1 1 0 011.707.707c0 1.12.07 1.973.398 2.654.18.374.461.74.945 1.067.116-1.061.328-2.354.614-3.58.225-.966.505-1.93.839-2.734.167-.403.356-.785.57-1.116.208-.322.476-.649.822-.88a1 1 0 01.812-.134zm.364 13.087A2.998 2.998 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879.586.585.879 1.353.879 2.121s-.293 1.536-.879 2.121z"
                                    clip-rule="evenodd"
                                />
                            </svg>-->
                {{--<img src="{{ asset('assets/img/logo.png') }}" class="w-10a h-20"/>--}}
            <!--            <span class="leading-10 text-gray-800 text-4xl font-bold ml-1 uppercase">{{ config('app.name') }}</span>-->
            </a>
        </div>

        <div class="text-sm sm:text-base text-gray-600 my-4">Login To Your Account</div>

        <div class="flex flex-col w-full max-w-md">
            @include('control.fragments.general.flash')
            <div class="bg-white shadow-md px-2 sm:px-6 md:px-4 lg:px-8 py-8 rounded-md">
                <form action="{{ route('control.login.process-login') }}" method="post">
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

                            <input id="email" type="email" name="identity"
                                   class="text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400"
                                   placeholder="E-Mail Address"/>
                        </div>
                        <x-form.input-error for="identity" class="mt-2"/>
                    </div>
                    <div class="flex flex-col mb-6">
                        <label for="password"
                               class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">Password:</label>
                        <div class="relative">
                            <div
                                class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
              <span>
                <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
              </span>
                            </div>

                            <input id="password" type="password" name="password"
                                   class="text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400"
                                   placeholder="Password"/>
                        </div>
                        <x-form.input-error for="password" class="mt-2"/>
                    </div>

                    {{--<div class="flex items-center mb-6 -mt-4">
                        <div class="flex ml-auto">
                            <a href="{{ route('control.password.reset-request') }}"
                               class="inline-flex text-xs sm:text-sm text-blue-500 hover:text-blue-700">Forgot
                                Your Password?</a>
                        </div>
                    </div>--}}

                    <div class="flex w-full">
                        <button type="submit"
                                class="flex items-center justify-center focus:outline-none text-white text-sm sm:text-base bg-primary rounded py-2 w-full transition duration-150 ease-in">
                            <span class="mr-2 uppercase">Login</span>
                            <span>
                      <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round"
                           stroke-width="2"
                           viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                    </span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endsection
</x-layouts.auth>
