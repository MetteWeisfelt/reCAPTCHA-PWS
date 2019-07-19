<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use App\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubcategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubcategoryRequest $request)
    {
        $subcategory = new Subcategory();

        $subcategory->category_id = $request->get('category_id');
        $subcategory->name = $request->get('name');
        $subcategory->question = $request->get('question');

        $subcategory->save();

        return response()->json([
            'message' => 'Subcategory saved.'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubcategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubcategoryRequest $request, $id)
    {
        $subcategory = Subcategory::find($request->get('id'));

        $subcategory->update([
            'name' => $request->get('name'),
            'question' => $request->get('question'),
        ]);

        return response()->json([
            'message' => 'Subcategory updated.'
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
        Subcategory::destroy($id);

        return response()->json([
            'message' => 'Category deleted.'
        ], 200);
    }

    public function data(Request $request)
    {
        $subcategory = Subcategory::find($request->get('subcategory_id'));

        return response()->json($subcategory);
    }
}
