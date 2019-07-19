<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\StoreControlImageRequest;
use App\ControlImage;
use Illuminate\Support\Facades\Storage;

class ControlImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return view('admin.control_images.index')->with([
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreControlImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreControlImageRequest $request)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $fileNameWithExt = $file->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $fileExt = $file->getClientOriginalExtension();
                $fileNameToStore = $fileName . '.' . $fileExt;
                $filePath = basename($file->store('public/images'));
                list($width, $height) = getimagesize(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'public/images/' . $filePath);

                $image = new ControlImage();

                $image->category_id = $request->input('category_id');
                $image->path = $filePath;
                $image->name = substr($fileNameToStore, 0, 255);
                $image->height = $height;
                $image->width = $width;

                $image->save();
            }
        }

        return response()->json([
            'message' => 'Image(s) saved.'
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
        ControlImage::destroy($id);

        return response()->json([
            'message' => 'Image deleted.'
        ], 200);
    }
}
