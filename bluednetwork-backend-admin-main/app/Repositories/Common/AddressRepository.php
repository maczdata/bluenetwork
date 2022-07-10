<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           AddressRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\Address;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class AddressRepository
 * @package App\Repositories\Common
 */
class AddressRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Address::class;
    }

    /**
     * @param array $data
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function createAddress(array $data)
    {
        $data['default_address'] = isset($data['default_address']) ? 1 : 0;

        $default_address = $this
            ->findWhere(['user_id' => $data['user_id'], 'default_address' => 1])
            ->first();

        if (isset($default_address->id) && $data['default_address']) {
            $default_address->update(['default_address' => 0]);
        }

        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function updateAddress(array $data, $id)
    {
        $address = $this->find($id);

        $data['default_address'] = isset($data['default_address']) ? 1 : 0;

        $default_address = $this
            ->findWhere(['user_id' => $address->user_id, 'default_address' => 1])
            ->first();

        if (isset($default_address->id) && $data['default_address']) {
            if ($default_address->id != $address->id) {
                $default_address->update(['default_address' => 0]);
            }

            $address->update($data);
        } else {
            $address->update($data);
        }


        return $address;
    }
}
