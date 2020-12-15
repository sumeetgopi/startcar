<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use ArrayObject;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Razorpay\Api\Api;


class CustomerController extends Controller
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
            $customerId = jwtId();
            $data = [
                'data' => (string) 'hello',
            ];
            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }
}