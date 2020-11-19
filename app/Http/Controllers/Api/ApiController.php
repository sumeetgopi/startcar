<?php

namespace App\Http\Controllers\Api;

use App\Banner;
use App\Cart;
use App\Category;
use App\Coupon;
use App\OrderDetail;
use App\Product;
use App\ProductImage;
use App\UserAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notification;
use App\Order;
use App\ProductTag;
use App\Tag;
use App\User;
use App\UserCouponUsed;
use App\Validation;
use ArrayObject;
use Tymon\JWTAuth\Exceptions\JWTException;
use Razorpay\Api\Api;

class ApiController extends Controller
{
    private $api; 
    
    public function __construct() {
        $apiKey = env('RAZORPAY_KEY');
        $apiSecret = env('RAZORPAY_SECRET');
        $this->api = new Api($apiKey, $apiSecret);
    }
    public function register(Request $request) 
    {
        $inputs = $request->all();
        $validation = (new Validation)->customerRegister($inputs);
        $data = new ArrayObject();
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        // registered code start
        $isRegistered = false; 
        $customerId = null;
        $result = (new User)->findCustomer($inputs['mobile_number']);
        if($result) {
            $isRegistered = true; 
            $customerId = $result->id;
        }
        // registered code end

        try {    
            \DB::beginTransaction();
            $otpCode = rand(1111, 9999);
            $customer = [
                'mobile_number' => $inputs['mobile_number'],
                'device_type' => $inputs['device_type'],
                'otp_code' => $otpCode,
            ];

            if(!$isRegistered) {
                $customer['password'] = \Hash::make($inputs['mobile_number']);
            }

            (new User)->store($customer, $customerId);
            \DB::commit();

            // send sms code start
            $msg = __('message.sms_otp', ['otp_code' => $otpCode]);
            sendSMS($inputs['mobile_number'], $msg);
            // send sms code end

            $data = [
                // 'otp_code' => (string) $otpCode,
                'otp_code' => (string) '',
                'is_registered' => (bool) $isRegistered
            ];
            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function otpVerify(Request $request) 
    {
        $inputs = $request->all();
        $validation = (new Validation)->customerVerify($inputs);
        $data = new ArrayObject();
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        $result = (new User)->customerVerify($inputs['mobile_number'], $inputs['otp_code']);
        if(!$result) {
            return apiResponse(0, __('message.invalid_otp'), $data);
        }

        try {
            \DB::beginTransaction();
            $update = [
                'status' => '1',
                'fcm_token' => $inputs['fcm_token']
            ];

            // =================================
            // rozarpay code start =============
            // =================================
            if($result->rozarpay_customer_id == '') {
                
                $name = ($result->name != '') ? $result->name : 'Karyanna user';
                $mobile = $result->mobile_number;

                $customer = $this->api->customer->create([
                    'name' => $name,
                    'contact' => $mobile
                ]);

                if($customer) {
                    $update['rozarpay_customer_id'] = $customer->id;
                }
            }
            // ===================================
            // rozarpay code end =================
            // ===================================

            
            (new User)->store($update, $result->id);
            \DB::commit();

            $data = [
                'customer' => getCustomer($result->id),
                'token' => (string) customerToken($result)
            ];
            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function logout(Request $request) 
    {
        $apiToken = $request->header('Authorization');
        $data = new ArrayObject();
        try {
            customerLogout($apiToken);
            return apiResponse(1, __('message.logout'), $data);
        } 
        catch (JWTException $e) {
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function me(Request $request) 
    {
        // $data = auth('api')->user();
        /* $token = 'cqpLGlLMPGA:APA91bGgIu17ZmPI0kPr2x1M5wyepVAk715Be2Vpe2eiTU6hdho8SaWcZx6WsQe6c6oVg3Ozk5ax_Fh05VLhpSGd1DCXw-gxXfVdwj0oTvzgq-E7js9DCbYMzoWSyZCaNw2LFSO9S9nO';
        $title = 'testing title';
        $body = 'testing body';

        $data = [
            'first' => 'first data',
            'second' => 'second data'
        ];
        $fcm = sendFcmNotificaton($token, $title, $body, $data);
        dd($fcm); */

       /*  $mobile = '9876855626';
        $msg = 'Last my testing message';
        dumper(sendSMS($mobile, $msg)); */


        /* $products = (new Product)
            ->active()
            ->get(['id', 'product_name']);

        $tags = [];
        $t = new Tag();
        $pt = new ProductTag();
        if(isset($products) && count($products) > 0) {
            $search = [
                '&', 
                '(',
                ')',
                ')',
                "'",
                '-',
                ',',
                '%',
                '.',
                "/",
                "+",
                '"',
            ];

            foreach($products as $row) {
                $id = $row->id;
                $p = trim(str_replace($search, ' ', $row->product_name));
                $p = explode(' ', $p);

                if(isset($p) && count($p) > 0) {
                    $ptags = [];
                    foreach($p as $tg) {
                        if(!empty($tg) && !is_numeric($tg)) {
                            $tagId = $t->addTag($tg);
                            $ptags[] = [
                                'product_id' => $id,
                                'tag_id' => $tagId
                            ];
                        }
                    }

                    if(isset($ptags) && count($ptags) > 0) {
                        $pt->store($ptags, null, true);
                    }
                }
            }

        }
        dumper($tags); */


        $data = [];
        return apiResponse(1, '', $data);
    }

    public function home(Request $request) 
    {
        try {    
            $mostPurchased = $discountedOffer = [];
            $customerId = customerId();
            $path = env('PRODUCT_PATH');

            $mpResult = (new Order)->mostPurchased();
            if(isset($mpResult) && count($mpResult) > 0) {
                foreach($mpResult as $mp) {
                    $mostPurchased[] = [
                        'id' => (int) $mp->id ?? 0,
                        'category_id' => (int) $mp->category_id ?? 0,
                        'category_number' => (string) $mp->category_number ?? '',
                        'category_name' => (string) $mp->category_name ?? '',
                        'product_number' => (string) $mp->product_number ?? '',
                        'product_name' => (string) $mp->product_name ?? '',
                        'weight' => (string) (amount($mp->weight) ?? '0') . ' '. ($mp->unit_name ?? ''),
                        'mrp_price' => (string) amount($mp->mrp_price) ?? '0',
                        'our_price' => (string) amount($mp->our_price) ?? '0',
                        // 'total_purchased' => (string) $mp->total_purchased ?? '0',
                        'discount_percent' => (string) ((int) $mp->discount_percent) . '%'  ?? '0%',
                        'product_image' => [
                            [
                                'image' => (string) apiImg($mp->product_image, $path, 'uploads/banner/center_image.png') ?? '',
                            ]
                        ],
                        'cart_info' => cartInfo($mp->id, $customerId)
                    ];
                }
            }

            $doResult = (new Order)->discountedOffer();
            if(isset($doResult) && count($doResult) > 0) {
                foreach($doResult as $do) {
                    $discountedOffer[] = [
                        'id' => (int) $do->id ?? 0,
                        'category_id' => (int) $do->category_id ?? 0,
                        'category_number' => (string) $do->category_number ?? '',
                        'category_name' => (string) $do->category_name ?? '',
                        'product_number' => (string) $do->product_number ?? '',
                        'product_name' => (string) $do->product_name ?? '',
                        'weight' => (string) (amount($do->weight) ?? '0') . ' '. ($do->unit_name ?? ''),
                        'mrp_price' => (string) amount($do->mrp_price) ?? '0',
                        'our_price' => (string) amount($do->our_price) ?? '0',
                        'discount_percent' => (string) ((int) $do->discount_percent) . '%'  ?? '0%',
                        'product_image' => [
                            [
                                'image' => (string) apiImg($do->product_image, $path, 'uploads/banner/center_image.png') ?? '',
                            ]
                        ],
                        'cart_info' => cartInfo($do->id, $customerId)
                    ];
                }
            }

            $data = [
                'most_purchased' => $mostPurchased,
                'discounted_offer' => $discountedOffer
            ];

            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function common(Request $request) 
    {
        try {   
            $customerId = customerId(); 
            $category = [];
            $categoryRs = (new Category)->apiCategory();
            if(isset($categoryRs) && count($categoryRs) > 0) {
                $categoryPath = env('CATEGORY_PATH');
                foreach($categoryRs as $row) {
                    $category[] = [
                        'id' => (int) $row->id ?? 0,
                        'category_number' => (string) $row->category_number ?? '',
                        'category_name' => (string) $row->category_name ?? '',
                        'category_image' => (string) apiImg($row->category_image, $categoryPath) ?? '',
                        'description' => (string) $row->description ?? '',
                    ];
                }
            }

            $bannerRs = (new Banner)->apiBanner();
            if(isset($bannerRs) && count($bannerRs) > 0) {
                $bannerPath = env('BANNER_PATH');
                foreach($bannerRs as $row) {
                    $banner[] = [
                        'image' => (string) apiImg($row->image, $bannerPath) ?? '',
                    ];
                }
            }
            
            $settingPath = env('SETTING_PATH');
            $centerImage = (string) apiImg(getSetting('center_image'), $settingPath) ?? '';
            $totalCartItem = (string) totalCartItem($customerId) ?? '0';
            $cashbackAmount = (string) amount(customerCashbackAmount()) ?? '0';

            $data = [
                'category' => $category,
                'banner' => $banner,
                'center_image' => $centerImage,
                'total_cart_item' => $totalCartItem ?? '0',
                'total_cashback_amount' => $cashbackAmount ?? '0',
                'maximum_cashback_redeem_amount' => (string) amount(getSetting('maximum_cashback_amount')),
                'minimum_order_amount_for_cashback' => (string) amount(getSetting('minimum_order_amount_for_cashback')),
                'about_us' => (string) env('HTML_START') . getSetting('about_us') . env('HTML_END'),
                'privacy_policy' => (string) env('HTML_START') . getSetting('policy') . env('HTML_END'),
                'term_and_condition' => (string) env('HTML_START') . getSetting('term_condition') . env('HTML_END'),
                'read_policy' => (string) env('HTML_START') . getSetting('read_policy') . env('HTML_END'),
                'customer_support' => (string) env('HTML_START') . getSetting('customer_support') . env('HTML_END'),
                'cancel_reason' => cancelReason(),
                'rozarpay_api_key' => (string) env('RAZORPAY_KEY') ?? ''
            ];
            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function product(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Validation)->product($inputs);
        $data = new ArrayObject();
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }
        
        $customerId = customerId();
        $path = env('PRODUCT_PATH');
        
        if(isset($inputs['type'])) {
            if($inputs['type'] == 'discounted') {
                $result = (new Product)->apiProduct($inputs, $request);
                if(isset($result) && count($result) > 0) {
                    $result->transform(function($row) use ($path, $customerId) {
                        $productImg = [[
                            'image' => (string) apiImg($row->product_image, $path, 'uploads/banner/center_image.png') ?? ''
                        ]];

                        $cartInfo = cartInfo($row->id, $customerId);
                        return getProduct($row, $productImg, $cartInfo);
                    });
                }
            }
            elseif($inputs['type'] == 'purchased') {
                $result = (new Order)->apiProductPurchased($inputs, $request);
                if(isset($result) && count($result) > 0) {
                    $result->transform(function($row, $key) use ($path, $customerId) {
                        $productImg = [[
                            'image' => (string) apiImg($row->product_image, $path, 'uploads/banner/center_image.png') ?? ''
                        ]];

                        $cartInfo = cartInfo($row->id, $customerId);
                        return getProduct($row, $productImg, $cartInfo);
                    });
                }
            }
        }
        else {
            $result = (new Product)->apiProduct($inputs, $request);
            if(isset($result) && count($result) > 0) {
                $result->transform(function($row) use ($path, $customerId) {
                    $productImg = [[
                        'image' => (string) apiImg($row->product_image, $path, 'uploads/banner/center_image.png') ?? ''
                    ]];

                    $cartInfo = cartInfo($row->id, $customerId);
                    return getProduct($row, $productImg, $cartInfo);
                });
            }
        }

        $data = $result;
        return apiResponse(1, '', $data);
    }

    public function productDetail(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Validation)->productDetail($inputs);
        $data = new ArrayObject();
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        $customerId = customerId();
        $productId = $inputs['product_id'];
        $result = (new Product)->apiProductDetail($productId);
        $path = env('PRODUCT_PATH');
        $bannerPath = env('BANNER_PATH');

        $response = [];
        if($result) {
            // product image code start
            $images = [];
            $productImgs = (new ProductImage)->apiProductImage($productId);
            if(isset($productImgs) and count($productImgs)) {
                foreach($productImgs as $image) {
                    $images[] = [
                        'image' => (string) apiImg($image->product_image, $path, 'uploads/banner/center_image.png') ?? ''
                    ];
                }
            }
            else {
                $images[] = [
                    'image' => (string) apiImg('center_image.png', $bannerPath) ?? ''
                ];
            }
            // product image code end

            $cartInfo = cartInfo($result->id, $customerId);
            $response['product'] = getProduct($result, $images, $cartInfo);

            // similar product code start
            $categoryId = $result->category_id;
            $similarProducts = (new Product)->apiSimilarProduct($categoryId, $result->id, $result->product_name);
            $simiProduct = [];
            if(isset($similarProducts) and count($similarProducts) > 0) {
                foreach($similarProducts as $k => $sp) {
                    $spImage = [[
                        'image' => (string) apiImg($sp->product_image, $path, 'uploads/banner/center_image.png') ?? ''
                    ]];

                    $cartInfo = cartInfo($sp->id, $customerId);
                    $simiProduct[] = getProduct($sp, $spImage, $cartInfo);
                }
            }
            $response['similar_product'] = $simiProduct ?? [];
            // similar product code end
        }

        $data = $response;
        return apiResponse(1, '', $data);
    }

    public function search(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Validation)->search($inputs);
        $data = [];
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        $result = (new Product)->apiSearch($inputs['keyword']);
        if(isset($result) && count($result) > 0) {
            $path = env('PRODUCT_PATH');
            foreach($result as $row) {
                $replaceText = '<strong>' . ucwords($inputs['keyword']) . '</strong>';
                $row->searched_text = str_ireplace($inputs['keyword'], $replaceText, $row->searched_text);
                $row->product_image = (string) apiImg($row->product_image, $path, 'uploads/banner/center_image.png') ?? '';
            }
            $data = $result;
        }
        return apiResponse(1, '', $data);
    }

    public function addToCartBkp(Request $request)
    {
        $inputs = $request->all();
        $data = [];
        $validation = (new Validation)->addToCartBkp($inputs);
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        $customerId = customerId();
        $exist = (new Cart)->alreadyExist($inputs['product_id'], $customerId);
        if($exist) {
            return apiResponse(0, __('message.product_already_exist'), $data);
        }

        try {
            \DB::beginTransaction();
            $cart = [
                'customer_id' => $customerId,
                'product_id' => $inputs['product_id'],
                'quantity' => $inputs['quantity'],
            ];

            (new Cart)->store($cart);
            \DB::commit();

            $data = cartDetail($customerId);

            return apiResponse(1, __('message.add_to_cart'), $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function addToCart(Request $request)
    {
        $inputs = $request->all();
        $data = [];
        $validation = (new Validation)->addToCart($inputs);
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        $type = $inputs['type'];
        $customerId = customerId();

        if($type == 'add') {
            $exist = (new Cart)->alreadyExist($inputs['product_id'], $customerId);
            if($exist) {
                return apiResponse(0, __('message.product_already_exist'), $data);
            }
        }

        try {
            \DB::beginTransaction();
            if($type == 'add') {
                $cart = [
                    'customer_id' => $customerId,
                    'product_id' => $inputs['product_id'],
                    'quantity' => $inputs['quantity'],
                ];
                $cartId = (new Cart)->store($cart);
                $message = __('message.add_to_cart');
                $data = cartDetailSingle($cartId, $customerId, $type);
            }
            else if($type == 'update') {
                $cartId = $inputs['cart_id'];
                $cart = [
                    'quantity' => $inputs['quantity'],
                ];
                (new Cart)->updateCart($cart, $cartId, $customerId);

                $data = cartDetailSingle($cartId, $customerId, $type);
                $message = __('message.cart_updated');
            }
            else if($type == 'delete') {
                $cartId = $inputs['cart_id'];
                $data = cartDetailSingle($cartId, $customerId, $type);
                (new Cart)->deleteFromCart($cartId, $customerId);
                $data['summary'] = cartSummary($customerId);

                $message = __('message.cart_deleted');
            }
            \DB::commit();

            $data['total_cart_item'] = (string) totalCartItem(customerId()) ?? '0';
            return apiResponse(1, $message, $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function updateCart(Request $request)
    {
        $inputs = $request->all();
        $data = [];
        $validation = (new Validation)->updateCart($inputs);
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        $cartId = $inputs['cart_id'];
        $customerId = customerId();
        try {
            \DB::beginTransaction();
            $cart = [
                'quantity' => $inputs['quantity'],
            ];

            (new Cart)->updateCart($cart, $cartId, $customerId);
            \DB::commit();

            $data = cartDetail($customerId);

            return apiResponse(1, __('message.cart_updated'), $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function deleteFromCart(Request $request)
    {
        $inputs = $request->all();
        $data = [];
        $validation = (new Validation)->deleteFromCart($inputs);
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        $cartId = $inputs['cart_id'];
        $customerId = customerId();
        try {
            \DB::beginTransaction();
            (new Cart)->deleteFromCart($cartId, $customerId);
            \DB::commit();

            $data = cartDetail($customerId);

            return apiResponse(1, __('message.cart_deleted'), $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function cartDetail()
    {
        $data = [];
        try {
            $data = cartDetail(customerId());
            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function addAddress(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Validation)->addAddress($inputs);
        $data = new ArrayObject();
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        try {
            \DB::beginTransaction();
            $inputs['customer_id'] = customerId();
            $addressId = (new UserAddress)->store($inputs);
            \DB::commit();

            $data = [
                'id' => (int) $addressId ?? 0,
                'address' => (string) $inputs['address'] ?? ''
            ];

            return apiResponse(1, __('message.add_address'), $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function editAddress(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Validation)->editAddress($inputs);
        $data = new ArrayObject();
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        try {
            \DB::beginTransaction();
            (new UserAddress)->store($inputs, $inputs['address_id']);
            \DB::commit();

            $data = [
                'id' => (int) $inputs['address_id'] ?? 0,
                'address' => (string) $inputs['address'] ?? ''
            ];

            return apiResponse(1, __('message.edit_address'), $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function deleteAddress(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Validation)->deleteAddress($inputs);
        $data = new ArrayObject();
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        try {
            \DB::beginTransaction();
            (new UserAddress)->deleteAddress($inputs['address_id'], customerId());
            \DB::commit();

            $data = [
                'id' => (int) 0,
                'address' => ''
            ];

            return apiResponse(1, __('message.delete_address'), $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }                                                                                                                                                                                                                   

    public function listAddress(Request $request)
    {
        $data = [];
        try {
            $result = (new UserAddress)->apiListAddress(customerId());
            if($result) {
                foreach($result as $row) {
                    $data[] = [
                        'id' => (int) $row->id ?? 0,
                        'address' => (string) $row->address ?? '',
                    ];
                }
            }
            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function placeOrder(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Validation)->placeOrder($inputs);
        $data = new ArrayObject();
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        // cart code start
        $customerId = customerId();
        $cart = (new Cart)->customerCart($customerId);
        if(count($cart) <= 0) {
            return apiResponse(0, __('message.cart_empty'), $data);
        }
        // cart code end

        // coupon/cashback code start
        $couponId = 0;
        $coupon = null;
        $redeemCashbackAmount = 0;

        $type = (isset($inputs['type']) && $inputs['type'] != '') ? $inputs['type'] : '';
        if($type == 'coupon') {
            $coupon = (new Coupon)->apiCashbackOrCoupon($inputs['code']);
            if(!$coupon) {
                return apiResponse(0, __('message.invalid_code'), $data);
            }

            if($coupon->apply_type == 'single') {
                $exist = (new UserCouponUsed)->alreadyExist($customerId, $coupon->id);
                if($exist) {
                    return apiResponse(0, __('message.already_code_used'), $data);
                }
            }

            $couponId = $coupon->id;
        }
        else if($type == 'cashback') {
            $maxCashbackAmount = amount(getSetting('maximum_cashback_amount')); // 150
            $redeemCashbackAmount = $inputs['code'];

            if($redeemCashbackAmount > $maxCashbackAmount) {
                $message = __('message.cashback_reedem_upto', ['amount' => $maxCashbackAmount]);
                return apiResponse(0, $message, $data);
            }

            $customerCbAmount = customerCashbackAmount();
            if($customerCbAmount < $redeemCashbackAmount) {
                $message = __('message.not_sufficent_cb_amount', [
                    'amount' => amount($customerCbAmount),
                    'cb_amount' => $redeemCashbackAmount,
                ]);
                return apiResponse(0, $message, $data);
            }
        }
        // coupon/cashback code end

        try {
            \DB::beginTransaction();
            $totalProduct = count($cart);
            $orderNumber = (new Order)->orderNumber();
            $order = [
                'customer_id' => $customerId,
                'user_address_id' => $inputs['address_id'],
                'coupon_id' => $couponId,
                'order_number' => $orderNumber,
                'order_status' => 'pending',
                'order_date' => date('Y-m-d H:i:s'),
                'payment_mode' => $inputs['payment_mode'],
                'total_product' => $totalProduct,
            ];

            if($inputs['payment_mode'] == 'online') {
                $order['razorpay_order_id'] = $inputs['razorpay_order_id'];
            }

            $id = (new Order)->store($order);
            if($id > 0) {
                $orderDetail = [];
                $totalAmount = 0;

                if (isset($cart) && count($cart) > 0) {
                    foreach ($cart as $i => $c) {
                        $productPrice = $c->our_price;
                        $quantity = $c->quantity;
                        $amount = ($productPrice * $quantity);
                        $totalAmount += $amount;

                        $orderDetail[] = [
                            'order_id' => $id,
                            'customer_id' => $customerId,
                            'product_id' => $c->product_id,
                            'category_id' => $c->category_id,
                            'sub_category_id' => $c->sub_category_id,
                            'unit_id' => $c->unit_id,
                            'product_price' => $productPrice,
                            'quantity' => $quantity,
                            'previous_quantity' => $quantity,
                            'amount' => $amount,
                            'order_detail_status' => 'pending'
                        ];
                    }

                    if (isset($orderDetail) && count($orderDetail) > 0) {
                        (new OrderDetail)->store($orderDetail, null, true);
                    }
                }

                // cashback code start
                $couponDiscountAmount = $userCashbackAmount = $cashbackOfferAmount = $cashbackRedeemAmount = 0;
                if($type == 'coupon' && $coupon) {
                    if($coupon->coupon_type == 'cashback') { 
                        $userCashbackAmount = $coupon->cb_amount;
                        $cashbackOfferAmount = $coupon->cb_amount;
                    }
                    else if($coupon->coupon_type == 'coupon') {
                        if($totalAmount < $coupon->c_order_amount_upto) {
                            $message = __('message.apply_coupon_min_order_amount', ['amount' => amount($coupon->c_order_amount_upto)]);
                            return apiResponse(0, $message, $data);
                        }

                        if($totalAmount >= $coupon->c_order_amount_upto) {
                            if($coupon->c_discount_type == 'fixed') {
                                $couponDiscountAmount = $coupon->c_order_amount_upto_fix_amount;
                            }
                            else if($coupon->c_discount_type == 'percent') {
                                if($totalAmount > $coupon->c_order_amount_more_than) {
                                    $couponDiscountAmount = $coupon->c_order_amount_more_than_fix_amount;
                                }
                                else {
                                    $couponDiscountAmount = (int) ($totalAmount * $coupon->c_order_amount_upto_percent) / 100;
                                }
                            }
                        }
                    }
                }
                else if($type == 'cashback') {
                    $minOrderAmountForCashback = amount(getSetting('minimum_order_amount_for_cashback'));
                    if($totalAmount < $minOrderAmountForCashback) {
                        $message = __('message.min_order_amount_for_cb', ['amount' => $minOrderAmountForCashback]);
                        return apiResponse(0, $message, $data);
                    }

                    $couponDiscountAmount = $redeemCashbackAmount;
                    $userCashbackAmount = -$redeemCashbackAmount;
                    $cashbackRedeemAmount = $redeemCashbackAmount;
                }
                // cashback code end

                $updateOrder = [
                    'grand_amount' => $totalAmount,
                    'total_amount' => ($totalAmount - $couponDiscountAmount),
                    'coupon_discount_amount' => $couponDiscountAmount,
                    'cashback_offer_amount' => $cashbackOfferAmount,
                    'cashback_redeem_amount' => $cashbackRedeemAmount,
                ];

                (new Order)->store($updateOrder, $id);

                (new Cart)->deleteCustomerCart($customerId);

                $user = ['cashback_amount' => customerCashbackAmount() + $userCashbackAmount];
                (new User)->store($user, $customerId);

                if($coupon) {
                    $couponUsed = [
                        'customer_id' => $customerId,
                        'order_id' => $id,
                        'coupon_id' => $coupon->id,
                    ];
                    (new UserCouponUsed)->store($couponUsed);
                }
            }
            \DB::commit();

            // sms code start
            $msg = __('message.sms_order_placed', [
                'order_number' => $orderNumber,
                'order_total_amount' => amount($totalAmount - $couponDiscountAmount)
            ]);
            sendSMS(customerMobileNumber(), $msg);
            // sms code end

            // fcm code start
            $title = __('message.sms_order_placed_heading');
            $fcmData = [
                'order_id' => (int) $id ?? 0,
                'order_status' => (string) 'pending' ?? '',
                'title' => (string) $title ?? '',
                'body' => (string) $msg ?? '',
            ];
            sendFcmNotificaton(customerFcmToken(), $title, $msg, $fcmData);
            // fcm code end

            return apiResponse(1, __('message.order_placed'), $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function listOrder()
    {
        $data = [];
        try {
            $customerId = customerId();
            $result = (new Order)->apiListOrder($customerId);

            if(isset($result) && count($result) > 0) {
                $result->transform(function($row) {
                    return getOrder($row);
                });
            }

            $data = $result;
            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function orderDetail(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Validation)->orderDetail($inputs);
        $data = [];
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        try {
            $customerId = customerId();
            $orderId = $inputs['order_id'];

            $cartInfo = [
                'has_in_cart' => (bool) false,
                'cart_id' => (int) 0,
                'cart_quantity' => (string) 0
            ];

            $result = (new OrderDetail)->apiOrderDetail($orderId, $customerId);
            if(isset($result) && count($result) > 0) {
                $path = env('PRODUCT_PATH');
                foreach($result as $row) {
                    $productImg = [
                        [
                            'image' => (string) apiImg($row->product_image, $path, 'uploads/banner/center_image.png') ?? ''
                        ]
                    ];
    
                    $cartInfo = cartInfo($row->id, $customerId);
                    $row->product = getProduct($row, $productImg, $cartInfo);

                    $o = getOrder($row);
                    $od[] = getProduct($row, $productImg, $cartInfo);
                }

                $data['order_detail'] = $o;
                $data['product_detail'] = $od;
            }

            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function applyCoupon(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Validation)->applyCoupon($inputs);
        $data = new ArrayObject();
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        // cart code start
        $customerId = customerId();
        $cart = (new Cart)->customerCart($customerId);
        if(count($cart) <= 0) {
            return apiResponse(0, __('message.cart_empty'), $data);
        }
        // cart code end

        // coupon/cashback code start
        $coupon = null;
        $redeemCashbackAmount = 0;

        $type = (isset($inputs['type']) && $inputs['type'] != '') ? $inputs['type'] : '';
        if($type == 'coupon') {
            $coupon = (new Coupon)->apiCashbackOrCoupon($inputs['code']);
            if(!$coupon) {
                return apiResponse(0, __('message.invalid_code'), $data);
            }

            if($coupon->apply_type == 'single') {
                $exist = (new UserCouponUsed)->alreadyExist($customerId, $coupon->id);
                if($exist) {
                    return apiResponse(0, __('message.already_code_used'), $data);
                }
            }
        }
        else if($type == 'cashback') {
            $maxCashbackAmount = amount(150);
            $redeemCashbackAmount = $inputs['code'];

            if($redeemCashbackAmount > $maxCashbackAmount) {
                $message = __('message.cashback_reedem_upto', ['amount' => $maxCashbackAmount]);
                return apiResponse(0, $message, $data);
            }

            $customerCbAmount = customerCashbackAmount();
            if($customerCbAmount < $redeemCashbackAmount) {
                $message = __('message.not_sufficent_cb_amount', [
                    'amount' => amount($customerCbAmount),
                    'cb_amount' => $redeemCashbackAmount,
                ]);
                return apiResponse(0, $message, $data);
            }
        }
        // coupon/cashback code end

        try 
        {
            $totalAmount = 0;
            if (isset($cart) && count($cart) > 0) {
                foreach ($cart as $c) {
                    $productPrice = $c->our_price;
                    $quantity = $c->quantity;
                    $amount = ($productPrice * $quantity);
                    $totalAmount += $amount;
                }
            }

            // cashback code start
            $couponDiscountAmount = $cashbackOfferAmount = 0;
            if($type == 'coupon' && $coupon) {
                if($coupon->coupon_type == 'cashback') { 
                    $cashbackOfferAmount = $coupon->cb_amount;
                }
                else if($coupon->coupon_type == 'coupon') {
                    if($totalAmount < $coupon->c_order_amount_upto) {
                        $message = __('message.apply_coupon_min_order_amount', ['amount' => amount($coupon->c_order_amount_upto)]);
                        return apiResponse(0, $message, $data);
                    }

                    if($totalAmount >= $coupon->c_order_amount_upto) {
                        if($coupon->c_discount_type == 'fixed') {
                            $couponDiscountAmount = $coupon->c_order_amount_upto_fix_amount;
                        }
                        else if($coupon->c_discount_type == 'percent') {
                            if($totalAmount > $coupon->c_order_amount_more_than) {
                                $couponDiscountAmount = $coupon->c_order_amount_more_than_fix_amount;
                            }
                            else {
                                $couponDiscountAmount = (int) ($totalAmount * $coupon->c_order_amount_upto_percent) / 100;
                            }
                        }
                    }
                }
            }
            else if($type == 'cashback') {
                $minOrderAmountForCashback = amount(500);
                if($totalAmount < $minOrderAmountForCashback) {
                    $message = __('message.min_order_amount_for_cb', ['amount' => $minOrderAmountForCashback]);
                    return apiResponse(0, $message, $data);
                }

                $couponDiscountAmount = $redeemCashbackAmount;
            }
            // cashback code end

            $data = [
                'type' => (string) $inputs['type'] ?? '',
                'total_amount' => (string) amount($totalAmount) ?? 0,
                'sub_total_amount' => (string) amount($totalAmount - $couponDiscountAmount) ?? 0,
                'discount_amount' => (string) amount($couponDiscountAmount) ?? 0,
                'cashback_offer_amount' => (string) amount($cashbackOfferAmount) ?? 0
            ];
            return apiResponse(1, __('message.apply_coupon'), $data);
        }
        catch(\Exception $e) {
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function cancelOrder(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Validation)->cancelOrder($inputs);
        $data = new ArrayObject();
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        $customerId = customerId();
        $orderId = $inputs['order_id'];
        $result = (new Order)->apiFindOrder($orderId, $customerId);
        if(!$result) {
            return apiResponse(0, __('message.order_not_found'), $data);
        }

        if($result->order_status == 'canceled') {
            return apiResponse(0, __('message.order_already_canceled'), $data);
        }

        if(!in_array($result->order_status, ['pending', 'accepted'])) {
            return apiResponse(0, __('message.order_on_way'), $data);
        }

        try {
            \DB::beginTransaction();

            // is selected code start
            (new OrderDetail)->updateIsSelected($orderId, '0');
            // is selected code end

            // remove order coupon code start
            (new UserCouponUsed)->removeOrderCoupon($orderId, $result->coupon_id);
            // remove order coupon code end

            // user cashback code start
            (new User)->updateUserCashback($result->customer_id, $result->coupon_discount_amount);
            (new User)->updateUserCashback($result->customer_id, -$result->cashback_offer_amount);
            // user cashback code end

            // order code start
            $updateOrder = [
                // 'grand_amount' => $result->grand_amount,
                // 'total_amount' => $result->grand_amount,
                'order_status' => 'canceled',
                'coupon_discount_amount' => '0',
                'cashback_redeem_amount' => '0',
                'cashback_offer_amount' => '0',
                'coupon_id' => '0',
                'customer_cancel_reason' => $inputs['cancel_reason'],
            ];
            (new Order)->store($updateOrder, $orderId);
            // order code end 
            \DB::commit();

            $result = (new Order)->apiFindOrder($orderId, $customerId);
            $data = getOrder($result);
            return apiResponse(1, __('message.order_canceled'), $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function profile(Request $request)
    {
        $inputs = $request->all();
        $customerId = customerId();
        $validation = (new Validation)->customerProfile($inputs, $customerId);
        $data = new ArrayObject();
        if($validation->fails()) {
            return apiResponse(0, $validation->getMessageBag(), $data, true);
        }

        try {
            \DB::beginTransaction();
            $user = [
                'name' => ucwords($inputs['name']),
            ];
            if(isset($inputs['email']) && $inputs['email'] != '') {
                $user['email'] = $inputs['email'];
            }
            (new User)->store($user, $customerId);
            \DB::commit();

            $data = [
                'customer' => getCustomer($customerId)
            ];
            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return apiResponse(0, __('message.server_error'), $data);
        }
    }

    public function notification(Request $request)
    {
        $data = new ArrayObject();
        try {
            $result = (new Notification)->apiNotification(customerMobileNumber());
            
            if(isset($result) and count($result) > 0) {
                $result->transform(function($row) {
                    return [
                        'id' => (int) $row->id ?? 0,
                        'created_date' => (string) dateFormat($row->created_at, 'd-m-Y') ?? '',
                        'created_time' => (string) dateFormat($row->created_at, 'h:i A') ?? '',
                        'created_datetime' => (string) dateFormat($row->created_at, 'd-m-Y h:i A') ?? '',
                        'title' => (string) $row->title ?? '',
                        'message' => (string) $row->message ?? '',
                    ];
                });
            }

            $data = $result;
            return apiResponse(1, '', $data);
        }
        catch(\Exception $e) {
            return apiResponse(0, __('message.server_error'), $data);
        }
    }
}