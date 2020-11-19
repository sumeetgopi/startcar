<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortOrder = (new Category)->sortOrder;
        $sortEntity = (new Category)->sortEntity;

        $result = null;
        if($request->ajax()) {
            $sortEntity = $request->sortEntity;
            $sortOrder = $request->sortOrder;

            $result = (new Category)->pagination($request);
            return view('category.pagination', compact('result', 'sortOrder', 'sortEntity'));
        }

        return view('category.index', compact('result', 'sortOrder', 'sortEntity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
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
        $validation = (new Category)->validation($inputs);
        if($validation->fails()) { 
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        try {
            \DB::beginTransaction();

            // image code start
            if($request->hasFile('category_image')) {
                $inputs['category_image'] = webImgUpload($request, 'category_image', env('CATEGORY_PATH'));
            }
            // image code end

            $inputs['category_number'] = (new Category)->categoryNumber();
            (new Category)->store($inputs);
            \DB::commit();

            $extra = ['redirect' => route('category.index')];
            $message = __('category.created');
            return jsonResponse(true, 201, $message, $extra);
        }
        catch(\Exception $e) {
            \DB::rollBack();
            return jsonResponse(true, 207, __('message.server_error'));
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
        $result = (new Category)->fetch($id);
        if(!$result) {
            abort(404);
        }
        return view('category.edit', compact('result'));
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
        $result = (new Category)->fetch($id);
        if(!$result) {
            $message = __('message.invalid_detail');
            return jsonResponse(false, 207, $message);
        }

        $inputs = $request->all();
        $validation = (new Category)->validation($inputs, $id);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        try {
            \DB::beginTransaction();

            // image upload code start
            if($request->hasFile('category_image')) {
                $path = env('CATEGORY_PATH');
                $image = webImgUpload($request, 'category_image', $path);
                if($image) {
                    $inputs['category_image'] = $image;
                    removeImg($result->image, $path);
                }
            }
            // image upload code end

            (new Category)->store($inputs, $id);
            \DB::commit();

            $extra = ['redirect' => route('category.index')];
            $message = __('category.updated');
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
        (new Category)->find($id)->delete();
        $message = __('message.deleted');
        return jsonResponse(true, 201, $message);
    }

    public function toggleStatus($id, $status) 
    {
        $result = (new Category)->fetch($id);
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

            (new Category)->toggleStatus($status, $inputs['ids']);
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
        $result = (new Category)->fetch($id);
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

    public function service(Request $request)
    {
        $inputs = $request->all();
        $validation = (new Category)->serviceValidation($inputs);
        if($validation->fails()) {
            return jsonResponse(false, 206, $validation->getMessageBag());
        }

        $options = '<option value="">-Select-</option>';
        $result = (new Category)->subCategoryService(['parent_id' => $inputs['parent_id']], false);
        if(isset($result) && count($result) > 0) {
            foreach($result as $key => $option) {
                $options .= '<option value="'.$key.'">'.$option.'</option>';
            }
        }
        return response()->json(['success' => true, 'options' => $options]);
    }
}