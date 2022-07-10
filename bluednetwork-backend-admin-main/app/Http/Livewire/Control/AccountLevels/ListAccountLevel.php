<?php

namespace App\Http\Livewire\Control\AccountLevels;

use App\Repositories\Common\AccountLevelRepository;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ListAccountLevel extends DataTableComponent
{
    protected AccountLevelRepository $accountLevelRepository;

    protected string $pageName = 'accountLevels';

    protected string $tableName = 'accountLevels';

    public bool $dumpFilters = false;
    public bool $columnSelect = true;
    public bool $rememberColumnSelection = true;

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public int $perPage = 20;
    public array $perPageAccepted = [20, 40, 60, 80, 100];

    public function __construct()
    {
        parent::__construct(null);

        $this->accountLevelRepository = app(AccountLevelRepository::class);
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->format(function ($title) {
                    return ucfirst($title);
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
            Column::make('Transaction Limit', 'transaction_limit')
                ->format(function ($transaction_limit) {
                    return core()->formatBasePrice($transaction_limit ?? 0);
                }),
            Column::make('Withdrawal Limit', 'withdrawal_limit')
                ->format(function ($withdrawal_limit) {
                    return core()->formatBasePrice($withdrawal_limit ?? 0);
                }),
            Column::make('Daily Limit', 'daily_limit')
                ->format(function ($daily_limit) {
                    return core()->formatBasePrice($daily_limit ?? 0);
                }),
            Column::make('Created at', 'created_at')
                ->sortable()
                ->excludeFromSelectable(),
            Column::make('Edit', ''),
        ];
    }

    public function query(): Builder
    {
        return $this->accountLevelRepository->getModel()->query()
            ->when($this->getFilter('enabled'), fn ($query, $status) => $query->whereIn('enabled', $status));
    }


    public function rowView(): string
    {
        return 'control.livewire.account-levels.row';
    }
}
