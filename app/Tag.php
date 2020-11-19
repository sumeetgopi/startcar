<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'tag';

    public $sortOrder = 'asc';
    public $sortEntity = 'tag.id';

    protected $fillable = [
        'tag_name',

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
        return $query->where('tag.status', 1);
    }

    public function tag() {
        return $this->active()->get();
    }

    public function validation($inputs = [], $id = null)
    {
        $rules = [
            // 'tag_number' => 'required|unique:tag',
            'tag_name' => 'required|unique:tag',
            'status' => 'required|numeric',
        ];

        if($id) {
            $rules['tag_name'] = 'required|unique:tag,tag_name,'.$id;
        }
        return validator($inputs, $rules);
    }

    public function fetch($id) 
    {
        return $this
            ->where('tag.id', $id)
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
            $filter .= " and tag.tag_name like '%" . addslashes($request->get('keyword')) . "%'";
        }

        if($request->has('status') && $request->get('status') != '') {
            $filter .= " and tag.status = '" . addslashes($request->get('status')) . "'";
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
            return $this->whereIn('tag.id', $ids)->update(['status' => $status]);
        }
    }

    public function service($heading = false) {
        $result = $this
            // ->active()
            ->get(['id', 'tag_name']);

        $service = [];
        if($heading) {
            $service = ['' => '-Select-'];
        }

        if(isset($result) && count($result) > 0) {
            foreach($result as $row) {
                $service[$row->tag_name] = $row->tag_name;
            }
        }
        return $service;
    }

    public function addTag($tag) {
        $tag = strtolower($tag);
        $tag = replaceSpecialChar($tag);
        $result = $this->where('tag_name', $tag)->first(['id']);
        if($result) {
            return $result->id;
        }
        else {
            $create = ['tag_name' => $tag];
            return $this->create($create)->id;
        }
    }
}
