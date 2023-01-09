<?php


namespace App\Http\Controllers\Swager;


use App\Http\Controllers\Controller;

class SwagerController extends Controller
{

    public function web()
    {

        $data['doc'] = asset('swager/web/api-docs.yaml'.'?v='.time());
        return view('swager.index', $data);
    }

    public function crm()
    {

        $data['doc'] = asset('swager/crm/api-docs.yaml'.'?v='.time());
        return view('swager.index', $data);
    }

    public function courier()
    {

        $data['doc'] = asset('swager/courier/api-docs.yaml'.'?v='.time());
        return view('swager.index', $data);
    }

    public function tablet()
    {

        $data['doc'] = asset('swager/tablet/api-docs.yaml'.'?v='.time());
        return view('swager.index', $data);
    }
}
