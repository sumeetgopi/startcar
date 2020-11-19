<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'banner';

    public $sortOrder = 'asc';
    public $sortEntity = 'banner.id';

    protected $fillable = [
        'image',
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
        return $query->where('banner.status', 1);
    }

    public function banner() {
        return $this->active()->get();
    }

    public function validation($inputs = [], $id = null)
    {
        $rules = [
            'image' => 'required|image|mimes:jpg,jpeg,png,gif',
            'status' => 'required|numeric',
        ];

        if($id) {
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png,gif';
        }
        return validator($inputs, $rules);
    }

    public function fetch($id) 
    {
        return $this
            ->where('banner.id', $id)
            ->first();
    }

    public function pagination(Request $request)
    {
        $filter = 1;
        $perPage = defaultPerPage();
        $sortOrder = $this->sortOrder;
        $sortEntity = $this->sortEntity;

        $fields = "banner.*";

        if($request->has('perPage') && $request->get('perPage') != '') {
            $perPage = $request->get('perPage');
        }

        if($request->has('status') && $request->get('status') != '') {
            $filter .= " and banner.status = '" . addslashes($request->get('status')) . "'";
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
            return $this->whereIn('banner.id', $ids)->update(['status' => $status]);
        }
    }

    public function apiBanner() 
    {
        return $this
            ->active()
            ->get();
    }
}
