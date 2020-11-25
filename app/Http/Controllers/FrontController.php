<?php

namespace App\Http\Controllers;

use App\Booking;
use App\User;
use App\Validation;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function home() {
        return view('front.home');
    }

    public function homeBook(Request $request) 
    {
        $inputs = $request->all();
        $validation = (new Booking)->homeFrontBookByRoute($inputs);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        $booking = [
            'vehicle_category_id' => $inputs['vehicle_category'],
            'booking_type' => $inputs['booking_type'],
            
            'from_location' => $inputs['from_location'],
            'from_lat' => '75.36',
            'from_lon' => '31.38',
        ];

        if($inputs['booking_type'] == 'route') {
            $booking['to_location'] = $inputs['to_location'];
            $booking['to_lat'] = '74.36';
            $booking['to_lon'] = '32.38';
        }

        session()->put('book', $booking);
        session()->put('email', $inputs['email']);
          
        if(!isAuthCustomerLogin()) {
            $emailExist = (new User)->customerEmailExist($inputs['email']);
            if ($emailExist) {
                $extra['redirect'] = route('front.login-page');
                return jsonResponse(true, 201, '', $extra);
            }
            else {
                $password = rand(111111, 999999);
                $create = [
                    'email' => $inputs['email'],
                    'password' => \Hash::make($password),
                    'status' => '1',
                    'user_type' => 'customer'
                ];
                $id = (new User)->store($create);
                $booking['customer_id'] = $id;
                \Auth::loginUsingId($id);
               
                // send mail code start
                sendRegisterMail($inputs['email'], $password);

                $extra['redirect'] = route('front.book');
                return jsonResponse(true, 201, '', $extra);
            }
        }
        else {
            $extra['redirect'] = route('front.book');
            return jsonResponse(true, 201, '', $extra);
        }
    }

    public function book(Request $request) 
    {
        $e = session('email', '');
        if($e == '') {
            $e = authCustomerEmail();
        }

        $m = authCustomerMobile();
        $fl = session('book.from_location', '');
        $tl = session('book.to_location', '');
        $vc = session('book.vehicle_category_id', '');
        $t = session('book.booking_type', 'route');
        return view('front.book', compact('e', 'fl', 'tl', 'vc', 't', 'm'));
    }

    public function bookByRoute(Request $request) {
        $inputs = $request->all();
        $validation = (new Booking)->frontBookByRoute($inputs);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        $transferDate = $inputs['transfer_date'] . ' ' . $inputs['transfer_time'];
        $booking = [
            'customer_id' => authCustomerId(),
            'booking_number' => (new Booking)->bookingNumber(),
            'vehicle_category_id' => $inputs['vehicle_category'],
            'booking_type' => 'route',
            'booking_status' => 'pending',
            'customer_mobile_number' => $inputs['mobile_number'],

            'from_location' => $inputs['from_location'],
            'from_lat' => '75.36',
            'from_lon' => '31.38',

            'to_location' => $inputs['to_location'],
            'to_lat' => '74.36',
            'to_lon' => '32.38',

            'transfer_datetime' => dateFormat($transferDate, 'Y-m-d H:i:s'),
            'no_of_adult' => $inputs['no_of_adult'],
            'no_of_children' => $inputs['no_of_children'],
            'requirement' => $inputs['requirement'],
        ];

        if(isset($inputs['is_return_way'])) {
            $booking['is_return_way'] = 1;
            $returnDate = $inputs['return_date'] . ' ' . $inputs['return_time'];
            $booking['return_datetime'] = dateFormat($returnDate, 'Y-m-d H:i:s');
        }
        if(isset($inputs['is_flight'])) {
            $booking['is_flight'] = 1;
            $booking['flight_no'] = $inputs['flight_number'];
        }
        if(isset($inputs['is_meeting'])) {
            $booking['is_meeting'] = 1;
            $booking['passenger_name'] = $inputs['passenger_name'];
        }
        if(isset($inputs['is_promo_code'])) {
            $booking['is_promo_code'] = 1;
            $booking['promo_code'] = $inputs['promo_code'];
        }

        try {
            \DB::beginTransaction();
            (new Booking)->store($booking);

            if(authCustomerMobile() == '') {
                (new User)->store(['mobile_number' => $inputs['mobile_number']], authCustomerId());
            }
            \DB::commit();

            if(session()->has('email')) {
                session()->remove('email');
            }
            if(session()->has('book')) {
                session()->remove('book');
            }

            $extra = ['redirect' => route('front.pending')];
            return jsonResponse(true, 201, '', $extra);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function bookPerHour(Request $request) {
        $inputs = $request->all();
        $validation = (new Booking)->frontBookPerHour($inputs);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        $transferDate = $inputs['transfer_date'] . ' ' . $inputs['transfer_time'];
        $returnDate = $inputs['return_date'] . ' ' . $inputs['return_time'];
        $booking = [
            'customer_id' => authCustomerId(),
            'booking_number' => (new Booking)->bookingNumber(),
            'vehicle_category_id' => $inputs['vehicle_category'],
            'booking_type' => 'hour',
            'booking_status' => 'pending',
            'customer_mobile_number' => $inputs['mobile_number'],

            'from_location' => $inputs['from_location'],
            'from_lat' => '75.36',
            'from_lon' => '31.38',

            'to_location' => $inputs['to_location'],
            'to_lat' => '74.36',
            'to_lon' => '32.38',

            'transfer_datetime' => dateFormat($transferDate, 'Y-m-d H:i:s'),

            'is_return_way' => 1,
            'return_datetime' => dateFormat($returnDate, 'Y-m-d H:i:s'),

            'no_of_adult' => $inputs['no_of_adult'],
            'no_of_children' => $inputs['no_of_children'],
            'requirement' => $inputs['requirement'],
        ];

        if(isset($inputs['is_flight'])) {
            $booking['is_flight'] = 1;
            $booking['flight_no'] = $inputs['flight_number'];
        }
        if(isset($inputs['is_meeting'])) {
            $booking['is_meeting'] = 1;
            $booking['passenger_name'] = $inputs['passenger_name'];
        }
        if(isset($inputs['is_promo_code'])) {
            $booking['is_promo_code'] = 1;
            $booking['promo_code'] = $inputs['promo_code'];
        }

        try {
            \DB::beginTransaction();
            (new Booking)->store($booking);
            
            if(authCustomerMobile() == '') {
                (new User)->store(['mobile_number' => $inputs['mobile_number']], authCustomerId());
            }
            \DB::commit();

            if(session()->has('email')) {
                session()->remove('email');
            }
            if(session()->has('book')) {
                session()->remove('book');
            }

            $extra = ['redirect' => route('front.pending')];
            return jsonResponse(true, 201, '', $extra);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function loginPage()
    {
        if(isAuthCustomerLogin()) {
            return redirect()->route('front.book');
        }

        $email = session('email', '');
        return view('front.login', compact('email'));
    }

    public function loginPagePost(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Validation)->frontLogin($inputs);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        try {
            $credentials = [
                'email' => $inputs['email'],
                'password' => $inputs['password'],
                'status' => '1',
                'user_type' => 'customer'
            ];

            if (\Auth::attempt($credentials)) {
                $extra['redirect'] = route('front.book');
                return jsonResponse(true, 201, '', $extra);
            }
            else {
                return jsonResponse(false, 207, __('message.invalid_details'));
            }
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function pending() 
    {
        $customerId = authCustomerId();
        $result = (new Booking)->pending($customerId);
        return view('front.pending', compact('result'));
    }

    public function upcoming() 
    {
        $customerId = authCustomerId();
        $result = (new Booking)->upcoming($customerId);
        return view('front.upcoming', compact('result'));
    }

    public function past() 
    {
        $customerId = authCustomerId();
        $result = (new Booking)->past($customerId);
        return view('front.past', compact('result'));
    }

    public function bookEdit($id) 
    {
        $customerId = authCustomerId();
        $result = (new Booking)->customerBooking($customerId, $id);
        if(!$result) {
            return redirect()->route('front.pending');
        }

        $e = session('email', '');
        if($e == '') {
            $e = authCustomerEmail();
        }

        $m = authCustomerMobile();
        $fl = session('book.from_location', '');
        $tl = session('book.to_location', '');
        $vc = session('book.vehicle_category_id', '');
        $t = session('book.booking_type', 'route');

        return view('front.book-edit', compact('result', 'e', 'fl', 'tl', 'vc', 't', 'm'));
    }

    public function bookEditPost($id) 
    {
        $customerId = authCustomerId();
        $result = (new Booking)->customerBooking($customerId, $id);
        if(!$result) {
            return redirect()->route('front.pending');
        }

        return view('front.book-edit', compact('result',));
    }

    public function bookCancel($id) 
    {
        $customerId = authCustomerId();
        $result = (new Booking)->customerBooking($customerId, $id);
        if(!$result) {
            return jsonResponse(false, 207, __('message.invalid_details'));
        }

        try {
            \DB::beginTransaction();            
            \DB::commit();            
        }
        catch(\Exception $e) {

        }
    }

    public function faqs() {
        return view('front.faqs');
    }

    public function reward() {
        if(!isAuthCustomerLogin()) {
            return redirect()->route('front.home');
        }

        return view('front.reward');
    }
    
    public function setting() {
        return view('front.setting');
    }

    public function login(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Validation)->frontLogin($inputs);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        try {
            \DB::beginTransaction();
            $credentials = [
                'email' => $inputs['email'],
                'password' => $inputs['password'],
                'status' => '1',
                'user_type' => 'customer'
            ];

            if (\Auth::attempt($credentials)) {
                $extra['redirect'] = route('front.pending');
                return jsonResponse(true, 201, '', $extra);
            }
            else {
                return jsonResponse(false, 207, __('message.invalid_details'));
            }
        }
        catch(\Exception $e) {
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function register(Request $request)
    {

        $inputs = $request->all();
        $validation = (new Validation)->frontRegister($inputs);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        try {
            \DB::beginTransaction();
            $create = [
                'name' => $inputs['name'],
                'mobile_number' => $inputs['mobile_number'],
                'email' => $inputs['email'],
                'password' => \Hash::make($inputs['password']),
                'status' => '1',
                'user_type' => 'customer'
            ];
            $id = (new User)->store($create);
            \DB::commit();

            \Auth::loginUsingId($id);

            $extra['redirect'] = route('front.book');
            return jsonResponse(true, 201, '', $extra);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function logout()
    {
        try {
            \Auth::logout();
            $extra['redirect'] = route('front.home');
            return jsonResponse(true, 201, '', $extra);
        }
        catch(\Exception $e) {
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function forgotPassword() {
        return view('front.forgot-password');
    }

    public function forgotPasswordPost(Request $request) 
    {
        $inputs = $request->all();
        $validation = (new Validation)->frontForgotPassword($inputs);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        $result = (new User)->customerEmailExist($inputs['email']);
        if (!$result) {
            $message = __('message.invalid_email');
            return jsonResponse(false, 207, $message);
        }

        try {
            \DB::beginTransaction();

            // user code start
            $password = rand(111111, 999999);
            $update = [
                'password' => \Hash::make($password)
            ];

            (new User)->store($update, $result->id);
            // user code end

            // send mail code start
            sendRegisterMail($inputs['email'], $password);
            // send mail code end

            \DB::commit();

            $extra = ['redirect' => route('front.forgot-password')];
            $message = __('message.password_updated');

            return jsonResponse(true, 201, $message, $extra);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }
}
