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

use App\Models\Users\User;
use App\Models\Users\UserOffer;
use League\Fractal\TransformerAbstract;

class UserOfferTransformer extends TransformerAbstract
{
    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        $offerTransform = new OfferTransformer();
        return [
            'id' => $user->user_offer->id ?? null,
            'amount' => $user->user_offer->amount ?? null,
            'formattedAmount' =>  core()->formatBasePrice($user->user_offer->amount ?? 0),
            'user' => $user->id,
            'offer' => $user->user_offer->offer ? $offerTransform->transform($user->user_offer->offer) : null,
        ];
    }

    public function transformUserOffer(UserOffer $offer)
    {
        $offerTransform = new OfferTransformer();

        return [
            'id' => $offer->id,
            'amount' => $offer->amount ?? null,
            'formattedAmount' =>  core()->formatBasePrice($offer->amount ?? 0),
            'status' => $offer->status,
            'offer' => $offer->offer ? $offerTransform->transform($offer->offer) : null,
            'filled_form' => $offer->fields ? $this->collect_filled_form($offer->fields) : null,
            'created_at' => $offer->created_at->format('Y-m-d H:ia'),
            'updated_at' => $offer->updated_at->format('Y-m-d H:ia'),
        ];
    }

    public function collect_filled_form($collection)
    {
        $transformer = new UserOfferTransformer();
        return collect($collection)->map(fn($model) => [
            'id' => $model->id,
            'field_id' => $model->offer_field_id ?? null,
            'filled_field' => ($model->type == "Image" || $model->type == "File") ? optional(optional($model)->getMedia('filled_field'.$model->id)?->first())->original_url ?? $model->filled_field : $model->filled_field,
            'type' => $model->type,
        ]);
    }

    public function collect_user_offers($collection)
    {
        $transformer = new UserOfferTransformer();
        return collect($collection)->map(fn($model) => $transformer->transformUserOffer($model));
    }

}
