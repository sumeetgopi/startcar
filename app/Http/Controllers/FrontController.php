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

    public function book(Request $request) {
        $e = $request->get('e', '');
        $m = '';
        if(isAuthCustomerLogin()) {
            $e = authCustomerEmail();
            $m = authCustomerMobile();
        }

        $fl = $request->get('fl', '');
        $tl = $request->get('tl', '');
        $vc = $request->get('vc', '');
        $t = $request->get('t', '');
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

        if(!isAuthCustomerLogin()) {
            // check email code start
            $emailExist = (new User)->customerEmailExist($inputs['email']);
            if ($emailExist) {
                session()->put('book', $booking);
                session()->put('email', $inputs['email']);
                $extra['redirect'] = route('front.login-page');
                return jsonResponse(true, 201, '', $extra);
            }
            else {
                $password = rand(111111, 999999);
                $create = [
                    'mobile_number' => $inputs['mobile_number'],
                    'email' => $inputs['email'],
                    'password' => \Hash::make($password),
                    'status' => '1',
                    'user_type' => 'customer'
                ];
                $id = (new User)->store($create);
                $booking['customer_id'] = $id;
                \Auth::loginUsingId($id);

                $email = $inputs['email'];
                /* $data = [
                    'subject' => 'Login Details',
                    'username' => $email,
                    'password' => $password,
                    'url' => route('front.home')
                ];

                 \Mail::send('front.mail.register', compact('data'), function ($message) use ($data, $email) {
                    $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'));
                    $message->to($email);
                    $message->subject($data['subject']);
                }); */

                // send email start
                $subject = "Login Info";
                $msg = "Username/Email: $email\nPassword: $password";
                $msg = wordwrap($msg, 70);
                mail($email, $subject, $msg);
                // send mail end

            }
            // check email code end
        }
        else {
            $booking['customer_id'] = authCustomerId();
        }

        try {
            \DB::beginTransaction();
            (new Booking)->store($booking);
            \DB::commit();

            $extra = ['redirect' => route('front.pending')];
            return jsonResponse(true, 201, '', $extra);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error') . $e->getMessage());
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
            'customer_id' => 2,
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

        // check email code start
        $emailExist = (new User)->customerEmailExist($inputs['email']);
        if($emailExist) {
            session()->put('book', $booking);
            session()->put('email', $inputs['email']);
            $extra['redirect'] = route('front.login-page');
            return jsonResponse(true, 201, '', $extra);
        }
        else {
            $password = rand(111111, 999999);
            $create = [
                'mobile_number' => $inputs['mobile_number'],
                'email' => $inputs['email'],
                'password' => \Hash::make($password),
                'status' => '1',
                'user_type' => 'customer'
            ];
            $id = (new User)->store($create);
            $booking['customer_id'] = $id;
            \Auth::loginUsingId($id);

            $email = $inputs['email'];
           /*  $data = [
                'subject' => 'Login Details',
                'username' => $email,
                'password' => $password,
                'url' => route('front.home')
            ];

            \Mail::send('front.mail.register', compact('data'), function ($message) use ($data, $email) {
                $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'));
                $message->to($email);
                $message->subject($data['subject']);
            }); */

            // send email start
            $subject = "Login Info";
            $msg = "Username/Email: $email\nPassword: $password";
            $msg = wordwrap($msg, 70);
            mail($email, $subject, $msg);
            // send mail end
        }
        // check email code end

        try {
            \DB::beginTransaction();
            (new Booking)->store($booking);
            \DB::commit();

            return jsonResponse(true, 201, __('booking.created'));
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function loginPage()
    {
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
                $book = session('book');
                if(isset($book)) {
                    \DB::beginTransaction();
                    $book['customer_id'] = authCustomerId();
                    (new Booking)->store($book);

                    session()->remove('book');
                    session()->remove('email');
                    \DB::commit();
                }

                $extra['redirect'] = route('front.pending');
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
        if(!isAuthCustomerLogin()) {
            return redirect()->route('front.home');
        }
        
        $customerId = authCustomerId();
        $result = (new Booking)->pending($customerId);
        return view('front.pending', compact('result'));
    }

    public function upcoming() 
    {
        if(!isAuthCustomerLogin()) {
            return redirect()->route('front.home');
        }

        $customerId = authCustomerId();
        $result = (new Booking)->upcoming($customerId);
        return view('front.upcoming', compact('result'));
    }

    public function past() 
    {
        if(!isAuthCustomerLogin()) {
            return redirect()->route('front.home');
        }

        $customerId = authCustomerId();
        $result = (new Booking)->past($customerId);
        return view('front.past', compact('result'));
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
        if(!isAuthCustomerLogin()) {
            return redirect()->route('front.home');
        }

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
            if(!isAuthCustomerLogin()) {
                return redirect()->route('front.home');
            }
            
            \Auth::logout();
            $extra['redirect'] = route('front.home');
            return jsonResponse(true, 201, '', $extra);
        }
        catch(\Exception $e) {
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function test()
    {
        try {
            // mail code start
            $email = 'sumeetnarula1@gmail.com';
            $password = rand(111111, 999999);

            $data = [
                'subject' => 'Login Details',
                'username' => $email,
                'password' => $password,
                'url' => route('front.home')
            ];

            try {
                /* \Mail::send('front.mail.register', compact('data'), function ($message) use ($data, $email) {
                    $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'));
                    $message->to($email);
                    $message->subject($data['subject']);
                }); */

                // send email start
                $subject = "Login Info";
                $msg = "Username/Email: $email\nPassword: $password";
                $msg = wordwrap($msg, 70);
                mail($email, $subject, $msg);
                // send mail end
            }
            catch(\Exception $e) {
                dd($e->getMessage());
            }
                // mail code end

            dd('done');
        }
        catch(\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function gurpreetemail()
    {
        // send mail code start
               $email = "sumeetgopi13@gmail.com";
               $password = rand(111111, 999999);
               $subject = 'Login Details';
               
               $data = ['subject' => $subject, 'username' => $email , 'password' => $password];
               Mail::send('front.mail.registerDetails', ['data'=>$data] , function($message) use ($data, $email){
                $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'));
                
                $message->to($email);
                $message->subject($data['subject']);
            });            
            // send mail code end
            print_r('email code run');
    }
}
