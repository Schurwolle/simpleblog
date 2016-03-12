<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Response;
use Validator;

class CropController extends Controller
{
    public function upload()
    {
        $inputs = Input::all();

        $photo = $inputs['img'];

        $files = array('image' => $photo);
        $rules = array('image' => 'image|required');

        $validator = Validator::make($files, $rules);

        if($validator->fails())
        {
        	return Response::json([
                'status' => 'error',
                'message' => $validator->messages()->first(),
            ], 200);
        }


        $manager = new ImageManager();
        $image = $manager->make($photo)->save('pictures/image');

        if(!$image) 
        {
            return Response::json([
                'status' => 'error',
                'message' => 'Server error while uploading',
            ], 200);

        }

        return Response::json([
            'status'    => 'success',
            'url'       => '/pictures/image',
            'width'     => $image->width(),
            'height'    => $image->height()
        ], 200);
    }

    public function crop()
    {
        $inputs = Input::all();

        $imgUrl =substr($inputs['imgUrl'], 1, (strlen($inputs['imgUrl'])-1)); 

        $imgW = $inputs['imgW'];
        $imgH = $inputs['imgH'];

        $imgX1 = $inputs['imgX1'];
        $imgY1 = $inputs['imgY1'];

        $cropW = $inputs['cropW'];
        $cropH = $inputs['cropH'];

        $rotation = $inputs['rotation'];

        $manager = new ImageManager();
        $image = $manager->make($imgUrl);
        $image->resize($imgW, $imgH)
            ->rotate(-$rotation)
            ->crop($cropW, $cropH, $imgX1, $imgY1)
            ->save('pictures/imagecropped');

        if(!$image) {

            return Response::json([
                'status' => 'error',
                'message' => 'Server error while uploading',
            ], 200);

        }

        return Response::json([
            'status' => 'success',
            'url' =>'/pictures/imagecropped'
        ], 200);

    }
}
