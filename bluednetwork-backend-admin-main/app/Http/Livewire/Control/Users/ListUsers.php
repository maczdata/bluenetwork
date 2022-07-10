<?php

namespace App\Http\Livewire\Control\Users;

use App\Exports\UserExport;
use App\Models\Users\UserProfile;
use App\Repositories\Users\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class ListUsers extends DataTableComponent
{
    protected UserRepository $userRepository;

    protected string $pageName = 'users';

    protected string $tableName = 'users';

    public bool $columnSelect = true;

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public array $sortNames = [
        'email_verified_at' => 'Email verified',
        'phone_verified_at' => 'Phone number verified',
        'proof_of_address_verified_at' => 'Proof of Address verified',
        'identity_verified_at' => 'Identity verified',
    ];

    public array $filterNames = [
        'email_verified' => 'E-mail Verified',
        'phone_number_verified' => 'Phone number verified',
        'proof_of_address_verified' => 'Proof of Address verified',
        'identity_verified' => 'Identity verified',
    ];

    public array $bulkActions = [
        'exportSelected' => 'Export',
    ];

    public function __construct()
    {
        parent::__construct(null);

        $this->userRepository = app(UserRepository::class);
    }

    public function columns(): array
    {
        return [
            Column::make('Username', 'username')
                ->sortable()
                ->searchable()
                ->linkTo(fn ($value, $column, $row) => route('control.user.view', ['user_id' => $row->hashId()])),
            Column::make('First name', 'first_name')
                ->sortable()
                ->searchable(),
            Column::make('last name', 'last_name')
                ->sortable()
                ->searchable(),
            Column::make('E-mail', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Role', 'role')
                ->searchable(function (Builder $query, $word) {
                    return $query->orWhereHas('roles', function ($query) use ($word) {
                        return $query->where('name', 'like', '%' . $word . '%');
                    });
                }),
            Column::make('Phone number', 'phone_number')
                ->sortable()
                ->searchable(),
            Column::make('Email verified', 'email_verified_at')
                ->sortable()
                ->format(function ($value) {
                    return view(
                        'control.livewire.general.boolean',
                        [
                            'boolean' => $value,
                        ]
                    );
                }),
            Column::make('Phone verified', 'phone_verified_at')
                ->sortable()
                ->format(function ($value) {
                    return view(
                        'control.livewire.general.boolean',
                        [
                            'boolean' => $value,
                        ]
                    );
                }),
            Column::make('Proof of Address verified', 'user.profile')
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy(
                        UserProfile::select('proof_of_address_verified_at')->whereColumn('user_profiles.user_id', 'users.id'),
                        $direction
                    );
                })
                ->format(function ($value) {
                    return view(
                        'control.livewire.general.boolean',
                        [
                            'boolean' => $value->proof_of_address_verified_at,
                        ]
                    );
                }),
            Column::make('Identity verified', 'user.profile.identity_verified_at')
                ->sortable(function (Builder $query, $direction) {
                    return $query->orderBy(
                        UserProfile::select('identity_verified_at')->whereColumn('user_profiles.user_id', 'users.id'),
                        $direction
                    );
                })
                ->format(function ($value) {
                    return view(
                        'control.livewire.general.boolean',
                        [
                            'boolean' => $value->identity_verified_at,
                        ]
                    );
                }),
            Column::make('Action', ''),
        ];
    }

    public function query(): Builder
    {
        return $this->userRepository->getModel()->append('role')->query()
            ->when(
                $this->getFilter('email_verified'),
                fn ($query, $verified) =>
                $verified === 'yes' ?
                    $query->whereNotNull('email_verified_at') :
                    $query->whereNull('email_verified_at')
            )
            ->when(
                $this->getFilter('phone_number_verified'),
                fn ($query, $verified) =>
                $verified === 'yes' ? $query->whereNotNull('phone_verified_at') :
                    $query->whereNull('phone_verified_at')
            )
            ->when(
                $this->getFilter('proof_of_address_verified'),
                fn ($query, $verified) =>
                $verified === 'yes' ? $query->whereHas(
                    'profile',
                    fn ($query) =>
                    $query->whereNotNull('proof_of_address_verified_at')
                ) : $query->whereHas(
                    'profile',
                    fn ($query) =>
                    $query->whereNull('proof_of_address_verified_at')
                )
            )
            ->when(
                $this->getFilter('identity_verified'),
                fn ($query, $verified) =>
                $verified === 'yes' ? $query->whereHas(
                    'profile',
                    fn ($query) =>
                    $query->whereNotNull('identity_verified_at')
                ) : $query->whereHas(
                    'profile',
                    fn ($query) =>
                    $query->whereNull('identity_verified_at')
                )
            );
    }

    public function exportSelected()
    {
        if ($this->selectedRowsQuery->count() > 0) {
            return (new UserExport($this->selectedRowsQuery))->download($this->tableName . '.xlsx');
        }
    }

    public function rowView(): string
    {
        return 'control.livewire.users.row';
    }

    public function filters(): array
    {
        return [
            'email_verified' => Filter::make('E-mail Verified')
                ->select([
                    '' => 'Any',
                    'yes' => 'Yes',
                    'no' => 'No',
                ]),
            'phone_number_verified' => Filter::make('Phone number verified')
                ->select([
                    '' => 'Any',
                    'yes' => 'Yes',
                    'no' => 'No',
                ]),
            'proof_of_address_verified' => Filter::make('Proof of Address verified')
                ->select([
                    '' => 'Any',
                    'yes' => 'Yes',
                    'no' => 'No',
                ]),
            'identity_verified' => Filter::make('Identity verified')
                ->select([
                    '' => 'Any',
                    'yes' => 'Yes',
                    'no' => 'No',
                ]),
        ];
    }

    public function setTableRowClass($row): ?string
    {
        if (!$row->hasVerifiedEmail()) {
            return 'bg-red-200';
        }

        return null;
    }
}
