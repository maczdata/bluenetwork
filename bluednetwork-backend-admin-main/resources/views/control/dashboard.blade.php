<x-layouts.authenticated>
    @section('seo')
        <title>Dashboard &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Dashboard">
    @stop
    @section('content')
        <div>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <h1 class="font-bold text-2xl text-gray-700">Dashboard</h1>
                <div class="flex flex-wrap -mx-3 mb-3">
                    <!-- stat grid-->

                    <div class="px-3 mb-6 w-1/4">
                        <div class="px-4 py-4 bg-white border border-gray-300 rounded">
                            <div>
                                <p class="text-3xl font-semibold text-left text-gray-800">
                                    {{ $statistics['total_orders']['current'] }}
                                </p>
                                <p class="text-lg text-left text-gray-500">Total Orders</p>
                                <p class="flex items-center text-80 font-bold">
                                    @if ($statistics['total_orders']['progress'] < 0)
                                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" class="text-red-500 stroke-current mr-2"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                        </svg>
                                        {{ -number_format($statistics['total_orders']['progress'], 1) }}% Decrease
                                    @else
                                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             class="text-green-500 stroke-current mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        {{ number_format($statistics['total_orders']['progress'], 1) }}% Increases
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="px-3 mb-6 w-1/4">
                        <div
                            class="px-4 py-4 mt-4 bg-white border border-gray-300 rounded sm:mt-0">
                            <div>
                                <p class="text-3xl font-semibold text-left text-gray-800">
                                    {{ number_format($statistics['total_users']['current'],0) }}
                                </p>
                                <p class="text-lg text-left text-gray-500">Total Users</p>
                                <p class="flex items-center text-80 font-bold">
                                    @if ($statistics['total_users']['progress'] < 0)
                                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" class="text-red-500 stroke-current mr-2"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                        </svg>
                                        {{ -number_format($statistics['total_users']['progress'], 1) }}% Decrease
                                    @else
                                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             class="text-green-500 stroke-current mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        {{ number_format($statistics['total_users']['progress'], 1) }}% Increases
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="px-3 mb-6 w-1/4">
                        <div
                            class="px-4 py-4 mt-4 bg-white border border-gray-300 rounded sm:mt-0">
                            <div>
                                <p class="text-3xl font-semibold text-left text-gray-800">
                                    {{ core()->formatBasePrice($statistics['total_sales']['current'] ?? 0) }}
                                </p>
                                <p class="text-lg text-left text-gray-500">Total Sales</p>
                                <p class="flex items-center text-80 font-bold">
                                    @if ($statistics['total_sales']['progress'] < 0)
                                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" class="text-red-500 stroke-current mr-2"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                        </svg>
                                        {{ -number_format($statistics['total_sales']['progress'], 1) }}% Decrease
                                    @else
                                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             class="text-green-500 stroke-current mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        {{ number_format($statistics['total_sales']['progress'], 1) }}% Increases
                                    @endif
                                </p>
                            </div>
                        </div>

                    </div>
                    <div class="px-3 mb-6 w-1/4">
                        <div
                            class="px-4 py-4 bg-white border border-gray-300 rounded">
                            <div>
                                <p class="text-3xl font-semibold text-left text-gray-800">
                                    {{ core()->formatBasePrice($statistics['avg_sales']['current'] ?? 0) }}
                                </p>
                                <p class="text-lg text-left text-gray-500">Average Sales</p>
                                <p class="flex items-center text-80 font-bold">
                                    @if ($statistics['avg_sales']['progress'] < 0)
                                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" class="text-red-500 stroke-current mr-2"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                        </svg>
                                        {{ -number_format($statistics['avg_sales']['progress'], 1) }} Decrease
                                    @else
                                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             class="text-green-500 stroke-current mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        {{ number_format($statistics['avg_sales']['progress'], 1) }}% Increase
                                    @endif
                                </p>
                            </div>
                        </div>

                    </div>


                    <div class="px-3 mb-6 w-1/2">
                        <div class="bg-white rounded-md shadow-md flex flex-col h-auto"><h1
                                class="text-90 font-normal text-2xl p-4">Latest Orders</h1>

                            <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden rounded-none md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-none">
                                    <thead>
                                    <tr>
                                        <th class="px-3 py-2 md:px-6 md:py-3 bg-gray-50 dark:bg-gray-800">
                                            Purchase number
                                        </th>
                                        <th class="px-3 py-2 md:px-6 md:py-3 bg-gray-50 dark:bg-gray-800">
                                            User
                                        </th>
                                        <th class="px-3 py-2 md:px-6 md:py-3 bg-gray-50 dark:bg-gray-800">
                                            Status
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-none">
                                    @foreach ($latestOrder as $item)
                                        <tr class="bg-white dark:bg-gray-700 dark:text-white hover:bg-gray-100 hover:bg-gray-100 dark:hover:bg-gray-900 transition">
                                            <td class="whitespace-nowrap px-3 py-2 md:px-6 md:py-4 text-sm leading-5 text-gray-900 dark:text-white">
                                                <a href="">
                                                    #{{ $item->order_number }}
                                                </a>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-2 md:px-6 md:py-4 text-sm leading-5 text-gray-900 dark:text-white">
                                                <a href="">
                                                    {{ $item?->user?->full_name }}
                                                </a>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-2 md:px-6 md:py-4 text-sm leading-5 text-gray-900 dark:text-white">
                                                @if ($item->status == 'completed')
                                                    <x-general.badge type="success">{{ ucfirst($item->status) }}</x-general.badge>
                                                @elseif ($item->status == 'processing')
                                                    <x-general.badge type="info">{{ ucfirst($item->status) }}</x-general.badge>
                                                @elseif ($item->status == 'pending')
                                                    <x-general.badge type="warning">{{ ucfirst($item->status) }}</x-general.badge>
                                                @elseif (in_array($item->status,['closed', 'canceled']))
                                                    <x-general.badge type="danger">{{ ucfirst($item->status) }}</x-general.badge>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    @endsection
    @push('scripts')
        <script type="text/javascript">
            window.onscroll = function (ev) {
                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                    //window.livewire.emit('load-more');
                }
            };

        </script>
    @endpush
</x-layouts.authenticated>
