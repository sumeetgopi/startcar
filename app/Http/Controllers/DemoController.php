<?php

namespace App\Http\Controllers;

use App\Data;
use App\Setting;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function setting()
    {
        return view('demo.setting');
    }

    public function coupon() {
        return view('demo.coupon');
    }

    public function getData() {
        return view('demo.data');
    }

    public function setData(Request $request)
    {
        $inputs = $request->all();
        $validation = validator($inputs, [
            'data' => 'required'
        ]);

        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        try {
            $data = $inputs['data'];

            $entries = (new Data)->getData();
            $finding = [];
            $replacing = [];
            if(isset($entries) && count($entries) > 0) {
                foreach($entries as $entry) {
                    $finding[] = '/\b'.trim($entry->finding).'\b/u';
                    $replacing[] = trim($entry->replacing);
                }
            }

            $data = preg_replace($finding, $replacing, $data);
            $data = str_replace(['+', '¢', '»', '¥'], 's', $data);
            $data = str_replace('”', '"', $data);
            $data = str_replace(
                [' no-', ' to-', ' do-', ' so-', ' a-'], 
                [' no', ' to', ' do', ' so', ' a'], 
            $data);
            $data = strtolower($data);
            return jsonResponse(true, 201, $data);
        }
        catch (\Exception $e) {
            return jsonResponse(true, 207, __('message.server_error'));
        }
    }
}