<?php

namespace App\Repositories\Finance;

use App\Eloquent\Repository;
use App\Models\Sales\OrderCacDirector;
use Prettus\Validator\Exceptions\ValidatorException;

class OrderCacDirectorRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return OrderCacDirector::class;
    }

    /**
     * @param $orderId
     * @param array $directors
     * @throws ValidatorException
     */
    public function saveDirectors($orderId, array $directors = [])
    {
        collect($directors)->each(function ($director, $key) use ($orderId) {
            $directorSaved = $this->create([
                'order_id' => $orderId,
                'designation' => $director['designation'],
                'full_name' => $director['full_name'],
                'email' => $director['email'],
                'phone_number' => $director['phone_number'],
                'address' => $director['address']
            ]);
            if (isset($director['passport'])) {
                $directorSaved->addMediaFromRequest('directors.' . $key . '.passport')
                    ->preservingOriginal()->toMediaCollection('cac_directors_passport');
            }

            if (isset($director['valid_id'])) {
                $directorSaved->addMediaFromRequest('directors.' . $key . '.valid_id')
                    ->preservingOriginal()->toMediaCollection('cac_directors_valid_id');
            }
            if (isset($director['signature'])) {
                $directorSaved->addMediaFromRequest('directors.' . $key . '.signature')
                    ->preservingOriginal()->toMediaCollection('cac_directors_signature');
            }
        });
    }
}
