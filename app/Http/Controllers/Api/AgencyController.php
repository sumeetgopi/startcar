<?php

namespace App\Http\Controllers\Api;

use App\Agency;
use App\Driver;
use App\Http\Controllers\Controller;
use App\Validation;
use App\Vehicle;
use ArrayObject;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Razorpay\Api\Api;


class AgencyController extends Controller
{
    private $api; 
    
    public function __construct() {
        $apiKey = env('RAZORPAY_KEY');
        $apiSecret = env('RAZORPAY_SECRET');
        $this->api = new Api($apiKey, $apiSecret);
    }

    public function logout(Request $request) 
    {
        $apiToken = $request->header('Authorization');
        $data = new ArrayObject();
        try {
            jwtLogout($apiToken);
            return apiResponse(1, __('message.logout'), $data);
        } 
        catch (JWTException $e) {
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function common()
    {
        $data = new ArrayObject();
        try {
            $agencyId = jwtId();
            $data = [
                'maximum_cashback_redeem_amount' => (string) amount(getSetting('maximum_cashback_amount')),
                'minimum_order_amount_for_cashback' => (string) amount(getSetting('minimum_order_amount_for_cashback')),
                'about_us' => (string) env('HTML_START') . getSetting('about_us') . env('HTML_END'),
                'privacy_policy' => (string) env('HTML_START') . getSetting('policy') . env('HTML_END'),
                'term_and_condition' => (string) env('HTML_START') . getSetting('term_condition') . env('HTML_END'),
                'read_policy' => (string) env('HTML_START') . getSetting('read_policy') . env('HTML_END'),
                'customer_support' => (string) env('HTML_START') . getSetting('customer_support') . env('HTML_END'),
                'rozarpay_api_key' => (string) env('RAZORPAY_KEY') ?? ''
            ];
            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function agencyUpdate(Request $request)
    {
        $inputs = $request->all();
        $data = new ArrayObject();
        $validation = (new Validation)->agencyUpdate($inputs);
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        try {
            \DB::beginTransaction();
            (new Agency)->apiAgencyUpdate($inputs, jwtId());
            \DB::commit();

            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function addDriver(Request $request)
    {
        $inputs = $request->all();
        $data = new ArrayObject();
        $validation = (new Validation)->addDriver($inputs);
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        try {
            \DB::beginTransaction();

            // document code start
            if($request->hasFile('pan_adhar_document')) {
                $inputs['pan_adhar_document'] = webImgUpload($request, 'pan_adhar_document', env('DRIVER_PATH'));
            }
            // document code end

            $agencyId = jwtId();
            $inputs['agency_id'] = $agencyId;
            (new Driver)->store($inputs, $agencyId);
            \DB::commit();

            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function addVehicle(Request $request)
    {
        $inputs = $request->all();
        $data = new ArrayObject();
        $validation = (new Validation)->addVehicle($inputs);
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        try {
            \DB::beginTransaction();

            // document code start
            if($request->hasFile('registration_document')) {
                $inputs['registration_document'] = webImgUpload($request, 'registration_document', env('VEHICLE_PATH'));
            }

            if($request->hasFile('insurance_document')) {
                $inputs['insurance_document'] = webImgUpload($request, 'insurance_document', env('VEHICLE_PATH'));
            }

            if($request->hasFile('vehicle_pics')) {
                $inputs['vehicle_pics'] = webImgUpload($request, 'vehicle_pics', env('VEHICLE_PATH'));
            }
            // document code end

            $agencyId = jwtId();
            $inputs['agency_id'] = $agencyId;
            (new Vehicle)->store($inputs, $agencyId);
            \DB::commit();

            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }
}