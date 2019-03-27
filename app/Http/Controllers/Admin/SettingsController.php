<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Txt;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use App\Models\Setting;
use Carbon\Carbon;
use Carbon\CarbonPeriod; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Show settings list
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::orderBy('sort_order')->where('visible', 1)->get();
        
        return view('admin/settings/index', [
            'settings'=>$settings,
        ]);
    }
    
    /**
     * Update settings
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        $data = $request->input('data');
        
        if(is_array($data) && !empty($data)) {
            
            foreach ($data as $name=>$value) {
                $setting = Setting::where('name', $name)->first();
                $setting->value = $value;
                $setting->save();
            }
        }
        
        return response('<div class="alert success">Настройки сохранены</div>
                <script>window.location.reload()</script>');
    }
}
