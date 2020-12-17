<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'vehicle';

    public $sortOrder = 'asc';
    public $sortEntity = 'vehicle.id';

    protected $fillable = [
        'model_in_year',
        'brand_id',
        'type_id',
        'color_id',
        'vehicle_variant',
        'kms_driven',
        'registration_number',

        'registration_document',
        'insurance_document',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function apiAgencyUpdate($inputs, $vehicleId)
    {
        $result = $this->where('vehicle.user_id', $vehicleId)->first();
        if($result) {
            $result->update($inputs);
            return $result->id;
        }
        else {
            return $this->create($inputs)->id;
        }
    }
}
