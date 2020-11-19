<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsTemplate extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'sms_template';

    public $sortOrder = 'asc';
    public $sortEntity = 'sms_template.id';

    protected $fillable = [
        'sms_name',
        'template',
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
        return $query->where('sms_template.status', 1);
    }

    public function sms_template() {
        return $this->active()->get();
    }

    public function validation($inputs = [], $id = null)
    {
        $rules = [
            'sms_name' => 'required|unique:sms_template',
            'template' => 'required|max:1000',
            'status' => 'required|numeric',
        ];

        if($id) {
            $rules['sms_name'] = 'required|unique:sms_template,sms_name,'.$id;
        }
        return validator($inputs, $rules);
    }

    public function serviceValidation($inputs = [])
    {
        $rules = [
            'id' => 'required|numeric|min:1',
        ];
        return validator($inputs, $rules);
    }

    public function smsValidation($inputs = [])
    {
        $rules = [
            'send_type' => 'required|in:all,selected',
            'sms_id' => 'nullable|numeric|min:1|exists:sms_template,id',
            'message' => 'required|max:1000',
        ];
        return validator($inputs, $rules);
    }

    public function fetch($id) 
    {
        return $this
            ->where('sms_template.id', $id)
            ->first();
    }

    public function pagination(Request $request)
    {
        $filter = 1;
        $perPage = defaultPerPage();
        $sortOrder = $this->sortOrder;
        $sortEntity = $this->sortEntity;

        $fields = "sms_template.*";

        if($request->has('perPage') && $request->get('perPage') != '') {
            $perPage = $request->get('perPage');
        }

        if($request->has('keyword') && $request->get('keyword') != '') {
            $filter .= " and sms_template.sms_name like '%" . addslashes($request->get('keyword')) . "%'";
        }

        if($request->has('status') && $request->get('status') != '') {
            $filter .= " and sms_template.status = '" . addslashes($request->get('status')) . "'";
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
            return $this->whereIn('sms_template.id', $ids)->update(['status' => $status]);
        }
    }

    public function service($heading = true) {
        $result = $this
            ->active()
            ->get(['id', 'sms_name']);

        $service = [];
        if($heading) {
            $service = ['' => '-Select-'];
        }

        if(isset($result) && count($result) > 0) {
            foreach($result as $row) {
                $service[$row->id] = $row->sms_name;
            }
        }
        return $service;
    }
}
