<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           OrderRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Repositories\Finance;

use App\Eloquent\Repository;
use App\Models\Sales\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container as App;
use App\Services\Generators\OrderNumberSequencer;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class OrderRepository
 * @package App\Repositories\Finance
 */
class OrderRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return Order::class;
    }


    /**
     * @param array $data
     * @return mixed
     * @throws ValidatorException
     */
    public function saveOrder(array $data): mixed
    {
        DB::beginTransaction();

        try {
            $data['status'] = 'pending';
            $order = $this->create(array_merge($data, ['order_number' => OrderNumberSequencer::generate()]));
            if (isset($data['meta']) && count($data['meta'])) {
                foreach ($data['meta'] as $metaKey => $metaValue) {
                    $order->setMeta($metaKey, $metaValue);
                }
            }
            if (isset($data['items']) && count($data['items'])) {
                foreach ($data['items'] as $item) {
                    $saveItem = $item['item']->orderitem()->create(array_merge($item, ['order_id' => $order->id]));
                    if (isset($item['fields']) && count($item['fields'])) {
                        $saveItem->saveCustomFields($item['fields']);
                        //$order->items()->create(array_merge($item, ['order_id' => $order->id]));
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $order;
    }
}
