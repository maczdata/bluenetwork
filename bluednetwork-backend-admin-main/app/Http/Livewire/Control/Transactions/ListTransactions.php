<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ListTransactions.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/11/2021, 9:21 AM
 */

namespace App\Http\Livewire\Control\Transactions;

use App\Exports\TransactionExport;
use App\Repositories\Common\TransactionRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class ListTransactions extends DataTableComponent
{
    /** @var Application|mixed */
    protected $transactionRepository;

    protected string $pageName = 'transactions';

    protected string $tableName = 'transactions';

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
        'ownerable_id' => 'User',
        'status' => 'Status'
    ];

    public array $filterNames = [
        'status' => 'Status',
        'user' => 'User(s)',
        'transaction_type' => 'Transaction type',
    ];

    public array $bulkActions = [
        'exportSelected' => 'Export',
    ];

    public function __construct()
    {
        parent::__construct(null);

        $this->transactionRepository = app(TransactionRepository::class);

        $this->userRepository = app(UserRepository::class);
    }

    public function columns(): array
    {
        return [
            Column::make('Reference number', 'reference')
                ->sortable()
                ->searchable()
                ->format(function ($number) {
                    return '#' . $number;
                }),
            Column::make('Transaction type', 'transactionable_type', 'transactionable_id')
                ->format(function ($transactionable_type, $transactionable_id) {
                    return ucfirst($transactionable_type);
                })->asHtml(),
            Column::make('Owner', 'ownerable')
                ->sortable()
                ->format(function ($ownerable) {
                    return $ownerable?->full_name;
                }),
            Column::make('Status', 'status')
                ->sortable()
                ->format(function ($status) {
                    return view('control.livewire.transactions.status', compact('status'));
                }),

            Column::make('Amount', 'amount')
                ->format(function ($amount) {
                    return core()->formatBasePrice($amount ?? 0);
                }),
            Column::make('Created at', 'created_at')
                ->sortable()
                ->excludeFromSelectable(),
        ];
    }

    public function query(): Builder
    {
        return $this->transactionRepository->getModel()->query()
            ->when($this->getFilter('status'), fn ($query, $status) => $query->whereIn('status', $status))
            ->when($this->getFilter('user'), fn ($query, $user) => $query->whereOwnerableType('user')->whereOwnerableId($user))
            ->when($this->getFilter('transaction_type'), fn ($query, $types) => $query->whereIn('transactionable_type', $types));
    }

    public function exportSelected()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            return (new TransactionExport($this->selectedRowsQuery))->download($this->tableName . date('Y-m-d-h-m') . '.xlsx');
        }

        // Not included in package, just an example.
        //$this->notify(__('You did not select any users to export.'), 'danger');
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
                    '0' => 'Failed',
                    '1' => 'Successful',
                ]),
            'transaction_type' => Filter::make('Transaction type')
                ->multiSelect([
                    'wallet' => 'Wallet',
                    'order' => 'Order',
                    'offer' => 'Offer'
                ]),
        ];
    }

    public function setTableRowClass($row): ?string
    {
        if ($row->status == '1') {
            return 'bg-green-200';
        } elseif ($row->status == '0') {
            return 'bg-red-200';
        }

        return null;
    }

    public function getTableRowUrl($row): string
    {
        return route('control.transaction.single', ['transaction_id' => $row->hashId()]);
    }

    /*public function rowView(): string
    {
        return 'control.livewire.orders.row';
    }*/
}
