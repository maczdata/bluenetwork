<?php

namespace App\Http\Livewire\Control\ServiceTypes;

use App\Exports\ServiceTypeExport;
use App\Repositories\Common\ServiceTypeRepository;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ListServiceTypes extends DataTableComponent
{
    protected ServiceTypeRepository $serviceTypeRepository;

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
        'slug' => 'Slug',
        'enabled' => 'Enabled',
    ];

    public array $filterNames = [
        'title' => 'Title',
        'slug' => 'Slug',
        'enabled' => 'Enabled',
    ];

    public array $bulkActions = [
        'exportSelected' => 'Export',
    ];

    public function __construct()
    {
        parent::__construct(null);

        $this->serviceTypeRepository = app(ServiceTypeRepository::class);
    }

    public function columns(): array
    {
        return [
            Column::make('Service', 'title'),
            Column::make('Service Slug', 'slug'),
            Column::make('Status', 'enabled')
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
            Column::make('Action', ''),
        ];
    }

    public function query(): Builder
    {
        return $this->serviceTypeRepository->getModel()->query();
    }

    public function exportSelected()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            return (new ServiceTypeExport($this->selectedRowsQuery))->download($this->tableName . date('Y-m-d-h-m') . '.xlsx');
        }
    }

    public function filters(): array
    {
        return [];
    }

    public function rowView(): string
    {
        return 'control.livewire.service-types.row';
    }
}
