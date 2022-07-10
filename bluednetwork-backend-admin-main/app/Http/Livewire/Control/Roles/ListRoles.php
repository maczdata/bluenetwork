<?php

namespace App\Http\Livewire\Control\Roles;

use App\Repositories\Common\RoleRepository;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ListRoles extends DataTableComponent
{
    protected RoleRepository $roleRepository;

    protected string $pageName = 'roles';

    protected string $tableName = 'roles';

    public bool $columnSelect = true;

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public array $sortNames = [
        'created_at' => 'Created At',
    ];


    public function __construct()
    {
        parent::__construct(null);

        $this->roleRepository = app(RoleRepository::class);
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable()
                ->linkTo(fn ($value, $column, $row) => route('control.roles.view', ['role_id' => $row->hashId()])),
            Column::make('Guard name', 'guard_name')
                ->sortable()
                ->searchable(),
            Column::make('Permissions', 'permissions')
                ->sortable()
                ->format(function ($roles) {
                    return $roles?->pluck('name')->first();
                }),
            Column::make('Action', ''),
        ];
    }

    public function query(): Builder
    {
        return $this->roleRepository->getModel()->query();
    }

    public function rowView(): string
    {
        return 'control.livewire.roles.row';
    }
}
