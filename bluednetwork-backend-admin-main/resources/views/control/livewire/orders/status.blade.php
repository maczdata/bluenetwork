@if ($status == 'completed')
    <x-general.badge type="success">{{ ucfirst($status) }}</x-general.badge>
@elseif ($status == 'processing')
    <x-general.badge type="info">{{ ucfirst($status) }}</x-general.badge>
@elseif ($status == 'pending')
    <x-general.badge type="warning">{{ ucfirst($status) }}</x-general.badge>
@elseif (in_array($status,['closed', 'canceled']))
    <x-general.badge type="danger">{{ ucfirst($status) }}</x-general.badge>
@endif
