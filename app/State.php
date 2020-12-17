<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'state';

    public $sortOrder = 'asc';
    public $sortEntity = 'state.id';

    protected $fillable = [
        'state_name',        
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

    public function scopeActive($query) {
        return $query->where('state.status', 1);
    }



    public function validation($inputs = [], $id = null)
    {
        $rules = [
            'state_name' => 'required|unique:state',
            'status' => 'required|numeric',
            
        ];

        if($id) {
            $rules['state_name'] = 'required|unique:state,state_name,'.$id;
            
        }
        return validator($inputs, $rules);
    }

    public function fetch($id) 
    {
        return $this
            ->where('state.id', $id)
            ->first();
    }

    public function pagination(Request $request)
    {
        $filter = 1;
        $perPage = defaultPerPage();
        $sortOrder = $this->sortOrder;
        $sortEntity = $this->sortEntity;

        $fields = "state.*";

        if($request->has('perPage') && $request->get('perPage') != '') {
            $perPage = $request->get('perPage');
        }

        if($request->has('keyword') && $request->get('keyword') != '') {
            $filter .= " and state.state_name like '%" . addslashes($request->get('keyword')) . "%'";
        }

        if($request->has('status') && $request->get('status') != '') {
            $filter .= " and state.status = '" . addslashes($request->get('status')) . "'";
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

    public function toggleStatus($status, $ids = [])
    {
        if(isset($ids) && count($ids) > 0) {
            return $this->whereIn('state.id', $ids)->update(['status' => $status]);
        }
    }

    // public function service($heading = true) {
    //     $result = $this
    //         ->active()
    //         ->get(['id', 'brand_name']);

    //     $service = [];
    //     if($heading) {
    //         $service = ['' => '-Select-'];
    //     }

    //     if(isset($result) && count($result) > 0) {
    //         foreach($result as $row) {
    //             $service[$row->id] = $row->category_name;
    //         }
    //     }
    //     return $service;
    // }


    public function apiState()
    {
        return $this
            ->active()
            ->get();
    }
    
}
