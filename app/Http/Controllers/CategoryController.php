<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderby('id', 'ASC')->get();

        return view('admin.categories.index')->with([
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category();

        $category->name = $request->get('name');
        $category->question = $request->get('question');

        $category->save();

        return response()->json([
            'message' => 'Category saved.'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::find($request->get('id'));

        $category->update([
            'name' => $request->get('name'),
            'question' => $request->get('question'),
        ]);

        return response()->json([
            'message' => 'Category updated.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::destroy($id);

        return response()->json([
            'message' => 'Category deleted.'
        ], 200);
    }

    public function data(Request $request)
    {
        $category = Category::find($request->get('category_id'));

        return response()->json($category);
    }
}
