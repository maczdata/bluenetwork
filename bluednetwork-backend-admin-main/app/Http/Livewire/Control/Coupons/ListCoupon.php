<?php

namespace App\Http\Livewire\Control\Coupons;

use App\Repositories\Finance\CouponRepository;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ListCoupon extends DataTableComponent
{
    protected CouponRepository $couponRepository;

    protected string $pageName = 'coupons';

    protected string $tableName = 'coupons';

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

        $this->couponRepository = app(CouponRepository::class);
    }

    public function columns(): array
    {
        return [
            Column::make('Code', 'code')
                ->format(function ($code) {
                    return $code;
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

            Column::make('Coupon Item', 'couponable')
                ->format(function ($couponable) {
                    return $couponable->title;
                }),
            Column::make('Type', 'type')
                ->format(function ($type) {
                    return $type;
                }),
            Column::make('Value', 'Value')
                ->format(function ($value) {
                    return core()->formatBasePrice($value ?? 0);
                }),
            Column::make('Percentage Off', 'percentage_off')
                ->format(function ($percentage_off) {
                    return $percentage_off;
                }),
            Column::make('Current Usage Count', 'uses')
                ->format(function ($type) {
                    return $type;
                }),
            Column::make('Maximum Usage Count', 'max_uses')
                ->format(function ($type) {
                    return $type;
                }),
            Column::make('Starts At', 'starts_at')
                ->format(function ($type) {
                    return $type;
                }),
            Column::make('Expires At', 'expires_at')
                ->format(function ($type) {
                    return $type;
                }),
            Column::make('Created at', 'created_at')
                ->sortable()
                ->excludeFromSelectable(),
            Column::make('Edit', ''),
        ];
    }

    public function query(): Builder
    {
        return $this->couponRepository->getModel()->query()
            ->when($this->getFilter('enabled'), fn ($query, $status) => $query->whereIn('enabled', $status));
    }


    public function rowView(): string
    {
        return 'control.livewire.coupons.row';
    }
}
