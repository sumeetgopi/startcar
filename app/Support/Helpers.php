<?php

use App\BookingQuotation;
use App\Cart;
use App\Category;
use App\Notification;
use App\Setting;
use App\Sms;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

function jsonResponse($status, $statusCode, $message, $extra = [])
{
    $response = ['success' => $status, 'status' => $statusCode];
    if ($statusCode == 206) {
        $response['message'] = jsonErrors($message);
    } elseif ($statusCode == 207 || $statusCode == 201 || $statusCode == 204) {
        $response['message'] = $message;
    }

    $response['extra'] = $extra;
    return response()->json($response, $statusCode);
}

function jsonErrors($errors = []) {
    $error = [];
    foreach ($errors->toArray() as $key => $value) {
        $error[$key] = $value[0];
    }
    return $error;
}

function dumper(...$dump) {
    echo '<pre>';
    foreach ($dump as $d) {
        print_r($d); echo '<br/><br/>';
    }
    echo '</pre>'; die;
}

function status() {
    return [
        '' => '-Select-',
        0 => 'In Active',
        1 => 'Active'
    ];
}

function orderStatus() {
    return [
        'pending' => 'Pending',
        'accepted' => 'Accepted',
        'delivery' => 'Delivery',
        'completed' => 'Completed',
        'canceled' => 'Canceled',
    ];
}

function editOrderStatus($status) {
    if($status == 'pending') { 
        $types = [
            '' => '-Select Status -',
            'accepted' => 'Accepted',
            'canceled' => 'Canceled',
        ];
    }
    else if($status == 'accepted') { 
        $types = [
            '' => '-Select Status -',
            'delivery' => 'Delivery',
            'canceled' => 'Canceled',
        ];
    } 
    else if($status == 'delivery') { 
        $types = [
            '' => '-Select Status -',
            'completed' => 'Completed',
            'pending' => 'Pending',
            'canceled' => 'Canceled',
        ];
    }
    else if($status == 'completed') { 
        $types = [];
    }
    else if($status == 'canceled') { 
        $types = []; 
    }

    return $types;
}

function webImgUpload(Request $request, $column, $path) {
    $uploadFile = null;
    $file = $request->file($column);
    $filename = $file->getClientOriginalName();
    $filename = strtolower(str_replace(' ', '', $filename));
    $filename = str_random(8) . '_'. $filename;

    if($file->move($path, $filename)) {
        $uploadFile = $filename;
    }
    return $uploadFile;
}

function webImgUploadMultiple(Request $request, $column, $path) {
    $uploadFiles = [];
    $files = $request->file($column);

    foreach($files as $file) {
        $filename = $file->getClientOriginalName();
        $filename = strtolower(str_replace(' ', '', $filename));
        $filename = str_random(8) . '_' . $filename;

        if ($file->move($path, $filename)) {
            $uploadFiles[] = $filename;
        }
    }
    return $uploadFiles;
}


function removeImg($img, $path) {
    $file = public_path($path . $img);
    if ($img != '' && file_exists($file)) {
        unlink($file);
    }
}

function srNo($count = 0, $prefix = '', $length = 4) {
    $count++;
    return $prefix . str_pad($count, $length, 0, STR_PAD_LEFT);
}

function defaultPerPage() { return 20; }

function pageDetail($pagination) {
    $from = ((($pagination->currentPage() - 1) * $pagination->perPage()) + 1);
    $to = (($pagination->currentPage()) * $pagination->perPage());
    $total = $pagination->total();
    if ($to > $total) {
        $to = $pagination->total();
    }
    $detail = 'Showing ' . $from . ' to ' . $to . ' of ' . $total . ' entries';
    return $detail;
}

function pageIndex($pagination) {
    return $from = ((($pagination->currentPage() - 1) * $pagination->perPage()) + 1);
}

function perPage() {
    return [
        20 => 20, 50 => 50, 100 => 100,
        200 => 200, 500 => 500, 1000 => 1000
    ];
}

function sorting($sortEntity, $name, $sortOrder) {
    return '<a href="javascript:void(0);" data-sortEntity="' . $sortEntity . '" data-sortOrder="' . ($sortOrder == 'desc' ? 'asc' : 'desc') . '">
        ' . $name . ' <i class="fa fa-sort pull-right"></i> </a>';
}

