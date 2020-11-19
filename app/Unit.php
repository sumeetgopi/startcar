<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'unit';

    public $sortOrder = 'asc';
    public $sortEntity = 'unit.id';

    protected $fillable = [
        'unit_number',
        'unit_name',
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
        return $query->where('unit.status', 1);
    }

    public function unit() {
        return $this->active()->get();
    }

    public function validation($inputs = [], $id = null)
    {
        $rules = [
            // 'unit_number' => 'required|unique:unit',
            'unit_name' => 'required|unique:unit',
            'status' => 'required|numeric',
        ];

        if($id) {
            $rules['unit_name'] = 'required|unique:unit,unit_name,'.$id;
        }
        return validator($inputs, $rules);
    }

    public function fetch($id) 
    {
        return $this
            ->where('unit.id', $id)
            ->first();
    }

    public function pagination(Request $request)
    {
        $filter = 1;
        $perPage = defaultPerPage();
        $sortOrder = $this->sortOrder;
        $sortEntity = $this->sortEntity;

        if($request->has('perPage') && $request->get('perPage') != '') {
            $perPage = $request->get('perPage');
        }

        if($request->has('keyword') && $request->get('keyword') != '') {
            $filter .= " and unit.unit_name like '%" . addslashes($request->get('keyword')) . "%'";
        }

        if($request->has('status') && $request->get('status') != '') {
            $filter .= " and unit.status = '" . addslashes($request->get('status')) . "'";
        }

        if($request->has('sortEntity') && $request->get('sortEntity') != '') {
            $sortEntity = $request->get('sortEntity');
        }

        if($request->has('sortOrder') && $request->get('sortOrder') != '') {
            $sortOrder = $request->get('sortOrder');
        }

        return $this
            ->whereRaw($filter)
            ->orderBy($sortEntity, $sortOrder)
            ->paginate($perPage);
    }

    public function toggleStatus($status, $ids = [])
    {
        if(isset($ids) && count($ids) > 0) {
            return $this->whereIn('unit.id', $ids)->update(['status' => $status]);
        }
    }

    public function service($heading = true) {
        $result = $this
            ->active()
            ->get(['id', 'unit_name']);

        $service = [];
        if($heading) {
            $service = ['' => '-Select-'];
        }

        if(isset($result) && count($result) > 0) {
            foreach($result as $row) {
                $service[$row->id] = $row->unit_name;
            }
        }
        return $service;
    }

    public function unitNumber() {
        $count = 0;
        $result = $this->orderBy('id', 'desc')->first(['id']);
        if($result) { $count = $result->id; }
        return srNo($count);
    }
}
