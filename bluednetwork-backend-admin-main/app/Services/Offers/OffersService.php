<?php

namespace App\Services\Offers;

use App\Models\Offer;
use App\Models\OfferField;
use App\Models\OfferService;
use App\Models\UserOfferField;
use App\Models\Users\UserOffer;

class OffersService
{
    public function create($request)
    {
        return Offer::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'price' => $request['price'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
        ]);
    }

    public function update($request, $id)
    {
        return Offer::where('id', $id)->update([
            'name' => $request['name'],
            'description' => $request['description'],
            'price' => $request['price'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
        ]);
    }

    public function list()
    {
        return Offer::orderby('id', 'desc')->get();
    }

    public function getOfferById($id)
    {
        return Offer::where('id', $id)->first();
    }

    public function createService(array $request)
    {
        return OfferService::create($request);
    }

    public function updateService(array $request, $id)
    {
        unset($request['_token']);

        return OfferService::where('id', $id)->update($request);
    }

    public function getOfferServiceById($id)
    {
        return OfferService::orderby('id', 'desc')->where('offer_id', $id)->get();
    }

    public function deleteServiceById($id)
    {
        return OfferService::where('id', $id)->delete();
    }

    public function createField(array $request)
    {
        return OfferField::create($request);
    }

    public function updateField(array $request, $id)
    {
        unset($request['_token']);

        return OfferField::where('id', $id)->update($request);
    }

    public function deleteFieldById($id)
    {
        return OfferField::where('id', $id)->delete();
    }

    public function createUserOffer($user, $offer)
    {
        $data = [
            'user_id' => $user->id,
            'offer_id' => $offer->id,
            'amount' => $offer->price,
            'status' => "pending",
        ];

        return UserOffer::create($data);
    }

    public function getUserOfferlist()
    {
        return UserOffer::orderby('id', 'desc')->get();
    }

    public function getUserOfferById($id)
    {
        return UserOffer::where('id', $id)->orderby('id', 'desc')->first();
    }

    public function getUserOfferByUserId($id)
    {
        return UserOffer::where('user_id', $id)->orderby('id', 'desc')->get();
        
    }

    public function cancelStatus($id)
    {
        return UserOffer::where('id', $id)->update(['status' => 'cancel']);
    }

    public function processStatus($id)
    {
        return UserOffer::where('id', $id)->update(['status' => 'processing']);
    }

    public function completeStatus($id)
    {
        return UserOffer::where('id', $id)->update(['status' => 'completed']);
    }

    public function deleteUsersOffers($id)
    {
        return UserOffer::where('id', $id)->delete();
    }

    public function arrangeFieldData(array $request)
    {
        $data = $request;
        if ($request['answers'] != null) {
            $answers = explode(',', $request['answers']);
            unset($data['answers']);
            $data['answers'] = json_encode($answers);
        }

        if ($request['file_types'] != null) {
            $file_types = explode(',', $request['file_types']);
            unset($data['file_types']);
            $data['file_types'] = json_encode($file_types);
        }

        return $data;
    }

    public function saveofferForm($user_offer_id, array $fields)
    {
        $userOffer = UserOffer::where('id', $user_offer_id)->first();
        $offerfield = OfferField::where('offer_id', $userOffer->offer_id)->get();

        foreach ($fields as $key => $value) {
            $type = null;
            if (count($offerfield) > 0) {
                foreach ($offerfield as $field) {
                    if ($field->field_name == $key) {
                        $type = $field->type;
                        $filled = UserOfferField::create([
                            'user_offer_id' => $user_offer_id,
                            'offer_field_id' => $field->id,
                            'filled_field' => $value,
                            'type' => $type,
                        ]);

                        if ($type === "File" || $type === "Image") {
                            $filled->addMedia($value)->toMediaCollection('filled_field' . $user_offer_id);
                        }
                    }
                }
            }
        }
    }

    public function updateOfferForm($user_offer_id, array $fields)
    {
        $userOffer = UserOffer::where('id', $user_offer_id)->first();
        $offerfield = OfferField::where('offer_id', $userOffer->offer_id)->get();
        UserOfferField::where('user_offer_id', $user_offer_id)->delete();

        foreach ($fields as $key => $value) {
            $type = null;
            if (count($offerfield) > 0) {
                foreach ($offerfield as $field) {
                    if ($field->field_name == $key) {
                        $type = $field->type;
                        $filled = UserOfferField::create([
                            'user_offer_id' => $user_offer_id,
                            'filled_field' => $value,
                            'offer_field_id' => $field->id,
                            'type' => $type,
                        ]);

                        if ($type === "File" || $type === "Image") {
                            $filled->addMedia($value)->toMediaCollection('filled_field' . $user_offer_id);
                        }
                    }
                }
            }
        }
    }

    public function getSingleField($user_offer_id)
    {
        return UserOfferField::where('user_offer_id', $user_offer_id)->first();
    }
}