function webImg($path = 'uploads/', $img, $style = 'width:100% !important;', $default = 'default.png') {
    $image = asset('uploads/' . $default);
    $file = public_path($path . $img);
    if ($img != '' && file_exists($file)) {
        $image = asset($path . $img);
    }
    return '<img src="'.$image.'" alt="'.$image.'" class="img-thumbnail img-fluid" style="'.$style.'" />';
}

function frontWebImg($path = 'uploads/', $img, $style = 'width:100% !important;', $default = 'default.png') {
    $image = asset('uploads/' . $default);
    $file = public_path($path . $img);
    if ($img != '' && file_exists($file)) {
        $image = asset($path . $img);
    }
    return '<img src="'.$image.'" alt="'.$image.'" style="'.$style.'" />';
}

function statusSlider($route, $id, $check) {
    $checked = ($check) ? 'checked="checked"' : '';
    return '<label class="switch">'.
    '<input type="checkbox" class="__status" '.$checked.' data-route="'.route($route, $id).'">'.
    '<span class="slider round"></span>'.
    '</label>';
}

function dateFormat($date, $format = 'Y-m-d') {
    $response = 'N/A';
    if($date != '') {
        $response = date($format, strtotime($date));
    }
    return $response;
}

function numberFormat($number) {
    return number_format($number, 2);
}

function getIndianCurrency($number) {
    if($number < 0) { $number = 0; }
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'fourty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'rupees only' : '') . $paise;
}

function apiResponse($status, $message, $data, $errors = false)
{
    if($errors) {
        $message = array_first($message->toArray())[0];
    }
 
    $response = [
        'status' => $status,
        'message' => $message,
        'data' => $data
    ];
    return response()->json($response);
}

function errors($errors) {
    $result = [];
    if (isset($errors) && count($errors) > 0) {
        foreach ($errors->toArray() as $key => $error) {
            $result[] = ['field' => $key, 'error' => $error[0]];
        }
    }
    return $result;
}


function getCustomer($customerId) {
    $response = [];
    $customer = (new User)->getCustomer($customerId);
    if($customer) {
        $response = [
            'name' => (string) $customer->name ?? '',
            'email' => (string) $customer->email ?? '',
            'mobile_number' => (string) $customer->mobile_number ?? '',
            'image' => (string) apiImg($customer->image, env('CUSTOMER_PATH')) ?? '',
            'rozarpay_customer_id' => (string) $customer->rozarpay_customer_id ?? '',
        ];
    }
    return $response;
}

function getProduct($row, $productImg = [], $cartInfo = []) {
    $isSelected = (isset($row->order_is_selected) && $row->order_is_selected == 1) ? '1' : '0';
    
    return [
        'id' => (int) $row->id ?? 0,
        'category_id' => (int) $row->category_id ?? 0,
        'sub_category_id' => (int) $row->sub_category_id ?? 0,
        'unit_id' => (int) $row->unit_id ?? 0,
        'product_number' => (string) $row->product_number ?? '',
        'product_name' => (string) $row->product_name ?? '',
        'weight' => (string) (amount($row->weight) ?? '0') . ' '. ($row->unit_name ?? ''),
        'description' => (string) $row->description ?? '',
        'mrp_price' => (string) amount($row->mrp_price) ?? '0',
        'our_price' => (string) amount($row->our_price) ?? '0',
        'category_number' => (string) $row->category_number ?? '',
        'category_name' => (string) $row->category_name ?? '',
        'unit_name' => (string) $row->unit_name ?? '',
        'discount_percent' => (string) $row->discount_percent ?? '0%',
        'product_image' => $productImg,

        'cart_info' => $cartInfo,

        'order_info' => [
            'is_selected' => (string) $isSelected,
            'previous_quantity' => (isset($row->order_previous_quantity) && $isSelected) ? (string) amount($row->order_previous_quantity) : '0',
            'quantity' => (isset($row->order_quantity) && $isSelected) ? (string) amount($row->order_quantity) : '0',
            'price' => (isset($row->order_price) && $isSelected) ? (string) amount($row->order_price) : '0',
            'amount' => (isset($row->order_amount) && $isSelected) ? (string) amount($row->order_amount) : '0',
        ]
    ];
}

