<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgencyState extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'agency_state';

    public $sortOrder = 'asc';
    public $sortEntity = 'agency_state.id';

    protected $fillable = [
        'user_id',
        'agency_id',
        'state_id',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function removeState($userId)
    {
        $this->where('agency_state.user_id', $userId)->forceDelete();
    }
}
