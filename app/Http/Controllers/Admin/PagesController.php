<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;

use Validator;
use Session;


class PagesController extends Controller
{
    /**
     * Display a listing of the pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
        $parents = [];
        $page = null;
        
        $cond = Page::where('parent_id', $id);
        
        if ($id != 0) {
            $parent = $page = Page::findOrFail($id);
            while ($parent->parent_id != 0) {
                $parent = $parent->parent;
                $parents[] = $parent;
            }            
        }
        
        $cond->orderBy($page && $page->order_childs_by ?: 'sort_order');
        
        $items = $cond->get();

        return view('admin/pages/index', [
            'items'=>$items,
            'parents'=>array_reverse($parents),
            'page'=>$page,
            'id'=>$id
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $skeds = Sked::all();
        
        return view('admin/pages/add', [
            'skeds' => $skeds,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mark' => 'required',
            'model' => 'required',
            'status' => 'required',
            'color' => 'required',
            'year' => 'required|integer',
            'gibdd_number' => 'required',
        ]);
        
        if ($validator->fails()) {
            /* $request->flash();
            return view('admin/pages/add', [
                'errors' => $validator->errors()
            ]) ;*/
            return redirect()->to('admin/pages/add')->withInput()->withErrors($validator->errors());
        }
        
        $item = Page::create($request->all());
        $item->save();
        
        return response('<div class="alert success">Автомобиль добавлен</div><script>window.location.reload()</script>');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $item = Page::findOrFail($id);
        
        return view('admin/pages/view', [
            'item' => $item,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $parents = [];
        $page = null;
        
        $cond = Page::where('parent_id', $id);

        $parent = $page = Page::findOrFail($id);
        
        while ($parent->parent_id != 0) {
            $parent = $parent->parent;
            $parents[] = $parent;
        }            

        return view('admin/pages/edit', [
            'parents'=>array_reverse($parents),
            'page'=>$page,
            'id'=>$id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'mark' => 'required',
            'model' => 'required',
            'status' => 'required',
            'color' => 'required',
            'year' => 'required|integer',
            'gibdd_number' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->to('admin/pages/edit/'.$id)->withInput()->withErrors($validator->errors());
        } 
        
        $item = Page::find($id)->update($request->all());
        
        return response('<div class="alert success">Автомобиль изменен</div><script>window.location.reload()</script>');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        $item = Page::findOrFail($id);
        $item->delete();

        return redirect()->to('/cars');
    }
}
