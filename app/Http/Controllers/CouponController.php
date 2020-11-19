<?php

namespace App\Http\Controllers;

use App\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortOrder = (new Coupon)->sortOrder;
        $sortEntity = (new Coupon)->sortEntity;

        $result = null;
        if($request->ajax()) {
            $sortEntity = $request->sortEntity;
            $sortOrder = $request->sortOrder;

            $result = (new Coupon)->pagination($request);
            return view('coupon.pagination', compact('result', 'sortOrder', 'sortEntity'));
        }

        return view('coupon.index', compact('result', 'sortOrder', 'sortEntity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('coupon.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Coupon)->validation($inputs);
        if($validation->fails()) { 
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        try {
            \DB::beginTransaction();
            $create = [
                'sr_number' => (new Coupon)->couponNumber(),
                'coupon_type' => $inputs['coupon_type'],
                'name' => $inputs['name'],
                'code' => strtoupper($inputs['code']),
                'description' => $inputs['description'],
                'status' => $inputs['status'],
                'expiry_date' => dateFormat($inputs['expiry_date']),
                'apply_type' => $inputs['apply_type'],
            ];

            if($inputs['coupon_type'] == 'cashback') {
                $create['cb_amount'] = $inputs['cb_amount'];
                $create['c_discount_type'] = 'fixed';
            }
            else if($inputs['coupon_type'] == 'coupon') {
                $create['c_discount_type'] = $inputs['c_discount_type'];
                $create['c_order_amount_upto'] = $inputs['c_order_amount_upto'];
                
                if($inputs['c_discount_type'] == 'fixed') {
                    $create['c_order_amount_upto_fix_amount'] = $inputs['c_order_amount_upto_fix_amount'];
                }
                else if($inputs['c_discount_type'] == 'percent') {
                    $create['c_order_amount_upto_percent'] = $inputs['c_order_amount_upto_percent'];
                    $create['c_order_amount_more_than'] = $inputs['c_order_amount_more_than'];
                    $create['c_order_amount_more_than_fix_amount'] = $inputs['c_order_amount_more_than_fix_amount'];
                }
            }

            (new Coupon)->store($create);
            \DB::commit();

            $extra = ['redirect' => route('coupon.index')];
            $message = __('coupon.created');
            return jsonResponse(true, 201, $message, $extra);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return jsonResponse(true, 207, __('message.server_error'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = (new Coupon)->fetch($id);
        if(!$result) {
            abort(404);
        }
        return view('coupon.edit', compact('result'));
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
        $result = (new Coupon)->fetch($id);
        if(!$result) {
            $message = __('message.invalid_detail');
            return jsonResponse(false, 207, $message);
        }

        $inputs = $request->all();
        $validation = (new Coupon)->validation($inputs, $id);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        try {
            \DB::beginTransaction();
            $update = [
                'code' => strtoupper($inputs['code']),
                'name' => $inputs['name'],
                'description' => $inputs['description'],
                'status' => $inputs['status'],
                'expiry_date' => dateFormat($inputs['expiry_date']),
                'apply_type' => $inputs['apply_type'],

                'cb_amount' => '0',
                'c_order_amount_upto' => '0',
                'c_order_amount_upto_fix_amount' => '0',
                'c_order_amount_upto_percent' => '0',
                'c_order_amount_more_than' => '0',
                'c_order_amount_more_than_fix_amount' => '0',
            ];

            if($inputs['coupon_type'] == 'cashback') {
                $update['cb_amount'] = $inputs['cb_amount'];
                $update['c_discount_type'] = 'fixed';
            }
            else if($inputs['coupon_type'] == 'coupon') {
                $update['c_discount_type'] = $inputs['c_discount_type'];
                $update['c_order_amount_upto'] = $inputs['c_order_amount_upto'];
                
                if($inputs['c_discount_type'] == 'fixed') {
                    $update['c_order_amount_upto_fix_amount'] = $inputs['c_order_amount_upto_fix_amount'];
                }
                else if($inputs['c_discount_type'] == 'percent') {
                    $update['c_order_amount_upto_percent'] = $inputs['c_order_amount_upto_percent'];
                    $update['c_order_amount_more_than'] = $inputs['c_order_amount_more_than'];
                    $update['c_order_amount_more_than_fix_amount'] = $inputs['c_order_amount_more_than_fix_amount'];
                }
            }

            (new Coupon)->store($update, $id);
            \DB::commit();

            $extra = ['redirect' => route('coupon.index')];
            $message = __('coupon.updated');
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
        (new Coupon)->find($id)->delete();
        $message = __('message.deleted');
        return jsonResponse(true, 201, $message);
    }

    public function toggleStatus($id, $status) 
    {
        $result = (new Coupon)->fetch($id);
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
            $inputs = $request->only('ids');

            (new Coupon)->toggleStatus($status, $inputs['ids']);
            \DB::commit();

            $message = __('message.status');
            return jsonResponse(true, 201, $message);
        }
        catch (\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function status($id) {
        $result = (new Coupon)->fetch($id);
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

    public function service(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Coupon)->serviceValidation($inputs);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        $options = '<option value="">-Select-</option>';
        $result = (new Coupon)->subCouponService(['parent_id' => $inputs['parent_id']], false);
        if(isset($result) && count($result) > 0) {
            foreach($result as $key => $option) {
                $options .= '<option value="'.$key.'">'.$option.'</option>';
            }
        }
        return response()->json(['success' => true, 'options' => $options]);
    }
}