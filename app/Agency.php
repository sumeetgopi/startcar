<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'agency';

    public $sortOrder = 'asc';
    public $sortEntity = 'agency.id';

    protected $fillable = [
        'user_id',
        'agency_name',
        'contact_person',
        'email',
        'address',
        'gst_number',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function apiAgencyUpdate($inputs, $userId)
    {
        $result = $this->where('agency.user_id', $userId)->first();
        if($result) {
            $result->update($inputs);
            return $result->id;
        }
        else {
            return $this->create($inputs)->id;
        }
    }
}
