<?php

namespace App\Http\Controllers\API;

use App\ControlImage;
use App\Http\Resources\ControlImageCollection;
use App\Http\Controllers\Controller;

class ControlImageController extends Controller
{
    function list()
    {
        $controlImages = ControlImage::orderby('created_at', 'ASC')->get();
        return new ControlImageCollection($controlImages);
    }
}