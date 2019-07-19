<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.images.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageRequest $request)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $fileNameWithExt = $file->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $fileExt = $file->getClientOriginalExtension();
                $fileNameToStore = $fileName . '.' . $fileExt;
                $filePath = basename($file->store('public/images'));
                list($width, $height) = getimagesize(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'public/images/' . $filePath);

                $image = new Image();

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
        Image::destroy($id);

        return response()->json([
            'message' => 'Image deleted.'
        ], 200);
    }
}
