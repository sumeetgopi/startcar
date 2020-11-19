<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Data extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'data';

    public $sortOrder = 'asc';
    public $sortEntity = 'setting.id';

    protected $fillable = [
        'finding',
        'replacing',
        'status',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ]; 

    public function validation($inputs = []) {
        $rules = [
            'data' => 'required|numeric|min:1',
            'minimum_order_amount_for_cashback' => 'required|numeric|min:1',
        ];
        return validator($inputs, $rules);
    }

    public function getData()
    {
        return $this->get();
    }
}
