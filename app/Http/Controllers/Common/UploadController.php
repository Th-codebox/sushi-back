<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Upload\Image;
use App\Http\Requests\CRM\Upload\Doc;
use App\Libraries\ImageUpload;
use App\Libraries\UploadDoc;
use Illuminate\Http\Request;

class UploadController extends Controller
{

    /**
     * @param Image $request
     * @return array
     * @throws \Exception
     */
    public function uploadImage(Image $request)
    {

        return ImageUpload::upload($request,request()->input('path'));
    }

    /**
     * @param Image $request
     * @return array
     * @throws \Exception
     */
    public function uploadDoc(Doc $request)
    {

        return UploadDoc::upload($request,request()->input('path'));
    }

}

