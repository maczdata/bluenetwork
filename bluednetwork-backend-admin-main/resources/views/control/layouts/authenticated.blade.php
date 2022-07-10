<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="m-0 p-0">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Chistel Brown">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    @section('seo')
    @show

    @yield('style')
    <link type="text/css" href="{{ mix('assets/css/control/app.css')}}" rel="stylesheet">
    @livewireStyles
    @stack('styles')
    <script type="text/javascript"><!--
        chistel = <?php echo json_encode([
            'currency' => null,
            'url' => '',
            'css_url' => url('assets/css'),
            'js_url' => url('assets/js'),
            'csrfToken' => csrf_token(),
            'flash_notification' => ((session()->has('flash_notification')) ? json_encode(session()->get('flash_notification')) : 'false')
        ]); ?>;
        //--></script>
    <script src="{{ mix('assets/js/control/manifest.js')}}"></script>
    <script type="text/javascript" defer src="{{ mix('assets/js/control/vendor.js')}}"></script>
    @stack('head-js')
</head>

<body class="font-sans antialiased">
<div class="main-wrapper" id="app" x-data="chistelWrapper()" @click.away="isMobile = false"
     @resize.window="isMobile = (window.innerWidth < 1024) ? true : false">
    <div class="flex flex-row min-h-screen bg-gray-100 text-gray-800">

        <main
            class="main flex flex-col flex-grow relative z-0 transition-all duration-150 ease-in">
            @livewire('control.navigation-menu')
            <div class="relative min-h-screen md:flex">
                @include('control.fragments.aside')
                <div class="main-content flex flex-col flex-grow p-4ds w-96">
                    <!-- Page Heading -->
                    @if (isset($header))
                        <div class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-6">
                                {{ $header }}
                            </div>
                        </div>
                    @endif
                    @if(\Illuminate\Support\Facades\View::hasSection('content'))
                        @yield('content')
                    @else
                        {{ $slot }}
                    @endif
                </div>
            </div>
            <footer class="bg-white">
                <div>
                    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                        <div class="flex justify-between">
                            <div class="text-base text-left">
                            </div>
                            <div class="text-base text-gray-600 text-right">
                                © <a href="/" class="hover:text-primary focus:text-primary text-primary">
                                    {{ config('app.name') }}
                                </a>
                                {{ date('Y') }}
                                .
                                All rights
                                reserved.
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>

</div>

<script type="text/javascript" defer src="{{ mix('assets/js/control/app.js')}}"></script>
@livewireScripts
@livewire('livewire-ui-modal')
@stack('scripts')
</body>
</html>
