<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'user_address';

    public $sortOrder = 'asc';
    public $sortEntity = 'user_address.id';

    protected $fillable = [
        'customer_id',
        'address',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function apiListAddress($customerId) {
        return $this
            ->where('user_address.customer_id', $customerId)
            ->orderBy('user_address.id', 'desc')
            ->get();
    }

    public function deleteAddress($addressId, $customerId)
    {
        $this
            ->where('user_address.id', $addressId)
            ->where('user_address.customer_id', $customerId)
            ->delete();
    }
}
