<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\ProductImage;
use App\ProductTag;
use App\Tag;
use App\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortOrder = (new Product)->sortOrder;
        $sortEntity = (new Product)->sortEntity;

        $result = null;
        if($request->ajax()) {
            $sortEntity = $request->sortEntity;
            $sortOrder = $request->sortOrder;

            $result = (new Product)->pagination($request);
            return view('product.pagination', compact('result', 'sortOrder', 'sortEntity'));
        }

        return view('product.index', compact('result', 'sortOrder', 'sortEntity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = (new Category)->parentService();
        $subCategory = ['' => '-Select-'];
        $unit = (new Unit)->service();
        $tags = (new Tag)->service();
        return view('product.create', compact('category', 'subCategory', 'unit', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Product)->validation($inputs);
        if($validation->fails()) { 
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        if(!isset($inputs['tags'])) {
            return jsonResponse(false, 207, 'Please select alteast one tag');
        }

        try {
            \DB::beginTransaction();
            // image code start
            $images = [];
            if($request->hasFile('product_image')) {
                $images = webImgUploadMultiple($request, 'product_image', env('PRODUCT_PATH'));
            }
            // image code end

            $inputs['product_number'] = (new Product)->productNumber();
            $inputs['sub_category_id'] = $inputs['sub_category_id'] ?? 0;
            $id = (new Product)->store($inputs);
            if($id) {
                if(isset($images) && count($images) > 0) {
                    $productImages = [];
                    foreach($images as $img) {
                        $productImages[] = ['product_id' => $id, 'product_image' => $img];
                    }

                    if(isset($productImages) && count($productImages) > 0) {
                        (new ProductImage)->store($productImages, null, true);
                    }
                }

                // tag code start
                $t = new Tag();
                $productTag = [];
                if(isset($inputs['tags']) && count($inputs['tags']) > 0) {
                    foreach($inputs['tags'] as $tg) {
                        $tagId = $t->addTag($tg);
                        $productTag[] = [
                            'product_id' => $id,
                            'tag_id' => $tagId,
                        ];
                    }
                }

                if(isset($productTag) && count($productTag) > 0) {
                    (new ProductTag)->store($productTag, null, true);
                }
                // tag code end
            }
            \DB::commit();

            $extra = ['redirect' => route('product.index')];
            $message = __('product.created');
            return jsonResponse(true, 201, $message, $extra);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return jsonResponse(true, 207, __('message.server_error') . $e->getMessage() . $e->getFile() . $e->getLine());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = (new Product)->fetch($id);
        if(!$result) {
            abort(404);
        }

        $category = (new Category)->parentService();
        $subCategory = (new Category)->subCategoryService(['parent_id' => $result->category_id]);
        $unit = (new Unit)->service();
        $tags = (new Tag)->service();
        $productTags = (new ProductTag)->getTag($id);
        $productImages = (new ProductImage)->getProductImage($result->id);

        return view('product.edit', compact('result', 'tags', 'productTags', 'category', 'subCategory', 'unit', 'productImages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = (new Product)->fetch($id);
        if(!$result) {
            $message = __('message.invalid_detail');
            return jsonResponse(false, 207, $message);
        }

        $inputs = $request->all();
        $validation = (new Product)->validation($inputs, $id);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        if(!isset($inputs['tags'])) {
            return jsonResponse(false, 207, 'Please select alteast one tag');
        }

        try {
            \DB::beginTransaction();
            // image upload code start
            if($request->hasFile('product_image')) {
                $path = env('PRODUCT_PATH');
                $images = webImgUploadMultiple($request, 'product_image', $path);
                /*if($image) {
                    $inputs['product_image'] = $image;
                    removeImg($result->image, $path);
                }*/
            }
            // image upload code end

            $inputs['sub_category_id'] = $inputs['sub_category_id'] ?? 0;
            (new Product)->store($inputs, $id);

            if(isset($images) && count($images) > 0) {
                $productImages = [];
                foreach($images as $img) {
                    $productImages[] = ['product_id' => $id, 'product_image' => $img];
                }

                if(isset($productImages) && count($productImages) > 0) {
                    (new ProductImage)->store($productImages, null, true);
                }
            }

            // tag code start
            $t = new Tag();
            $productTag = [];
            if(isset($inputs['tags']) && count($inputs['tags']) > 0) {
                foreach($inputs['tags'] as $tg) {
                    $tagId = $t->addTag($tg);
                    $productTag[] = [
                        'product_id' => $id,
                        'tag_id' => $tagId,
                    ];
                }
            }

            if(isset($productTag) && count($productTag) > 0) {
                $pt = new ProductTag();
                $pt->removeTag($id);
                $pt->store($productTag, null, true);
            }
            // tag code end
            \DB::commit();

            $extra = ['redirect' => route('product.index')];
            $message = __('product.updated');
            return jsonResponse(true, 201, $message, $extra);
        }
        catch (\Exception $e) {
            \DB::rollBack();
            return jsonResponse(true, 207, __('message.server_error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        (new Product)->find($id)->delete();
        $message = __('message.deleted');
        return jsonResponse(true, 201, $message);
    }

    public function toggleStatus($id, $status) 
    {
        $result = (new Product)->fetch($id);
        if(!$result) {
            $message = __('message.invalid_detail');
            return jsonResponse(false, 207, $message);
        }

        try {
            \DB::beginTransaction();
            $result->update(['status' => $status]);
            \DB::commit();

            $message = __('message.status');
            return jsonResponse(true, 201, $message);
        }
        catch (\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function toggleAllStatus($status, Request $request) {
        try {
            \DB::beginTransaction();
            $inputs = $request->only('ids');

            (new Product)->toggleStatus($status, $inputs['ids']);
            \DB::commit();

            $message = __('message.status');
            return jsonResponse(true, 201, $message);
        }
        catch (\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function status($id) {
        $result = (new Product)->fetch($id);
        if(!$result) {
            $message = __('message.invalid_id');
            return jsonResponse(false, 207, $message);
        }

        try {
            \DB::beginTransaction();
            $result->update(['status' => !$result->status]);
            \DB::commit();

            $message = __('message.status');
            return jsonResponse(true, 201, $message);
        }
        catch (\Exception $e) {
            \DB::rollBack();
            return jsonResponse(false, 207, __('message.server_error'));
        }
    }

    public function deleteImage(Request $request) 
    {
        $inputs = $request->all();
        $validation = (new Product)->deleteImageValidation($inputs);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }
        
        (new ProductImage)->deleteImage($inputs['product_id'], $inputs['product_image_id']);
        $path = env('PRODUCT_PATH');
        removeImg($inputs['product_image'], $path);
        
        return response()->json(['success' => true, 'message' => 'Image deleted']);
    }
}