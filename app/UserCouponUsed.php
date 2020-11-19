<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCouponUsed extends Model
{
    use Utility;

    protected $table = 'user_coupon_used';

    public $timestamps = false;

    protected $fillable = [
       'customer_id',
       'order_id',
       'coupon_id',

       'created_at',
       'updated_at',
       'deleted_at',
    ];

    public function alreadyExist($customerId, $couponId) {
        return $this
            ->where('user_coupon_used.customer_id', $customerId)
            ->where('user_coupon_used.coupon_id', $couponId)
            ->first();
    }

    public function removeOrderCoupon($orderId, $couponId)
    {
        $this
            ->where('user_coupon_used.order_id', $orderId)
            ->where('user_coupon_used.coupon_id', $couponId)
            ->delete();
    }
}
