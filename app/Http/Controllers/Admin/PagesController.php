<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Media;

use Validator;

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
    public function add($parentId = 0)
    {
        return view('admin/pages/add', [
            'parentId' => $parentId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $parentId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($parentId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required',
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            /* $request->flash();
            return view('admin/pages/add', [
                'errors' => $validator->errors()
            ]) ;*/
            return redirect()->to(config('cms.admin_uri').'/pages/add/'.$parentId)->withInput()->withErrors($validator->errors());
        }
        
        $item = Page::create($request->all());
        $item->parent_id = $parentId;
        $item->save();

        return response('<div class="alert success">Страница добавлена</div><script>window.location.reload()</script>');
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
            'id'=>$id,
            'media'=>$page->media()->orderBy('sort_order')->get(),
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
            'slug' => 'required',
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->to(config('cms.admin_uri').'/pages/edit/'.$id)->withInput()->withErrors($validator->errors());
        }

        $item = Page::find($id)->update($request->all());

        return response('<div class="alert success">Изменения сохранены</div><script>setTimeout(function() {cms.modal.closeAll();}, 500);</script>');
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

        return redirect()->to(config('cms.admin_uri').'/pages/index/'.$item->parent_id);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mediaAdd($pageId = 0)
    {
        return view('admin/pages/media_add', [
            'pageId' => $pageId,
        ]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $pageId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mediaStore($pageId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            /* $request->flash();
            return view('admin/pages/add', [
                'errors' => $validator->errors()
            ]) ;*/
            return redirect()->to(config('cms.admin_uri').'/pages/media/add/'.$parentId)->withInput()->withErrors($validator->errors());
        }
        
        if ($request->hasFile('file')) {
            
            $notAllowedExts = ['exe','php'];
            
            $files = $request->file('file');
            
            foreach ($files as $i=>$file) {
                
                $origFilename = $file->getClientOriginalName();
                $extension = $file->extension();
                $check = in_array($extension, $notAllowedExts);

                if (!$check) {
                    $storedFilename = $file->store('media');
                    
                    $item = Media::create();
                    $item->page_id = $pageId;
                    $item->type_id = $request->type_id;
                    $item->title = $request->title;
                    $item->content = $request->content;
                    $item->filename = $storedFilename; //$pageId.'_'.uniqid().'.'.$extension;
                    $item->fn_original = $origFilename;
                    
                    $item->save();
                    
                } 
            }
        } else {
            $item = Media::create();
            $item->page_id = $pageId;
            $item->type_id = $request->type_id;
            $item->title = $request->title;
            $item->content = $request->content;
            $item->save();
        }

        return response(['status' => 'ok']);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mediaEdit($id)
    {

        $item = Media::findOrFail($id);

        return view('admin/pages/media_edit', [
            'item'=>$item,
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mediaUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required',
            //'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->to(config('cms.admin_uri').'/pages/media/edit/'.$id)->withInput()->withErrors($validator->errors());
        }

        $item = Media::find($id)->update($request->all());

        return response('ok');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mediaRemove($id)
    {
        $item = Media::findOrFail($id);
        
        $file = public_path('files/'.$item->filename);
        if ($item->filename && file_exists($file)) {
            unlink($file);
        }
        $item->forceDelete();

        return redirect()->to(config('cms.admin_uri').'/pages/edit/'.$item->page_id);
    }
}
