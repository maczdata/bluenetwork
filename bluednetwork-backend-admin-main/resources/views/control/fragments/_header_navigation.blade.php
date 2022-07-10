<header class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex-shrink-0 flex items-center">
                <!-- mobile menu button -->
                <button class="rounded-lg md:hidden rounded-lg focus:outline-none focus:shadow-outline" @click="isMobile = !isMobile">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                        <path x-show="isMobile" fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        <path x-show="!isMobile" fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <a href="{{ route('control.dashboard') }}" class="block p-2 text-blue-800 font-bold">
                    BDS
                </a>

                {{--<a href="{{ route('control.dashboard') }}">
                    <img src="{{ asset('assets/img/logo.png') }}" class="block h-9 w-auto"/>
                </a>--}}
            </div>
            <!-- Navigation Links -->

            <div class="flex items-center flex-row">
                <div class="relative" x-data="{ openUserNav: false }" @click.away="openUserNav = false"
                     @close.stop="openUserNav = false">
                    <div @click="openUserNav = !openUserNav">
                        <button
                            class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div class="border-2 border-transparent">
                                <img
                                    src=" {{ auth()->user()->user_avatar }}"
                                    alt=" {{ auth()->user()->full_name }}"
                                    class="h-10 w-10 bg-gray-200 rounded-full object-cover"
                                />
                            </div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>

                    </div>
                    <div x-show="openUserNav"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"
                         style="display: none;"
                         @click="openUserNav = false">
                        <div class="rounded-b-lg shadow-xs py-1 bg-white">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>
                            <a href="{{ route('control.account.manage-account') }}"
                               class="block px-4 py-2 text-gray-600 hover:bg-gray-100 hover:text-primary">
                                Account Settings
                            </a>
                            <a href="javascript:void(0);"
                               onclick="event.preventDefault(); document.getElementById('auth-logout').submit();"
                               class="block px-4 py-2 text-gray-600 hover:bg-gray-100 hover:text-primary">
                                Logout
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>

<form id="auth-logout" action="{{ route('control.account.logout') }}" method="POST" style="display: none;">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
</form>

