<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'product_image';

    public $sortOrder = 'asc';
    public $sortEntity = 'product_image.id';

    protected $fillable = [
        'product_id',
        'product_image',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getProductImage($productId) 
    {
        $fields = ['product_image.*'];
        return $this
            ->where('product_image.product_id', $productId)
            ->get($fields);
    }

    public function deleteImage($productId, $id) {
        $this
            ->where('product_image.product_id', $productId)
            ->where('product_image.id', $id)
            ->forceDelete();
    }

    public function getImage($productId) {
        $result = $this
            ->where('product_image.product_id', $productId)
            ->first(['product_image']);

        return $result ? $result->product_image : '';
    }

    public function apiProductImage($productId)
    {
        return $this
            ->where('product_image.product_id', $productId)
            ->get(['id', 'product_image']);
    }
}
