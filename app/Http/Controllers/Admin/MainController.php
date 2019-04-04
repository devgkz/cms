<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
    public function about()
    {
        return view('admin/main/about', [
            'text' => file_get_contents('../docs/about.md')
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveOrder(Request $request)
    {
        $request->table;
        $arr = explode(';', trim($request->input('data'), ';'));
        $ids = join(', ', array_values($arr));
        $ord = join(', ', array_keys($arr));
        $sql = 'UPDATE `'.$request->table.'` SET sort_order = ELT(FIELD(id, '.$ids.'), '.$ord.') WHERE id IN ('.$ids.')';
        DB::unprepared($sql);
          
        return $arr;
        
    }
}
