<?php

namespace App\Http\Controllers\Control\Offers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Offers\OffersService;
use Illuminate\Support\Facades\Validator;
use App\Abstracts\Http\Controllers\ControlController;

class OfferController extends ControlController
{
    //
    public function index()
    {
        $offerService = new OffersService();
        $offers = $offerService->list();
      return view('control.offer.list')->with(compact('offers'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'fromDate' => 'bail|required',
            'toDate' => 'bail|required',
        ]);

        DB::beginTransaction();
        try {

            $offerService = new OffersService();

            $offerService->create($request->all());
           
            DB::commit();
         
            flash("Offer has been created successfully")->success();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('offer error : ' . $e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function update(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'fromDate' => 'bail|required',
            'toDate' => 'bail|required',
        ]);

        DB::beginTransaction();
        try {

            $offerService = new OffersService();

            $offerService->update($request->all(), $id);
           
            DB::commit();

            flash("Offer has been created updated")->success();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('offer error : ' . $e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function viewMore($id)
    {
        $offerService = new OffersService();

        $offer = $offerService->getOfferById($id);
        $offerServices = $offerService->getOfferServiceById($id);
        
        return view('control.offer.view-more')->with(compact('offer', 'offerServices'));
    }

    public function storeService(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);

  
        DB::beginTransaction();
        try {
            $offerService = new OffersService();
            $offerService->createService($request->all());
           
            DB::commit();
       
            flash("Offer Service has been created successfully")->success();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('offer Service error : ' . $e);
            dd($e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function updateService(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);

  
        DB::beginTransaction();
        try {
            $offerService = new OffersService();
            $offerService->updateService($request->all(), $id);
           
            DB::commit();
       
            flash("Offer Service has been updated successfully")->success();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('offer Service error : ' . $e);
            dd($e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function deleteService($id)
    {
        DB::beginTransaction();
        try {
            $offerService = new OffersService();
            $offerService->deleteServiceById($id);
           
            DB::commit();
       
            flash("Offer Service has been deleted successfully")->success();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('offer Service error : ' . $e);
            dd($e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function storeField(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'type' => 'required',
            'required_field' => 'required',
            'enabled' => 'required',
            'has_values' => 'required',
            'answers' => 'nullable',
            'default_value' => 'required',
            'validation_rules' => 'required',
            'file_types' => 'required_if:type,File,Image',
            'max_file_size' => 'required_if:type,File,Image'
        ]);




        DB::beginTransaction();
        try {
            $offerService = new OffersService();
            $data = $offerService->arrangeFieldData($request->all());
            $offerService->createField($data);
           
            DB::commit();
       
            flash("Offer Field has been created successfully")->success();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('offer Service error : ' . $e);
            dd($e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function updateField(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'type' => 'required',
            'required_field' => 'required'
        ]);

  
        DB::beginTransaction();
        try {
           
            $offerService = new OffersService();
            $offerService->updateField($request->all(), $id);
           
            DB::commit();
       
            flash("Offer Field has been updated successfully")->success();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('offer Service error : ' . $e);
            dd($e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function deleteField($id)
    {
        DB::beginTransaction();
        try {
            $offerService = new OffersService();
            $offerService->deleteFieldById($id);
           
            DB::commit();
       
            flash("Offer Field has been deleted successfully")->success();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('offer Service error : ' . $e);
            dd($e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function viewUsersOffers()
    {
        $offerService = new OffersService();
        $userOffers = $offerService->getUserOfferlist();

        return view('control.offer.user_offer_list')->with(compact('userOffers'));
    }

    public function markCompleteUsersOffers($id)
    {
        DB::beginTransaction();
        try {
           
            $offerService = new OffersService();
            $offerService->completeStatus($id);
           
            DB::commit();
       
            flash("Users offer has been mark as completed")->success();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('offer Service error : ' . $e);
            dd($e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function markCancelUsersOffers($id)
    {
        DB::beginTransaction();
        try {
           
            $offerService = new OffersService();
            $offerService->cancelStatus($id);
           
            DB::commit();
       
            flash("Users offer has been mark as completed")->success();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('offer Service error : ' . $e);
          
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function markProcessingUsersOffers($id)
    {
        DB::beginTransaction();
        try {
           
            $offerService = new OffersService();
            $offerService->processStatus($id);
           
            DB::commit();
       
            flash("Users offer has been moved to processing")->success();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('offer Service error : ' . $e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function deleteUsersOffers($id)
    {
        DB::beginTransaction();
        try {
            $offerService = new OffersService();
            $offerService->deleteUsersOffers($id);
           
            DB::commit();
       
            flash("Offer Field has been deleted successfully")->success();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('offer Service error : ' . $e);
            dd($e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }
}
