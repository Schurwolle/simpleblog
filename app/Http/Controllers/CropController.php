<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Response;
use Validator;
use Auth;

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

        if(Auth::check())
        {
            $fileName = Auth::user()->name;
        } else {
            $fileName = \Session::getId();
        }
        
        $mask = 'pictures/image'.$fileName.'*';
        if (!empty($mask))
        {
            array_map('unlink', glob($mask));
        }

        $fileName .= rand(0,1000000);

        $manager = new ImageManager();
        $image = $manager->make($photo)->save('pictures/image'.$fileName);

        if(!$image) 
        {
            return Response::json([
                'status' => 'error',
                'message' => 'Server error while uploading',
            ], 200);

        }

        return Response::json([
            'status'    => 'success',
            'url'       => '/pictures/image'.$fileName,
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

        if($cropW == 648)
        {
            $fileName = 'img';
        } else {
            $fileName = 'thumb';
        }

        if(Auth::check())
        {
            $fileName .= Auth::user()->name;
        } else {
            $fileName = \Session::getId();
        }

        $mask = 'pictures/cropped'.$fileName.'*';
        if (!empty($mask))
        {
            array_map('unlink', glob($mask));
        }

        $fileName .= rand(0,1000000);

        $manager = new ImageManager();
        $image = $manager->make($imgUrl);
        $image->resize($imgW, $imgH)
            ->rotate(-$rotation)
            ->crop($cropW, $cropH, $imgX1, $imgY1)
            ->save('pictures/cropped'.$fileName);

        if(!$image) {

            return Response::json([
                'status' => 'error',
                'message' => 'Server error while uploading',
            ], 200);

        }

        return Response::json([
            'status' => 'success',
            'url' =>'/pictures/cropped'.$fileName
        ], 200);

    }
}