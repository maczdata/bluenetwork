@if ($status == '1')
    <x-general.badge type="success">Successful</x-general.badge>
@elseif ($status == '0')
    <x-general.badge type="danger">Failed</x-general.badge>
@endif
