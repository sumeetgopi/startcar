<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Sms extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'sms';

    public $sortOrder = 'desc';
    public $sortEntity = 'sms.id';

    protected $fillable = [
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

    public function pagination(Request $request)
    {
        $filter = 1;
        $perPage = defaultPerPage();
        $sortOrder = $this->sortOrder;
        $sortEntity = $this->sortEntity;

        $fields = "sms.*";

        if($request->has('perPage') && $request->get('perPage') != '') {
            $perPage = $request->get('perPage');
        }

        if($request->has('keyword') && $request->get('keyword') != '') {
            $filter .= " and (sms.message like '%" . addslashes($request->get('keyword')) . "%' 
            or sms.mobile_number like '%" . addslashes($request->get('keyword')) . "%')";
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
}
