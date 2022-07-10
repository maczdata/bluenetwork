<!-- sidebar -->
<aside :class="{'block': !isMobile, 'hidden': isMobile}"
    class="sidebar bg-blue-800 text-blue-100 z-50 w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-fulld md:relative md:translate-x-0 transition duration-200 ease-in-out">
    <!-- logo -->
    <a href="/" class="text-white flex items-center space-x-2 px-4">
        <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
        </svg>
        <span class="text-2xl font-extrabold">BDS</span>
    </a>

    <!-- nav -->
    <nav>
        <a href="{{ route('control.dashboard') }}"
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white">
            Dashboard
        </a>
        @can('view_order')
            <a href="{{ route('control.order.list') }}"
                class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white">
                Orders
            </a>
        @endcan
        @can('view_transaction')
            <a href="{{ route('control.transaction.list') }}"
                class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white">
                Transactions
            </a>
            <a href="{{ route('control.payouts.index') }}"
                class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white">
                Payouts
            </a>
        @endcan
        @can('view_service_type')
            <a href="{{ route('control.service-type.list') }}"
                class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white">
                Service Types
            </a>
        @endcan
        @can('view_service')
            <a href="{{ route('control.service.list') }}"
                class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white">
                Services
            </a>
        @endcan
        @role('super_admin')
            <a href="/offers"
                class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white">
                Offers
            </a>
            <a href="/offers/view/users-offer"
                class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white">
                Users Offers
            </a>
            
        @endrole

        @role('super_admin')
            <a href="{{ route('control.coupons.index') }}"
                class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white">
                Coupon Codes
            </a>
        @endrole
        @can('view_settings')
            <a href="{{ route('control.settings.index') }}"
                class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white">
                Settings
            </a>
        @endcan
        @if(auth()->user()->role === 'super_admin' || auth()->user()->hasPermissionTo('view_user'))
        <div @click.away="open = false" class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                class="flex flex-row items-center w-full px-4 py-2 mt-2 text-sm font-semibold text-left bg-transparent rounded-lg dark-mode:bg-transparent    md:block hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                <span>Access Control</span>
                <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}"
                    class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 w-full mt-2 origin-top-right rounded-md shadow-lg">
                <div class="px-2 py-2 rounded-md shadow bg-transparent ">
                    @can('view_user')
                    <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white"
                        href="{{ route('control.user.list') }}">Users</a>
                    <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white"
                        href="{{ route('control.user.manage.level') }}">Manage User Account Level</a>
                    @endcan
                    @role('super_admin')
                        <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white"
                            href="{{ route('control.roles.list') }}">Roles</a>
                        <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 hover:text-white"
                            href="{{ route('control.account-levels.list') }}">Account Level</a>
                    @endrole
                </div>
            </div>
        </div>
        @endif
    </nav>
</aside>
