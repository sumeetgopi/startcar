<?php

namespace App;

class Validation
{
    public function register($inputs = []) {
        $rules = [
            'name' => 'nullable|min:1|max:255|regex:/^[a-zA-Z]+$/',
            'device_type' => 'required|in:android,ios',
            'user_type' => 'required|in:customer,agency',
        ];

        if(isset($inputs['user_type'])) {
            if($inputs['user_type'] == 'customer') {
                $rules['email'] = 'required|email';
            }
            else if($inputs['user_type'] == 'agency') {
                $rules['mobile_number'] = 'required|numeric|digits:10';
            }
        }
        return validator($inputs, $rules);
    }

    public function otpVerify($inputs = []) {
        $rules = [
            'user_type' => 'required|in:customer,agency',
            'otp_code' => 'required|numeric|digits:4',
            // 'fcm_token' => 'required',
        ];

        if(isset($inputs['user_type'])) {
            if($inputs['user_type'] == 'customer') {
                $rules['email'] = 'required|email';
            }
            else if($inputs['user_type'] == 'agency') {
                $rules['mobile_number'] = 'required|numeric|digits:10';
            }
        }
        return validator($inputs, $rules);
    }

    public function frontLogin($inputs = [])
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:3',
        ];
        return validator($inputs, $rules);
    }

    public function frontRegister($inputs = [])
    {
        $rules = [
            'name' => 'required|min:3',
            'mobile_number' => 'required|digits:10',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3'
        ];
        return validator($inputs, $rules);
    }

    public function frontForgotPassword($inputs = [])
    {
        $rules = [
            'email' => 'required|email',
        ];
        return validator($inputs, $rules);
    }

    public function frontForgotPasswordOtp($inputs = [])
    {
        $rules = [
            'email' => 'required|email',
            'otp_code' => 'required|digits:4',
            'password' => 'required|min:3',
        ];
        return validator($inputs, $rules);
    }

    public function agencyUpdate($inputs = [])
    {
        $rules = [
            'agency_name' => 'nullable|regex:/^[a-zA-Z]+$/',
            'contact_person' => 'required|min:1|max:255|regex:/^[a-zA-Z]+$/',
            'email' => 'required|email',
            'address' => 'required|min:1|max:255',
            'gst_number' => 'nullable',
        ];
        return validator($inputs, $rules);
    }

    public function addDriver($inputs = [])
    {
        $rules = [
            'driver_name' => 'required|min:1|max:255|regex:/^[a-zA-Z]+$/',
            'license_number' => 'required',
            'experience_in_year' => 'required',
            'pan_adhar_number' => 'required',
            'pan_adhar_document' => 'required|image',
        ];
        return validator($inputs, $rules);
    }

    public function addVehicle($inputs = [])
    {
        $rules = [
            'model_in_year' => 'required',
            'brand_id' => 'required|numeric|min:1',
            'type_id' => 'required|numeric|min:1',
            'color_id' => 'required|numeric|min:1',
            'vehicle_variant' => 'required|in:automatic,manual',
            'kms_driven' => 'required',
            'registration_number' => 'required',

            'registration_document' => 'required|image',
            'insurance_document' => 'required|image',
            'vehicle_pics' => 'required|image',
        ];
        return validator($inputs, $rules);
    }
}
