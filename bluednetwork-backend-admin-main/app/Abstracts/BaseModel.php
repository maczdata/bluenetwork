<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           BaseModel.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Abstracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Common\Token;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;
use Plank\Metable\Metable;

abstract class BaseModel extends Model
{
    use SearchString;
    use Sortable;
    use HasFactory;
    use HashidRouting;
    use HasHashid;
    use Metable;

    /**
     * Scope to only include enabled models.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('enabled', 1);
    }

    /**
     * Scope to only include disabled models.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDisabled(Builder $query): Builder
    {
        return $query->where('enabled', 0);
    }

    /**
     * @return MorphMany
     */
    public function tokens(): MorphMany
    {
        return $this->morphMany(Token::class, 'tokenable');
    }

    /**
     * Scope autocomplete.
     *
     * @param Builder $query
     * @param array $filter
     * @return Builder
     */
    public function scopeAutocomplete($query, $filter): Builder
    {
        return $query->where(function ($query) use ($filter) {
            foreach ($filter as $key => $value) {
                $query->orWhere($key, 'LIKE', "%" . $value  . "%");
            }
        });
    }
    /**
     * @param $query
     * @param $name
     * @param $type
     * @param $callback
     */
    protected function filterPolymorphicRelation($query, $name, $type, callable $callback = null)
    {
        $class = Relation::getMorphedModel($type) ?? $type;
        $query->where("{$name}_type", '=', $type)
            ->whereIn("{$name}_id", function ($query) use ($class, $callback) {
                $model = new $class;
                $query = $model->newEloquentBuilder($query)->setModel($model);
                if (is_callable($callback)) {
                    $callback($query);
                }
                $query->select([$model->getKeyName()]);
            });
    }


    /**
     * @return mixed
     */
    protected function columns(): mixed
    {
        return Schema::getColumnListing($this->getTable());
    }

    /**
     * @param $query
     * @param array $value
     * @return mixed
     */
    public function scopeExclude($query, array $value = []): mixed
    {
        return $query->select(array_diff($this->columns(), $value));
    }
}
