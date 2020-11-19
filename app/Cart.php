<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use Utility;

    protected $table = 'cart';

    public $timestamps = false;

    protected $fillable = [
       'customer_id',
       'product_id',
       'quantity',
    ];

    public function alreadyExist($productId, $customerId) {
        return $this
            ->where('cart.product_id', $productId)
            ->where('cart.customer_id', $customerId)
            ->first();
    }

    public function cartDetail($customerId)
    {
        $query = "
            select
                cart.id as cart_id,
                cart.quantity,
                product.*,
                category.category_number,
                category.category_name,
                unit.unit_name,
                (select product_image from product_image where product_id = cart.product_id limit 1) product_image,
                concat(round(((product.mrp_price - product.our_price ) / product.mrp_price) * 100), '%') as discount_percent
            from cart
            left join product on product.id = cart.product_id
            left join category on category.id = product.category_id
            left join unit on unit.id = product.unit_id

            where cart.customer_id = '$customerId'
        ";

        return \DB::select($query);
    }

    public function cartDetailSingle($cartId, $customerId)
    {
        $query = "
            select
                cart.id as cart_id,
                cart.quantity,
                product.*,
                category.category_number,
                category.category_name,
                unit.unit_name,
                (select product_image from product_image where product_id = cart.product_id limit 1) product_image,
                concat(round(((product.mrp_price - product.our_price ) / product.mrp_price) * 100), '%') as discount_percent
            from cart
            left join product on product.id = cart.product_id
            left join category on category.id = product.category_id
            left join unit on unit.id = product.unit_id

            where cart.customer_id = '$customerId'
            and cart.id = '$cartId'
        ";

        return \DB::select($query);
    }

    public function totalCartItem($customerId)
    {
        $fields = [
            \DB::raw("sum(cart.quantity) as total_cart_item")
        ];

        return $this
            ->select($fields)
            ->where('cart.customer_id', $customerId)
            ->first();
    }

    public function totalCartMrpPrice($customerId)
    {
        $fields = [
            \DB::raw("sum(product.mrp_price * cart.quantity) as total_cart_mrp_price")
        ];

        return $this
            ->select($fields)
            ->leftJoin('product', 'product.id', '=', 'cart.product_id')
            ->where('cart.customer_id', $customerId)
            ->first();
    }
    
    public function totalOurPrice($customerId)
    {
        $fields = [
            \DB::raw("sum(product.our_price * cart.quantity) as total_cart_our_price")
        ];

        return $this
            ->select($fields)
            ->leftJoin('product', 'product.id', '=', 'cart.product_id')
            ->where('cart.customer_id', $customerId)
            ->first();
    }
    
    public function totalDiscountedPrice($customerId)
    {
        $fields = [
            \DB::raw("sum(product.our_price * cart.quantity) as total_cart_our_price"),
            \DB::raw("sum(product.mrp_price * cart.quantity) as total_cart_mrp_price")
        ];

        return $this
            ->select($fields)
            ->leftJoin('product', 'product.id', '=', 'cart.product_id')
            ->where('cart.customer_id', $customerId)
            ->first();
    }

    public function updateCart($cart, $cartId, $customerId) 
    {
        return $this
            ->where('cart.id', $cartId)
            ->where('cart.customer_id', $customerId)
            ->update($cart);
    }

    public function deleteFromCart($cartId, $customerId)
    {
        return $this
            ->where('cart.id', $cartId)
            ->where('cart.customer_id', $customerId)
            ->delete();
    }

    public function cartInfo($productId, $customerId)
    {
        return $this
            ->where('cart.product_id', $productId)
            ->where('cart.customer_id', $customerId)
            ->first();
    }

    public function customerCart($customerId)
    {
        $fields = [
            'product.*',
            'cart.product_id',
            'cart.quantity',
            'cart.customer_id'
        ];
        return $this
            ->join('product', 'product.id', '=', 'cart.product_id')
            ->where('cart.customer_id', $customerId)
            ->get($fields);
    }

    public function deleteCustomerCart($customerId)
    {
        $this
            ->where('cart.customer_id', $customerId)
            ->delete();

    }
}
