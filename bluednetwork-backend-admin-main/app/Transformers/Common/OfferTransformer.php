<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                         OfferTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     01/09/2021, 4:47 AM
 */

namespace App\Transformers\Common;

use App\Models\Offer;
use App\Models\Users\User;
use League\Fractal\TransformerAbstract;

class OfferTransformer extends TransformerAbstract
{
    /**
     * @param Offer $offer
     * @return array
     */
    public function transform(Offer $offer)
    {
        return [
            'id' =>  $offer->id,
            'name' => $offer->name,
            'price' => "₦ ".$offer->price,
            'description' => $offer->description,
            'services' =>  $offer->services ? $offer->services : null,
            'fields' => $offer->fields ? $offer->fields : null
        ];
    }

     /**
     * @param User $user
     * @return array
     */
    public function UserOffertransform(User $user)
    {
        return [
            'id' =>  $user->user_offer->id,
            'user' => $user->id,
            'offer' => $this->transform($user->user_offer->offer)
        ];
    }
}
