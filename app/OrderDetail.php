<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'order_detail';

    public $sortOrder = 'asc';
    public $sortEntity = 'order_detail.id';

    protected $fillable = [
        'is_selected',
        'order_id',
        'customer_id',
        'product_id',
        'category_id',
        'sub_category_id',
        'unit_id',
        'product_price',
        'quantity',
        'previous_quantity',
        'amount',
        'order_detail_status',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getOrder($orderId)
    {
        $fields = [
            'order_detail.*',
            'product.product_number',
            'product.weight',
            'product.product_name',
            'unit.unit_name'
        ];
        return $this
            ->leftJoin('product', 'product.id', '=', 'order_detail.product_id')
            ->leftJoin('unit', 'unit.id', '=', 'order_detail.unit_id')
            ->where('order_detail.order_id', $orderId)
            ->get($fields);
    }

    public function procurementReport($search = [])
    {
        $filter = 1;
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

            $filter .= (isset($search['keyword']) && $search['keyword'] != '') ? 
                " and (product.product_number like '%" . addslashes($search['keyword']) . "%' 
                or product.product_name like '%" . addslashes($search['keyword']) . "%')" : '';
        }   

        $fields = [
            'product.product_number',
            'product.product_name',
            'product.mrp_price',
            'product.our_price',
            \DB::raw("sum(order_detail.quantity) as total_quantity"),
            \DB::raw("group_concat(orders.order_number) as order_number"),
        ];

        return $this
            ->join('orders', 'orders.id', '=', 'order_detail.order_id')
            ->join('product', 'product.id', '=', 'order_detail.product_id')
            ->whereRaw($filter)
            ->where('orders.order_status', 'pending')
            ->groupBy('order_detail.product_id')
            ->orderBy('order_detail.product_id', 'asc')
            ->get($fields);
    }

    public function apiOrderDetail($orderId, $customerId)
    {
        $query = "
            select
                orders.id as order_id,
                orders.order_number,
                orders.order_status,
                orders.order_date,
                orders.delivery_date,
                orders.total_product,
                orders.payment_mode,
                orders.grand_amount,
                orders.total_amount,
                orders.coupon_discount_amount,
                orders.cashback_redeem_amount,
                orders.cashback_offer_amount,
                orders.customer_cancel_reason,
                orders.admin_cancel_reason,

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
                ) as total_mrp_price,

                product.*,
                (select product_image from product_image where product_id = product.id limit 1) product_image,
                category.category_number,
                category.category_name,
                unit.unit_name,
                concat(round(((product.mrp_price - product.our_price ) / product.mrp_price) * 100), '%') as discount_percent,

                order_detail.is_selected as order_is_selected,
                order_detail.previous_quantity as order_previous_quantity,
                order_detail.quantity as order_quantity,
                order_detail.product_price as order_price,
                order_detail.amount as order_amount
            from order_detail
            left join orders on orders.id = order_detail.order_id
            left join user_address on user_address.id = orders.user_address_id
            left join product on product.id = order_detail.product_id
            left join category on category.id = product.category_id
            left join unit on unit.id = product.unit_id
            
            where 
                orders.id = '$orderId' 
                and orders.customer_id = '$customerId'
        ";

        return \DB::select($query);
    }

    public function updateIsSelected($orderId, $isSelected)
    {
        $this->where('order_detail.order_id', $orderId)
            ->update([
                'order_detail.is_selected' => $isSelected,
                'order_detail.quantity' => '0',
                'order_detail.amount' => '0',
            ]);
    }

    public function totalProducts($search = []) {
        $filter = 1;

        if(isset($search['from_date']) && $search['from_date'] == '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and orders.order_date >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'";
        }

        if(isset($search['from_date']) && $search['from_date'] != '' && isset($search['to_date']) && $search['to_date'] != '') {
            $filter .= " and (orders.order_date >= '" . addslashes(dateFormat($search['from_date'], 'Y-m-d 00:00:00')) . "'  and orders.order_date <= '" . addslashes(dateFormat($search['to_date'], 'Y-m-d 23:59:59')) . "')";
        }

        return $this
            ->leftJoin('orders', 'orders.id', '=', 'order_detail.order_id')
            ->whereRaw($filter)
            ->count();
    }
}
