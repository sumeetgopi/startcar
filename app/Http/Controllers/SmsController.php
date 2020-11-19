<?php

namespace App\Http\Controllers;

use App\Sms;
use App\SmsTemplate;
use App\User;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function getSms() {
        $smsTemplate = (new SmsTemplate)->service();
        return view('sms.get', compact('smsTemplate'));
    }

    public function setSms(Request $request)
    {
        $inputs = $request->all();
        $validation = (new SmsTemplate)->smsValidation($inputs);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        if($inputs['send_type'] == 'selected') {
            if(!isset($inputs['mobile_number'])) {
                return jsonResponse(false, 207, 'Please select alteast one user(customer)');
            }
        }

        try {
            if($inputs['send_type'] == 'selected') {
                $mobiles = $inputs['mobile_number'];
            }
            else {
                $customerMobile = (new User)->getCustomerMobile();
                if(isset($customerMobile) && count($customerMobile) > 0) {
                    $mobiles = array_column($customerMobile->toArray(), 'mobile_number');
                }
            }

            if(isset($mobiles) && count($mobiles) > 0) {
                $mobiles = array_unique($mobiles);
                sendSMS($mobiles, $inputs['message']);
            }

            $extra = ['redirect' => route('sms')];
            $message = __('sms.send');
            return jsonResponse(true, 201, $message, $extra);
        }
        catch (\Exception $e) {
            return jsonResponse(true, 207, __('message.server_error'));
        }
    }

    public function getList(Request $request)
    {
        $sortOrder = (new Sms)->sortOrder;
        $sortEntity = (new Sms)->sortEntity;

        $result = null;
        if($request->ajax()) {
            $sortEntity = $request->sortEntity;
            $sortOrder = $request->sortOrder;

            $result = (new Sms)->pagination($request);
            return view('sms.pagination', compact('result', 'sortOrder', 'sortEntity'));
        }

        return view('sms.index', compact('result', 'sortOrder', 'sortEntity'));
    }
}