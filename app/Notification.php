<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Notification extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'notification';

    public $sortOrder = 'desc';
    public $sortEntity = 'notification.id';

    protected $fillable = [
        'title',
        'message',
        'mobile_number',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function validation($inputs = [])
    {
        $rules = [
            'title' => 'required|max:255',
            'message' => 'required'
        ];
        return validator($inputs, $rules);
    }

    public function pagination(Request $request)
    {
        $filter = 1;
        $perPage = defaultPerPage();
        $sortOrder = $this->sortOrder;
        $sortEntity = $this->sortEntity;

        $fields = "notification.*";

        if($request->has('perPage') && $request->get('perPage') != '') {
            $perPage = $request->get('perPage');
        }

        if($request->has('keyword') && $request->get('keyword') != '') {
            $filter .= " and (notification.message like '%" . addslashes($request->get('keyword')) . "%' 
            or notification.title like '%" . addslashes($request->get('keyword')) . "%'  
            or notification.mobile_number like '%" . addslashes($request->get('keyword')) . "%')";
        }

        if($request->has('sortEntity') && $request->get('sortEntity') != '') {
            $sortEntity = $request->get('sortEntity');
        }

        if($request->has('sortOrder') && $request->get('sortOrder') != '') {
            $sortOrder = $request->get('sortOrder');
        }

        return $this
            ->select(\DB::raw($fields))
            ->whereRaw($filter)
            ->orderBy($sortEntity, $sortOrder)
            ->paginate($perPage);
    }

    public function apiNotification($mobileNumber = '')
    {
        return $this
            ->whereRaw("find_in_set('$mobileNumber', mobile_number)")
            ->orderBy('notification.id', 'desc')
            ->paginate(20);
    }
}