function getOrder($row) {
    return [
        'id' => (int) $row->id ?? $row->order_id ?? 0,
        'order_number' => (string) $row->order_number ?? '',
        'order_status' => (string) $row->order_status ?? '',
        'order_date' => (string) dateFormat($row->order_date, 'd-m-Y h:i A') ?? '',
        'order_short_date' => (string) dateFormat($row->order_date, 'd-m-Y') ?? '',
        'delivery_date' => (string) ($row->delivery_date != '') ? dateFormat($row->delivery_date, 'd-m-Y') : '',
        'grand_amount' => (string) amount($row->grand_amount) ?? '0',
        'total_amount' => (string) amount($row->total_amount) ?? '0',
        'coupon_discount_amount' => (string) amount($row->coupon_discount_amount) ?? '0',
        'cashback_redeem_amount' => (string) amount($row->cashback_redeem_amount) ?? '0',
        'cashback_offer_amount' => (string) amount($row->cashback_offer_amount) ?? '0',
        'total_product' => (string) $row->total_product ?? '0',
        'payment_mode' => (string) $row->payment_mode ?? '',
        'address' => (string) $row->address ?? '',
        
        'total_mrp_price' => (string) amount($row->total_mrp_price) ?? '0',
        'total_our_price' => (string) amount($row->total_our_price) ?? '0',
        'total_discounted_price' => (string) amount($row->total_mrp_price - $row->total_our_price) ?? '0',

        'customer_cancel_reason' => (string) $row->customer_cancel_reason ?? '',
        'admin_cancel_reason' => (string) $row->admin_cancel_reason ?? '',
    ];
}

function apiImg($img, $path, $default = 'uploads/default.png') {
    $image = asset($default);
    $file = public_path($path . $img);
    if ($img != '' && file_exists($file)) {  $image = asset($path . $img); }
    return $image;
}

function customerToken($customer) { return 'bearer ' . auth('api')->fromUser($customer); }
function customerLogout($token) { return auth('api')->invalidate($token); }
function customerId() { return auth('api')->user()->id; }
function customerMobileNumber() { return auth('api')->user()->mobile_number; }
function customerFcmToken() { return auth('api')->user()->fcm_token; }
function customerCashbackAmount() { return auth('api')->user()->cashback_amount; }


function isAuthCustomerLogin() { return \Auth::check(); }
function authCustomerId() { return \Auth::id(); }

function cartDetail($customerId) {
    $result = (new Cart)->cartDetail($customerId);
    
    $detail = [];
    $totalItem = $totalMrpPrice = $totalOurPrice = $totalDiscountedPrice = 0;
    if($result) {
        $path = env('PRODUCT_PATH');
        foreach($result as $row) {
            $productImage = [
                [
                    'image' => (string) apiImg($row->product_image, $path, 'uploads/banner/center_image.png') ?? ''
                ]
            ];

            $cartInfo = cartInfo($row->id, $customerId);
            $detail[] = [
                'cart_id' => (int) $row->cart_id ?? 0,
                'quantity' => (string) (int) $row->quantity ?? 0,
                'product' => getProduct($row, $productImage, $cartInfo)
            ];

            $totalItem = $totalItem + ($row->quantity);
            $mrpPrice = ($row->mrp_price * $row->quantity);
            $ourPrice = ($row->our_price * $row->quantity);
            $totalMrpPrice +=  $mrpPrice;
            $totalOurPrice +=  $ourPrice;
            $totalDiscountedPrice += ($mrpPrice - $ourPrice);
        }
    }

    return [
        'detail' => $detail ?? [],
        'summary' => [
            'total_item' => (string) $totalItem ?? 0,
            'total_mrp_price' => (string) round($totalMrpPrice, 2) ?? 0,
            'total_our_price' => (string) round($totalOurPrice, 2) ?? 0,
            'total_discounted_price' => (string) round($totalDiscountedPrice, 2) ?? 0
        ]
    ];
}

function cartDetailSingle($cartId, $customerId, $type) {
    $result = (new Cart)->cartDetailSingle($cartId, $customerId);
    $detail = new ArrayObject();
    
    if($result) {
        $path = env('PRODUCT_PATH');
        foreach($result as $row) {
            $productImage = [[
                'image' => (string) apiImg($row->product_image, $path, 'uploads/banner/center_image.png') ?? ''
            ]];

            if($type == 'delete') {
                $cartInfo = [
                    'has_in_cart' => (bool) false,
                    'cart_id' => (int) 0,
                    'cart_quantity' => (string) 0
                ];
            }
            else {
                $cartInfo = cartInfo($row->id, $customerId);
            }

            $detail = [
                'cart_id' => (int) $row->cart_id ?? 0,
                'quantity' => (string) (int) $row->quantity ?? 0,
                'product' => getProduct($row, $productImage, $cartInfo),
            ];

            if($type != 'delete') {
                $detail['summary'] = cartSummary($customerId);
            }
        }
    }

    return $detail;
}

