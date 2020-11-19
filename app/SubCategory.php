<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'category';

    public $sortOrder = 'asc';
    public $sortEntity = 'category.id';

    protected $fillable = [
        'parent_id',
        'category_number',
        'category_name',
        'category_image',
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
        return $query->where('category.status', 1);
    }

    public function category() {
        return $this->active()->get();
    }

    public function indexValidation($inputs = [])
    {
        $rules = ['parent_id' => 'required|numeric|min:1'];
        return validator($inputs, $rules);
    }

    public function validation($inputs = [], $id = null)
    {
        $rules = [
            // 'category_number' => 'required|unique:category',
            'parent_category_id' => 'required|numeric|min:1',
            'sub_category_name' => 'required|unique:category,category_name',
            'status' => 'required|numeric',
            // 'category_image' => 'required|image|mimes:jpg,jpeg,png,gif',
        ];

        if($id) {
            $rules['sub_category_name'] = 'required|unique:category,category_name,'.$id;
            // $rules['category_image'] = 'nullable|image|mimes:jpg,jpeg,png,gif';
        }
        return validator($inputs, $rules);
    }

    public function fetch($id) 
    {
        return $this
            ->where('category.id', $id)
            ->first();
    }

    public function pagination(Request $request)
    {
        $filter = 1;
        $perPage = defaultPerPage();
        $sortOrder = $this->sortOrder;
        $sortEntity = $this->sortEntity;

        $fields = "category.*, b.category_name as parent_name";

        if($request->has('perPage') && $request->get('perPage') != '') {
            $perPage = $request->get('perPage');
        }

        if($request->has('keyword') && $request->get('keyword') != '') {
            $filter .= " and category.category_name like '%" . addslashes($request->get('keyword')) . "%'";
        }

        if($request->has('parent_id') && $request->get('parent_id') != '') {
            $filter .= " and category.parent_id = '" . addslashes($request->get('parent_id')) . "'";
        }

        if($request->has('status') && $request->get('status') != '') {
            $filter .= " and category.status = '" . addslashes($request->get('status')) . "'";
        }

        if($request->has('sortEntity') && $request->get('sortEntity') != '') {
            $sortEntity = $request->get('sortEntity');
        }

        if($request->has('sortOrder') && $request->get('sortOrder') != '') {
            $sortOrder = $request->get('sortOrder');
        }

        return $this
            ->select(\DB::raw($fields))
            ->leftJoin('category as b', 'b.parent_id', '=', 'category.id')
            ->whereRaw($filter)
            ->orderBy($sortEntity, $sortOrder)
            ->paginate($perPage);
    }

    public function toggleStatus($status, $ids = [])
    {
        if(isset($ids) && count($ids) > 0) {
            return $this->whereIn('category.id', $ids)->update(['status' => $status]);
        }
    }

    public function service($heading = true) {
        $result = $this
            ->active()
            ->get(['id', 'category_name']);

        $service = [];
        if($heading) {
            $service = ['' => '-Select-'];
        }

        if(isset($result) && count($result) > 0) {
            foreach($result as $row) {
                $service[$row->id] = $row->category_name;
            }
        }
        return $service;
    }

    public function parentService($heading = true) {
        $result = $this
            ->active()
            ->where('parent_id', '0')
            ->get(['id', 'category_name']);

        $service = [];
        if($heading) {
            $service = ['' => '-Select-'];
        }

        if(isset($result) && count($result) > 0) {
            foreach($result as $row) {
                $service[$row->id] = $row->category_name;
            }
        }
        return $service;
    }

    public function subCategoryNumber() {
        $count = 0;
        $result = $this->orderBy('id', 'desc')->first(['id']);
        if($result) { $count = $result->id; }
        return srNo($count);
    }
}
