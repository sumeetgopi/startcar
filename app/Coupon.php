<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'coupon';

    public $sortOrder = 'desc';
    public $sortEntity = 'coupon.id';

    protected $fillable = [
        'coupon_type',
        'sr_number',
        'name',
        'code',
        'description',
        'status',
        'expiry_date',
        'apply_type',

        'cb_amount',

        'c_discount_type',

        'c_order_amount_upto',
        'c_order_amount_upto_fix_amount',
        'c_order_amount_upto_percent',

        'c_order_amount_more_than',
        'c_order_amount_more_than_fix_amount',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function scopeActive($query) {
        return $query->where('coupon.status', 1);
    }

    public function coupon() {
        return $this->active()->get();
    }

    public function validation($inputs = [], $id = null)
    {
        $rules = [
            'coupon_type' => 'required|in:cashback,coupon',
            'code' => 'required|unique:coupon',
            'status' => 'required|numeric',
            'name' => 'required',
            'expiry_date' => 'required|date',
            'apply_type' => 'required|in:single,multiple',
        ];

        if($inputs['coupon_type'] == 'cashback') {
            $rules['cb_amount'] = 'required|numeric|min:1';
        }
        else if($inputs['coupon_type'] == 'coupon') {
            $rules['c_discount_type'] = 'required|in:fixed,percent';
            $rules['c_order_amount_upto'] = 'required|numeric|min:1';

            if($inputs['c_discount_type'] == 'fixed') {
                $rules['c_order_amount_upto_fix_amount'] = 'required|numeric|min:1';
            }
            else if($inputs['c_discount_type'] == 'percent') {
                $rules['c_order_amount_upto_percent'] = 'required|numeric|min:1';
                $rules['c_order_amount_more_than'] = 'required|numeric|min:1';
                $rules['c_order_amount_more_than_fix_amount'] = 'required|numeric|min:1';
            }
        }

        if($id) {
            $rules['code'] = 'required|unique:coupon,code,' . $id;
        }
        return validator($inputs, $rules);
    }

    public function fetch($id) 
    {
        return $this
            ->where('coupon.id', $id)
            ->first();
    }

    public function pagination(Request $request)
    {
        $filter = 1;
        $perPage = defaultPerPage();
        $sortOrder = $this->sortOrder;
        $sortEntity = $this->sortEntity;

        $fields = "coupon.*";

        if($request->has('perPage') && $request->get('perPage') != '') {
            $perPage = $request->get('perPage');
        }

        if($request->has('keyword') && $request->get('keyword') != '') {
            $filter .= " and (coupon.code like '%" . addslashes($request->get('keyword')) . "%' 
                or coupon.name like '%" . addslashes($request->get('keyword')) . "%')";
        }

        

        if($request->has('status') && $request->get('status') != '') {
            $filter .= " and coupon.status = '" . addslashes($request->get('status')) . "'";
        }

        if($request->has('sortEntity') && $request->get('sortEntity') != '') {
            $sortEntity = $request->get('sortEntity');
        }

        if($request->has('sortOrder') && $request->get('sortOrder') != '') {
            $sortOrder = $request->get('sortOrder');
        }

        return $this
            ->select(\DB::raw($fields))
            ->whereRaw($filter)
            ->orderBy($sortEntity, $sortOrder)
            ->paginate($perPage);
    }

    public function toggleStatus($status, $ids = [])
    {
        if(isset($ids) && count($ids) > 0) {
            return $this->whereIn('coupon.id', $ids)->update(['status' => $status]);
        }
    }

    public function service($heading = true) {
        $result = $this
            ->active()
            ->get(['id', 'coupon_name']);

        $service = [];
        if($heading) {
            $service = ['' => '-Select-'];
        }

        if(isset($result) && count($result) > 0) {
            foreach($result as $row) {
                $service[$row->id] = $row->coupon_name;
            }
        }
        return $service;
    }

    public function parentService($heading = true) {
        $result = $this
            ->active()
            ->where('parent_id', '0')
            ->get(['id', 'coupon_name']);

        $service = [];
        if($heading) {
            $service = ['' => '-Select-'];
        }

        if(isset($result) && count($result) > 0) {
            foreach($result as $row) {
                $service[$row->id] = $row->coupon_name;
            }
        }
        return $service;
    }
    

    public function couponNumber() {
        $count = 0;
        $result = $this->orderBy('id', 'desc')->first(['id']);
        if($result) { $count = $result->id; }
        return srNo($count);
    }

    public function serviceValidation($inputs = [])
    {
        $rules = [
            'parent_id' => 'required|numeric|min:1',
        ];
        return validator($inputs, $rules);
    }

    public function apiCashbackOrCoupon($code) 
    {
        return $this
            ->where('coupon.code', $code)
            ->where('expiry_date', '>=', date('Y-m-d'))
            ->active()
            ->first();
    }
}
