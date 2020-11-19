<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'setting';

    public $sortOrder = 'asc';
    public $sortEntity = 'setting.id';

    protected $fillable = [
        'center_image',
        'policy',
        'about_us',
        'term_condition',
        'read_policy',
        'customer_support',
        'maximum_cashback_amount',
        'minimum_order_amount_for_cashback',
        'invoice_address',

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
            'maximum_cashback_amount' => 'required|numeric|min:1',
            'minimum_order_amount_for_cashback' => 'required|numeric|min:1',
        ];
        return validator($inputs, $rules);
    }

    public function getSetting($settingName, $default = '')
    {
        $result = $this->first();
        return $result->$settingName ?? $default;
    }

    public function saveSetting($inputs = []) 
    {
        $result = $this->orderBy('id', 'asc')->first();
        if(!$result) {
            $result = $this->create($inputs);
        }
        else {
            $result->update($inputs);
        }
    }
}
