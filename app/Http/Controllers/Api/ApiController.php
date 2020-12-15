<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\Validation;
use ArrayObject;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Razorpay\Api\Api;


class ApiController extends Controller
{
    private $api; 
    
    public function __construct() {
        $apiKey = env('RAZORPAY_KEY');
        $apiSecret = env('RAZORPAY_SECRET');
        $this->api = new Api($apiKey, $apiSecret);
    }

    public function register(Request $request) 
    {
        $inputs = $request->all();
        $validation = (new Validation)->register($inputs);
        $data = new ArrayObject();
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        $isRegistered = false;
        $registerId = null;

        if($inputs['user_type'] == 'customer') {
            $result = (new User)->findCustomer($inputs['email']);
            if($result) {
                $isRegistered = true;
                $registerId = $result->id;
            }
        }
        else if($inputs['user_type'] == 'agency') {
            $result = (new User)->findAgency($inputs['mobile_number']);
            if($result) {
                $isRegistered = true;
                $registerId = $result->id;
            }
        }

        try {    
            \DB::beginTransaction();

            if($inputs['user_type'] == 'customer') {
                $otpCode = rand(1111, 9999);
                $update = [
                    'email' => $inputs['email'],
                    'otp_code' => $otpCode,
                    'status' => '1',
                    'user_type' => 'customer'
                ];

                if(isset($inputs['name']) && $inputs['name'] != '') {
                    $update['name'] = $inputs['name'];
                }

                (new User)->store($update, $registerId);

                $data = [
                    'otp_code' => (string) '',
                    'is_registered' => (bool) $isRegistered
                ];

                // send mail code start
                sendRegisterOtpMail($inputs['email'], $otpCode);
                // send mail code end
            }
            else if($inputs['user_type'] == 'agency') {
                $otpCode = rand(1111, 9999);
                $update = [
                    'mobile_number' => $inputs['mobile_number'],
                    'device_type' => $inputs['device_type'],
                    'otp_code' => $otpCode,
                    'user_type' => 'agency'
                ];

                if(isset($inputs['name']) && $inputs['name'] != '') {
                    $update['name'] = $inputs['name'];
                }

                (new User)->store($update, $registerId);
                \DB::commit();

                // send sms code start
                // $msg = __('message.sms_otp', ['otp_code' => $otpCode]);
                // sendSMS($inputs['mobile_number'], $msg);
                // send sms code end

                $data = [
                    'otp_code' => (string) $otpCode,
                    // 'otp_code' => (string) '',
                    'is_registered' => (bool) $isRegistered
                ];
            }

            \DB::commit();
            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function otpVerify(Request $request) 
    {
        $inputs = $request->all();
        $validation = (new Validation)->otpVerify($inputs);
        $data = new ArrayObject();
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        $result = null;
        if($inputs['user_type'] == 'customer') {
            $result = (new User)->customerVerify($inputs['email'], $inputs['otp_code']);
            if(!$result) {
                return apiResponse(0, __('message.invalid_otp'), $data);
            }
        }
        else if($inputs['user_type'] == 'agency') {
            $result = (new User)->agencyVerify($inputs['mobile_number'], $inputs['otp_code']);
            if(!$result) {
                return apiResponse(0, __('message.invalid_otp'), $data);
            }
        }

        try {
            \DB::beginTransaction();
            $update = [
                'status' => '1',
                // 'fcm_token' => $inputs['fcm_token']
            ];

            // rozarpay code start
            /*if($result->rozarpay_customer_id == '') {
                
                $name = ($result->name != '') ? $result->name : 'Jeetii user';
                $mobile = $result->mobile_number;
                $customer = $this->api->customer->create([
                    'name' => $name,
                    'contact' => $mobile
                ]);

                if($customer) {
                    $update['rozarpay_customer_id'] = $customer->id;
                }
            }*/
            // rozarpay code end
            
            (new User)->store($update, $result->id);
            \DB::commit();

            $data = [
                'user' => getUser($result->id, $result->user_type),
                'token' => (string) jwtToken($result)
            ];
            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }
}