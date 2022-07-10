<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ServiceExport implements FromQuery
{
    use Exportable;

    public function __construct(protected Builder $query)
    {
    }

    public function query()
    {
        return $this->query;
    }
}
