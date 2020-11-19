<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTag extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'product_tag';

    public $sortOrder = 'asc';
    public $sortEntity = 'product_tag.id';

    protected $fillable = [
        'product_id',
        'tag_id',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getTag($productId) 
    {
        $tags = [];
        $result = $this
            ->leftJoin('tag', 'tag.id', 'product_tag.tag_id')
            ->where('product_tag.product_id', $productId)
            ->get();

        if($result) {
            $tags = array_column($result->toArray(), 'tag_name');
        }
        return $tags;
    }

    public function removeTag($productId)
    {
        $this->where('product_id', $productId)->forceDelete();
    }
}
