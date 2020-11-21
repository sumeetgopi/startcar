<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use Utility;

    protected $table = 'users';

    public $sortOrder = 'asc';
    public $sortEntity = 'users.id';

    protected $fillable = [
        
        'name',
        'email',        
        'status',         
        'mobile_number',
        'address',
        'user_type'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',        
        
    ];

    public function scopeActive($query) {
        return $query->where('users.status', 1);
    }

    public function users() {
        return $this->active()->get();
    }

    public function validation($inputs = [], $id = null)
    {
        $rules = [
            'email' => 'required|unique:users|email',
            'status' => 'required|numeric',
            'mobile_number' => 'required|numeric|',
        ];

        if($id) {
            $rules['email'] = 'required|unique:users,email,'.$id;
            
        }
        return validator($inputs, $rules);
    }

    public function fetch($id) 
    {
        return $this
            ->where('users.id', $id)
            ->first();
    }

    public function pagination(Request $request)
    {
        $filter = 1;
        $perPage = defaultPerPage();
        $sortOrder = $this->sortOrder;
        $sortEntity = $this->sortEntity;

        $fields = "users.*";

        if($request->has('perPage') && $request->get('perPage') != '') {
            $perPage = $request->get('perPage');
        }

        if($request->has('keyword') && $request->get('keyword') != '') {
            $filter .= " and (users.email like '%" . addslashes($request->get('keyword')) . "%'" .
            " or users.address like '%" . addslashes($request->get('keyword')) . "%'".             
            " or users.mobile_number like '%" . addslashes($request->get('keyword')) . "%')";         
        }

        if($request->has('status') && $request->get('status') != '') {
            $filter .= " and users.status = '" . addslashes($request->get('status')) . "'";
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
            ->where('user_type','customer')
            ->paginate($perPage);
    }

    public function toggleStatus($status, $ids = [])
    {
        if(isset($ids) && count($ids) > 0) {
            return $this->whereIn('users.id', $ids)->update(['status' => $status]);
        }
    }

   
    
}
