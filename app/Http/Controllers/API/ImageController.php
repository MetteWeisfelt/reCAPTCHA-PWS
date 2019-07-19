<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ImageCollection;
use App\Image;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    function list()
    {
        $images = Image::orderby('created_at', 'ASC')->get();
        return new ImageCollection($images);
    }
}