function cartSummary($customerId) 
{
    $totalItem = totalCartItem($customerId);
    $totalMrpPrice = totalCartMrpPrice($customerId);
    $totalOurPrice = totalOurPrice($customerId);
    $totalDiscountedPrice = totalDiscountedPrice($customerId);

    return [
        'total_item' => (string) $totalItem ?? 0,
        'total_mrp_price' => (string) round($totalMrpPrice, 2) ?? 0,
        'total_our_price' => (string) round($totalOurPrice, 2) ?? 0,
        'total_discounted_price' => (string) round($totalDiscountedPrice, 2) ?? 0
    ];
}

function totalCartItem($customerId) {
    $result = (new Cart)->totalCartItem($customerId);
    return ($result) ? $result->total_cart_item : '0';
}

function totalCartMrpPrice($customerId) {
    $result = (new Cart)->totalCartMrpPrice($customerId);
    return ($result) ? $result->total_cart_mrp_price : '0';
}

function totalOurPrice($customerId) {
    $result = (new Cart)->totalOurPrice($customerId);
    return ($result) ? $result->total_cart_our_price : '0';
}

function totalDiscountedPrice($customerId) {
    $result = (new Cart)->totalDiscountedPrice($customerId);
    return ($result) ? ($result->total_cart_mrp_price - $result->total_cart_our_price) : '0';
}

function cartInfo($productId, $customerId)
{
    $hasInCart = false;
    $cartId = 0;
    $cartQuantity = 0;

    $result = (new Cart)->cartInfo($productId, $customerId);
    if($result) {
        $hasInCart = true;
        $cartId = $result->id;
        $cartQuantity = $result->quantity;
    }

    return [
        'has_in_cart' => (bool) $hasInCart ?? false,
        'cart_id' => (int) $cartId ?? 0,
        'cart_quantity' => (string) $cartQuantity ?? 0
    ];
}

function amount($number) {
    $n1 = (int) $number ?? 0;
    $n2 = (float) $number ?? 0;

    if($n2 > $n1) {
        $number = round($n2, 2);
    }
    else {
        $number = (int) $n1;
    }
    return (string) $number;
}

function discountType($type = '', $heading = false) {
    $types = [
        'fixed' => 'Fixed',
        'percent' => 'Percent'
    ];

    if($heading) { $types = ['' => '-Select Discount Type-'] + $types; }
    if($type != '') { return $types[$type]; }
    return $types;
}

function applyType($type = '', $heading = false) {
    $types = [
        'single' => 'Single',
        'multiple' => 'Multiple'
    ];

    if($heading) { $types = ['' => '-Select Apply Type-'] + $types; }
    if($type != '') { return $types[$type]; }
    return $types;
}

function couponType($type = '', $heading = false) {
    $types = [
        'cashback' => 'Cashback',
        'coupon' => 'Coupon'
    ];

    if($heading) { $types = ['' => '-Select Coupon Type-'] + $types; }
    if($type != '') { return $types[$type]; }
    return $types;
}

function smsSendType($type = '', $heading = false) {
    $types = [
        'all' => 'All',
        'selected' => 'Selected'
    ];

    if($heading) { $types = ['' => '-Select Send Type-'] + $types; }
    if($type != '') { return $types[$type]; }
    return $types;
}

function notificationSendType($type = '', $heading = false) {
    $types = [
        'all' => 'All',
        'selected' => 'Selected'
    ];

    if($heading) { $types = ['' => '-Select Send Type-'] + $types; }
    if($type != '') { return $types[$type]; }
    return $types;
}

function radio($name, $array = [], $match = null, $attr = '') {
    $str = '';
    if(isset($array) && count($array) > 0) {
        foreach($array as $k => $v) {
            $checked = ($k == $match) ? 'checked="checked"' : '';
            $str .= "<label style='margin-right: 10px;'><input type='radio' name='$name' value='$k' $checked $attr /> $v</label>";
        }
    }
    return $str;
}

function getSetting($settingName, $default = '') {
    return (new Setting)->getSetting($settingName, $default);
}

