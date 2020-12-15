<?php

namespace App;

class Validation
{
    public function register($inputs = []) {
        $rules = [
            'name' => 'nullable|min:1|max:255|regex:/^[a-zA-Z ]+$/',
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

    public function customerProfile($inputs = [], $id) {
        $rules = [
            'name' => 'required|min:1|max:255|regex:/^[a-zA-Z ]+$/', 
            'email' => 'nullable|email|max:255|unique:users,email,' . $id,
            // 'image' => 'present|nullable|regex:/^data:image/',
        ];
        return validator($inputs, $rules);
    }

    public function product($inputs = []) {
        $rules = [
            // 'category_id' => 'required|numeric|min:1|exists:category,id'
            'type' => 'nullable|in:discounted,purchased'
        ];
        return validator($inputs, $rules);
    }

    public function productDetail($inputs = []) {
        $rules = [
            'product_id' => 'required|numeric|min:1|exists:product,id'
        ];
        return validator($inputs, $rules);
    }

    public function search($inputs = []) {
        $rules = [
            'keyword' => 'required|min:1'
        ];
        return validator($inputs, $rules);
    }

    public function addToCartBkp($inputs = []) {
        $rules = [
            'product_id' => 'required|numeric|min:1|exists:product,id',
            'quantity' => 'required|numeric|min:1|max:9999'
        ];
        return validator($inputs, $rules);
    }

    public function addToCart($inputs = []) {
        $rules = [
            'product_id' => 'required|numeric|min:1|exists:product,id',
            'quantity' => 'required|numeric|min:1|max:9999',
            'type' => 'required|in:add,update,delete'
        ];

        if(isset($inputs['type'])) {
            if($inputs['type'] == 'update') {
                unset($rules['product_id']);
                $rules['cart_id'] = 'required|numeric|min:1|exists:cart,id';
            }
            else if($inputs['type'] == 'delete') {
                unset($rules['product_id']);
                unset($rules['quantity']);
                $rules['cart_id'] = 'required|numeric|min:1|exists:cart,id';
            }
        }
        return validator($inputs, $rules);
    }

    public function updateCart($inputs = []) {
        $rules = [
            'cart_id' => 'required|numeric|min:1|exists:cart,id',
            'quantity' => 'required|numeric|min:1|max:9999'
        ];
        return validator($inputs, $rules);
    }

    public function deleteFromCart($inputs = []) {
        $rules = [
            'cart_id' => 'required|numeric|min:1|exists:cart,id',
        ];
        return validator($inputs, $rules);
    }

    public function addAddress($inputs = []) {
        $rules = [
            'address' => 'required',
        ];
        return validator($inputs, $rules);
    }

    public function editAddress($inputs = []) {
        $rules = [
            'address_id' => 'required|numeric|min:1|exists:user_address,id',
            'address' => 'required',
        ];
        return validator($inputs, $rules);
    }

    public function deleteAddress($inputs = []) {
        $rules = [
            'address_id' => 'required|numeric|min:1|exists:user_address,id',
        ];
        return validator($inputs, $rules);
    }

    public function placeOrder($inputs = [])
    {
        $message = [];
        $rules = [
            'payment_mode' => 'required|in:cash,online',
            'address_id' => 'required|numeric|min:1|exists:user_address,id',
            'type' => 'nullable|in:cashback,coupon',
        ];

        if(isset($inputs['type'])) {
            if($inputs['type'] == 'coupon') {
                $rules['code'] = 'required';
            }
            else if($inputs['type'] == 'cashback') {
                $rules['code'] = 'required|numeric|min:1';
                $message['code.required'] = 'The cashback amount is required';
                $message['code.numeric'] = 'The cashback amount must be number';
                $message['code.min'] = 'The cashback amount alteast minimum 1';
            }
        }

        if(isset($inputs['payment_mode']) && $inputs['payment_mode'] == 'online') {
            $rules['razorpay_order_id'] = 'required';
        }
        
        return validator($inputs, $rules, $message);
    } 
    
    public function orderDetail($inputs = [])
    {
        $rules = [
            'order_id' => 'required|numeric|min:1|exists:orders,id',
        ];
        return validator($inputs, $rules);
    }

    public function applyCoupon($inputs = [])
    {
        $message = [];
        $rules = [
            'type' => 'nullable|in:cashback,coupon',
        ];

        if(isset($inputs['type'])) {
            if($inputs['type'] == 'coupon') {
                $rules['code'] = 'required';
            }
            else if($inputs['type'] == 'cashback') {
                $rules['code'] = 'required|numeric|min:1';
                $message['code.required'] = 'The cashback amount is required';
                $message['code.numeric'] = 'The cashback amount must be number';
                $message['code.min'] = 'The cashback amount alteast minimum 1';
            }
        }
        return validator($inputs, $rules, $message);
    } 

    public function cancelOrder($inputs = [])
    {
        $rules = [
            'order_id' => 'required|numeric|min:1|exists:orders,id',
            'cancel_reason' => 'required',
        ];
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
}
