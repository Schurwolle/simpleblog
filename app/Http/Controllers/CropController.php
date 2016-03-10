<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Response;


class CropController extends Controller
{
    public function upload()
    {
        $inputs = Input::all();

        $photo = $inputs['img'];

        $manager = new ImageManager();
        $image = $manager->make($photo)->save('/pictures/100');

        if(!$image) 
        {
            return Response::json([
                'status' => 'error',
                'message' => 'Server error while uploading',
            ], 200);

        }

        return Response::json([
            'status'    => 'success',
            'url'       => '/uploads/100',
            'width'     => $image->width(),
            'height'    => $image->height()
        ], 200);
    }
}
