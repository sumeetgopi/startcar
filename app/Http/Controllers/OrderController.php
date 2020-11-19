<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderDetail;
use App\User;
use App\UserCouponUsed;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortOrder = (new Order)->sortOrder;
        $sortEntity = (new Order)->sortEntity;
        
        $result = null;
        if($request->ajax()) {
            $inputs = $request->all();
            $sortEntity = $request->sortEntity;
            $sortOrder = $request->sortOrder;

            $result = (new Order)->pagination($request);
            return view('order.pagination', compact('result', 'sortOrder', 'sortEntity', 'inputs'));
        }
        return view('order.index', compact('result', 'sortOrder', 'sortEntity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('order.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$inputs = $request->all();
        $validation = (new Order)->validation($inputs);
        if($validation->fails()) { 
            return jsonResponse(false, 206, $validation->getMessageBag());
        }*/

        try {
            \DB::beginTransaction();

            // $paymentMode    = ['cash', 'online'];
            $totalProduct   = rand(3, 5);
            $customerId     = rand(2, 4);
            $order = [
                'customer_id'       => $customerId,
                'order_number'      => (new Order)->orderNumber(),
                'order_status'      => 'pending',
                'order_date'        => date('Y-m-d H:i:s'),
                'payment_mode'      => 'cash',
                'total_product'     => $totalProduct,
            ];

            $id = (new Order)->store($order);

            $orderDetail = [];
            $totalAmount = 0;
            for($i=1; $i<=$totalProduct; $i++) {
                $productPrice   = rand(111, 999);
                $quantity       = rand(1, 5);
                $amount         = ($productPrice * $quantity);
                $totalAmount   += $amount;

                $orderDetail[] = [
                    'order_id'              => $id,
                    'customer_id'           => $customerId,
                    'product_id'            => rand(1, 5),
                    'category_id'           => rand(1, 3),
                    'sub_category_id'       => rand(0, 3),
                    'unit_id'               => rand(1, 3),
                    'product_price'         => $productPrice,
                    'quantity'              => $quantity,
                    'previous_quantity'     => $quantity,
                    'amount'                => $amount,
                    'order_detail_status'   => 'pending'
                ];
            }

            if(isset($orderDetail) && count($orderDetail) > 0) {
                (new OrderDetail)->store($orderDetail, null, true);
            }

            $updateOrder = ['total_amount' => $totalAmount];
            (new Order)->store($updateOrder, $id);

            \DB::commit();

            $extra = ['redirect' => route('order.index')];
            $message = __('order.created');
            return jsonResponse(true, 201, $message, $extra);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return jsonResponse(true, 207, __('message.server_error') . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = (new Order)->show($id);
        if(!$result) {
            abort(404);
        }

        $orderDetails = (new OrderDetail)->getOrder($result->id);
        return view('order.show', compact('result', 'orderDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = (new Order)->show($id);
        if(!$result) {
            abort(404);
        }

        $orderDetails = (new OrderDetail)->getOrder($result->id);
        return view('order.edit', compact('result', 'orderDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = (new Order)->fetch($id);
        if(!$result) {
            $message = __('message.invalid_detail');
            return jsonResponse(false, 207, $message);
        }

        $inputs = $request->all();
        $validation = (new Order)->updateValidation($inputs);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        if($inputs['order_status'] != 'canceled') {
            if(!isset($inputs['product_id'])) {
                return jsonResponse(false, 207, 'Please select alteast one product');
            }
        }

        if(isset($inputs['product_id']) && count($inputs['product_id']) > 0) {
            foreach($inputs['product_id'] as $odId => $v) {
                if((int) $inputs['quantity'][$odId] <= 0) {
                    return jsonResponse(false, 207, 'Please enter minimum 1 quantity');
                }
            }
        }

        try {
            \DB::beginTransaction();
            
            // is selected code start
            (new OrderDetail)->updateIsSelected($id, '0');
            // is selected code end
            
            if($inputs['order_status'] == 'canceled')
            {
                // remove order coupon code start
                (new UserCouponUsed)->removeOrderCoupon($id, $result->coupon_id);
                // remove order coupon code end

                // user cashback code start
                (new User)->updateUserCashback($result->customer_id, $result->coupon_discount_amount);
                (new User)->updateUserCashback($result->customer_id, -$result->cashback_offer_amount);
                // user cashback code end

                // order code start
                $updateOrder = [
                    'grand_amount' => $result->grand_amount,
                    'total_amount' => $result->grand_amount,
                    'order_status' => $inputs['order_status'],
                    'coupon_discount_amount' => '0',
                    'cashback_redeem_amount' => '0',
                    'cashback_offer_amount' => '0',
                    'coupon_id' => '0',
                    'admin_cancel_reason' => webCancelReason($inputs['cancel_reason'])
                ];
                (new Order)->store($updateOrder, $id);
                // order code end 
            }
            else 
            {
                $grandAmount = 0;
                if(isset($inputs['product_id']) && count($inputs['product_id']) > 0) {
                    foreach($inputs['product_id'] as $odId => $v) {
                        $quantity = $inputs['quantity'][$odId];
                        $productPrice = $inputs['product_price'][$odId];
                        $amount = ($productPrice * $quantity);
                        $grandAmount += $amount;
                        
                        $updateOrderDetail = [
                            'product_price' => $productPrice,
                            'quantity' => $quantity,
                            'amount' => $amount,
                            'is_selected' => '1'
                        ];
                        (new OrderDetail)->store($updateOrderDetail, $odId);
                    }
                }

                $totalAmount = ($grandAmount - $result->coupon_discount_amount);

                // order code start
                $updateOrder = [
                    'grand_amount' => $grandAmount,
                    'total_amount' => $totalAmount,
                    'order_status' => $inputs['order_status']
                ];
                (new Order)->store($updateOrder, $id);
                // order code end
            }
            \DB::commit();

            if($result->order_status != $inputs['order_status']) {
                $token = $result->fcm_token;

                if($inputs['order_status'] == 'delivery') {
                    $msg = __('message.sms_order_delivered', [
                        'order_number' => $result->order_number,
                        'order_total_amount' => ($totalAmount - $result->coupon_discount_amount)
                    ]);
                    sendSMS($result->mobile_number, $msg);

                    // fcm code start
                    $title = __('message.sms_order_delivered_heading');
                    $fcmData = [
                        'order_id' => (int) $result->id ?? 0,
                        'order_status' => (string) 'delivery' ?? '',
                        'title' => (string) $title ?? '',
                        'body' => (string) $msg ?? '',
                    ];
                    sendFcmNotificaton($token, $title, $msg, $fcmData);
                    // fcm code end
                }
                else if($inputs['order_status'] == 'completed') {
                    $msg = __('message.sms_order_completed', [
                        'order_number' => $result->order_number,
                        'order_total_amount' => ($totalAmount - $result->coupon_discount_amount)
                    ]);
                    sendSMS($result->mobile_number, $msg);

                    // fcm code start
                    $title = __('message.sms_order_completed_heading');
                    $fcmData = [
                        'order_id' => (int) $result->id ?? 0,
                        'order_status' => (string) 'completed' ?? '',
                        'title' => (string) $title ?? '',
                        'body' => (string) $msg ?? '',
                    ];
                    sendFcmNotificaton($token, $title, $msg, $fcmData);
                    // fcm code end
                }
                else if($inputs['order_status'] == 'pending' && $result->order_status == 'delivery') {
                    $msg = __('message.sms_order_unsuccess_delivery', [
                        'order_number' => $result->order_number,
                        'order_total_amount' => ($totalAmount - $result->coupon_discount_amount)
                    ]);
                    sendSMS($result->mobile_number, $msg);

                    // fcm code start
                    $title = __('message.sms_order_unsuccess_delivery_heading');
                    $fcmData = [
                        'order_id' => (int) $result->id ?? 0,
                        'order_status' => (string) 'pending' ?? '',
                        'title' => (string) $title ?? '',
                        'body' => (string) $msg ?? '',
                    ];
                    sendFcmNotificaton($token, $title, $msg, $fcmData);
                    // fcm code end
                }
            }

            $extra = ['redirect' => route('order.edit', $id)];
            $message = __('order.updated', [
                'order_number' => $result->order_number, 
                'order_status' => $inputs['order_status']
            ]);
            return jsonResponse(true, 201, $message, $extra);
        }
        catch (\Exception $e) {
            \DB::rollBack();
            return jsonResponse(true, 207, __('message.server_error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        (new Order)->find($id)->delete();
        $message = __('message.deleted');
        return jsonResponse(true, 201, $message);
    }

    public function toggleStatus($id, $status) 
    {
        $result = (new Order)->fetch($id);
        if(!$result) {
            $message = __('message.invalid_detail');
            return jsonResponse(false, 207, $message);
        }

        try {
            \DB::beginTransaction();
            $result->update(['status' => $status]);
            \DB::commit();

            $message = __('message.status');
            return jsonResponse(true, 201, $message);
        }
        catch (\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function toggleAllStatus($status, Request $request) {
        try {
            \DB::beginTransaction();
            $inputs = $request->all();
            $inputs['order_status'] = $status;

            $o = new Order();
            // dumper($inputs);

            if(isset($inputs['ids']) && count($inputs['ids']) > 0) {
                foreach($inputs['ids'] as $id) {

                    $result = $o->fetch($id);
                    $totalAmount = $result->total_amount;
                    $token = $result->fcm_token;

                    $updateOrder = ['order_status' => $status];

                    if($inputs['order_status'] == 'delivery') {
                        $msg = __('message.sms_order_delivered', [
                            'order_number' => $result->order_number,
                            'order_total_amount' => ($totalAmount - $result->coupon_discount_amount)
                        ]);
                        sendSMS($result->mobile_number, $msg);
    
                        // fcm code start
                        $title = __('message.sms_order_delivered_heading');
                        $fcmData = [
                            'order_id' => (int) $result->id ?? 0,
                            'order_status' => (string) 'delivery' ?? '',
                            'title' => (string) $title ?? '',
                            'body' => (string) $msg ?? '',
                        ];
                        sendFcmNotificaton($token, $title, $msg, $fcmData);
                        // fcm code end
                    }
                    else if($inputs['order_status'] == 'completed') {
                        $msg = __('message.sms_order_completed', [
                            'order_number' => $result->order_number,
                            'order_total_amount' => ($totalAmount - $result->coupon_discount_amount)
                        ]);
                        sendSMS($result->mobile_number, $msg);
    
                        // fcm code start
                        $title = __('message.sms_order_completed_heading');
                        $fcmData = [
                            'order_id' => (int) $result->id ?? 0,
                            'order_status' => (string) 'completed' ?? '',
                            'title' => (string) $title ?? '',
                            'body' => (string) $msg ?? '',
                        ];
                        sendFcmNotificaton($token, $title, $msg, $fcmData);
                        // fcm code end
                    }
                    else if($inputs['order_status'] == 'pending' && $result->order_status == 'delivery') {
                        $msg = __('message.sms_order_unsuccess_delivery', [
                            'order_number' => $result->order_number,
                            'order_total_amount' => ($totalAmount - $result->coupon_discount_amount)
                        ]);
                        sendSMS($result->mobile_number, $msg);
    
                        // fcm code start
                        $title = __('message.sms_order_unsuccess_delivery_heading');
                        $fcmData = [
                            'order_id' => (int) $result->id ?? 0,
                            'order_status' => (string) 'pending' ?? '',
                            'title' => (string) $title ?? '',
                            'body' => (string) $msg ?? '',
                        ];
                        sendFcmNotificaton($token, $title, $msg, $fcmData);
                        // fcm code end
                    }
                    else if($inputs['order_status'] == 'canceled') 
                    {
                        // remove order coupon code start
                        (new UserCouponUsed)->removeOrderCoupon($id, $result->coupon_id);
                        // remove order coupon code end

                        // user cashback code start
                        (new User)->updateUserCashback($result->customer_id, $result->coupon_discount_amount);
                        (new User)->updateUserCashback($result->customer_id, -$result->cashback_offer_amount);
                        // user cashback code end

                        $updateOrder['grand_amount'] = $result->grand_amount;
                        $updateOrder['total_amount'] = $result->grand_amount;
                        $updateOrder['order_status'] = $inputs['order_status'];
                        $updateOrder['coupon_discount_amount'] = '0';
                        $updateOrder['cashback_redeem_amount'] = '0';
                        $updateOrder['cashback_offer_amount'] = '0';
                        $updateOrder['coupon_id'] = '0';
                        $updateOrder['admin_cancel_reason'] = webCancelReason($inputs['cancel_reason']);
                    }

                    $o->store($updateOrder, $id);
                }
            }
            // (new Order)->toggleStatus($status, $inputs['ids']);
            \DB::commit();

            $message = __('message.status');
            return jsonResponse(true, 201, $message);
        }
        catch (\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error') . $e->getmes);
        }
    }

    public function status($id) {
        $result = (new Order)->fetch($id);
        if(!$result) {
            $message = __('message.invalid_id');
            return jsonResponse(false, 207, $message);
        }

        try {
            \DB::beginTransaction();
            $result->update(['status' => !$result->status]);
            \DB::commit();

            $message = __('message.status');
            return jsonResponse(true, 201, $message);
        }
        catch (\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function orderPdf(Request $request)
    {       
        $result = (new Order)->pdf($request);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'landscape')
            ->setWarnings(false)
            ->loadView('order.pdf', compact('result'));
            
        return $pdf->stream();
    }
}