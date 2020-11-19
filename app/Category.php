<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'category';

    public $sortOrder = 'asc';
    public $sortEntity = 'category.id';

    protected $fillable = [
        'category_number',
        'category_name',
        'description',
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

    public function validation($inputs = [], $id = null)
    {
        $rules = [
            'category_name' => 'required|unique:category',
            'status' => 'required|numeric',
            'category_image' => 'required|image|mimes:jpg,jpeg,png,gif',
        ];

        if($id) {
            $rules['category_name'] = 'required|unique:category,category_name,'.$id;
            $rules['category_image'] = 'nullable|image|mimes:jpg,jpeg,png,gif';
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

        $fields = "category.*";

        if($request->has('perPage') && $request->get('perPage') != '') {
            $perPage = $request->get('perPage');
        }

        if($request->has('keyword') && $request->get('keyword') != '') {
            $filter .= " and category.category_name like '%" . addslashes($request->get('keyword')) . "%'";
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

    public function categoryNumber() {
        $count = 0;
        $result = $this->orderBy('id', 'desc')->first(['id']);
        if($result) { $count = $result->id; }
        return srNo($count);
    }

    public function bookingCategory()
    {
        return $this
            ->active()
            ->get();
    }
}
