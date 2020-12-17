<?php

namespace App\Http\Controllers\Api;

use App\Agency;
use App\AgencyState;
use App\Driver;
use App\Http\Controllers\Controller;
use App\State;
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
            // $agencyId = jwtId();
            $stateRes = (new State)->apiState();
            $state = [];
            if(isset($stateRes) && count($stateRes) > 0) {
                foreach($stateRes as $s) {
                    $state[] = [
                        'id' => (int) $s->id ?? 0,
                        'state_name' => (string) $s->state_name ?? ''
                    ];
                }
            }

            $data = [
                'state' => $state,
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
            $userId = jwtId();
            $inputs['user_id'] = $userId;
            $id = (new Agency)->apiAgencyUpdate($inputs, $userId);

            if($inputs['state_ids'] != '') {
                $stateIds = explode(',', $inputs['state_ids']);
                foreach($stateIds as $stateId) {
                    $state[] = [
                        'user_id' => $userId,
                        'agency_id' => $id,
                        'state_id' => $stateId,
                        'created_at' => date(DB_DATETIME_FORMAT)
                    ];
                }

                if(isset($state) && count($state) > 0) {
                    (new AgencyState)->removeState($userId);
                    (new AgencyState)->store($state, null, true);
                }
            }
            \DB::commit();

            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function driverAdd(Request $request)
    {
        $inputs = $request->all();
        $data = new ArrayObject();
        $validation = (new Validation)->driverAdd($inputs);
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

            $inputs['agency_id'] = jwtId();
            (new Driver)->store($inputs);
            \DB::commit();

            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function vehicleAdd(Request $request)
    {
        $inputs = $request->all();
        $data = new ArrayObject();
        $validation = (new Validation)->vehicleAdd($inputs);
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
                // insert in vehicle_image table
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