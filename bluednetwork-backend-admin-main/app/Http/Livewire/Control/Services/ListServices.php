<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ListServices.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/10/2021, 1:57 AM
 */

namespace App\Http\Livewire\Control\Services;

use App\Exports\ServiceExport;
use App\Repositories\Common\ServiceRepository;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ListServices extends DataTableComponent
{
    protected ServiceRepository $serviceRepository;

    protected string $pageName = 'services';

    protected string $tableName = 'services';

    public bool $dumpFilters = false;
    public bool $columnSelect = true;
    public bool $rememberColumnSelection = true;

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';
    
    public int $perPage = 20;
    public array $perPageAccepted = [20, 40, 60, 80, 100];

    public array $sortNames = [
        'title' => 'Title',
        'service_type_id' => 'Service Type',
        'enabled'    => 'Enabled',
    ];

    public array $filterNames = [
        'title' => 'Title',
        'service_type_id' => 'Service Type',
        'enabled'    => 'Enabled',
    ];

    public array $bulkActions = [
        'exportSelected' => 'Export',
    ];

    public function __construct()
    {
        parent::__construct(null);

        $this->serviceRepository = app(ServiceRepository::class);
    }

    public function columns(): array
    {
        return [
            Column::make('Service', 'title')
                ->format(function ($title) {
                    return ucfirst($title);
                }),
            Column::make('Service Type', 'service_type')
                ->format(function ($serviceType) {
                    return ucfirst($serviceType->title);
                }),
            Column::make('Price', 'price')
                ->format(function ($amount) {
                    return core()->formatBasePrice($amount ?? 0);
                }),
            Column::make('Enabled', 'enabled')
                ->sortable()
                ->format(function ($value) {
                    return view(
                        'control.livewire.general.boolean',
                        [
                            'boolean' => $value,
                        ]
                    );
                }),
            Column::make('Created at', 'created_at')
                ->sortable()
                ->excludeFromSelectable(),
            Column::make('Edit', ''),
            Column::make('Delete', ''),
        ];
    }

    public function query(): Builder
    {
        return $this->serviceRepository->getModel()->query()->whereNull('parent_id')
            ->when($this->getFilter('enabled'), fn ($query, $status) => $query->whereIn('enabled', $status));
        // ->when($this->getFilter('user'), fn($query, $user) => $query->whereUserId($user));
    }

    public function exportSelected()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            return (new ServiceExport($this->selectedRowsQuery))->download($this->tableName . date('Y-m-d-h-m') . '.xlsx');
        }
    }


    public function filters(): array
    {
        return [];
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
        return 'control.livewire.services.row';
    }
}
