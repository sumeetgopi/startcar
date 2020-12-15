<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $data = new arrayObject();
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
}