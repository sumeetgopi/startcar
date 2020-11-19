<?php

namespace App\Http\Controllers;

use App\Notification;
use App\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotification() {
        return view('notification.get');
    }

    public function setNotification(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Notification)->validation($inputs);
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
                $tokens = $inputs['mobile_number'];
            }
            else {
                $customerTokens = (new User)->getCustomerFcmToken();
                if(isset($customerTokens) && count($customerTokens) > 0) {
                    $tokens = array_column($customerTokens->toArray(), 'fcm_token');
                }
            }

            if(isset($tokens) && count($tokens) > 0) {
                $tokens = array_unique($tokens);
                $fcmData = [
                    'order_id' => (int) 0,
                    'order_status' => (string) 'from_web',
                    'title' => (string) $inputs['title'] ?? '',
                    'body' => (string) $inputs['message'] ?? '',
                ];
                sendFcmNotificaton($tokens, $inputs['title'], $inputs['message'], $fcmData);
            }

            $extra = ['redirect' => route('notification')];
            $message = __('notification.send');
            return jsonResponse(true, 201, $message, $extra);
        }
        catch (\Exception $e) {
            return jsonResponse(true, 207, __('message.server_error'));
        }
    }

    public function getList(Request $request)
    {
        $sortOrder = (new Notification)->sortOrder;
        $sortEntity = (new Notification)->sortEntity;

        $result = null;
        if($request->ajax()) {
            $sortEntity = $request->sortEntity;
            $sortOrder = $request->sortOrder;

            $result = (new Notification)->pagination($request);
            return view('notification.pagination', compact('result', 'sortOrder', 'sortEntity'));
        }

        return view('notification.index', compact('result', 'sortOrder', 'sortEntity'));
    }
}