<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getSetting() {
        return view('setting.setting');
    }

    public function setSetting(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Setting)->validation($inputs);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }
        try {

            // center image code start
            if($request->hasFile('center_image')) {
                $path = env('SETTING_PATH');
                $centerImage = webImgUpload($request, 'center_image', $path);
                if($centerImage) {
                    $inputs['center_image'] = $centerImage;
                    $settingCenterImage = getSetting('center_image');
                    removeImg($settingCenterImage, $path);
                }
            }
            // center image code end

            (new Setting)->saveSetting($inputs);
            \DB::commit();

            $extra = ['redirect' => route('setting')];
            $message = __('setting.updated');
            return jsonResponse(true, 201, $message, $extra);
        }
        catch (\Exception $e) {
            \DB::rollBack();
            return jsonResponse(true, 207, __('message.server_error'));
        }
    }
}