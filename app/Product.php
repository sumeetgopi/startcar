<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, Utility;

    protected $table = 'product';

    public $sortOrder = 'asc';
    public $sortEntity = 'product.id';

    protected $fillable = [
        'category_id',
        'sub_category_id',
        'unit_id',

        'product_number',
        'product_name',
        'weight',
        'description',
        'mrp_price',
        'our_price',
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
        return $query->where('product.status', 1);
    }

    public function product() {
        return $this->active()->get();
    }

    public function validation($inputs = [], $id = null)
    {
        $rules = [
            // 'product_number' => 'required|unique:product',
            // 'product_name' => 'required|unique:product',
            'category_id' => 'required|numeric|min:1',
            // 'sub_category_id' => 'required|numeric|min:1',
            'unit_id' => 'required|numeric|min:1',
            'mrp_price' => 'required|numeric|min:1',
            'our_price' => 'required|numeric|min:1',
            'weight' => 'required|numeric|min:1',
            'status' => 'required|numeric',
        ];

        if($id) {
            $rules['product_name'] = 'required|unique:product,product_name,'.$id;
        }
        return validator($inputs, $rules);
    }

    public function fetch($id) 
    {
        return $this
            ->where('product.id', $id)
            ->first();
    }

    public function pagination(Request $request)
    {
        $filter = 1;
        $perPage = defaultPerPage();
        $sortOrder = $this->sortOrder;
        $sortEntity = $this->sortEntity;

        $fields = "
            product.*,
            p.category_name as p_category_name,
            s.category_name as s_category_name,
            unit.unit_name
        ";

        if($request->has('perPage') && $request->get('perPage') != '') {
            $perPage = $request->get('perPage');
        }

        if($request->has('keyword') && $request->get('keyword') != '') {
            $filter .= " and product.product_name like '%" . addslashes($request->get('keyword')) . "%'";
        }

        if($request->has('status') && $request->get('status') != '') {
            $filter .= " and product.status = '" . addslashes($request->get('status')) . "'";
        }

        if($request->has('sortEntity') && $request->get('sortEntity') != '') {
            $sortEntity = $request->get('sortEntity');
        }

        if($request->has('sortOrder') && $request->get('sortOrder') != '') {
            $sortOrder = $request->get('sortOrder');
        }

        return $this
            ->select(\DB::raw($fields))
            ->leftJoin('category as p', 'p.id', '=', 'product.category_id')
            ->leftJoin('category as s', 's.id', '=', 'product.sub_category_id')
            ->leftJoin('unit', 'unit.id', '=', 'product.unit_id')
            ->whereRaw($filter)
            ->orderBy($sortEntity, $sortOrder)
            ->paginate($perPage);
    }

    public function toggleStatus($status, $ids = [])
    {
        if(isset($ids) && count($ids) > 0) {
            return $this->whereIn('product.id', $ids)->update(['status' => $status]);
        }
    }

    public function service($heading = true) {
        $result = $this
            ->active()
            ->get(['id', 'product_name']);

        $service = [];
        if($heading) {
            $service = ['' => '-Select-'];
        }

        if(isset($result) && count($result) > 0) {
            foreach($result as $row) {
                $service[$row->id] = $row->product_name;
            }
        }
        return $service;
    }

    public function productNumber() {
        $count = 0;
        $result = $this->orderBy('id', 'desc')->first(['id']);
        if($result) { $count = $result->id; }
        return srNo($count);
    }

    public function deleteImageValidation($inputs = [])
    {
        $rules = [
            'product_id' => 'required|numeric|min:1',
            'product_image_id' => 'required|numeric|min:1',
            'product_image' => 'required'
        ];
        return validator($inputs, $rules);
    }

    public function apiProductBkp($search = [])
    {
        $filter = 1;

        if(isset($search) && count($search) > 0) {
            $f1 = (isset($search['category_id']) && $search['category_id'] != '') ?
                " and product.category_id = '" . $search['category_id'] . "'" : '';

            $f2 = (isset($search['keyword']) && $search['keyword'] != '') ?
                " and product.product_name like '%" . $search['keyword'] . "%'" : '';

            $filter .= $f1 . $f2;
        }

        $fields = "
            product.*,
            category.category_number,
            category.category_name,
            unit.unit_name,
            concat(round(((product.mrp_price - product.our_price ) / product.mrp_price) * 100), '%') as discount_percent,
            (select product_image from product_image where product_id = product.id limit 1) product_image
        ";

        $orderColumn = 'id';
        $orderSort = 'asc';

        if(isset($search['type']) && $search['type'] == 'discounted') {
            $orderColumn = 'discount_percent';
            $orderSort = 'desc';
        }

        return $this
            ->select(\DB::raw($fields))
            ->active()
            ->leftJoin('category', 'category.id', '=', 'product.category_id')
            ->leftJoin('unit', 'unit.id', '=', 'product.unit_id')
            ->whereRaw($filter)
            ->whereNull('product.deleted_at')
            ->orderBy($orderColumn, $orderSort)
            ->paginate(20);
    }
    
    public function apiProduct($search = [], $request)
    {
        $filter = 1;

        if(isset($search) && count($search) > 0) {
            $f1 = (isset($search['category_id']) && $search['category_id'] != '') ?
                " and t.category_id = '" . $search['category_id'] . "'" : '';

            $f2 = (isset($search['keyword']) && $search['keyword'] != '') ?
                " and t.product_name like '%" . $search['keyword'] . "%'" : '';

            $filter .= $f1 . $f2;
        }

        $query = "order by id asc";

        if(isset($search['type']) && $search['type'] == 'discounted') {
            $query = "having discount_percent > 0
            order by discount_percent desc";
        }

        $query = "
            select 
                t.*,
                c.category_number,
                c.category_name,
                (select product_image from product_image where product_id = t.id limit 1) product_image,
                unit.unit_name,
                round(((t.mrp_price - t.our_price ) / t.mrp_price) * 100) as discount_percent
            from product t
            left join category c on c.id = t.category_id
            left join unit on unit.id = t.unit_id
            where $filter and t.status = '1' and t.deleted_at is null
            
            $query
        ";
         
        $array = \DB::select($query);
        return arrayPaginator($array, $request);
    }

    public function apiProductDetail($productId)
    {
        $fields = "
            ifnull(product.id, 0) as id,
            ifnull(product.category_id, 0) as category_id,
            ifnull(product.sub_category_id, 0) as sub_category_id,
            ifnull(product.unit_id, 0) as unit_id,

            ifnull(product.product_number, '') as product_number,
            ifnull(product.product_name, '') as product_name,
            ifnull(product.description, '') as description,
            concat(round(ifnull(product.weight, '0.00')), ' ', unit.unit_name) as weight,
            ifnull(product.mrp_price, '0.00') as mrp_price,
            ifnull(product.our_price, '0.00') as our_price,

            ifnull(category.category_number, '') as category_number,
            ifnull(category.category_name, '') as category_name,
            ifnull(unit.unit_name, '') as unit_name,
            concat(round(((product.mrp_price - product.our_price ) / product.mrp_price) * 100), '%') as discount_percent
        ";

        return $this
            ->select(\DB::raw($fields))
            ->active()
            ->leftJoin('category', 'category.id', '=', 'product.category_id')
            ->leftJoin('unit', 'unit.id', '=', 'product.unit_id')
            ->where('product.id', $productId)
            ->whereNull('product.deleted_at')
            ->first();
    }

    public function apiSimilarProductBkp($categoryId, $productId, $productName)
    {
        $filter = '';
        $productName = str_replace(['and', '&'], '', $productName);
        $productName = explode(' ', $productName);
        if(isset($productName) && count($productName) > 0) {
            $filter_arr = [];
            foreach($productName as $name) {
                if($name != '' && !is_numeric($name)) {
                    $filter_arr[] = " t.product_name like '%". addslashes($name) ."%' ";
                }
            }
            $filter = ' and ('.implode(' or ', $filter_arr) . ')';
        }

        $query = "
            select t.*,
            c.category_number,
            c.category_name,
            unit.unit_name,
            concat(round(((t.mrp_price - t.our_price ) / t.mrp_price) * 100), '%') as discount_percent,
            (select product_image from product_image where product_id = t.id limit 1) product_image
            from product t

            left join category c on c.id = t.category_id
            left join unit on unit.id = t.unit_id

            where t.status = '1' and t.deleted_at is null
            and t.id != '$productId' 
            and t.category_id = '$categoryId' 

            $filter

            limit 12
        ";

        $result = \DB::select($query);

        if($result) {
            return $result;
        }
        else {
            $query = "
                select t.*,
                c.category_number,
                c.category_name,
                unit.unit_name,
                concat(round(((t.mrp_price - t.our_price ) / t.mrp_price) * 100), '%') as discount_percent,
                (select product_image from product_image where product_id = t.id limit 1) product_image
                from product t

                left join category c on c.id = t.category_id
                left join unit on unit.id = t.unit_id

                where t.status = '1' and t.deleted_at is null 
                and t.id != '$productId' 
                and t.category_id = '$categoryId' 

                limit 12
            ";

            return \DB::select($query);
        }
    }

    public function apiSimilarProduct($categoryId, $productId, $productName)
    {
        $query = "
                select 
                product.*,
                category.category_number,
                category.category_name,
                unit.unit_name,
                (select product_image from product_image where product_id = product.id limit 1) product_image,
                concat(round(((product.mrp_price - product.our_price ) / product.mrp_price) * 100), '%') as discount_percent
            
            from product_tag
            
            left join product on product.id = product_tag.product_id
            left join category on category.id = product.category_id
            left join unit on unit.id = product.unit_id
            
            where 1 
            and tag_id in (select tag_id from product_tag where product_id = '$productId') 
            and product.id != '$productId' 
            and product.status = '1' 
            and product.deleted_at is null
            and product.category_id = '$categoryId'

            group by product.id

            limit 12
        ";

        $result = \DB::select($query);

        if($result) {
            return $result;
        }
        else {
            $query = "
                select t.*,
                c.category_number,
                c.category_name,
                unit.unit_name,
                concat(round(((t.mrp_price - t.our_price ) / t.mrp_price) * 100), '%') as discount_percent,
                (select product_image from product_image where product_id = t.id limit 1) product_image
                from product t

                left join category c on c.id = t.category_id
                left join unit on unit.id = t.unit_id

                where t.status = '1' and t.deleted_at is null 
                and t.id != '$productId' 
                and t.category_id = '$categoryId' 

                limit 12
            ";

            return \DB::select($query);
        }
    }

    public function apiSearch($keyword) {
        /* $query = "
            select
              category.id as id,
              ifnull(category.category_name, '') as searched_text,
              'category' as type
            from category
            where category.category_name like '%".addslashes($keyword)."%'

            union

            select
                product.category_id as id,
                ifnull(product.product_name, '') as searched_text,
                'product' as type
            from product
            where product.product_name like '%".addslashes($keyword)."%'
        "; */

        $query = "
            select
                product.id,
                (select product_image from product_image where product_id = product.id limit 1) product_image,
                concat(
                    ifnull(product.product_name, ''), 
                    ' - ',
                    round(ifnull(product.weight, '0')),
                    ifnull(unit.unit_name, '')
                ) as searched_text,
                'product' as type
            from product 
            left join unit on unit.id = product.unit_id
            where product.product_name like '%".addslashes($keyword)."%'
            and product.status = '1' and product.deleted_at is null
            limit 6
        ";
        return \DB::select($query);
    }

    public function totalProductRange()
    {
        return $this->count();
    }
}