function sendFcmNotificaton($token, $title, $body, $data = []) {
    $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    
    $notification = [
        'title' => $title,
        'body' => $body,
        'sound' => true,
    ];

    $fcmNotification = [
        //'registration_ids' => $tokenList, //multple token array
        // 'to' => $token, // single token
        // 'notification' => $notification,
        'data' => $data
    ];

    if(is_array($token)) {
        $fcm = $token;
        $fcmNotification['registration_ids'] = $token;
    }
    else {
        $fcm[] = $token;
        $fcmNotification['to'] = $token;
    }

    $headers = [
        'Authorization: key=' . env('FCM_KEY'),
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fcmUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
    $result = curl_exec($ch);

    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        return "cURL Error #:" . $err;
    } 
    else {  
        $mobile_number = '';  
        $customerTokens = (new User)->getCustomerByFcm($fcm);
        if(isset($customerTokens) && count($customerTokens) > 0) {
            $mobile_number = array_column($customerTokens->toArray(), 'mobile_number');
        }

        $create = [
            'title' => $title,
            'message' => $body,
            'mobile_number' => implode(',', $mobile_number)
        ];
        (new Notification)->store($create);

        return $result;
    }
}

function sendSMS($mobile, $msg, $countryCode = '91')
{
    $user = '20092126';
    $pwd = 'topgun@16';
    $sender = 'Kryana';

    $message = $msg;
    $msg = urlencode($msg);

    if(is_array($mobile)) {
        $mobile = implode(',', $mobile);
    }
    
    $url = "http://mshastra.com/sendurlcomma.aspx?user=$user&pwd=$pwd&senderid=$sender&CountryCode=$countryCode&mobileno=$mobile&msgtext=$msg";
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "cURL Error #:" . $err;
    } 
    else {    
        $create = [
            'message' => $message,
            'mobile_number' => $mobile
        ];
        (new Sms)->store($create);

        return $response;
    }
}

function customerMobileService() {
    return (new User)->customerMobileService();
}

function customerFcmTokenService() {
    return (new User)->customerFcmTokenService();
}

function arrayPaginator($array, $request, $perPage = 20)
{
    $page = $request->get('page', 1);
    $offset = ($page * $perPage) - $perPage;

    return new LengthAwarePaginator(
        array_values(array_slice($array, $offset, $perPage, true)), 
        count($array), 
        $perPage, 
        $page,
        [
            'path' => $request->url(), 
            'query' => $request->query()
        ]
    );
}

function cancelReason() {
    return [
        ['id' => 1, 'text' => 'Cancel reason1'],
        ['id' => 2, 'text' => 'Cancel reason2'],
        ['id' => 3, 'text' => 'Cancel reason3'],
    ];
}

function webCancelReason($type = null) {
    $types = [
        '' => '-Select Cancel Reason-',
        '1' => 'Cancel reason1',
        '2' => 'Cancel reason2',
        '3' => 'Cancel reason3',
    ];

    if($type != '') { return $types[$type]; }
    return $types;
}

function replaceSpecialChar($str = '') {
    $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
    return strtr( $str, $unwanted_array);
}

function orderCount($type = null) {
    $types = [
        '' => '-All-',
        '1_order' => 'Single Order',
        '1_5_order' => '1-5 Orders',
        '5_10_order' => '5-10 Orders',
        'more_than_10_order' => 'More than 10 Orders',
    ];

    if($type != '') { return $types[$type]; }
    return $types;
}

function friendlyTime($date_time) {
    $day = 60 * 60 * 24; $hour = 60 * 60; $minute = 60;
    $week = $day * 7; $month = $day * 30; $year = $day * 365;
    $time = strtotime($date_time);
    $current_time = time();
    $time_diff = $current_time - $time;
    if ($time_diff < $minute) return $time_diff . ' seconds ago';
    if ($time_diff < $hour) return floor($time_diff / $minute) . ' minutes ago';
    if ($time_diff < $day) return floor($time_diff / $hour) . ' hours ago';
    if ($time_diff < $week) return floor($time_diff / $day) . ' days ago';
    if ($time_diff < $month) return floor($time_diff / $week) . ' weeks ago';
    if ($time_diff < $year) return floor($time_diff / $month) . ' months ago';
    return floor($time_diff / $year) . ' years ago';
}

function bookingQuotation($bookingId) {
    return (new BookingQuotation)->bookingQuotation($bookingId);
}

function bookingCategory() {
    return (new Category)->bookingCategory();
}
?>