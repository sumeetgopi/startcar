<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'orders';

    public $sortOrder = 'desc';
    public $sortEntity = 'orders.id';

    protected $fillable = [
        'razorpay_order_id',
        'customer_id',
        'user_address_id',
        'coupon_id',
        
        'order_number',
        'order_status',
        'customer_cancel_reason',
        'admin_cancel_reason',
       
        'order_date',
        'accepted_date',
        'delivery_date',
        'completed_date',
        'canceled_date',

        'grand_amount',
        'total_amount',

        'coupon_discount_amount',
        'cashback_offer_amount',
        'cashback_redeem_amount',

        'total_product',
        'payment_mode',
        'transaction_id',

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
        return $query->where('orders.status', 1);
    }

    public function orders() {
        return $this->active()->get();
    }

    public function validation($inputs = [], $id = null)
    {
        $rules = [
            // 'orders_number' => 'required|unique:orders',
            // 'orders_name' => 'required|unique:orders',
            'category_id' => 'required|numeric|min:1',
            // 'sub_category_id' => 'required|numeric|min:1',
            'unit_id' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            'weight' => 'required|numeric|min:1',
            'status' => 'required|numeric',
        ];

        if($id) {
            $rules['orders_name'] = 'required|unique:orders,orders_name,'.$id;
        }
        return validator($inputs, $rules);
    }

    public function updateValidation($inputs = [])
    {
        $rules = [
           'order_status' => 'required'
        ];

        if(isset($inputs['order_status']) && $inputs['order_status'] == 'canceled') {
            $rules['cancel_reason'] = 'required';
        }
        return validator($inputs, $rules);
    }

    public function fetch($id) 
    {
        $fields = [
            'orders.*',
            'users.mobile_number',
            'users.fcm_token',
        ];
        
        return $this
            ->leftJoin('users', 'users.id', '=', 'orders.customer_id')
            ->where('orders.id', $id)
            ->first($fields);
    }

    public function show($id)
    {
        $fields = [
            'orders.*',
            'users.name',
            'users.email',
            'users.mobile_number',
            'user_address.address',
        ];
        return $this
            ->leftJoin('users', 'users.id', '=', 'orders.customer_id')
            ->leftJoin('user_address', 'user_address.id', '=', 'orders.user_address_id')
            ->where('orders.id', $id)
            ->first($fields);
    }

    public function pagination(Request $request)
    {
        $filter = 1;
        $perPage = defaultPerPage();
        $sortOrder = $this->sortOrder;
        $sortEntity = $this->sortEntity;

        $fields = "
            orders.*,
            users.name,
            users.email,
            users.mobile_number
        ";

        if($request->has('perPage') && $request->get('perPage') != '') {
            $perPage = $request->get('perPage');
        }

        if($request->has('keyword') && $request->get('keyword') != '') {
            $filter .= " and (orders.order_number like '%" . addslashes($request->get('keyword')) . "%' 
                or users.name like '%" . addslashes($request->get('keyword')) . "%' 
                or users.email like '%" . addslashes($request->get('keyword')) . "%' 
                or users.mobile_number like '%" . addslashes($request->get('keyword')) . "%')";
        }

        if($request->has('order_status') && $request->get('order_status') != '') {
            $filter .= " and orders.order_status='" . addslashes($request->get('order_status')) . "'";
        }

        if($request->get('from_date') != '' && $request->get('to_date') == '') { 
            $filter .= " and orders.order_date >= '" . addslashes(dateFormat($request->get('from_date'), 'Y-m-d 00:00:00')) . "'";
        }

        if($request->get('from_date') != '' && $request->get('to_date') != '') { 
            $filter .= " and (orders.order_date >= '" . addslashes(dateFormat($request->get('from_date'), 'Y-m-d 00:00:00')) . "'  and orders.order_date <= '" . addslashes(dateFormat($request->get('to_date'), 'Y-m-d 23:59:59')) . "')";
        }

        if($request->has('status') && $request->get('status') != '') {
            $filter .= " and orders.status = '" . addslashes($request->get('status')) . "'";
        }

        if($request->has('sortEntity') && $request->get('sortEntity') != '') {
            $sortEntity = $request->get('sortEntity');
        }

        if($request->has('sortOrder') && $request->get('sortOrder') != '') {
            $sortOrder = $request->get('sortOrder');
        }

        return $this
            ->select(\DB::raw($fields))
            ->leftJoin('users', 'users.id', '=', 'orders.customer_id')
            ->whereRaw($filter)
            ->orderBy($sortEntity, $sortOrder)
            ->paginate($perPage);
    }

    public function pdf(Request $request)
    {
        $filter = 1;
    
        $fields = "
            orders.order_number,
            orders.order_date,
            orders.payment_mode,
            orders.order_status,
            orders.grand_amount,
            orders.coupon_discount_amount,
            orders.total_amount,
            orders.total_product,
            users.name,
            users.mobile_number
        ";

        if($request->has('keyword') && $request->get('keyword') != '') {
            $filter .= " and (orders.order_number like '%" . addslashes($request->get('keyword')) . "%' 
                or users.name like '%" . addslashes($request->get('keyword')) . "%' 
                or users.email like '%" . addslashes($request->get('keyword')) . "%' 
                or users.mobile_number like '%" . addslashes($request->get('keyword')) . "%')";
        }

        if($request->has('payment_mode') && $request->get('payment_mode') != '') {
            $filter .= " and orders.payment_mode='" . addslashes($request->get('payment_mode')) . "'";
        }

        if($request->has('customer_id') && $request->get('customer_id') != '') {
            $filter .= " and orders.customer_id='" . addslashes($request->get('customer_id')) . "'";
        }

        if($request->get('from_date') != '' && $request->get('to_date') == '') { 
            $filter .= " and orders.order_date >= '" . addslashes(dateFormat($request->get('from_date'), 'Y-m-d 00:00:00')) . "'";
        }

        if($request->get('from_date') != '' && $request->get('to_date') != '') { 
            $filter .= " and (orders.order_date >= '" . addslashes(dateFormat($request->get('from_date'), 'Y-m-d 00:00:00')) . "'  and orders.order_date <= '" . addslashes(dateFormat($request->get('to_date'), 'Y-m-d 23:59:59')) . "')";
        }

        return $this
            ->select(\DB::raw($fields))
            ->leftJoin('users', 'users.id', '=', 'orders.customer_id')
            ->whereRaw($filter)
            ->orderBy('orders.id', 'desc')
            ->get();
    }

    public function toggleStatus($status, $ids = [])
    {
        if(isset($ids) && count($ids) > 0) {
            return $this->whereIn('orders.id', $ids)->update(['orders.order_status' => $status]);
        }
    }

    public function service($heading = true) {
        $result = $this
            ->active()
            ->get(['id', 'orders_number']);

        $service = [];
        if($heading) {
            $service = ['' => '-Select-'];
        }

        if(isset($result) && count($result) > 0) {
            foreach($result as $row) {
                $service[$row->id] = $row->orders_number;
            }
        }
        return $service;
    }

    public function orderNumber() {
        $count = 0;
        $result = $this->orderBy('id', 'desc')->first(['id']);
        if($result) { $count = $result->id; }
        return srNo($count);
    }

    public function mostPurchased()
    {
        $query = "
            select t.*,
            c.category_number,
            c.category_name,
            (select product_image from product_image where product_id = t.id limit 1) product_image,
            unit.unit_name,
            round(((t.mrp_price - t.our_price ) / t.mrp_price) * 100) as discount_percent,
            total_purchased
            from product t

            left join category c on c.id = t.category_id
            left join unit on unit.id = t.unit_id

            join (
                select product_id, sum(product_id) as total_purchased from order_detail
                group by product_id
            ) od on od.product_id = t.id

            where t.status = '1' and t.deleted_at is null

            order by total_purchased desc
            limit 12
        ";
        return \DB::select($query);
    }

    public function discountedOffer() {
        $query = "
            select 
                t.*,
                c.category_number,
                c.category_name,
                (select product_image from product_image where product_id = t.id limit 1) product_image,
                unit.unit_name,
                round(((t.mrp_price - t.our_price ) / t.mrp_price) * 100) as discount_percent
            from product t
            left join category c on c.id = t.category_id
            left join unit on unit.id = t.unit_id
            where t.status = '1' and t.deleted_at is null
            having discount_percent > 0
            order by discount_percent desc
            limit 12
        ";
        return \DB::select($query);
    }

    public function apiListOrder($customerId)
    {
        $fields = "
            orders.*, 
            user_address.address,
            (select 
                sum(if(order_detail.is_selected = '1', order_detail.product_price * order_detail.quantity, 0)) 
                from order_detail 
                where order_detail.order_id = orders.id 
            ) as total_our_price,
            (select 
                sum(if(order_detail.is_selected = '1', product.mrp_price * order_detail.quantity, 0)) 
                from order_detail 
                left join product on product.id = order_detail.product_id
                where order_detail.order_id = orders.id 
            ) as total_mrp_price
        ";
        return $this
            ->select(\DB::raw($fields))
            ->leftJoin('user_address', 'user_address.id', '=', 'orders.user_address_id')
            ->where('orders.customer_id', $customerId)
            ->orderBy('orders.id', 'desc')
            ->paginate(20);
    }

    public function apiProductPurchased($search, $request)
    {
        $filter = 1;

        if(isset($search) && count($search) > 0) {
            $f1 = (isset($search['category_id']) && $search['category_id'] != '') ?
                " and t.category_id = '" . $search['category_id'] . "'" : '';

            $f2 = (isset($search['keyword']) && $search['keyword'] != '') ?
                " and t.product_name like '%" . $search['keyword'] . "%'" : '';

            $filter .= $f1 . $f2;
        }

        $query = "
            select t.*,
            c.category_number,
            c.category_name,
            (select product_image from product_image where product_id = t.id limit 1) product_image,
            unit.unit_name,
            round(((t.mrp_price - t.our_price ) / t.mrp_price) * 100) as discount_percent,
            total_purchased
            from product t

            left join category c on c.id = t.category_id
            left join unit on unit.id = t.unit_id

            join (
                select product_id, sum(product_id) as total_purchased from order_detail
                group by product_id
            ) od on od.product_id = t.id

            where $filter and t.status = '1' and t.deleted_at is null
        
            order by total_purchased desc
        ";

        $array = \DB::select($query);
        return arrayPaginator($array, $request);
    }

    public function apiFindOrder($orderId, $customerId)
    {
        $fields = [
            'orders.*',
            'user_address.address',
            \DB::raw("(select 
                sum(if(order_detail.is_selected = '1', order_detail.product_price * order_detail.quantity, 0)) 
                from order_detail 
                where order_detail.order_id = orders.id 
            ) as total_our_price"),
            \DB::raw("(select 
                sum(if(order_detail.is_selected = '1', product.mrp_price * order_detail.quantity, 0)) 
                from order_detail 
                left join product on product.id = order_detail.product_id
                where order_detail.order_id = orders.id 
            ) as total_mrp_price")
        ];

        return $this
            ->leftJoin('user_address', 'user_address.id', '=', 'orders.user_address_id')
            ->where('orders.id', $orderId)
            ->where('orders.customer_id', $customerId)
            ->first($fields);
    }

    public function totalOrders($search = []) {
        $filter = 1;

        if(isset($search['from_date']) && $search['from_date'] == '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and orders.order_date >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'";
        }

        if(isset($search['from_date']) && $search['from_date'] != '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and (orders.order_date >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'  and orders.order_date <= '" . addslashes(dateFormat($search['to_date'], 'Y-m-d 23:59:59')) . "')";
        }

        return $this
            ->whereRaw($filter)
            ->count();
    }

    public function deliveredOrders() {
        return $this
            ->where('order_status', 'delivery')
            ->count();
    }

    public function pendingOrders() {
        return $this
            ->whereIn('order_status', ['pending', 'accepted'])
            ->count();
    }
    
    public function canceledOrders() {
        return $this
            ->where('order_status', 'canceled')
            ->count();
    }
    
    public function totalOrdersAmount($search = []) {
        $filter = 1;

        if(isset($search['from_date']) && $search['from_date'] == '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and orders.order_date >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'";
        }

        if(isset($search['from_date']) && $search['from_date'] != '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and (orders.order_date >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'  and orders.order_date <= '" . addslashes(dateFormat($search['to_date'], 'Y-m-d 23:59:59')) . "')";
        }

        return $this
            ->whereRaw($filter)
            ->sum('total_amount');
    }

    public function onlinePaymentAmount($search = []) {
        $filter = 1;

        if(isset($search['from_date']) && $search['from_date'] == '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and orders.order_date >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'";
        }

        if(isset($search['from_date']) && $search['from_date'] != '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and (orders.order_date >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'  and orders.order_date <= '" . addslashes(dateFormat($search['to_date'], 'Y-m-d 23:59:59')) . "')";
        }

        return $this
            ->whereRaw($filter)
            ->where('payment_mode', 'online')
            ->sum('total_amount');
    }

    public function cashPaymentAmount($search = []) {
        $filter = 1;

        if(isset($search['from_date']) && $search['from_date'] == '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and orders.order_date >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'";
        }

        if(isset($search['from_date']) && $search['from_date'] != '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and (orders.order_date >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'  and orders.order_date <= '" . addslashes(dateFormat($search['to_date'], 'Y-m-d 23:59:59')) . "')";
        }

        return $this
            ->whereRaw($filter)
            ->where('payment_mode', 'cash')
            ->sum('total_amount');
    }

    public function repeatOrderReport($search = [])
    {
        $filter = 1;
        $having = 'total_orders > 1';
        if(isset($search) && count($search) > 0)
        {
            if((isset($search['from_date']) && $search['from_date'] != '') && (isset($search['to_date']) && $search['to_date'] != '')) {
                $filter .= " and (orders.order_date >= '" . dateFormat($search['from_date'], 'Y-m-d 00:00:00')
                    . "' and orders.order_date <= '" . dateFormat($search['to_date'], 'Y-m-d 23:59:59') . "')";
            }
            else {
                $filter .= (isset($search['from_date']) && $search['from_date'] != '') ?
                    " and orders.order_date >= '" . dateFormat($search['from_date'], 'Y-m-d 00:00:00') . "'" : '';

                $filter .= (isset($search['to_date']) && $search['to_date'] != '') ?
                    " and orders.order_date <= '" . dateFormat($search['to_date'], 'Y-m-d 23:59:59') . "'" : '';
            }

            if(isset($search['order_count']) && $search['order_count'] != '') {
                

                if($search['order_count'] == '1_order') {
                    $having = " total_orders = 1";
                }
                else if($search['order_count'] == '1_5_order') {
                    $having = " total_orders > 1 and total_orders <= 5";
                }
                else if($search['order_count'] == '5_10_order') {
                    $having = " total_orders > 5 and total_orders <= 10";
                }
                else if($search['order_count'] == 'more_than_10_order') {
                    $having = " total_orders > 10";
                }
            }

            $filter .= (isset($search['keyword']) && $search['keyword'] != '') ? 
                " and (users.mobile_number like '%" . addslashes($search['keyword']) . "%' 
                or users.name like '%" . addslashes($search['keyword']) . "%')" : '';
        }

        $fields = [
            'users.id as customer_id',
            'users.name',
            'users.mobile_number',
            \DB::raw("count(*) as total_orders"),
        ];

        return $this
            ->join('users', 'users.id', '=', 'orders.customer_id')
            ->whereRaw($filter)
            ->groupBy('orders.customer_id')
            ->havingRaw($having)
            ->orderByRaw('total_orders desc')
            ->get($fields);
    }
}
