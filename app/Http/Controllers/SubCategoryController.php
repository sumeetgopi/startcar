<?php

namespace App\Http\Controllers;

use App\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortOrder = (new SubCategory)->sortOrder;
        $sortEntity = (new SubCategory)->sortEntity;

        $result = null;
        if($request->ajax()) {

            $inputs = $request->all();
            $validation = (new SubCategory)->indexValidation($inputs);
            if($validation->fails()) {
                return jsonResponse(false, 206, $validation->getMessageBag());
            }


            $sortEntity = $request->sortEntity;
            $sortOrder = $request->sortOrder;

            $result = (new SubCategory)->pagination($request);
            return view('subcategory.pagination', compact('result', 'sortOrder', 'sortEntity'));
        }

        $parent = (new SubCategory)->parentService();
        return view('subcategory.index', compact('result', 'sortOrder', 'sortEntity', 'parent'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentCategory = (new SubCategory)->parentService();
        return view('subcategory.create', compact('parentCategory'));
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
        $validation = (new SubCategory)->validation($inputs);
        if($validation->fails()) { 
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        try {
            \DB::beginTransaction();

            // image code start
            if($request->hasFile('subcategory_image')) {
                $inputs['category_image'] = webImgUpload($request, 'subcategory_image', env('CATEGORY_PATH'));
            }
            // image code end

            $inputs['category_number'] = (new SubCategory)->subCategoryNumber();
            $inputs['category_name'] = $inputs['sub_category_name'];
            $inputs['parent_id'] = $inputs['parent_category_id'] ?? 0;
            (new SubCategory)->store($inputs);
            \DB::commit();

            $extra = ['redirect' => route('subcategory.index')];
            $message = __('subcategory.created');
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
        $result = (new SubCategory)->fetch($id);
        if(!$result) {
            abort(404);
        }

        $parentCategory = (new SubCategory)->parentService();
        return view('subcategory.edit', compact('result', 'parentCategory'));
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
        $result = (new SubCategory)->fetch($id);
        if(!$result) {
            $message = __('message.invalid_detail');
            return jsonResponse(false, 207, $message);
        }

        $inputs = $request->all();
        $validation = (new SubCategory)->validation($inputs, $id);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        try {
            \DB::beginTransaction();

            // image upload code start
            if($request->hasFile('subcategory_image')) {
                $path = env('CATEGORY_PATH');
                $image = webImgUpload($request, 'subcategory_image', $path);
                if($image) {
                    $inputs['category_image'] = $image;
                    removeImg($result->category_image, $path);
                }
            }
            // image upload code end

            $inputs['parent_id'] = $inputs['parent_category_id'] ?? 0;
            $inputs['category_name'] = $inputs['sub_category_name'];
            (new SubCategory)->store($inputs, $id);
            \DB::commit();

            $extra = ['redirect' => route('subcategory.index')];
            $message = __('subcategory.updated');
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
        (new SubCategory)->find($id)->delete();
        $message = __('message.deleted');
        return jsonResponse(true, 201, $message);
    }

    public function toggleStatus($id, $status) 
    {
        $result = (new SubCategory)->fetch($id);
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

            (new SubCategory)->toggleStatus($status, $inputs['ids']);
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
        $result = (new SubCategory)->fetch($id);
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
}