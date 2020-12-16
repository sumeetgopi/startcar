<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'driver';

    public $sortOrder = 'asc';
    public $sortEntity = 'driver.id';

    protected $fillable = [
        'agency_id',
        'driver_name',
        'license_number',
        'experience_in_year',
        'pan_adhar_number',
        'pan_adhar_document',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
