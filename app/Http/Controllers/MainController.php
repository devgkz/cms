<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Image;

class MainController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin/main/index');
    }
    
    /**
     * Show the about page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function resized($profile, $dir, $filename)
    {
        $orig = public_path('files/'.$dir.'/'.$filename);
        //dd($orig);
        $image = Image::make($orig);
        $profileData = config('resized.'.$profile);
        
        foreach ($profileData['actions'] as $action=>$params) {
            switch ($action) {
                case 'resize':
                    $image->resize($params['width'], $params['height'], function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    break;
                case 'fit':
                    $image->fit($params['width'], $params['height']);
                    break;
                case 'orientate':
                    $image->orientate();
                    break;
                case 'resize':
                    $image->resize($params['width'], $params['height']);
                    break;
                case 'rotate':
                    $image->rotate($params['angle'], $params['bgcolor']);
                    break;
            }
        }
        
        if (!file_exists(public_path('files/resized').'/'.$profile.'/'.$dir)) {
            mkdir(public_path('files/resized').'/'.$profile.'/'.$dir, 0777, true);
        }
        
        $result = public_path('files/resized').'/'.$profile.'/'.$dir.'/'.$filename;
        
        $image->save($result);
        
        if (file_exists($result)) {
            return redirect()->to('files/resized/'.$profile.'/'.$dir.'/'.$filename);
        } else {
            return response('Image resize failed', 500);
        }
    }
}
