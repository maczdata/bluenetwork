<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ListOrders.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/10/2021, 1:57 AM
 */

namespace App\Http\Livewire\Control\Orders;

use App\Exports\OrderExport;
use App\Repositories\Finance\OrderRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class ListOrders extends DataTableComponent
{
    /** @var Application|mixed */
    protected $orderRepository;

    protected string $pageName = 'orders';

    protected string $tableName = 'orders';

    /** @var mixed|Application */
    private mixed $userRepository;

    public bool $dumpFilters = false;
    public bool $columnSelect = true;
    public bool $rememberColumnSelection = true;

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public int $perPage = 20;
    public array $perPageAccepted = [20, 40, 60, 80, 100];

    public array $sortNames = [
        'user_id' => 'User',
        'status' => 'Status',
        'total_item_count' => 'Total item',
    ];

    public array $filterNames = [
        'status' => 'Status',
        'user' => 'User(s)'
    ];

    public array $bulkActions = [
        'exportSelected' => 'Export',
    ];


    public function __construct()
    {
        parent::__construct(null);

        $this->orderRepository = app(OrderRepository::class);

        $this->userRepository = app(UserRepository::class);
    }

    public function columns(): array
    {
        return [
            Column::make('Order number', 'order_number')
                ->sortable()
                ->searchable()
                ->format(function ($number) {
                    return '#' . $number;
                }),
            Column::make('Order Service', 'orderable')
                ->format(function ($orderable) {
                    return $orderable->title;
                })->asHtml(),
            Column::make('User', 'user.full_name'),
            Column::make('Status', 'status')
                ->sortable()
                ->format(function ($status) {
                    return view('control.livewire.orders.status', compact('status'));
                }),
            Column::make('Subtotal', 'sub_total')
                ->format(function ($amount) {
                    return core()->formatBasePrice($amount ?? 0);
                }),
            Column::make('Grand total', 'grand_total')
                ->format(function ($amount) {
                    return core()->formatBasePrice($amount ?? 0);
                }),
            Column::make('Ordered at', 'created_at')
                ->sortable()
                ->excludeFromSelectable(),
            Column::make('Action', ''),
        ];
    }

    public function query(): Builder
    {
        return $this->orderRepository->getModel()->query()
            ->when($this->getFilter('status'), fn ($query, $status) => $query->whereIn('status', $status))
            ->when($this->getFilter('user'), fn ($query, $user) => $query->whereUserId($user));
    }

    public function exportSelected()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            return (new OrderExport($this->selectedRowsQuery))->download($this->tableName . date('Y-m-d-h-m') . '.xlsx');
        }
    }


    public function filters(): array
    {
        return [
            'user' => Filter::make('User')
                ->select(array_merge(['' => 'Any'], $this->userRepository->scopeQuery(function ($query) {
                    return $query->whereHas('orders')->orderBy('first_name');
                })->get()->keyBy('id')
                    ->map(fn ($user) => optional($user)->full_name)->toArray())),
            /*  'order_date' => Filter::make('Order Date')
                  ->date([
                      'min' => now()->subYear()->format('Y-m-d'), // Optional
                      'max' => now()->format('Y-m-d') // Optional
                  ]),*/
            'status' => Filter::make('Status')
                ->multiSelect([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                    'closed' => 'Closed',
                    'cancelled' => 'Cancelled',
                    //'fraud' => 'Fraud'
                ]),
        ];
    }

    public function setTableRowClass($row): ?string
    {
        if ($row->status == 'pending') {
            return 'bg-yellow-200';
        } elseif (in_array($row->status, ['closed', 'canceled'])) {
            return 'bg-red-200';
        }

        return null;
    }

    public function rowView(): string
    {
        return 'control.livewire.orders.row';
    }
}
